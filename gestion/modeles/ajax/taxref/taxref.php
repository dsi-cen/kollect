<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function vidertable()
{
	$bdd = PDO2::getInstance();		
	$bdd->exec("DELETE FROM referentiel.taxref ");
}
function insere_change($data)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO referentiel.taxref (cdnom, cdsup, cdtaxsup, cdref, rang, nom, auteur, groupe, classe, ordre, famille, nomvern, statut) VALUES(:a, :b, :c, :d, :e, :f, :g, :h, :i, :j, :k, :l, :m) ");
	$req->bindParam(':a', $data0);
	$req->bindParam(':b', $data1);
	$req->bindParam(':c', $data2);
	$req->bindParam(':d', $data3);
	$req->bindParam(':e', $data4);
	$req->bindParam(':f', $data5);
	$req->bindParam(':g', $data6);
	$req->bindParam(':h', $data7);
	$req->bindParam(':i', $data8);
	$req->bindParam(':j', $data9);
	$req->bindParam(':k', $data10);
	$req->bindParam(':l', $data11);
	$req->bindParam(':m', $data12);
	foreach($data as $n)
	{
		$data0 = $n[0];
		$data1 = (!empty($n[1])) ? $n[1] : null;
		$data2 = (!empty($n[2])) ? $n[2] : null;
		$data3 = $n[3];
		$data4 = $n[4];
		$data5 = $n[5];
		$data6 = $n[6];
		$data7 = $n[7];
		$data8 = $n[8];
		$data9 = $n[9];
		$data10 = $n[10];
		$data11 = $n[11];
		$data12 = $n[12];
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
		while(($data = fgetcsv($liste, 0, ";")) !== FALSE) 
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