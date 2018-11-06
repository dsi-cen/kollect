<?php 
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';
session_start();

function modif($idm,$type,$modif,$datem)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO site.modif (typemodif, modif, datemodif, idmembre)
						VALUES(:type, :modif, :datem, :idm) ");
	$req->bindValue(':type', $type);
	$req->bindValue(':modif', $modif);
	$req->bindValue(':datem', $datem);
	$req->bindValue(':idm', $idm);
	$req->execute();
	$req->closeCursor();
}
function calc_obs($nomvar,$obs,$maillage)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	if($maillage == 'l93')
	{
		$req = $bdd->prepare("SELECT COUNT(nb) FROM (
								SELECT codel93, COUNT(idobs) AS nb FROM obs.obs
								INNER JOIN obs.fiche USING(idfiche)
								INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
								WHERE observa = :sel 
								GROUP BY codel93 ) AS s
							WHERE nb <= :mini ");
	}
	else
	{
		$req = $bdd->prepare("SELECT COUNT(nb) FROM (
								SELECT codel935, COUNT(idobs) AS nb FROM obs.obs
								INNER JOIN obs.fiche USING(idfiche)
								INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
								WHERE observa = :sel 
								GROUP BY codel935 ) AS s
							WHERE nb <= :mini ");
	}
	$req->bindValue(':mini', $obs);
	$req->bindValue(':sel', $nomvar);
	$req->execute();
	$m = $req->fetchColumn();
	$req->closeCursor();
	return $m;
}
function calc_es($nomvar,$es,$maillage)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	if($maillage == 'l93')
	{
		$req = $bdd->prepare("SELECT COUNT(nb) FROM (
							SELECT codel93, COUNT(distinct cdref) AS nb FROM obs.obs
							INNER JOIN obs.fiche USING(idfiche)
							INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
							WHERE observa = :sel
							GROUP BY codel93 ) AS s
						WHERE nb <= :mini ");
	}
	else
	{
		$req = $bdd->prepare("SELECT COUNT(nb) FROM (
							SELECT codel935, COUNT(distinct cdref) AS nb FROM obs.obs
							INNER JOIN obs.fiche USING(idfiche)
							INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
							WHERE observa = :sel
							GROUP BY codel935 ) AS s
						WHERE nb <= :mini ");		
	}
	$req->bindValue(':mini', $es);
	$req->bindValue(':sel', $nomvar);
	$req->execute();
	$m = $req->fetchColumn();
	$req->closeCursor();
	return $m;
}
function calc_m($choix,$nomvar,$obs,$es,$maillage,$date)
{
	$code = ($maillage == 'l93') ? 'codel93' : 'codel935';
	$count = ($choix == 'obs') ? 'COUNT(idobs) AS nb' : 'COUNT(DISTINCT cdref) AS nb';
	$strQuery = 'SELECT COUNT(nb) FROM (';
	$strQuery .= ' SELECT '.$code.', '.$count.' FROM obs.obs';
	$strQuery .= ' INNER JOIN obs.fiche USING(idfiche) INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord';
	$strQuery .= ' WHERE observa = :sel';
	if(!empty($date)) { $strQuery .= ' AND date1 >= :date'; }
	$strQuery .= ' GROUP BY '.$code.' ) AS s';
	$strQuery .= ' WHERE nb <= :mini';
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':sel', $nomvar);
	if(!empty($date)) { $req->bindValue(':date', $date.'-01-01'); }
	if($choix == 'obs') { $req->bindValue(':mini', $obs); } else { $req->bindValue(':mini', $es); }
	$req->execute();
	$m = $req->fetchColumn();
	$req->closeCursor();
	return $m;
}
function calc($ir,$cr1,$cr2,$cr3,$cr4,$cr5,$cr6,$cr7,$crp1,$crp2,$crp3,$crp4,$crp5,$crp6,$crp7)
{
	if($ir >= $cr1) {$indice = 'Exceptionnelle';}
	elseif($ir >= $cr2 && $ir < $cr1) {$indice = 'Très rare';}
	elseif($ir >= $cr3 && $ir < $cr2) {$indice = 'Rare';}
	elseif($ir >= $cr4 && $ir < $cr3) {$indice = 'Assez rare';}
	elseif($ir >= $cr5 && $ir < $cr4) {$indice = 'Peu commune';}
	elseif($ir >= $cr6 && $ir < $cr5) {$indice = 'Assez commune';}
	elseif($ir >= $cr7 && $ir < $cr6) {$indice = 'Commune';}
	elseif ($ir < $cr7) {$indice = 'Très commune';}
	
	if($ir >= $crp1) {$indice = 'Exceptionnelle';}
	elseif($ir >= $crp2 && $ir < $crp1) {$indicep = 'Très rare';}
	elseif($ir >= $crp3 && $ir < $crp2) {$indicep = 'Rare';}
	elseif($ir >= $crp4 && $ir < $crp3) {$indicep = 'Assez rare';}
	elseif($ir >= $crp5 && $ir < $crp4) {$indicep = 'Peu commune';}
	elseif($ir >= $crp6 && $ir < $crp5) {$indicep = 'Assez commune';}
	elseif($ir >= $crp7 && $ir < $crp6) {$indicep = 'Commune';}
	elseif ($ir < $crp7) {$indicep = 'Très commune';}	
	
	return array($indice,$indicep);
}

