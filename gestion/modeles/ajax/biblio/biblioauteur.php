<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

if(isset($_GET['term'])) 
{
	$term = $_GET['term'];
	function liste($term)
	{
		$bdd = PDO2::getInstance();
		$bdd->query('SET NAMES "utf8"');
		$req = $bdd->prepare("SELECT nom, prenom, annee, titre, idbiblio FROM biblio.biblio
							LEFT JOIN biblio.plusauteur USING(idbiblio)
							INNER JOIN biblio.auteurs ON auteurs.idauteur = biblio.idauteur OR auteurs.idauteur = plusauteur.idauteur
							WHERE nom ILIKE :recherche
							ORDER BY annee");
		$req->bindValue(':recherche', ''.$term.'%');
		$req->execute();
		$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
		$req->closeCursor();
		return $resultat;
	}
	$resultat = liste($term);

	foreach($resultat as $n)
	{
		$titre = strip_tags($n['titre']);
		$auteur = $n['nom'].' '.$n['prenom'];
		$data[] = ['titre'=>$titre,'annee'=>$n['annee'],'auteur'=>$auteur,'id'=>$n['idbiblio']];		
	}
	echo json_encode($data); 
}
?>