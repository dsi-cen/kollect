<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
session_start();

function recupobs($idobs,$idm)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT cdref, idobser, date1, codecom, observa, stade FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN obs.ligneobs USING(idobs)
						INNER JOIN referentiel.observateur USING(idobser)
						WHERE idobs = :idobs AND idm = :idm ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':idm', $idm);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;		
}
function liste_photo($cdref)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT COUNT(idphoto) AS nb FROM site.photo WHERE cdnom = :cdnom ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':cdnom', $cdref);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;
}
function recupnom($idpdet)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT nomphoto, observa FROM site.photodet 
						INNER JOIN obs.obs USING(idobs)
						WHERE idpdet = :idpdet ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idpdet', $idpdet);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;		
}
function mod_photodet($idpdet,$idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE site.photodet SET idobs = :idobs WHERE idpdet = :idpdet ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idpdet', $idpdet);
	$req->bindValue(':idobs', $idobs);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;
}
function insere_photo($cdref,$idobser,$datep,$codecom,$stade,$nomphoto,$dates,$sexe,$obser,$idobs,$ordre)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO site.photo (cdnom, idobser, datephoto, codecom, stade, nomphoto, datesaisie, sexe, observatoire, idobs, ordre) VALUES(:cdnom, :idobser, :datep, :codecom, :stade, :nom, :dates, :sexe, :obser, :idobs, :ordre) ") or die(print_r($bdd->errorInfo()));
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
function insere_com($idpdet,$idm,$com,$datecom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO site.comdet (idpdet,idm,commentaire,datecom) VALUES(:idpdet, :idm, :com, :datecom) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idm', $idm);
	$req->bindValue(':idpdet', $idpdet);
	$req->bindValue(':com', $com);
	$req->bindValue(':datecom', $datecom);
	$ok = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $ok;	
}
function chercheidm($idpdet)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idm FROM site.comdet WHERE idpdet = :idpdet ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idpdet', $idpdet, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function insere_notif($idpdet,$idmor)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO site.notif (idm,type,idtype)
						VALUES(:idm, :type, :idpdet) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idm', $idmor);
	$req->bindValue(':idpdet', $idpdet);
	$req->bindValue(':type', 'det');
	$ok = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
}

if(isset($_POST['idpdet']))
{
	$idpdet = $_POST['idpdet'];
	$idobs = $_POST['idobs'];
	$idm = $_SESSION['idmembre'];
	$idmor = $_POST['idmor'];	
	
	$obs = recupobs($idobs,$idm);
	if(!empty($obs['cdref']))
	{
		$vali = mod_photodet($idpdet,$idobs);
		$nphoto = recupnom($idpdet);
		$retour['nomphoto'] = $nphoto;		
		
		$nbphoto = liste_photo($obs['cdref']);
		$ordre = ($nbphoto == 0) ? 1 : $nbphoto + 1 ;
		$dates = date("Y-m-d H:i:s");
		$sexe = '';
		
		insere_photo($obs['cdref'],$obs['idobser'],$obs['date1'],$obs['codecom'],$obs['stade'],$nphoto['nomphoto'],$dates,$sexe,$obs['observa'],$idobs,$ordre);
		
		$p200 = '../../../photo/det/p200/'.$nphoto['nomphoto'].'.jpg';
		$p400 = '../../../photo/det/p400/'.$nphoto['nomphoto'].'.jpg';
		$p800 = '../../../photo/det/p800/'.$nphoto['nomphoto'].'.jpg';
		if(file_exists($p200)) { $retour['p200'] = 'Oui'; copy($p200, '../../../photo/P200/'.$nphoto['observa'].'/'.$nphoto['nomphoto'].'.jpg'); }
		if(file_exists($p400)) { copy($p400, '../../../photo/P400/'.$nphoto['observa'].'/'.$nphoto['nomphoto'].'.jpg'); }
		if(file_exists($p800)) { copy($p800, '../../../photo/P800/'.$nphoto['observa'].'/'.$nphoto['nomphoto'].'.jpg'); }
		
		if($vali == 'oui')
		{
			$datecom = date("Y-m-d H:i:s");
			$com = 'Commentaire automatique : Enregistré en observation par '.$_POST['membre'];
			insere_com($idpdet,$idm,$com,$datecom);
			$lidm = chercheidm($idpdet);
			if($lidm != false)
			{
				foreach($lidm as $n)
				{
					if($n['idm'] != $idm)
					{	
						insere_notif($idpdet,$n['idm']);
					}
				}
			}			
			$retour['statut'] = 'Oui';
		}
		else
		{
			$retour['statut'] = 'Non';
			$retour['pbrq'] = 'Oui';
		}
		
	}
	else
	{
		$retour['statut'] = 'Non';
		$retour['pbmobs'] = 'Oui';
	}	
}
else
{
	$retour['statut'] = 'Non'; 
}	

echo json_encode($retour);