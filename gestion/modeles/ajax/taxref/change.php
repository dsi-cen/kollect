<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function vidertable()
{
	$bdd = PDO2::getInstance();		
	$bdd->exec("DELETE FROM taxref.change ");
}
function insere_change($data)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO taxref.change (cdnom, champ, valinit, valfinal, typechange) VALUES(:cdnom, :chp, :valinit, :valfin, :type) ");
	$req->bindParam(':cdnom', $data0);
	$req->bindParam(':chp', $data1);
	$req->bindParam(':valinit', $data2);
	$req->bindParam(':valfin', $data3);
	$req->bindParam(':type', $data4);
	foreach($data as $n)
	{
		$data0 = $n[0];
		$data1 = $n[1];
		$data2 = $n[2];
		$data3 = $n[3];
		$data4 = $n[4];
		$req->execute();
	}	
	$req->closeCursor();
}
if(isset($_POST['fichier'])) 
{
	$fichier = $_POST['fichier'];
	$premiere = $_POST['nbdeb'];
	$nbtraitement = 10000;
	
	if($premiere == 0)
	{
		vidertable();
	}	
	if(($liste = fopen("../../../taxref/".$fichier, "r")) !== FALSE) 
	{
		fseek($liste, $premiere);
		$nbligne = 0;
		$i = 0;
		while(($data = fgetcsv($liste, 1000, ";")) !== FALSE) 
		{
			if($i++ < $nbtraitement)
			{
				$nbligne++;
				$import[] = $data;
				
				$derniere = ftell($liste);
			}
			unset($data);
		}
		fclose($liste);
		insere_change($import);
		$retour['statut'] = 'Oui';
		$retour['nb'] = $nbligne;
		$retour['derniere'] = $derniere;
	}
	else
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Aucun fichier.</p></div>';
	}
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Aucun fichier.</p></div>';
}
echo json_encode($retour);