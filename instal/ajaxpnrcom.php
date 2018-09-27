<?php 
include '../global/configbase.php';
include '../lib/pdo2.php';

function pnr($pnr)
{
	$bdd = PDO2::getInstanceinstall();		
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT codecom, commune FROM install.pnrcom 
						INNER JOIN install.communefr USING(codecom)
						WHERE id = :pnr
						ORDER BY commune ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':pnr', $pnr);
	$req->execute();
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}

if (isset($_POST['pnr']))
{
	$pnr = $_POST['pnr'];
	$liste = pnr($pnr);
	
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