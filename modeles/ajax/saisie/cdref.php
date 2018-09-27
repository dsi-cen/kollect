<?php 
if (isset($_POST["cdref"]))
{
	$cdref = $_POST['cdref'];
	$sel = $_POST['sel'];
	include '../../../global/configbase.php';
	include '../../../lib/pdo2.php';
	//M
	function cdref($cdref,$sel)
	{
		$bdd = PDO2::getInstance();
		$bdd->query('SET NAMES "utf8"');
		$req = $bdd->prepare("SELECT nom FROM $sel.liste WHERE cdref = :cdref AND cdnom = :cdref ") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':cdref', $cdref);
		$req->execute();
		$resultat = $req->fetch();
		$req->closeCursor();
		return $resultat;
	}
	//C
	$resultat = cdref($cdref,$sel);
	$retour['statut'] = array("Ok"=>'Ok', "Nom"=>$resultat['nom']);
	
	//V
	echo json_encode($retour);	
}	
?>