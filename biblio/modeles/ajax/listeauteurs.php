<?php
include '../../../global/configbase.php';
include '../../lib/pdo2.php';

function liste_alpha($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idauteur, nom, prenom FROM biblio.auteurs
						WHERE nom ILIKE :recherche 
						ORDER BY nom ");
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
			$listealpha .= '<a href="index.php?module=liste&amp;action=liste&amp;choix=aut&amp;id='.$n['idauteur'].'">'.$n['nom'].' ('.$n['prenom'].')</a><br />';
		}		
	}
	else
	{
		$listealpha .= 'Aucun auteur pour la lettre '.$id;	
	}
		
	echo $listealpha;	
}
?>