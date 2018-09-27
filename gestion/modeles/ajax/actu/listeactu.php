<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

if(isset($_GET['term'])) 
{
	$term = $_GET['term'];
	function liste($term)
	{
		$resultat= array();
		$bdd = PDO2::getInstance();
		$bdd->query('SET NAMES "utf8"');
		$req = $bdd->prepare("SELECT idactu, titre, soustitre, actu, tag, theme, url FROM actu.actu WHERE idactu = :recherche ");
		$req->bindValue(':recherche', $term);
		$req->execute();
		$resultat = $req->fetch(PDO::FETCH_ASSOC);
		$req->closeCursor();
		return $resultat;
	}
	$resultat = liste($term);
	$liste[] = array("idactu"=>$resultat['idactu'],"titre"=>$resultat['titre'],"stitre"=>$resultat['soustitre'],"url"=>$resultat['url'],"tag"=>$resultat['tag'],"theme"=>$resultat['theme'],"actu"=>$resultat['actu']);		
	echo json_encode($liste); 
}
?>