<?php
include '../../../global/configbase.php';
include '../../lib/pdo2.php';

function liste_alpha($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT DISTINCT codecom, commune FROM biblio.bibliocom
						INNER JOIN referentiel.commune USING(codecom)
						WHERE commune ILIKE :recherche 
						ORDER BY commune ");
	$req->bindValue(':recherche', ''.$id.'%');
	$req->execute();
	$liste = $req->fetchAll();
	$req->closeCursor();
	return $liste;
}
if(isset($_POST['id'])) 
{	
	$id = htmlspecialchars($_POST['id']);
	
	$liste = liste_alpha($id);
	$nb = count($liste);
	
	$listealpha = '<h3 class="h5 ctitre">Résultats... </h3><hr />';
	
	if($nb > 0)
	{
		foreach($liste as $n)
		{
			$listealpha .= '<a href="index.php?module=liste&amp;action=liste&amp;choix=com&amp;id='.$n['codecom'].'">'.$n['commune'].'</a><br />';
		}		
	}
	else
	{
		$listealpha .= 'Aucune commune pour la lettre '.$id;	
	}
		
	echo $listealpha;	
}
?>