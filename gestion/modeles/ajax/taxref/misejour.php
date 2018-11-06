<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function recherche_rangobserva($observa)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT rang FROM $observa.rang ");
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}
function recherche_choix($observa)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT choix, val FROM $observa.choix ");
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}
function recherche_rang($observa,$valchoix,$choix)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->prepare("SELECT DISTINCT rang FROM referentiel.taxref
						INNER JOIN taxref.change USING(cdnom)
						WHERE typechange = 'AJOUT' AND $choix = :choix  ");
	$req->bindValue(':choix', $valchoix);
	$req->execute();
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}
function insere_famille($observa,$valchoix,$choix)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->prepare("INSERT INTO $observa.famille (cdnom,cdsup,famille,auteur,ordre,classe,nomvern,locale) 
						SELECT cdnom, cdsup, famille, auteur, ordre, classe, nomvern, 'non' AS locale FROM referentiel.taxref
						INNER JOIN taxref.change USING(cdnom)
						WHERE typechange = 'AJOUT' AND $choix = :choix AND rang = 'FM' ");
	$req->bindValue(':choix', $valchoix);
	$req->execute();
	$req->closeCursor();
}
function insere_sfamille($observa,$valchoix,$choix)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->prepare("INSERT INTO $observa.sousfamille (cdnom,cdsup,sousfamille,auteur,nomvern,locale) 
						SELECT cdnom, cdsup, nom, auteur, nomvern, 'non' AS locale FROM referentiel.taxref
						INNER JOIN taxref.change USING(cdnom)
						WHERE typechange = 'AJOUT' AND $choix = :choix AND rang = 'SBFM' ");
	$req->bindValue(':choix', $valchoix);
	$req->execute();
	$req->closeCursor();
}
function insere_genre($observa,$valchoix,$choix)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->prepare("INSERT INTO $observa.genre (cdnom,cdsup,cdtaxsup,genre,auteur,locale) 
						SELECT cdnom, cdsup, cdtaxsup, nom, auteur, 'non' AS locale FROM referentiel.taxref
						INNER JOIN taxref.change USING(cdnom)
						WHERE typechange = 'AJOUT' AND $choix = :choix AND rang = 'GN' ");
	$req->bindValue(':choix', $valchoix);
	$req->execute();
	$req->closeCursor();
}
function insere_sgenre($observa,$valchoix,$choix)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->prepare("INSERT INTO $observa.sousgenre (cdnom,genre,sousgenre,auteur,locale) 
						SELECT cdnom, cdsup, nom, auteur, 'non' AS locale FROM referentiel.taxref
						INNER JOIN taxref.change USING(cdnom)
						WHERE typechange = 'AJOUT' AND $choix = :choix AND rang = 'SSGN' ");
	$req->bindValue(':choix', $valchoix);
	$req->execute();
	$req->closeCursor();
}
function insere_tribu($observa,$valchoix,$choix)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->prepare("INSERT INTO $observa.tribu (cdnom,cdsup,cdtaxsup,tribu,auteur,locale) 
						SELECT cdnom, cdsup, cdtaxsup, nom, auteur, 'non' AS locale FROM referentiel.taxref
						INNER JOIN taxref.change USING(cdnom)
						WHERE typechange = 'AJOUT' AND $choix = :choix AND rang = 'TR' ");
	$req->bindValue(':choix', $valchoix);
	$req->execute();
	$req->closeCursor();
}
function insere_stribu($observa,$valchoix,$choix)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->prepare("INSERT INTO $observa.soustribu (cdnom,cdsup,cdtaxsup,soustribu,auteur,locale) 
						SELECT cdnom, cdsup, cdtaxsup, nom, auteur, 'non' AS locale FROM referentiel.taxref
						INNER JOIN taxref.change USING(cdnom)
						WHERE typechange = 'AJOUT' AND $choix = :choix AND rang = 'SSTR' ");
	$req->bindValue(':choix', $valchoix);
	$req->execute();
	$req->closeCursor();
}
function insere_liste($observa,$valchoix,$choix)
{
	set_time_limit(0);
	$bdd = PDO2::getInstance();		
	$req = $bdd->prepare("SELECT taxref.cdnom, taxref.cdref, taxref.cdsup, taxref.cdtaxsup, nom, taxref.auteur, rang, famille.cdnom AS famille, taxref.nomvern FROM referentiel.taxref
						INNER JOIN taxref.change USING(cdnom)
						INNER JOIN $observa.famille ON famille.famille = taxref.famille
						WHERE typechange = 'AJOUT' AND taxref.$choix = :choix AND (rang = 'GN' OR rang = 'ES' OR rang = 'SSES') ");
	$req->bindValue(':choix', $valchoix);
	$req->execute();
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	$req = $bdd->prepare("INSERT INTO $observa.liste (cdnom,cdref,cdsup,cdtaxsup,nom,genre,espece,auteur,rang,famille,nomvern,locale) VALUES(:cdnom, :cdref, :cdsup, :cdtaxsup, :nom, :genre, :espece, :auteur, :rang, :famille, :nomvern, :locale) ");
	foreach ($liste as $n)
	{
		$tabnv = explode(',',$n['nomvern']);
		$nomvern = preg_replace('/\((.*)\)/', '', $tabnv[0]);		
		if($n['cdnom'] == $n['cdref'] and ($n['rang'] == 'ES' || $n['rang'] == 'SSES')){$tab = explode(' ',$n['nom']);$genre = $tab[0];$espece = $tab[1];}
		else{$genre = null;$espece = null;}
		$req->execute(array('cdnom'=>$n['cdnom'], 'cdref'=>$n['cdref'], 'cdsup'=>$n['cdsup'], 'cdtaxsup'=>$n['cdtaxsup'], 'nom'=>$n['nom'], 'genre'=>$genre, 'espece'=>$espece, 'auteur'=>$n['auteur'], 'rang'=>$n['rang'], 'famille'=>$n['famille'], 'nomvern'=>$nomvern, 'locale'=>'non'));
	}
	$req->closeCursor();
}
function sup_famille($observa)
{
	$bdd = PDO2::getInstance();		
	$bdd->exec("DELETE FROM $observa.famille  WHERE cdnom IN (
					SELECT cdnom FROM $observa.famille
					INNER JOIN taxref.change USING(cdnom)
					WHERE typechange = 'RETRAIT') ");	
}
function sup_sfamille($observa)
{
	$bdd = PDO2::getInstance();		
	$bdd->exec("DELETE FROM $observa.sousfamille  WHERE cdnom IN (
					SELECT cdnom FROM $observa.sousfamille
					INNER JOIN taxref.change USING(cdnom)
					WHERE typechange = 'RETRAIT') ");	
}
function sup_genre($observa)
{
	$bdd = PDO2::getInstance();		
	$bdd->exec("DELETE FROM $observa.genre  WHERE cdnom IN (
					SELECT cdnom FROM $observa.genre
					INNER JOIN taxref.change USING(cdnom)
					WHERE typechange = 'RETRAIT') ");	
}
function sup_sgenre($observa)
{
	$bdd = PDO2::getInstance();		
	$bdd->exec("DELETE FROM $observa.sousgenre  WHERE cdnom IN (
					SELECT cdnom FROM $observa.sousgenre
					INNER JOIN taxref.change USING(cdnom)
					WHERE typechange = 'RETRAIT') ");	
}
function sup_tribu($observa)
{
	$bdd = PDO2::getInstance();		
	$bdd->exec("DELETE FROM $observa.tribu  WHERE cdnom IN (
					SELECT cdnom FROM $observa.tribu
					INNER JOIN taxref.change USING(cdnom)
					WHERE typechange = 'RETRAIT') ");	
}
function sup_stribu($observa)
{
	$bdd = PDO2::getInstance();		
	$bdd->exec("DELETE FROM $observa.soustribu  WHERE cdnom IN (
					SELECT cdnom FROM $observa.soustribu
					INNER JOIN taxref.change USING(cdnom)
					WHERE typechange = 'RETRAIT') ");	
}
function sup_liste($observa)
{
	$bdd = PDO2::getInstance();		
	$bdd->exec("DELETE FROM $observa.liste  WHERE cdnom IN (
					SELECT cdnom FROM $observa.liste
					INNER JOIN taxref.change USING(cdnom)
					WHERE typechange = 'RETRAIT') ");	
}
function recherche_table($tbl)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT cdnom, valinit, valfinal, champ FROM taxref.change
						INNER JOIN $tbl USING(cdnom)
						WHERE typechange = 'MODIFICATION' ");
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}
function mod_auteur($tbl,$cdnom,$val)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE $tbl SET auteur = :val WHERE cdnom = :cdnom ");
	$req->bindValue(':cdnom', $cdnom);
	$req->bindValue(':val', $val);
	$req->execute();
	$req->closeCursor();
}
function mod_nom($tbl,$cdnom,$val,$lbnom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE $tbl SET $lbnom = :val WHERE cdnom = :cdnom ");
	$req->bindValue(':cdnom', $cdnom);
	$req->bindValue(':val', $val);
	$req->execute();
	$req->closeCursor();
}
function mod_cdref($tbl,$cdnom,$val)
{
	$value = (!empty($val)) ? $val : null;
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE $tbl SET cdref = :val WHERE cdnom = :cdnom ");
	$req->bindValue(':cdnom', $cdnom);
	$req->bindValue(':val', $value);
	$req->execute();
	$req->closeCursor();
}
function mod_cdsup($tbl,$cdnom,$val,$lbcd)
{
	$value = (!empty($val)) ? $val : null;
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE $tbl SET $lbcd = :val WHERE cdnom = :cdnom ");
	$req->bindValue(':cdnom', $cdnom);
	$req->bindValue(':val', $value);
	$req->execute();
	$req->closeCursor();
}
function mod_cdtaxsup($tbl,$cdnom,$val)
{
	$value = (!empty($val)) ? $val : null;
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE $tbl SET cdtaxsup = :val WHERE cdnom = :cdnom ");
	$req->bindValue(':cdnom', $cdnom);
	$req->bindValue(':val', $value);
	$req->execute();
	$req->closeCursor();
}
function mod_nomvern($tbl,$cdnom,$val)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE $tbl SET nomvern = :val WHERE cdnom = :cdnom ");
	$req->bindValue(':cdnom', $cdnom);
	$req->bindValue(':val', $val);
	$req->execute();
	$req->closeCursor();
}
function recherche_modif_genre($observa)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT cdnom, nom FROM $observa.liste
						WHERE cdnom = cdref AND genre IS NULL AND rang != 'GN' ");
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}
function mod_modif_genre($observa,$genre,$espece,$cdnom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE $observa.liste SET genre = :genre, espece = :espece WHERE cdnom = :cdnom ");
	$req->bindValue(':genre', $genre);
	$req->bindValue(':espece', $espece);
	$req->bindValue(':cdnom', $cdnom);
	$req->execute();
	$req->closeCursor();
}
function recherche_liste($observa)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->prepare("SELECT change.cdnom, valfinal::int, l.nom, l.nomvern, l.auteur FROM taxref.change
						INNER JOIN referentiel.liste USING(cdnom)
						INNER JOIN $observa.liste AS l ON l.cdnom = change.valfinal::int 
						WHERE change.cdnom = valinit::int AND champ = 'CD_REF' AND observatoire = :observa ");
	$req->bindValue(':observa', $observa);
	$req->execute();
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}
function mod_liste_ref($cdnom,$val,$nom,$auteur,$nomvern)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE referentiel.liste SET cdnom = :val, nom = :nom, auteur = :auteur, nomvern = :nomvern WHERE cdnom = :cdnom ");
	$req->bindValue(':cdnom', $cdnom, PDO::PARAM_INT);
	$req->bindValue(':val', $val, PDO::PARAM_INT);
	$req->bindValue(':nom', $nom);
	$req->bindValue(':auteur', $auteur);
	$req->bindValue(':nomvern', $nomvern);
	$req->execute();
	$req->closeCursor();
}
function recherche_obs()
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT DISTINCT cdnom, valfinal FROM taxref.change
						INNER JOIN obs.obs USING(cdnom)
						WHERE cdnom = valinit::int AND champ = 'CD_REF' ");
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}
function mod_cdref_obs($cdnom,$val)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE obs.obs SET cdref = :val WHERE cdref = :cdnom ");
	$req->bindValue(':cdnom', $cdnom);
	$req->bindValue(':val', $val, PDO::PARAM_INT);
	$req->execute();
	$req->closeCursor();
}
function mod_cdnom_photo($cdnom,$val)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE site.photo SET cdnom = :val WHERE cdnom = :cdnom ");
	$req->bindValue(':cdnom', $cdnom);
	$req->bindValue(':val', $val, PDO::PARAM_INT);
	$req->execute();
	$req->closeCursor();
}
function mod_cdnom_son($cdnom,$val)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE site.son SET cdnom = :val WHERE cdnom = :cdnom ");
	$req->bindValue(':cdnom', $cdnom);
	$req->bindValue(':val', $val, PDO::PARAM_INT);
	$req->execute();
	$req->closeCursor();
}
function recherche_sensible()
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT DISTINCT cdnom, valfinal::int FROM taxref.change
						INNER JOIN referentiel.sensible USING(cdnom)
						WHERE cdnom = valinit::int AND champ = 'CD_REF' ");
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}
function mod_sensible($cdnom,$val)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE site.sensible SET cdnom = :val WHERE cdnom = :cdnom ");
	$req->bindValue(':cdnom', $cdnom, PDO::PARAM_INT);
	$req->bindValue(':val', $val, PDO::PARAM_INT);
	$req->execute();
	$req->closeCursor();
}
function recherche_critere()
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT DISTINCT cdnom, valfinal::int FROM taxref.change
						INNER JOIN vali.critere USING(cdnom)
						WHERE cdnom = valinit::int AND champ = 'CD_REF' ");
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}
function mod_critere($cdnom,$val)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE vali.critere SET cdnom = :val WHERE cdnom = :cdnom ");
	$req->bindValue(':cdnom', $cdnom, PDO::PARAM_INT);
	$req->bindValue(':val', $val, PDO::PARAM_INT);
	$req->execute();
	$req->closeCursor();
}

