<?php
include '../../../global/configbase.php';
include '../../lib/pdo2.php';

function liste_alpha($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT DISTINCT idmc, mot FROM biblio.motcle
						INNER JOIN biblio.bibliomc USING(idmc)
						INNER JOIN biblio.biblio USING(idbiblio)
						WHERE mot ILIKE :recherche AND typep != 'Livre'
						ORDER BY mot ");
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
			$listealpha .= '<a href="index.php?module=liste&amp;action=liste&amp;choix=mot&amp;id='.$n['idmc'].'">'.$n['mot'].'</a><br />';
		}		
	}
	else
	{
		$listealpha .= 'Aucun mot-clé pour la lettre '.$id;	
	}
		
	echo $listealpha;	
}
?>