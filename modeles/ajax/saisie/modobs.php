<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function nbligne($idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idligne, observa, stade.stade, idetatbio FROM obs.ligneobs 
						INNER JOIN obs.obs USING(idobs)
						INNER JOIN referentiel.stade ON stade.idstade = ligneobs.stade
						WHERE idobs = :idobs ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idobs', $idobs, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
if (isset($_POST['idobs']))
{	
	$idobs = $_POST['idobs'];
	$ligne = nbligne($idobs);
	$nbligne = count($ligne);
	if($nbligne == 1)
	{
		$retour['ligne'] = $ligne[0]['idligne'];
		$retour['sel'] = $ligne[0]['observa'];
	}
	elseif($nbligne > 1)
	{
		foreach($ligne as $n)
		{
			switch($n['idetatbio'])
			{
				case 0:$etat = 'Inconu'; break;
				case 1:$etat = 'Non renseigné'; break;
				case 2:$etat = 'Observé vivant'; break;
				case 3:$etat = 'Trouvé mort'; break;
			}
			$dligne[] = array('idligne'=>$n['idligne'],'stade'=>$n['stade'],'etat'=>$etat);
		}
		$retour['ligne'] = $ligne[] = $dligne;
		$retour['sel'] = $ligne[0]['observa'];
	}
	$retour['nbligne'] = $nbligne;	
	$retour['statut'] = 'Oui';
}
else
{
	$retour['statut'] = 'Non';	
}
echo json_encode($retour);	
?>