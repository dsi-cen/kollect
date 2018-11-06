<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function histovali($idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT dateval, vali, decision, CASE WHEN typevali = 1 THEN 'Automatique' ELSE 'Manuelle' END AS Typevali, nom FROM vali.histovali 
						INNER JOIN referentiel.taxref ON taxref.cdnom = histovali.cdnom
						WHERE idobs = :idobs ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idobs', $idobs);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function comvali($idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT commentaire, to_char(datecom, 'DD/MM/YYYY à HH24:MI') AS datefr, idm, prenom, nom FROM vali.comvali 
						INNER JOIN site.membre ON membre.idmembre = comvali.idm
						WHERE idobs = :idobs ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idobs', $idobs);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}

if(isset($_POST['idobs']))
{
	$idobs = $_POST['idobs'];
	$rvali = $_POST['rvali'];
	
	if($rvali == 'non') 
	{ 
		$histo = histovali($idobs);
			
		$h = '<h5 class="h6">Suivi de validation</h5>';
		if($histo != false)
		{
			$h .= '<ul>';
			foreach($histo as $n)
			{
				switch($n['vali'])
				{
					case 1:$vali = '1 Certain - très probable'; $class = 'val1'; break;
					case 2:$vali = '2 Probable'; $class = 'val2'; break;
					case 3:$vali = '3 Douteux'; $class = 'val3'; break;
					case 4:$vali = '4 Invalide'; $class = 'val4'; break;
					case 5:$vali = '5 Non réalisable'; $class = 'val5'; break;
					case 6:$vali = '6 Non évalué - en cours'; $class = ''; break;
				}
				$h .= '<li><i class="fa fa-check-circle '.$class.'"></i><span class="font-weight-bold"> '.$vali.'</span> : '.$n['decision'].' <i>'.$n['nom'].'</i></li>';
			}
			$h .= '</ul>';
		}
		else
		{
			$h .= '<p>Aucun</p>';
		}
		$retour['histo'] = $h;
	}
	
	$com = comvali($idobs);
	$c = '<h5 class="h6">Commentaire(s)</h5>';
	if($com != false)
	{
		$c .= '<ul>';
		foreach($com as $n)
		{
			$c .= '<li>'.$n['prenom'].' '.$n['nom'].' le '.$n['datefr'].' : '.$n['commentaire'].'</li>';
		}
		$c .= '</ul>';
	}
	else
	{
		$c .= '<p>Aucun</p>';
	}
	
	$retour['com'] = $c;	
	$retour['statut'] = 'Oui';	
}
else
{
	$retour['statut'] = 'Non';
}
echo json_encode($retour);