if(isset($_POST['mt']) && isset($_POST['sel']))
{	
	$maillage = $_POST['maillage'];
	$mt = $_POST['mt'];
	$m = $_POST['m'];
	$obs = $_POST['obs'];
	$es = $_POST['es'];
	$nomvar = $_POST['sel'];
	$date = $_POST['date'];
	
	$ms = ($mt < 200) ? 1 : ceil(0.5/100*$mt);			
	if(isset($_POST['choix']))
	{
		$m = calc_m($_POST['choix'],$nomvar,$obs,$es,$maillage,$date);
	}
	
	$M = round($m/$mt*100,1);

	$cr1b = round(100-($ms/$mt)*100,1);
	$cr2b = $cr1b - 1; $cr3b = $cr2b - 2; $cr4b = $cr3b - 4; $cr5b = $cr4b - 8; $cr6b = $cr5b - 16; $cr7b = $cr6b - 32; $cr8b = 0;
	$crp1b = round($cr1b+($M-($cr1b*$M/100)),1);
	$crp2b = round($cr2b+($M-($cr2b*$M/100)),1);
	$crp3b = round($cr3b+($M-($cr3b*$M/100)),1);
	$crp4b = round($cr4b+($M-($cr4b*$M/100)),1);
	$crp5b = round($cr5b+($M-($cr5b*$M/100)),1);
	$crp6b = round($cr6b+($M-($cr6b*$M/100)),1);
	$crp7b = round($cr7b+($M-($cr7b*$M/100)),1);
	$crp8b = 0;
	$m2b = ceil((100-$cr2b)/100*$mt); $m3b = ceil((100-$cr3b)/100*$mt); $m4b = ceil((100-$cr4b)/100*$mt); $m5b = ceil((100-$cr5b)/100*$mt); $m6b = ceil((100-$cr6b)/100*$mt); $m7b = ceil((100-$cr7b)/100*$mt); $m8b = ceil((100-$cr8b)/100*$mt);
	$m2a = $ms; $m3a = $m2b; $m4a = $m3b; $m5a = $m4b; $m6a = $m5b; $m7a = $m6b; $m8a = $m7b;
	$mp1b = ceil((100-$crp1b)/100*$mt);
	$mp2b = ceil((100-$crp2b)/100*$mt);
	$mp3b = ceil((100-$crp3b)/100*$mt);
	$mp4b = ceil((100-$crp4b)/100*$mt);
	$mp5b = ceil((100-$crp5b)/100*$mt);
	$mp6b = ceil((100-$crp6b)/100*$mt);
	$mp7b = ceil((100-$crp7b)/100*$mt);
	$mp8b = $mt;
	$mp2a = $mp1b; $mp3a = $mp2b; $mp4a = $mp3b; $mp5a = $mp4b; $mp6a = $mp5b; $mp7a = $mp6b; $mp8a = $mp7b;
	
	$tab = null;
	$tab .= '<tr><th scope="row" class="text-sm-left">Exceptionnelle</th><td>E</td><td>100</td><td>'.$cr1b.'</td><td>1</td><td>'.$ms.'</td><td>100</td><td>'.$crp1b.'</td><td></td><td>'.$mp1b.'</td></tr>';
	$tab .= '<tr><th scope="row" class="text-sm-left">Très rare</th><td>TR</td><td>'.$cr1b.'</td><td>'.$cr2b.'</td><td>'.$m2a.'</td><td>'.$m2b.'</td><td>'.$crp1b.'</td><td>'.$crp2b.'</td><td>'.$mp2a.'</td><td>'.$mp2b.'</td></tr>';
	$tab .= '<tr><th scope="row" class="text-sm-left">Rare</th><td>R</td><td>'.$cr2b.'</td><td>'.$cr3b.'</td><td>'.$m3a.'</td><td>'.$m3b.'</td><td>'.$crp2b.'</td><td>'.$crp3b.'</td><td>'.$mp3a.'</td><td>'.$mp3b.'</td></tr>';
	$tab .= '<tr><th scope="row" class="text-sm-left">Assez rare</th><td>AR</td><td>'.$cr3b.'</td><td>'.$cr4b.'</td><td>'.$m4a.'</td><td>'.$m4b.'</td><td>'.$crp3b.'</td><td>'.$crp4b.'</td><td>'.$mp4a.'</td><td>'.$mp4b.'</td></tr>';
	$tab .= '<tr><th scope="row" class="text-sm-left">Peu commun</th><td>PC</td><td>'.$cr4b.'</td><td>'.$cr5b.'</td><td>'.$m5a.'</td><td>'.$m5b.'</td><td>'.$crp4b.'</td><td>'.$crp5b.'</td><td>'.$mp5a.'</td><td>'.$mp5b.'</td></tr>';
	$tab .= '<tr><th scope="row" class="text-sm-left">Assez commun</th><td>AC</td><td>'.$cr5b.'</td><td>'.$cr6b.'</td><td>'.$m6a.'</td><td>'.$m6b.'</td><td>'.$crp5b.'</td><td>'.$crp6b.'</td><td>'.$mp6a.'</td><td>'.$mp6b.'</td></tr>';
	$tab .= '<tr><th scope="row" class="text-sm-left">Commun</th><td>C</td><td>'.$cr6b.'</td><td>'.$cr7b.'</td><td>'.$m7a.'</td><td>'.$m7b.'</td><td>'.$crp6b.'</td><td>'.$crp7b.'</td><td>'.$mp7a.'</td><td>'.$mp7b.'</td></tr>';
	$tab .= '<tr><th scope="row" class="text-sm-left">Très commun</th><td>TC</td><td>'.$cr7b.'</td><td>0</td><td>'.$m8a.'</td><td>'.$m8b.'</td><td>'.$crp7b.'</td><td>'.$crp8b.'</td><td>'.$mp8a.'</td><td>'.$mp8b.'</td></tr>';
	
	$retour['tab'] = $tab;
	$retour['Mm'] = $M;
	$retour['m'] = $m;
	
	//exemple
	//$val1 = ceil($mt * 0.05);
	$val1 = 11;
	$ir1 = round(100-($val1/$mt)*100,1);
	$indice1 = calc($ir1,$cr1b,$cr2b,$cr3b,$cr4b,$cr5b,$cr6b,$cr7b,$crp1b,$crp2b,$crp3b,$crp4b,$crp5b,$crp6b,$crp7b);
	$val2 = ceil($mt * 0.3);
	$ir2 = round(100-($val2/$mt)*100,1);
	$indice2 = calc($ir2,$cr1b,$cr2b,$cr3b,$cr4b,$cr5b,$cr6b,$cr7b,$crp1b,$crp2b,$crp3b,$crp4b,$crp5b,$crp6b,$crp7b);
	
	$ex = null;
	$ex .= '<p><b>Exemple 1</b><br />Espèce présente sur '.$val1.' mailles, <b>Ir</b> = '.$ir1.'<br />Espèce qualifiée de <b>'.$indice1[0].'</b> avec le coefficient de rareté et de <b>'.$indice1[1].'</b> avec le coefficient pondéré.<br />
	<b>Exemple 2</b><br />Espèce présente sur '.$val2.' mailles, <b>Ir</b> = '.$ir2.'<br />Espèce qualifiée de <b>'.$indice2[0].'</b> avec le coefficient de rareté et de <b>'.$indice2[1].'</b> avec le coefficient pondéré.</p>';
	$retour['ex'] = $ex;
	
	if($_POST['test'] == 'non' || $_POST['test'] == 'sup')
	{
		/*
		En cas de modification, il faut également vérifier :
		valobservatoir.php
		catinser.php
		importliste.php
		statutval.php
		indice.php
		site/mobser.php
		site/general.php
		site/obser.php
		site/fiche.php		
		*/ 
		$valchoix = ($_POST['choix'] == 'obs') ? $obs : $es;
		$choixdate = (!empty($date)) ? $date.'-01-01' : null;
		
		$json = file_get_contents('../../../../json/'.$nomvar.'.json');
		$rjson = json_decode($json, true);		
		$filename = '../../../../json/'.$nomvar.'.json';
		$datajson = array();
		$datajson['titre'] = $rjson['titre'];
		$datajson['metakey'] = $rjson['metakey'];
		$datajson['icon'] = $rjson['icon'];
		$datajson['couleur'] = $rjson['couleur'];
		$datajson['nomvar'] = $rjson['nomvar'];
		$datajson['nom'] = $rjson['nom'];
		$datajson['nomdeux'] = $rjson['nomdeux'];
		$datajson['latin'] = $rjson['latin'];
		if($_POST['test'] == 'non')
		{
			$datajson['indice'] = ['ms'=>$ms, 'choix'=>$_POST['choix'], 'valchoix'=>$valchoix, 'maillage'=>$maillage, 'date'=>$choixdate];
		}		
		if(isset ($rjson['saisie'])) { $datajson["saisie"] = $rjson['saisie']; }
		if(isset($rjson['categorie']))	{ $datajson["categorie"] = $rjson['categorie']; }
		if(isset ($rjson['systematique'])) { $datajson["systematique"] = $rjson['systematique']; }
		if(isset ($rjson['gen1'])) { $datajson["gen1"] = $rjson['gen1']; }
		if(isset ($rjson['gen2'])) { $datajson["gen2"] = $rjson['gen2']; }
		if(isset ($rjson['statut'])) { $datajson["statut"] = $rjson['statut']; }
		$datajson['description'] = $rjson['description'];
		$ajson = json_encode($datajson);
		if (!$fp = @fopen($filename, 'w+')) 
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert">Impossible de créer ou d\'écrire le fichier '.$nomvar.'.json dans le répertoire json. Assurez vous d\'avoir les droits nécessaires (CHMOD).</div>';
			echo json_encode($retour);	
			exit;
		} 
		else 
		{
			fwrite($fp, $ajson);
			fclose($fp);					
		}
		$json_site = file_get_contents('../../../../json/site.json');
		$rjson_site = json_decode($json_site, true);
		$filename1 = '../../../../json/site.json';
		$datajson1 = array();
		$datajson1['email'] = $rjson_site['email'];
		$datajson1['titre'] = $rjson_site['titre'];
		$datajson1['adresse'] = $rjson_site['adresse'];
		$datajson1['description'] = $rjson_site['description'];
		$datajson1['metakey'] = $rjson_site['metakey'];
		$datajson1["lien"] = $rjson_site['lien'];
		$datajson1["logo"] = $rjson_site['logo'];
		if(isset($rjson_site['orga'])) { $datajson1["orga"] = $rjson_site['orga']; }
		$datajson1["biblio"] = $rjson_site['biblio'];
		$datajson1["actu"] = $rjson_site['actu'];
		if($rjson_site['actu'] == 'oui') { $datajson1["nbactu"] = $rjson_site['nbactu']; }
		$datajson1["lieu"] = $rjson_site['lieu'];
		$datajson1["ad1"] = $rjson_site['ad1'];
		$datajson1["ad2"] = $rjson_site['ad2'];
		if(isset($rjson_site['observatoire'])) { $datajson1["observatoire"] = $rjson_site['observatoire']; }
		if(isset($rjson_site['fiche'])) { $datajson1["fiche"] = $rjson_site['fiche']; }
		if($_POST['test'] == 'non')
		{
			if(isset($rjson_site['indice']))
			{
				if(!isset($rjson_site['indice'][$nomvar]))
				{
					$an = $rjson_site['indice'];
					$tmp = [$nomvar =>['ms'=>$ms, 'choix'=>$_POST['choix'], 'valchoix'=>$valchoix, 'maillage'=>$maillage]];
					$datajson1['indice'] = array_merge($an,$tmp);
					$type = 'Ajout';
					$modif = 'Ajout indice à '.$nomvar;
				}
				else
				{
					$an = $rjson_site['indice'];
					unset($an[$nomvar]);
					$tmp = [$nomvar =>['ms'=>$ms, 'choix'=>$_POST['choix'], 'valchoix'=>$valchoix, 'maillage'=>$maillage]];
					$datajson1['indice'] = array_merge($an,$tmp);
					$type = 'Modif';
					$modif = 'Modif indice de '.$nomvar;
				}				
			}
			else
			{
				$datajson1['indice'] = array($nomvar =>(array('ms'=>$ms, 'choix'=>$_POST['choix'], 'valchoix'=>$valchoix, 'maillage'=>$maillage)));
				$type = 'Ajout';
				$modif = 'Ajout indice à '.$nomvar;
			}			
		}
		else
		{
			$an = $rjson_site['indice'];
			unset($an[$nomvar]);
			if(!empty($an)) 
			{
				$datajson1['indice'] = $an;
			}
			$type = 'Sup';
			$modif = 'Suppression indice de '.$nomvar;
		}		
		$datajson1['stitre'] = $rjson_site['stitre'];
		
		$ajson = json_encode($datajson1);
		if (!$fp = @fopen($filename1, 'w+')) 
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert">Impossible de créer ou d\'écrire le fichier site.json dans le répertoire json. Assurez vous d\'avoir les droits nécessaires (CHMOD).</div>';
			echo json_encode($retour);	
			exit;
		}
		else
		{
			fwrite($fp, $ajson);
			fclose($fp);
			$retour['statut'] = 'Oui';
		}	
		$datem = date("Y-m-d H:i:s");
		modif($_SESSION['idmembre'],$type,$modif,$datem);		
	}	
	else
	{
		$retour['statut'] = 'Oui';	
	}		
}
else
{
	$retour['statut'] = 'Non';
}
echo json_encode($retour);	