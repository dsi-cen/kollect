<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function cdref_or($idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT cdref, validation, vali FROM obs.obs 
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref 
						WHERE idobs = :idobs ");
	$req->bindValue(':idobs', $idobs, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function cherchenb($idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idligne, ndiff, male, femelle FROM obs.ligneobs WHERE idobs = :idobs ");
	$req->bindValue(':idobs', $idobs, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function cherchenbor($idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT nb FROM obs.obs WHERE idobs = :idobs ");
	$req->bindValue(':idobs', $idobs, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function modif_obs($idobs,$cdnom,$cdref,$iddet,$dates,$rq,$vali,$nb,$statutobs,$idetude,$idproto,$nomvar,$nom_cite)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("UPDATE obs.obs SET cdnom = :cdnom, cdref = :cdref, iddet = :iddet, nb = :nb, rqobs = :rq, validation = :vali, datesaisie = :datesaisie, observa = :observa, statutobs = :statut, idprotocole = :idproto, idetude = :idetude, nom_cite = :nom_cite WHERE idobs = :idobs ");
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':cdnom', $cdnom);
	$req->bindValue(':iddet', $iddet);
	$req->bindValue(':vali', $vali);
	$req->bindValue(':datesaisie', $dates);
	$req->bindValue(':observa', $nomvar);
	$req->bindValue(':cdref', $cdref);
	$req->bindValue(':rq', $rq);
	$req->bindValue(':nb', $nb);
	$req->bindValue(':statut', $statutobs);
	$req->bindValue(':idproto', $idproto);
	$req->bindValue(':idetude', $idetude);
    $req->bindValue(':nom_cite', $nom_cite);
	$req->execute();
	$req->closeCursor();
}
function modif_obsdeux($idobs,$nbmod,$dates)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("UPDATE obs.obs SET nb = :nb, datesaisie = :datesaisie WHERE idobs = :idobs ");
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':datesaisie', $dates);
	$req->bindValue(':nb', $nbmod);
	//$req->bindValue(':vali', $vali);
	$req->execute();
	$req->closeCursor();
}
function modif_listeob($cdref,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("UPDATE $nomvar.liste SET locale = :locale WHERE cdref = :cdref ");
	$req->bindValue(':cdref', $cdref);
	$req->bindValue(':locale', 'oui');
	$req->execute();
	$req->closeCursor();
}
function insere_lister($cdref,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO referentiel.liste (cdnom,nom,auteur,nomvern,observatoire)
						SELECT cdnom, nom, auteur, nomvern, '$nomvar' AS observatoire FROM $nomvar.liste
						WHERE cdref = :cdref AND cdref = cdnom AND rang = 'ES' ");
	$req->bindValue(':cdref', $cdref);
	$req->execute();
	$req->closeCursor();
}
function modif($idobs,$idmembre,$nom,$datem,$cdref)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO site.modif (typeid, numid, typemodif, modif, datemodif, idmembre)
						VALUES(:typeid, :id, :type, :modif, :datem, :idm) ");
	$req->bindValue(':id', $cdref);
	$req->bindValue(':typeid', 'Espèce');
	$req->bindValue(':type', 'Ajout espèce locale en saisie');
	$req->bindValue(':modif', 'Ajout de '.$nom.'. idobs = '.$idobs.'');
	$req->bindValue(':datem', $datem);
	$req->bindValue(':idm', $idmembre);
	$req->execute();
	$req->closeCursor();
}
function modif_ligneobs($idligne,$stade,$ndiff,$m,$f,$denom,$idetat,$idmethode,$idpros,$idstbio,$nbmin,$nbmax,$sexe,$tdenom, $idcomp)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("UPDATE obs.ligneobs SET stade = :stade, ndiff = :ndiff, male = :m, femelle = :f, denom = :denom, idetatbio = :etat, idmethode = :meth, idpros = :pros, idstbio = :bio, nbmin = :nbmin, nbmax = :nbmax, sexe = :sexe, tdenom = :tdenom, idcomp = :idcomp WHERE idligne = :idligne ");
	$req->bindValue(':idligne', $idligne);
	$req->bindValue(':stade', $stade);
	$req->bindValue(':ndiff', $ndiff);
	$req->bindValue(':m', $m);
	$req->bindValue(':f', $f);
	$req->bindValue(':denom', $denom);
	$req->bindValue(':etat', $idetat);
	$req->bindValue(':meth', $idmethode);
	$req->bindValue(':pros', $idpros);
	$req->bindValue(':bio', $idstbio);
	$req->bindValue(':nbmin', $nbmin);
	$req->bindValue(':nbmax', $nbmax);
	$req->bindValue(':sexe', $sexe);
	$req->bindValue(':tdenom', $tdenom);
    $req->bindValue(':idcomp', $idcomp);
	$req->execute();
	$req->closeCursor();
}
function insere_ligneobs($idobs,$stade,$ndiff,$m,$f,$denom,$idetat,$idmethode,$idpros,$idstbio,$nbmin,$nbmax,$sexe,$tdenom,$idcomp)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO obs.ligneobs (idobs, stade, ndiff, male, femelle, denom, idetatbio, idmethode, idpros, idstbio, nbmin, nbmax, sexe, tdenom, idcomp)
						VALUES(:idobs, :stade, :ndiff, :m, :f, :denom, :etat, :meth, :pros, :bio, :nbmin, :nbmax, :sexe, :tdenom, :idcomp) ");
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':stade', $stade);
	$req->bindValue(':ndiff', $ndiff);
	$req->bindValue(':m', $m);
	$req->bindValue(':f', $f);
	$req->bindValue(':denom', $denom);
	$req->bindValue(':etat', $idetat);
	$req->bindValue(':meth', $idmethode);
	$req->bindValue(':pros', $idpros);
	$req->bindValue(':bio', $idstbio);
	$req->bindValue(':nbmin', $nbmin);
	$req->bindValue(':nbmax', $nbmax);
	$req->bindValue(':sexe', $sexe);
	$req->bindValue(':tdenom', $tdenom);
    $req->bindValue(':idcomp', $idcomp);
	if ($req->execute())
	{
		$idligneobs = $bdd->lastInsertId('obs.ligneobs_idligne_seq');
	}
	$req->closeCursor();
	return $idligneobs;		
}
function insere_identif($idligneobs,$idobs,$idfiche,$dates)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO obs.identif (idligne, idobs, idfiche, dates) VALUES(:idligne, :idobs, :idfiche, :dates) ");
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':idligne', $idligneobs);
	$req->bindValue(':idfiche', $idfiche);
	$req->bindValue(':dates', $dates);
	$req->execute();
	$req->closeCursor();
}
function liste_photo($cdref)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT COUNT(idphoto) AS nb FROM site.photo WHERE cdnom = :cdnom ");
	$req->bindValue(':cdnom', $cdref);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;
}
function chp_fiche($idfiche)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT codecom, date1, idobser FROM obs.fiche WHERE idfiche = :idfiche ");
	$req->bindValue(':idfiche', $idfiche);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function habitat($idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT cdhab FROM obs.obshab WHERE idobs = :idobs ");
	$req->bindValue(':idobs', $idobs);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function mod_habitat($idobs,$cdhab,$cdref)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("UPDATE obs.obshab SET cdhab = :cdhab, cdnom = :cdnom WHERE idobs = :idobs ");
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':cdhab', $cdhab);
	$req->bindValue(':cdnom', $cdref);
	$req->execute();
	$req->closeCursor();
}
function insere_habitat($idobs,$cdhab,$cdref)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO obs.obshab (idobs, cdhab, cdnom) VALUES(:idobs, :cdhab, :cdnom) ");
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':cdhab', $cdhab);
	$req->bindValue(':cdnom', $cdref);
	$req->execute();
	$req->closeCursor();
}
function insere_photo($cdref,$idobser,$datep,$codecom,$stade,$nomphoto,$dates,$sexe,$obser,$idobs,$ordre)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO site.photo (cdnom, idobser, datephoto, codecom, stade, nomphoto, datesaisie, sexe, observatoire, idobs, ordre) VALUES(:cdnom, :idobser, :datep, :codecom, :stade, :nom, :dates, :sexe, :obser, :idobs, :ordre) ");
	$req->bindValue(':cdnom', $cdref);
	$req->bindValue(':idobser', $idobser);
	$req->bindValue(':datep', $datep);
	$req->bindValue(':codecom', $codecom);
	$req->bindValue(':stade', $stade);
	$req->bindValue(':nom', $nomphoto);
	$req->bindValue(':dates', $dates);
	$req->bindValue(':sexe', $sexe);
	$req->bindValue(':obser', $obser);
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':ordre', $ordre);
	$req->execute();
	$req->closeCursor();
}
function rphoto($idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idphoto, observatoire FROM site.photo WHERE idobs = :idobs ");
	$req->bindValue(':idobs', $idobs);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function modif_photo($idphoto,$cdref,$stade,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("UPDATE site.photo SET cdnom = :cdnom, stade = :stade, observatoire = :observa WHERE idphoto = :idphoto ");
	$req->bindValue(':idphoto', $idphoto, PDO::PARAM_INT);
	$req->bindValue(':cdnom', $cdref);
	$req->bindValue(':stade', $stade);
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$req->closeCursor();
}
function rechercheplte($idobs,$stade)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT COUNT(*) FROM obs.obsplte WHERE idobs = :idobs AND stade = :stade ");
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':stade', $stade);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;
}
function insere_obsplte($idobs,$stade,$nbplte,$cdnom)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO obs.obsplte (idobs,nb,cdnom,stade) VALUES(:idobs, :nb, :cdnom, :stade) ");
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':stade', $stade);
	$req->bindValue(':nb', $nbplte);
	$req->bindValue(':cdnom', $cdnom);
	$req->execute();
	$req->closeCursor();
}
function recherchepiaf($idobs,$stade)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idaves, code FROM obs.aves WHERE idobs = :idobs AND stade = :stade ");
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':stade', $stade);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function modif_aves($idaves,$code)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("UPDATE obs.aves SET code = :code WHERE idaves = :idaves ");
	$req->bindValue(':idaves', $idaves);
	$req->bindValue(':code', $code);
	$req->execute();
	$req->closeCursor();
}
function insere_aves($idobs,$stade,$code)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO obs.aves (idobs, code, stade) VALUES(:idobs, :code, :stade) ");
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':stade', $stade);
	$req->bindValue(':code', $code);
	$req->execute();
	$req->closeCursor();
}
function sup_aves($idaves)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("DELETE FROM obs.aves WHERE idaves = :idaves ");
	$req->bindValue(':idaves', $idaves);
	$req->execute();
	$req->closeCursor();
}
function recherchemort($idobs,$stade)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idmort, mort FROM obs.obsmort WHERE idobs = :idobs AND stade = :stade ");
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':stade', $stade);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function modif_mort($idmort,$mort)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("UPDATE obs.obsmort SET mort = :mort WHERE idmort = :idmort ");
	$req->bindValue(':idmort', $idmort);
	$req->bindValue(':mort', $mort);
	$req->execute();
	$req->closeCursor();
}
function insere_mort($idobs,$stade,$mort)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO obs.obsmort (idobs, mort, stade) VALUES(:idobs, :mort, :stade) ");
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':stade', $stade);
	$req->bindValue(':mort', $mort);
	$req->execute();
	$req->closeCursor();
}
function sup_mort($idmort)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("DELETE FROM obs.obsmort WHERE idmort = :idmort ");
	$req->bindValue(':idmort', $idmort);
	$req->execute();
	$req->closeCursor();
}
function recherchecol($idobs,$stade)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idcol FROM obs.obscoll WHERE idobs = :idobs AND stade = :stade ");
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':stade', $stade);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function modif_obscoll($idcol,$detcol,$typegen,$pregen,$codegen,$sexe,$detgen,$stade)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("UPDATE obs.obscoll SET iddetcol = :detcol, iddetgen = :detgen, codegen = :codegen, sexe = :sexe, idprep = :pregen, typedet = :typedet, stade = :stade WHERE idcol = :idcol ");
	$req->bindValue(':idcol', $idcol);
	$req->bindValue(':detcol', $detcol);
	$req->bindValue(':detgen', $detgen);
	$req->bindValue(':pregen', $pregen);
	$req->bindValue(':codegen', $codegen);
	$req->bindValue(':sexe', $sexe);
	$req->bindValue(':typedet', $typegen);
	$req->bindValue(':stade', $stade);
	$req->execute();
	$req->closeCursor();
}
function insere_obscoll($idobs,$detcol,$typegen,$pregen,$codegen,$sexe,$detgen,$stade)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO obs.obscoll (idobs, iddetcol, iddetgen, codegen, sexe, idprep, typedet, stade) VALUES(:idobs, :detcol, :detgen, :codegen, :sexe, :pregen, :typedet, :stade) ");
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':detcol', $detcol);
	$req->bindValue(':detgen', $detgen);
	$req->bindValue(':pregen', $pregen);
	$req->bindValue(':codegen', $codegen);
	$req->bindValue(':sexe', $sexe);
	$req->bindValue(':typedet', $typegen);
	$req->bindValue(':stade', $stade);
	$req->execute();
	$req->closeCursor();
}
function vali_auto($idobs,$typev)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT vali.validation(:idobs,:vali) ");
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':vali', $typev);
	$req->execute();
	$req->closeCursor();
}

