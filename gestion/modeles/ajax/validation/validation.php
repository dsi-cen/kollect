<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';
session_start();

function cherche_actuel($idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT validation, idm, cdref, mail FROM obs.obs 
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN referentiel.observateur USING(idobser)
						LEFT JOIN site.membre ON membre.idmembre = observateur.idm
						WHERE idobs = :idobs ");
	$req->bindValue(':idobs', $idobs);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function mod_vali($idobs,$choix)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE obs.obs SET validation = :vali WHERE idobs = :idobs ");
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':vali', $choix);
	$vali = ($req->execute()) ? 'oui' : '';
	$req->closeCursor();
	return $vali;
}
function inser_histovali($idobs,$cdnom,$dates,$choix,$dec,$idm)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO vali.histovali (idobs, cdnom, dateval, vali, decision, idm, typevali) VALUES(:idobs, :cdnom, :dateval, :vali, :dec, :idm, 2) ");
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':cdnom', $cdnom);
	$req->bindValue(':dateval', $dates);
	$req->bindValue(':vali', $choix);
	$req->bindValue(':dec', $dec);
	$req->bindValue(':idm', $idm);
	$vali = ($req->execute()) ? 'oui' : '';
	$req->closeCursor();
	return $vali;
}
function insere_com($idobs,$idm,$rq,$datecom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO vali.comvali (idobs,idm,commentaire,datecom) VALUES(:idobs, :idm, :com, :datecom) ");
	$req->bindValue(':idm', $idm);
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':com', $rq);
	$req->bindValue(':datecom', $datecom);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;	
}
function insere_notif($idobs,$idmor)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO site.notif (idm,type,idtype) VALUES(:idm, :type, :idobs) ");
	$req->bindValue(':idm', $idmor);
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':type', 'vali');
	$req->execute();
	$req->closeCursor();
}
function verif_suptaxon($cdref)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT count(*) AS nb FROM obs.obs WHERE cdref = :cdref AND validation != 4 ");
	$req->bindValue(':cdref', $cdref);
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;
}
function modif_listeob($cdref,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE $nomvar.liste SET locale = :locale WHERE cdref = :cdref ");
	$req->bindValue(':cdref', $cdref);
	$req->bindValue(':locale', 'non');
	$req->execute();
	$req->closeCursor();
}
function sup_lister($cdref)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("DELETE FROM referentiel.liste WHERE cdnom = :cdnom ");
	$req->bindValue(':cdnom', $cdref);
	$req->execute();
	$req->closeCursor();
}
function modif($idobs,$idmembre,$datem,$cdref)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO site.modif (typeid, numid, typemodif, modif, datemodif, idmembre)
						VALUES(:typeid, :id, :type, :modif, :datem, :idm) ");
	$req->bindValue(':id', $cdref);
	$req->bindValue(':typeid', 'Espèce');
	$req->bindValue(':type', 'Retrait espèce locale en saisie');
	$req->bindValue(':modif', 'Retrait du taxon : obs non valide idobs = '.$idobs.'');
	$req->bindValue(':datem', $datem);
	$req->bindValue(':idm', $idmembre);
	$req->execute();
	$req->closeCursor();
}

if(isset($_POST['idobs']) && !empty($_POST['idobs']))
{	
	$idobs = $_POST['idobs'];
	$choix = $_POST['choix'];
	$rq = str_replace(array("\r\n", "\n", "\r"), ' ', $_POST['rq']);
	$nouv = $_POST['nouv'];
	$observa = $_POST['observa'];
	
	$idm = $_SESSION['idmembre'];
	
	$actuel = cherche_actuel($idobs);
	
	if($actuel['validation'] == $choix || $choix == 'NR')
	{
		if(empty($rq))
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert">Vous n\'avez pas changer le statut de validation et vous avez apporter aucun commentaire</div>';
			echo json_encode($retour);	
			exit;
		}
		else
		{
			$envoicom = (!empty($actuel['idm'])) ? 'ok' : 'non';			
		}
	}
	else
	{
		if(!empty($rq))
		{
			$envoicom = (!empty($actuel['idm'])) ? 'ok' : 'non';
		}
		$vali = mod_vali($idobs,$choix);
		if($vali == 'oui')
		{
			$dates = date("Y-m-d");
			$datefr = date('d/m/Y à H:i');
			$dec = 'Validation manuelle du '.$datefr.' par '.$_SESSION['prenom'].' '.$_SESSION['nom'];
			$vali = inser_histovali($idobs,$actuel['cdref'],$dates,$choix,$dec,$idm);
			if($vali == '')
			{
				$retour['statut'] = 'Non';
				$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur ! Problème dans requête inser histo vali</div>';
				echo json_encode($retour);	
				exit;
			}
		}
		else
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur ! Problème dans requête modif vali obs</div>';
			echo json_encode($retour);	
			exit;
		}
	}
	if($nouv == 'oui' && $choix == 4)
	{
		$nbcdref = verif_suptaxon($actuel['cdref']);
		if($nbcdref == 0)
		{
			modif_listeob($actuel['cdref'],$observa);
			sup_lister($actuel['cdref']);
			$datem = date("Y-m-d H:i:s");
			modif($idobs,$idm,$datem,$actuel['cdref']);
		}
	}
	if(isset($envoicom))
	{
		if($envoicom == 'ok')
		{
			$datecom = date("Y-m-d H:i:s");
			$vali = insere_com($idobs,$idm,$rq,$datecom);
			if($vali == 'oui')
			{
				if($actuel['idm'] != $idm)
				{ 
					insere_notif($idobs,$actuel['idm']);
					
					$mail1 = $_SERVER['HTTP_HOST'];
					$mailval = ($mail1{3} == '.') ? str_replace('www.', '', $mail1) : $mail1;					
					$meshtml = '<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" /></head><body>Vous avez une notification concernant la validation d\'une observation.<br />Ceci est un mail automatique, Merci de ne pas y répondre.</body></html>'; 
					$mestxt = 'Vous avez une notification concernant la validation d\'une observation. Ceci est un mail automatique, Merci de ne pas y répondre.';
					$frontiere = '-----=' . md5(uniqid(mt_rand()));		
					$headers ='From: "'.$mailval.'"<no-reply@'.$mailval.'>'."\n"; 
					$headers .= 'MIME-Version: 1.0'."\n";
					$headers .= 'Content-Type: multipart/alternative; boundary="'.$frontiere.'"'."\n";
					$message = 'This is a multi-part message in MIME format.'."\n\n";
					$message .= '--'.$frontiere."\n";
					$message .= 'Content-Type: text/plain; charset=UTF-8'."\n";
					$message .= 'Content-Transfer-Encoding: 8bit'."\n\n";
					$message .= $mestxt."\n\n";
					$message .= '--'.$frontiere."\n";
					$message .= 'Content-Type: text/html; charset=UTF-8'."\n";
					$message .= 'Content-Transfer-Encoding: 8bit'."\n\n";
					$message .= $meshtml."\n\n";
					$message .= '--'.$frontiere.'--'."\n";	
					mail($actuel['mail'], 'Validation observation', $message, $headers);						
				}				
			}
		}
		else
		{
			$retour['mes'] = '<div class="alert alert-warning" role="alert">Cet observateur n\'est pas membre. Le mail n\'a pas été envoyé</div>';
		}
	}
	$retour['statut'] = 'Oui';
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur ! idobs absent</div>';
}
echo json_encode($retour);	
?>