$json = file_get_contents('../../../../json/site.json');
$rjson = json_decode($json, true);

foreach($rjson['observatoire'] as $o)
{
	$observa = $o['nomvar'];
	
	$listechoix = recherche_choix($observa);
	foreach($listechoix as $c)
	{
		$choix = $c['choix'];
		$valchoix = $c['val'];

		//Ajout des nouveaux taxons
		$listerang = recherche_rang($observa,$valchoix,$choix);
		
		foreach($listerang as $n)
		{
			if($n['rang'] == 'FM') { insere_famille($observa,$valchoix,$choix); }
			if($n['rang'] == 'SBFM') { insere_sfamille($observa,$valchoix,$choix); }
			if($n['rang'] == 'GN') { insere_genre($observa,$valchoix,$choix); }
			if($n['rang'] == 'SSGN') { insere_sgenre($observa,$valchoix,$choix); }
			if($n['rang'] == 'TR') { insere_tribu($observa,$valchoix,$choix); }
			if($n['rang'] == 'SSTR') { insere_stribu($observa,$valchoix,$choix); }
		}
		insere_liste($observa,$valchoix,$choix);
	}
	//Retrait de taxons
	$rang = recherche_rangobserva($observa);
	foreach($rang as $n)
	{
		if($n['rang'] == 'FM') { sup_famille($observa); }
		if($n['rang'] == 'SBFM') { sup_sfamille($observa); }
		if($n['rang'] == 'GN') { sup_genre($observa); }
		if($n['rang'] == 'SSGN') { sup_sgenre($observa); }
		if($n['rang'] == 'TR') { sup_tribu($observa); }
		if($n['rang'] == 'SSTR') { sup_stribu($observa); }
	}
	sup_liste($observa);
	//Modification
	foreach($rang as $r)
	{
		if($r['rang'] == 'FM')
		{
			$tbl = $observa.'.famille';
			$tab = recherche_table($tbl);
			if(count($tab) > 0)
			{
				foreach($tab as $n)
				{
					if($n['champ'] == 'LB_AUTEUR') { mod_auteur($tbl,$n['cdnom'],$n['valfinal']); }
					if($n['champ'] == 'CD_SUP') { $lbcd = 'cdsup'; mod_cdsup($tbl,$n['cdnom'],$n['valfinal'],$lbcd); }
					if($n['champ'] == 'LB_NOM') { $lbnom = 'famille'; mod_nom($tbl,$n['cdnom'],$n['valfinal'],$lbnom); }
				}
			}
		}	
		if($r['rang'] == 'SBFM')
		{
			$tbl = $observa.'.sousfamille';
			$tab = recherche_table($tbl);
			if(count($tab) > 0)
			{
				foreach($tab as $n)
				{
					if($n['champ'] == 'LB_AUTEUR') { mod_auteur($tbl,$n['cdnom'],$n['valfinal']); }
					if($n['champ'] == 'CD_SUP') { $lbcd = 'cdsup'; mod_cdsup($tbl,$n['cdnom'],$n['valfinal'],$lbcd); }
					if($n['champ'] == 'LB_NOM') { $lbnom = 'famille'; mod_nom($tbl,$n['cdnom'],$n['valfinal'],$lbnom); }
				}
			}
		}
		if($r['rang'] == 'GN')
		{
			$tbl = $observa.'.genre';
			$tab = recherche_table($tbl);
			if(count($tab) > 0)
			{
				foreach($tab as $n)
				{
					if($n['champ'] == 'LB_AUTEUR') { mod_auteur($tbl,$n['cdnom'],$n['valfinal']); }
					if($n['champ'] == 'CD_SUP') { $lbcd = 'cdsup'; mod_cdsup($tbl,$n['cdnom'],$n['valfinal'],$lbcd); }
					if($n['champ'] == 'LB_NOM') { $lbnom = 'genre'; mod_nom($tbl,$n['cdnom'],$n['valfinal'],$lbnom); }
					if($n['champ'] == 'CD_TAXSUP') { mod_cdtaxsup($tbl,$n['cdnom'],$n['valfinal']); }
				}
			}
		}
		if($r['rang'] == 'SSGN')
		{
			$tbl = $observa.'.sousgenre';
			$tab = recherche_table($tbl);
			if(count($tab) > 0)
			{
				foreach($tab as $n)
				{
					if($n['champ'] == 'LB_AUTEUR') { mod_auteur($tbl,$n['cdnom'],$n['valfinal']); }
					if($n['champ'] == 'CD_SUP') { $lbcd = 'genre'; mod_cdsup($tbl,$n['cdnom'],$n['valfinal'],$lbcd); }
					if($n['champ'] == 'LB_NOM') { $lbnom = 'sousgenre'; mod_nom($tbl,$n['cdnom'],$n['valfinal'],$lbnom); }
				}
			}
		}
		if($r['rang'] == 'TR')
		{
			$tbl = $observa.'.tribu';
			$tab = recherche_table($tbl);
			if(count($tab) > 0)
			{
				foreach($tab as $n)
				{
					if($n['champ'] == 'LB_AUTEUR') { mod_auteur($tbl,$n['cdnom'],$n['valfinal']); }
					if($n['champ'] == 'CD_SUP') { $lbcd = 'cdsup'; mod_cdsup($tbl,$n['cdnom'],$n['valfinal'],$lbcd); }
					if($n['champ'] == 'LB_NOM') { $lbnom = 'tribu'; mod_nom($tbl,$n['cdnom'],$n['valfinal'],$lbnom); }
					if($n['champ'] == 'CD_TAXSUP') { mod_cdtaxsup($tbl,$n['cdnom'],$n['valfinal']); }
				}
			}
		}
		if($r['rang'] == 'SSTR')
		{
			$tbl = $observa.'.soustribu';
			$tab = recherche_table($tbl);
			if(count($tab) > 0)
			{
				foreach($tab as $n)
				{
					if($n['champ'] == 'LB_AUTEUR') { mod_auteur($tbl,$n['cdnom'],$n['valfinal']); }
					if($n['champ'] == 'CD_SUP') { $lbcd = 'cdsup'; mod_cdsup($tbl,$n['cdnom'],$n['valfinal'],$lbcd); }
					if($n['champ'] == 'LB_NOM') { $lbnom = 'soustribu'; mod_nom($tbl,$n['cdnom'],$n['valfinal'],$lbnom); }
					if($n['champ'] == 'CD_TAXSUP') { mod_cdtaxsup($tbl,$n['cdnom'],$n['valfinal']); }
				}
			}
		}
	}
	$tbl = $observa.'.liste';
	$tab = recherche_table($tbl);
	if(count($tab) > 0)
	{
		foreach($tab as $n)
		{
			if($n['champ'] == 'LB_AUTEUR') { mod_auteur($tbl,$n['cdnom'],$n['valfinal']); }
			if($n['champ'] == 'CD_SUP') { $lbcd = 'cdsup'; mod_cdsup($tbl,$n['cdnom'],$n['valfinal'],$lbcd); }
			if($n['champ'] == 'LB_NOM') { $lbnom = 'nom'; mod_nom($tbl,$n['cdnom'],$n['valfinal'],$lbnom); }
			if($n['champ'] == 'CD_TAXSUP') { mod_cdtaxsup($tbl,$n['cdnom'],$n['valfinal']); }
			if($n['champ'] == 'CD_REF') { mod_cdref($tbl,$n['cdnom'],$n['valfinal']); }
			if($n['champ'] == 'NOM_VERN') 
			{ 
				$tabnv = explode(',',$n['valfinal']);
				$nomvern = preg_replace('/\((.*)\)/', '', $tabnv[0]);
				mod_nomvern($tbl,$n['cdnom'],$nomvern); 
			}
		}
	}

	//rajout genre pour cdnom = cdref dans liste
	$tab = recherche_modif_genre($observa);
	if(count($tab) > 0)
	{
		foreach($tab as $n)						
		{
			$tmp = explode(' ',$n['nom']); $genre = $tmp[0]; $espece = $tmp[1];
			mod_modif_genre($observa,$genre,$espece,$n['cdnom']);
		}	
	}
	//Modification liste referentiel
	$tab = recherche_liste($observa);
	if(count($tab) > 0)
	{
		foreach($tab as $n)						
		{
			mod_liste_ref($n['cdnom'],$n['valfinal'],$n['nom'],$n['auteur'],$n['nomvern']);
		}	
	}
}
//fin observa
//Modification obs, photo, son
$tab = recherche_obs();
if(count($tab) > 0)
{
	foreach($tab as $n)						
	{
		mod_cdref_obs($n['cdnom'],$n['valfinal']);
		mod_cdnom_photo($n['cdnom'],$n['valfinal']);
		mod_cdnom_son($n['cdnom'],$n['valfinal']);
	}	
}
//Modification espece sensible	
$tab = recherche_sensible();
if(count($tab) > 0)
{
	foreach($tab as $n)						
	{
		mod_sensible($n['cdnom'],$n['valfinal']);
	}	
}
//Modification critere validation	
$tab = recherche_critere();
if(count($tab) > 0)
{
	foreach($tab as $n)						
	{
		mod_critere($n['cdnom'],$n['valfinal']);
	}	
}

$retour['statut'] = 'Oui';

echo json_encode($retour);