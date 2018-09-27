<?php 
include '../global/configbase.php';
include '../lib/pdo2.php';

function massif($massif)
{
	$bdd = PDO2::getInstanceinstall();		
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT massif.codecom, commune FROM install.massif 
						INNER JOIN install.communefr ON communefr.codecom = massif.codecom
						WHERE massif = :massif
						ORDER BY commune ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':massif', $massif);
	$req->execute();
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}

if (isset($_POST['massif']))
{
	$massif = $_POST['massif'];
	$liste = massif($massif);
	
	$listeselect = null;
	$listeselect = '<br />';
	$listeselect .= '<select id="multi" class="multiselect" multiple="multiple" name="multi[]">';
	foreach ($liste as $n)
	{
		$listeselect .= '<option value="'.$n['codecom'].'" selected="selected">'.$n['commune'].' - ('.$n['codecom'].')</option>';		
	}
	$listeselect .= '</select><br />';
	echo $listeselect;
}