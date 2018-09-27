<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function chercheobservateur($idm)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idobser FROM referentiel.observateur WHERE idm = :idm ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idm', $idm);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function verif($cdnom,$codecom,$date)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idfiche, idobs, site, idobser, plusobser FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						LEFT JOIN obs.site USING(idsite)
						WHERE cdref = :cdnom AND fiche.codecom = :codecom AND date1 = :date ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':cdnom', $cdnom);
	$req->bindValue(':codecom', $codecom);
	$req->bindValue(':date', $date);
	$req->execute();
	$nbresultats = $req->rowCount();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return array($nbresultats, $resultat);	
}
function cherche_observateur($idfiche,$idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idobser FROM obs.plusobser WHERE idfiche = :idfiche AND idobser = :idobser ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idfiche', $idfiche, PDO::PARAM_INT);
	$req->bindValue(':idobser', $idobser, PDO::PARAM_INT);
	$req->execute();
	$obsplus = $req->rowCount();
	$req->closeCursor();
	return $obsplus;
}

if(!empty($_POST['cdnom']) && !empty($_POST['codecom']) && !empty($_POST['date']))
{
	$cdnom = $_POST['cdnom'];
	$codecom = $_POST['codecom'];
	$datetmp = DateTime::createFromFormat('d/m/Y', $_POST['date']);
	$date = $datetmp->format('Y-m-d');
	$idm = $_POST['idm'];
	
	$obser = chercheobservateur($idm);
	$idobser = $obser['idobser'];	
	
	$verif = verif($cdnom,$codecom,$date);
	if($verif[0] > 0)
	{
		foreach($verif[1] as $n)
		{
			if($n['idobser'] == $idobser)
			{
				$ok = 'oui';
			}
			else
			{
				if($n['plusobser'] == 'oui')
				{
					$verifobser = cherche_observateur($n['idfiche'],$idobser);
					$ok = ($verifobser >= 1) ? 'oui' : 'non';
				}				
			}
			if($verif[0] == 1)
			{
				$retour['idobs'] = $n['idobs'];
			}
			else
			{
				$retour['site'][] = array('site'=>$n['site'],'idobs'=>$n['idobs']);
			}			
		}
	}
	else
	{
		$ok = 'non';
	}
	if($ok == 'oui')
	{
		$retour['statut'] = 'Oui';
		$retour['idobser'] = $idobser;
	}
	else
	{
		$retour['statut'] = 'Non';
	}
	$retour['er'] = 'Non';
}
else
{
	$retour['statut'] = 'Non';
	$retour['er'] = 'Oui';
}
echo json_encode($retour);	