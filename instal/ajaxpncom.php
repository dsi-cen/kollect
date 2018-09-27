<?php 
include '../global/configbase.php';
include '../lib/pdo2.php';

function pn($pn)
{
	$bdd = PDO2::getInstanceinstall();		
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT pn.codecom, commune, coeur FROM install.pn 
						INNER JOIN install.communefr ON communefr.codecom = pn.codecom
						WHERE parc = :pn
						ORDER BY commune ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':pn', $pn);
	$req->execute();
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}

if (isset($_POST['pn']))
{
	$pn = $_POST['pn'];
	$liste = pn($pn);
	
	$listeselect = null;
	$listeselect = '<br />';
	$listeselect .= '<select id="multi" class="multiselect" multiple="multiple" name="multi[]">';
	foreach ($liste as $n)
	{
		if ($n['coeur'] == 'oui')
		{
			$listeselect .= '<option value="'.$n['codecom'].'" selected="selected">'.$n['commune'].' - ('.$n['codecom'].') - coeur</option>';
		}
		else
		{
			$listeselect .= '<option value="'.$n['codecom'].'" selected="selected">'.$n['commune'].' - ('.$n['codecom'].')</option>';
		}				
	}
	$listeselect .= '</select><br />';
	echo $listeselect;
}