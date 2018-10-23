<?php
/*
En cas de modification, il faut également vérifier :
valobservatoir.php
catinser.php
importliste.php
indice.php
statutval.php
site/mobser.php
*/ 
function table($idbota)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT table_name FROM information_schema.tables WHERE table_schema='$idbota' AND table_name='liste'");
	$table = $req->rowCount();
	$req->closeCursor();
	return $table;		
}
function tablebota()
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT table_name FROM information_schema.tables WHERE table_schema='referentiel' AND table_name='listebota'");
	$table = $req->rowCount();
	$req->closeCursor();
	return $table;		
}
function creerlistebota()
{
	$bdd = PDO2::getInstance();		
	$bdd->query('SET NAMES "utf8"');	
	$req = $bdd->query("CREATE TABLE referentiel.listebota (
						cdnom integer NOT NULL,
						nom text,
						auteur text,
						rang character varying(5),
						nomvern text,
						CONSTRAINT listebota_pkey PRIMARY KEY (cdnom))");
	$req->closeCursor();
}
function insere_listebota()
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT cdnom, nom, auteur, rang, nomvern FROM referentiel.taxref
						WHERE (groupe = 'Angiospermes' OR groupe = 'Gymnospermes') AND (rang = 'GN' OR rang = 'ES' OR rang = 'SSES') AND cdnom = cdref ");
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	$req = $bdd->prepare("INSERT INTO referentiel.listebota (cdnom,nom,auteur,rang,nomvern) VALUES(:cdnom, :nom, :auteur, :rang, :nomvern) ");
	$nb = 0;
	foreach ($liste as $n)
	{
		$tabnv = explode(',',$n['nomvern']);
		$req->execute(array('cdnom'=>$n['cdnom'], 'nom'=>$n['nom'], 'auteur'=>$n['auteur'], 'rang'=>$n['rang'], 'nomvern'=>$tabnv[0]));
		$nb++;
	}
	$req->closeCursor();
	return $nb;	
}
function tableaves()
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT table_name FROM information_schema.tables WHERE table_schema='obs' AND table_name='aves'");
	$table = $req->rowCount();
	$req->closeCursor();
	return $table;		
}
function creeraves()
{
	$bdd = PDO2::getInstance();		
	$bdd->query('SET NAMES "utf8"');	
	$req = $bdd->query("CREATE TABLE obs.aves (
					  idaves serial NOT NULL,
					  idobs integer,
					  code smallint,
					  stade smallint,
					  CONSTRAINT aves_pkey PRIMARY KEY (idaves),
                      CONSTRAINT aves_idobs_fk FOREIGN KEY (idobs) REFERENCES obs.obs (idobs)) ");
	$req->closeCursor();
}

function histo_aves() { // RLE : patch pour l'historique de la table aves
	$bdd = PDO2::getInstance();
	$req = null;
	$req = file_get_contents('histo_aves.sql');
	$bdd->exec($req);
	unset($req);
}

