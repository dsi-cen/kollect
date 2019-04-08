<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
session_start();


function verif_mail($mail) // Check if mail is unique
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->prepare("SELECT membre.mail FROM site.membre WHERE mail = :mail ") or die(print_r($bdd->errorInfo()));
    $req->bindValue(':mail', $mail);
    $req->execute();
    $nbresultats = $req->rowCount();
    $req->closeCursor();
    return $nbresultats;
}

function modif_mail($idm,$mail) // Insert mail
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE site.membre SET mail = :mail WHERE idmembre = :idm ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idm', $idm);
	$req->bindValue(':mail', $mail);
	$ok = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $ok;	
}

if(isset($_POST['mail']) && !empty($_POST['mail']))
{
	$idm = $_SESSION['idmembre'];
	$mail = $_POST['mail'];

    if (verif_mail($mail) < 1){
        $ok = modif_mail($idm,$mail);
        $retour['mess'] = ($ok == 'oui') ? 'Modification enregistrée' : 'Erreur à l\'enregistrement';
        $retour['statut'] = ($ok == 'oui') ? 'ok' : 'ko';
    }
    else {
        $retour['statut'] = 'ko';
        $retour['mess'] = 'L\'adresse de courriel est déjà utilisée par une autre personne';
    }
}
else
{
    $retour['statut'] = 'ko';
	$retour['mess'] = 'Erreur de réception des champs';
}
echo json_encode($retour);	