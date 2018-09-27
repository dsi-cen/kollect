<?php
session_start();
if(isset($_POST['idobs']))
{	
	$idobs = $_POST['idobs'];
	$_SESSION['idobs'] = $idobs;
	$retour['statut'] = 'Oui';
}
elseif(isset($_POST['idfiche']))
{	
	$_SESSION['idfiche'] = $_POST['idfiche'];
	$retour['statut'] = 'Oui';
}
else
{
	$retour['statut'] = 'Non';	
}
echo json_encode($retour);	
?>