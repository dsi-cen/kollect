<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';
//M
function liste_auteur()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT idauteur, nom, prenom, prenomab FROM biblio.auteurs ORDER BY nom") or die(print_r($bdd->errorInfo()));
	$req->execute();
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;
}

//C
$liste = liste_auteur();
$listeauteur = null;

//V
foreach($liste as $n)
{
	$listeauteur .= '<tr><td class="text-center"><img src="img/mod.png" title="Modifier/corriger" onclick="modifier('.$n['idauteur'].',\''.$n['nom'].'\',\''.$n['prenom'].'\',\''.$n['prenomab'].'\')" class="curseurlien"></td>
				<td class="text-center"><img src="img/sup.png" title="Supprimer cet auteur" onclick="supobs(id='.$n['idauteur'].')" class="curseurlien"></td><td>'.$n['idauteur'].'</td><td><b>'.$n['nom'].'</b></td><td>'.$n['prenom'].'</td><td>'.$n['prenomab'].'</td></tr>';
}
$reponse = $listeauteur;
header('Content-Type: application/json');
echo json_encode($reponse);