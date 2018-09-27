<?php 
include '../global/configbase.php';
include '../lib/pdo2.php';

function liste($iddep)
{
	$bdd = PDO2::getInstanceinstall();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT commune, codecom FROM install.communefr 
						WHERE iddep = :iddep
						ORDER BY commune ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':iddep', $iddep);
	$req->execute();
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;
}
function listecom($com)
{
	$bdd = PDO2::getInstanceinstall();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT commune, codecom FROM install.communefr WHERE codecom IN($com) ") or die(print_r($bdd->errorInfo()));
	$listecom = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $listecom;
}
if (isset($_POST['id']))
{
	$iddep = $_POST['id'];
	$com = $_POST['sel'];
	if (!empty ($iddep))
	{
		$liste = liste($iddep);
	}	
	
	$listeselect = null;
	$listeselect = '<br />';
	$listeselect .= '<select id="multi" class="multiselect" multiple="multiple" name="multi[]">';
	foreach ($liste as $n)
	{
		$listeselect .= '<option value="'.$n['codecom'].'">'.$n['commune'].' - ('.$n['codecom'].')</option>';		
	}
	if (!empty ($com))
	{
		$com = substr($com, 0, -2);
		$listecom = listecom($com);
		foreach ($listecom as $n)
		{
			$listeselect .= '<option value="'.$n['codecom'].'" selected="selected">'.$n['commune'].' - ('.$n['codecom'].')</option>';		
		}		
	}
	$listeselect .= '</select><br />';
	echo $listeselect;
}