if (isset($_POST['stadeid']) and isset($_POST['sel']))
{
	include '../../../../global/configbase.php';
	include '../../../../lib/pdo2.php';
	
	$nomvar = $_POST['sel'];
	$stadeid = $_POST['stadeid'];
	$stadeval = $_POST['stadeval'];
	$methid = $_POST['methid'];
	$methval = $_POST['methval'];
	$colid = $_POST['colid'];
	$colval = $_POST['colval'];
	$bioid = $_POST['bioid'];
	$bioval = $_POST['bioval'];
    $compid = $_POST['compid'];
    $compval = $_POST['compval'];
	$mortid = $_POST['mortid'];
	$mortval = $_POST['mortval'];
	$protoid = $_POST['protoid'];
	$protoval = $_POST['protoval'];
	$denomid = $_POST['denomid'];
	$denomval = $_POST['denomval'];
	$locale = $_POST['locale'];
	$plteh = $_POST['plteh'];
	$idbota = $_POST['idbota'];
	$aves = $_POST['aves'];
	$col = ($_POST['collect'] == 'oui') ? 'oui' : 'non';
	$clbota = $_POST['clbota'];
	$stbio = $_POST['stbio'];
	$mf = $_POST['mf'];
	
	if($plteh == 'oui')
	{
		if($idbota != '')
		{
			$table = table($idbota);
			if ($table == 0)
			{
				$retour['statut'] = 'Non';
				$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Erreur ! Aucune table dans le schema '.$idbota.'. Ce n\'est pas le bon identifiant.</p></div>';
				echo json_encode($retour);	
				exit;
			}
			$listebota = $idbota;
		}
		else
		{
			$tablebota = tablebota();
			if($tablebota == 0)
			{
				creerlistebota();
				$nbliste = insere_listebota();
				if ($nbliste == 0)
				{
					$retour['statut'] = 'Non';
					$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Erreur ! Problème lors de la creation de la table listebota.</p></div>';
					echo json_encode($retour);	
					exit;
				}
			}
			$listebota = 'aucune';	
		}
	}
	if($aves == 'oui')
	{
		$tableaves = tableaves();
		if($tableaves == 0)
		{
			creeraves();
			histo_aves();			
		}	
	}	
	
	$tabstade = array_combine($stadeval, $stadeid);
	$tabmethode = array_combine($methval, $methid);
	$tabcollecte = array_combine($colval, $colid);
	$tabstatutbio = array_combine($bioval, $bioid);
    $tabcomp = array_combine($compval, $compid);
	$tabmort = (count($mortid) > 1) ? array_combine($mortval, $mortid) : null;
	$tabprotocole = array_combine($protoval, $protoid);
	$tabdenom = array_combine($denomval, $denomid);
		
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
	if(isset($rjson['indice'])) { $datajson['indice'] = $rjson['indice']; }
	if($plteh == 'oui')
	{
		$datajson['saisie'] = array('stade'=>$tabstade,'methode'=>$tabmethode,'collecte'=>$tabcollecte,'statutbio'=>$tabstatutbio, 'comportement'=>$tabcomp, 'mort'=>$tabmort,'protocole'=>$tabprotocole,'denom'=>$tabdenom,'locale'=>$locale,'col'=>$col,'mf'=>$mf,'stbio'=>$stbio,'plteh'=>$plteh,'listebota'=>$listebota);
	}
	elseif($aves == 'oui')
	{
		$datajson['saisie'] = array('stade'=>$tabstade,'methode'=>$tabmethode,'collecte'=>$tabcollecte,'statutbio'=>$tabstatutbio, 'comportement'=>$tabcomp, 'mort'=>$tabmort,'protocole'=>$tabprotocole,'denom'=>$tabdenom,'locale'=>$locale,'col'=>$col,'mf'=>$mf,'stbio'=>$stbio,'aves'=>$aves);
	}
	elseif($clbota == 'oui')
	{
		$datajson['saisie'] = array('stade'=>$tabstade,'methode'=>$tabmethode,'collecte'=>$tabcollecte,'statutbio'=>$tabstatutbio, 'comportement'=>$tabcomp, 'mort'=>$tabmort,'protocole'=>$tabprotocole,'denom'=>$tabdenom,'locale'=>$locale,'col'=>$col,'mf'=>$mf,'stbio'=>$stbio,'bota'=>$clbota);
	}
	else
	{
		$datajson['saisie'] = array('stade'=>$tabstade,'methode'=>$tabmethode,'collecte'=>$tabcollecte,'statutbio'=>$tabstatutbio, 'comportement'=>$tabcomp, 'mort'=>$tabmort,'protocole'=>$tabprotocole,'denom'=>$tabdenom,'locale'=>$locale,'col'=>$col,'mf'=>$mf,'stbio'=>$stbio);
	}	
	if(isset($rjson['categorie'])) { $datajson["categorie"] = $rjson['categorie']; }
	if(isset($rjson['systematique'])) { $datajson["systematique"] = $rjson['systematique']; }
	if(isset($rjson['gen1'])) { $datajson["gen1"] = $rjson['gen1']; }
	if(isset($rjson['gen2'])) { $datajson["gen2"] = $rjson['gen2']; }
	if(isset($rjson['statut'])) { $datajson["statut"] = $rjson['statut']; }
	$datajson['description'] = $rjson['description'];
	$ajson = json_encode($datajson);
	if (!$fp = @fopen($filename, 'w+')) 
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Impossible de créer ou d\'écrire le fichier '.$nomvar.'.json dans le répertoire json. Assurez vous d\'avoir les droits nécessaires (CHMOD).</p></div>';
		echo json_encode($retour);	
		exit;
	} 
	else 
	{
		fwrite($fp, $ajson);
		fclose($fp);
		$retour['statut'] = 'Oui';
		$retour['mes'] = '<div class="alert alert-success" role="alert">L\'observatoire '.$rjson['nom'].' a bien été paramétré.</div>';
	}
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert">Problème survenu !.</div>';
}
echo json_encode($retour);