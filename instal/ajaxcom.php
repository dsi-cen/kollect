<?php
include '../global/configbase.php';
include '../lib/pdo2.php';

function liste($com,$iddep,$maxRows)
{
	$bdd = PDO2::getInstanceinstall();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT commune, codecom FROM install.communefr 
						WHERE (commune ILIKE :com) AND (iddep = :iddep)
						LIMIT :maxRows ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':com', ''.$com.'%');
	$req->bindValue(':iddep', $iddep);
	$req->bindValue(':maxRows', $maxRows);
	$req->execute();
	$resultat = $req->fetchAll();
	$req->closeCursor();
	return $resultat;
}
if (isset($_POST['com']))
{
	$com = $_POST['com'];
	$iddep = $_POST['iddep'];
	$maxRows = intval($_POST['maxRows']);
	$resultat = liste($com,$iddep,$maxRows);
	
	echo json_encode($resultat);
}

?>