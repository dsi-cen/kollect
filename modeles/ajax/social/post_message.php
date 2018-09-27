<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function postmess($idm,$message)
{  
    $datepost = date("Y-m-d H:i:s");

    $bdd = PDO2::getInstance();
    $bdd->query('SET NAMES "utf8"');
    $req = $bdd->prepare("INSERT INTO social.postmembre (idmembre, date, texte) VALUES(:idm, :datepost, :message) ") or die(print_r($bdd->errorInfo()));
    $req->bindValue(':message', $message);
    $req->bindValue(':idm', $idm);
    $req->bindValue(':datepost', $datepost);
	if ($req->execute()) 
	{
		$idpost = $bdd->lastInsertId('social.postmembre_idpost_seq'); //on récupère l'id du post
	}
    $req->closeCursor();
	return $idpost;
}   
function inserenotif($idm,$idpost)
{
	$bdd = PDO2::getInstance();
    $bdd->query('SET NAMES "utf8"');
    $req = $bdd->prepare("SELECT id_membre FROM social.abonements_membre WHERE id_abonnement = :idm ") or die(print_r($bdd->errorInfo()));
    $req->bindValue(':idm', $idm);
    $req->execute();
    $idabo = $req->fetchall(PDO::FETCH_ASSOC);
    $req->closeCursor();
	
	$req = $bdd->prepare("INSERT INTO site.notif (idm, type, idtype) VALUES(:idm, 'abo', :idtype) ") or die(print_r($bdd->errorInfo()));
    foreach ($idabo as $n)
    {
		$req->execute(array('idm'=>$n['id_membre'],'idtype'=>$idpost));        
    }
	$req->closeCursor();   
}

if (isset($_POST['mess']))
{
    $message = htmlspecialchars($_POST['mess']);
	$idm = htmlspecialchars($_POST['idm']);
	//on verifie cotès serveur si le message est pas vide
	if(!empty($message))
	{
		$idpost = postmess($idm,$message);
		if($idpost != 0) //si l'insertion c'est bien déroulée
		{
			//On va chercher les ID membres qui sont abonnés à la personne qui post et avec une boucle les rentrer dans la table des notifs
			inserenotif($idm,$idpost);			
		}
		$retour['statut'] = 'Oui';
		$retour['date'] = date("Y-m-d H:i:s");
	}
    else
	{
		$retour['statut'] = 'Non';
	}    
}
else
{
	$retour['statut'] = 'Non';
}	
echo json_encode($retour);