if(isset($_POST['idobs']) && isset($_POST['idligne']) && isset($_POST['cdnom'])) 
{
	$idobs = $_POST['idobs'];
	$idligne = $_POST['idligne'];
	$rq = htmlspecialchars($_POST['rq']);
	$dates = date("Y-m-d");				
	$cdnom = $_POST['cdnom'];
	$cdref = $_POST['cdref'];
	$nomvar = $_POST['sel'];
	$idetude = $_POST['etude'];
	$idproto = $_POST['protocol'];
	$stade = $_POST['stade'];
	$idetat = $_POST['etatbio'];
	$idmethode = $_POST['obsmethode'];
	$idpros = (isset($_POST['obscoll'])) ? $_POST['obscoll'] : 0;
	$idstbio = (isset($_POST['bio'])) ? $_POST['bio'] : 0;
	$statutobs = $_POST['statutobs'];
	$iddet = $_POST['iddet'];
	
	if($statutobs == 'Pr')
	{
		$denom = $_POST['denom'];
		$tdenom = (isset($_POST['tdenom'])) ? $_POST['tdenom'] : 'NSP';
		if($denom == 'Co')
		{
			if($tdenom == 'IND' || $tdenom == 'NSP')
			{
				$ndiff = ($_POST['ndiff'] != '') ? $_POST['ndiff'] : 0;
				$m = (isset($_POST['male']) && $_POST['male'] != '') ? $_POST['male'] : 0;
				$f = (isset($_POST['femelle']) && $_POST['femelle'] != '') ? $_POST['femelle'] : 0;
				$nb = $ndiff + $m + $f;
				if($nb == 0) { $nb = 1; }
				if($ndiff != 0 && $m != 0 && $f != 0) { $sexe = 5; }
				elseif($ndiff != 0 && $m != 0 && $f != 0) { $sexe = 5; }
				elseif($ndiff == 0 && $m != 0 && $f != 0) { $sexe = 5; }
				elseif($ndiff != 0 && $m != 0 && $f == 0) { $sexe = 5; }
				elseif($ndiff != 0 && $m == 0 && $f != 0) { $sexe = 5; }
				elseif($ndiff != 0 && $m == 0 && $f == 0) { $sexe = 0; }
				elseif($ndiff == 0 && $m != 0 && $f == 0) { $sexe = 3; }
				elseif($ndiff == 0 && $m == 0 && $f != 0) { $sexe = 2; }
				elseif($ndiff == 0 && $m == 0 && $f == 0) { $sexe = 0; }
				$nbmax = $nb; $nbmin = $nb;
			}
			else
			{
				$nbmax = $_POST['nbmax']; $nbmin = $_POST['nbmin'];
				if($tdenom == 'CPL') 
				{
					$ndiff = null; $m = $nbmin; $f = $nbmin; $nb = $m + $f; $sexe = 5;
				}
				else
				{
					$ndiff = null; $m = null; $f = null; $nb = $nbmin; $sexe = 6;
				}						
			}
		}
		elseif($denom == 'Es')
		{
			$nbmax = $_POST['nbmax']; $nbmin = $_POST['nbmin'];
			if($tdenom == 'IND' || $tdenom == 'NSP')
			{
				$ndiff = ($_POST['ndiff'] != '') ? $_POST['ndiff'] : 0;
				$m = (isset($_POST['male']) && $_POST['male'] != '') ? $_POST['male'] : 0;
				$f = (isset($_POST['femelle']) && $_POST['femelle'] != '') ? $_POST['femelle'] : 0;
				if($ndiff != 0 || $m != 0 || $f != 0) 
				{
					$nb = $ndiff + $m + $f; $nbmax = $nb; $nbmin = $nb;
					if($ndiff != 0 && $m != 0 && $f != 0) { $sexe = 5; }
					elseif($ndiff != 0 && $m != 0 && $f != 0) { $sexe = 5; }
					elseif($ndiff == 0 && $m != 0 && $f != 0) { $sexe = 5; }
					elseif($ndiff != 0 && $m != 0 && $f == 0) { $sexe = 5; }
					elseif($ndiff != 0 && $m == 0 && $f != 0) { $sexe = 5; }
					elseif($ndiff != 0 && $m == 0 && $f == 0) { $sexe = 0; }
					elseif($ndiff == 0 && $m != 0 && $f == 0) { $sexe = 3; }
					elseif($ndiff == 0 && $m == 0 && $f != 0) { $sexe = 2; }
				} 
				else
				{
					$ndiff = null; $m = null; $f = null; $sexe = 6;	
					if(!empty($nbmin) && !empty($nbmax)) { $nb = ($nbmax - ($nbmin - 1)) / 2; }
					elseif(!empty($nbmin) && empty($nbmax)) { $nb = $nbmin; }
				}
			}
			else
			{
				$ndiff = null; $m = null; $f = null;
				$sexe = 6;
				if(!empty($nbmin) && !empty($nbmax)) { $nb = ($nbmax - ($nbmin - 1)) / 2; }
				elseif(!empty($nbmin) && empty($nbmax)) { $nb = $nbmin; }
				if($tdenom == 'CPL')
				{
					$m = $nb; $f = $nb; $nb = $nb * 2; $sexe = 5;
				}
			}						
		}
		elseif($denom == 'NSP')
		{
			$sexe = 6;
			$ndiff = 1; $m = null; $f = null;
			$nbmax = 1; $nbmin = 1; $nb = 1;
		}				
	}
	else
	{
		$nb = 0;
	}
	if($_POST['nb'] != '')
	{
		$nbtmp = cherchenb($idobs);
		$nbor = 0;
		foreach($nbtmp as $n)
		{
			if($n['idligne'] != $idligne)
			{
				$nbor += $n['ndiff'] + $n['male'] + $n['femelle'];
			}
		}
		$nb = $nbor + $nb;
	}	
	
	if($idligne != 'Nouv') // modification de l'obs.
	{
		$cdrefor = cdref_or($idobs);
		if($cdrefor['cdref'] != $cdref)
		{
			if($_POST['validateur'] == 'oui')
			{
				if($_POST['tvali'] == 0 || $_POST['tvali'] == '')
				{
					$vali = 1;				
				}
				else
				{
					$vali = ($_POST['newsp'] == 'oui') ? 7 : 6;
				}
			}
			else
			{
				$vali = 5;
			}
		}
		else
		{
			$vali = $cdrefor['validation'];
		}
		$nom_cite = $_POST['nom_cite'];
		modif_obs($idobs,$cdnom,$cdref,$iddet,$dates,$rq,$vali,$nb,$statutobs,$idetude,$idproto,$nomvar,$nom_cite);
		if($_POST['newsp'] == 'oui')
		{
			modif_listeob($cdref,$nomvar);
			insere_lister($cdref,$nomvar);
			$datem = date("Y-m-d H:i:s");
			$idmembre = $_POST['idm'];
			modif($idobs,$idmembre,$nom,$datem,$cdref);
		}
		else
		{
			if($_POST['validateur'] == 'oui' && $cdrefor['cdref'] != $cdref)
			{
				if($_POST['tvali'] == 1 || $_POST['tvali'] == 2)
				{
					vali_auto($idobs,$_POST['tvali']);
					if($cdrefor['vali'] == 1 || $cdrefor['vali'] == 2)
					{
						//a faire supprimre ancien cdref de grille si ancien tvali 1 ou 2
					}
				}
			}
		}
		//modif ligneobs
		if($statutobs == 'Pr')
		{
            $idcomp = $_POST['comportement'];
			modif_ligneobs($idligne,$stade,$ndiff,$m,$f,$denom,$idetat,$idmethode,$idpros,$idstbio,$nbmin,$nbmax,$sexe,$tdenom,$idcomp);
			$idligneobs = $idligne;
			//habitat
			$habitat = habitat($idobs);
			if(isset($_POST['cdhab']) && $_POST['cdhab'] != '')
			{
				if(!empty($habitat['cdhab']))
				{
					if($habitat['cdhab'] != $_POST['cdhab'])
					{
						mod_habitat($idobs,$_POST['cdhab'],$cdref);
					}					
				}
				else
				{
					$retour['hab'] = $_POST['cdhab'];
					insere_habitat($idobs,$_POST['cdhab'],$cdref);
				}
			}			
		}
		// si photo existe + A faire peut-être : changement observatoire ?
		$existphto = rphoto($idobs);
		if(count($existphto) > 0)
		{
			foreach($existphto as $n)
			{
				modif_photo($n['idphoto'],$cdref,$stade,$nomvar);
			}
		}
	}
	else // insertion nouveau stade
	{
		$idfiche = $_POST['idfiche'];
		if($_POST['denom'] != 'NSP')
		{
			$nbtmp = cherchenbor($idobs);
			$nbor = $nbtmp['nb'];
			$nbmod = $nbor + $nb;
			modif_obsdeux($idobs,$nbmod,$dates);
		}
        $idcomp = $_POST['comportement'];
		$idligneobs = insere_ligneobs($idobs,$stade,$ndiff,$m,$f,$denom,$idetat,$idmethode,$idpros,$idstbio,$nbmin,$nbmax,$sexe,$tdenom,$idcomp);
		insere_identif($idligneobs,$idobs,$idfiche,$dates);
		$retour['stade'] = $_POST['stadeval'];
	}
	//plante hote
	if(isset($_POST['cdnombota']) && $_POST['cdnombota'] != '')
	{
		$plante = rechercheplte($idobs,$stade);
		if($plante == 0)
		{
			$cdnombota = explode(",", $_POST['cdnombota']);
			if(count($cdnombota) > 1)
			{
				$nbplte = explode(",", $_POST['nbplte']);
				foreach($cdnombota as $n)
				{
					$tabcdnom[] = $n;
				}
				foreach($nbplte as $n)
				{
					$tabnbplte[] = $n;
				}
				$tabplte = array_combine($tabcdnom,$tabnbplte);
				foreach($tabplte as $cle => $n)
				{
					$nbplte = ($n != '') ? $n : null;
					insere_obsplte($idobs,$stade,$nbplte,$cle);
				}
			}
			else
			{
				$nbplte = ($_POST['nbplte'] != '') ? $_POST['nbplte'] : null;
				insere_obsplte($idobs,$stade,$nbplte,$cdnombota[0]);
			}
		}
	}
	//code nicheur piaf
	/*if(isset($_POST['indnid']) && $_POST['indnid'] != 0)
	{
		$aves = recherchepiaf($idobs,$stade);
		if($aves['code'] == '')
		{
			insere_aves($idobs,$stade,$_POST['indnid']);
		}
		else
		{
			if($aves['code'] != $_POST['indnid'])
			{
				modif_aves($aves['idaves'],$_POST['indnid']);
			}
		}		
	}*/
	if(isset($_POST['indnid']))
	{
		$aves = recherchepiaf($idobs,$stade);
		if($aves['code'] == '' && $_POST['indnid'] != 0)
		{
			insere_aves($idobs,$stade,$_POST['indnid']);
		}
		else
		{
			if($aves['code'] != $_POST['indnid'])
			{
				if($_POST['indnid'] != 0)
				{
					modif_aves($aves['idaves'],$_POST['indnid']);
				}
				elseif($_POST['indnid'] == 0)
				{
					sup_aves($aves['idaves']);
				}
			}
		}		
	}
	//cause de la mort
	if(isset($_POST['mort']) && ($_POST['mort'] != 0 && $_POST['mort'] != ''))
	{
		$mort = recherchemort($idobs,$stade);
		if($mort['mort'] == '' && $_POST['mort'] != 1)
		{
			insere_mort($idobs,$stade,$_POST['mort']);
		}
		else
		{
			if($mort['mort'] != $_POST['mort'] && $_POST['mort'] != 1)
			{
				modif_mort($mort['idmort'],$_POST['mort']);
			}
			elseif($mort['mort'] != $_POST['mort'] && $_POST['mort'] == 1)
			{
				sup_mort($mort['idmort']);
			}
		}
	}
	//collection - genitalia
	if(!empty($_POST['iddetcol']) || !empty($_POST['idprep']))
	{
		$typegen = (isset($_POST['typegen'])) ? $_POST['typegen'] : '';
		$codegen = (isset($_POST['codegen'])) ? $_POST['codegen'] : '';
		$sexegen = (isset($_POST['sexegen'])) ? $_POST['sexegen'] : '';
		$detcol = (!empty($_POST['iddetcol'])) ? $_POST['iddetcol'] : null;
		$pregen = (!empty($_POST['idprep'])) ? $_POST['idprep'] : null;
		$detgen = (!empty($_POST['iddetgen'])) ? $_POST['iddetgen'] : null;
		$col = recherchecol($idobs,$stade);
		if($col['idcol'] == '')
		{
			insere_obscoll($idobs,$detcol,$typegen,$pregen,$codegen,$sexegen,$detgen,$stade);
		}
		else
		{
			modif_obscoll($col['idcol'],$detcol,$typegen,$pregen,$codegen,$sexegen,$detgen,$stade);
		}	
	}
	// si photo
	if(isset($_POST['image-data']) && !empty($_POST['image-data']))
	{
		$retour['photo'] = 'oui';		
		$dossier_destination1 = '../../../photo/P800/'.$nomvar.'/';
		$dossier_destination2 = '../../../photo/P400/'.$nomvar.'/';
		$dossier_destination3 = '../../../photo/P200/'.$nomvar.'/';
		$nbphoto = liste_photo($cdref);
		$nomphoto = $nomvar . time();
		$nomfichier = $nomphoto.'.jpg';
		$img = $_POST['image-data'];
		$exp = explode(',', $img);
		$data = base64_decode($exp[1]);
		$file = $dossier_destination1 . $nomfichier;
		if(file_put_contents($file, $data) !== false) 
		{
			require '../../../lib/RedimImageJpg.php';
			$orien = $_POST['orien'];
			$repSource = $dossier_destination1;
			$repDest = $dossier_destination2;
			$redim = ($orien == 'paysage') ? fctredimimage(400,266,$repDest,'',$repSource,$nomfichier) : fctredimimage(200,300,$repDest,'',$repSource,$nomfichier);
			$repDest = $dossier_destination3;
			$redim = ($orien == 'paysage') ? fctredimimage(200,133,$repDest,'',$repSource,$nomfichier) : fctredimimage(100,150,$repDest,'',$repSource,$nomfichier);
			if ($redim == true) 
			{ 
				//enregistrement bdd
				$idfiche = $_POST['idfiche'];
				$pfiche = chp_fiche($idfiche);							
				
				$sexe = $_POST['sexe'];
				$dates = date("Y-m-d H:i:s");							
				$ordre = ($nbphoto == 0) ? 1 : $nbphoto + 1 ;
				if(isset($_POST['opph']))
				{
					$idobserp = ($_POST['opph'] != $pfiche['idobser']) ? $_POST['opph'] : $pfiche['idobser'];								
				}
				else
				{
					$idobserp = $pfiche['idobser'];
				}							
				insere_photo($cdref,$idobserp,$pfiche['date1'],$pfiche['codecom'],$stade,$nomphoto,$dates,$sexe,$nomvar,$idobs,$ordre);
			}
		}
	}	
	$retour['statut'] = 'Oui';
}
else
{
	$retour['statut'] = 'Problème';
}
echo json_encode($retour);
?>