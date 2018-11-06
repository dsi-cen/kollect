<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function recherche($cdnom)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idphoto FROM site.photo WHERE cdnom = :cdnom ORDER BY idphoto ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':cdnom', $cdnom);
	$req->execute();
	$nbresultats = $req->rowCount();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return array($nbresultats, $resultat);	
}
function modordre($mordre,$id)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE site.photo SET ordre = :ordre WHERE idphoto = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id);
	$req->bindValue(':ordre', $mordre);
	$req->execute();
	$req->closeCursor();
}

if(isset($_POST['cdnom']))
{	
	$cdnom = $_POST['cdnom'];
	
	$liste = recherche($cdnom);
	
	$ordre = 1;
	foreach($liste[1] as $n)
	{
		modordre($ordre,$n['idphoto']);
		$ordre ++;
	}	
			
	$retour['statut'] = 'Oui';
}
else
{
	$retour['statut'] = 'Non';
}
echo json_encode($retour);	
?>