<?php
function rechercher_membre($nom, $prenom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT nom, prenom FROM site.membre 
						WHERE nom = :nom AND prenom = :prenom ");
	$req->bindValue(':nom', $nom);
	$req->bindValue(':prenom', $prenom);
	$req->execute();
	$nbresultats = $req->rowCount();
	$req->closeCursor();
	return $nbresultats;
}
function inscription($nom,$prenom,$pass_hache,$mail)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO site.membre (nom, prenom, droits, motpasse, mail, actif) VALUES (:nom, :prenom, :droit, :motpasse, :mail, :actif)  ");
	$req->bindValue(':nom', $nom);
	$req->bindValue(':prenom', $prenom);
	$req->bindValue(':droit', 0);
	$req->bindValue(':motpasse', $pass_hache);
	$req->bindValue(':mail', $mail);
	$req->bindValue(':actif', 0);
	if ($req->execute())
	{
		$id = $bdd->lastInsertId('site.membre_idmembre_seq');
		$insertion = 'Oui';
	}
	else
	{
		$insertion = 'Nom';
	}
	$req->closeCursor();
	return array($insertion, $id);
}
function connexion($prenom, $pass_hache)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT membre.idmembre, nom, droits, actif, latin, obser, floutage, couche, typedon, org FROM site.membre
						LEFT JOIN site.prefmembre ON prefmembre.idmembre = membre.idmembre
						WHERE prenom = :prenom AND motpasse = :motpasse ");
	//$req->bindValue(':prenom', $prenom);
	//$req->bindValue(':motpasse', $pass_hache);
	$req->bindParam(':prenom', $prenom, PDO::PARAM_STR);
	$req->bindParam(':motpasse', $pass_hache, PDO::PARAM_STR);
	$req->execute();
	$connexion = $req->fetch();
	$req->closeCursor();
	return $connexion;
}
function validation($prenom,$id)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idmembre, prenom, actif FROM site.membre
						WHERE prenom = :prenom AND idmembre = :id ");
	$req->bindValue(':prenom', $prenom);
	$req->bindValue(':id', $id);
	$req->execute();
	$validation = $req->fetch();
	$req->closeCursor();
	return $validation;
}
function modif_membre($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE site.membre SET actif = :actif WHERE idmembre = :id ");
	$req->bindValue(':id', $id);
	$req->bindValue(':actif', 1);
	$req->execute();
	$req->closeCursor();	
}
function rechercher_mail($mail,$prenom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT mail, idmembre FROM site.membre WHERE mail = :mail and prenom = :prenom ");
	$req->bindValue(':mail', $mail);
	$req->bindValue(':prenom', $prenom);
	$req->execute();
	$nmail = $req->fetch();
	$req->closeCursor();
	return $nmail;
}
function modif_ticket($id,$ticket)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE site.membre SET ticket = :ticket, mdpo = :mdpo WHERE idmembre = :id ");
	$req->bindValue(':id', $id);
	$req->bindValue(':ticket', $ticket);
	$req->bindValue(':mdpo', 1);
	$req->execute();
	$req->closeCursor();	
}
function verif_ticket($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idmembre, mdpo FROM site.membre WHERE ticket = :ticket");
	$req->bindValue(':ticket', $id);
	$req->execute();
	$verifticket = $req->fetch();
	$req->closeCursor();
	return $verifticket;
}
function modif_mdp($idmembre,$pass_hache)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE site.membre SET motpasse = :motpasse, mdpo = :mdpo WHERE idmembre = :id ");
	$req->bindValue(':id', $idmembre);
	$req->bindValue(':motpasse', $pass_hache);
	$req->bindValue(':mdpo', 0);
	$req->execute();
	$req->closeCursor();	
}
function modif($idmembre,$type,$modif,$datem)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO site.modif (typeid, numid, typemodif, modif, datemodif, idmembre)
						VALUES(:typeid, :id, :type, :modif, :datem, :idm) ");
	$req->bindValue(':id', $idmembre);
	$req->bindValue(':typeid', 'Membre');
	$req->bindValue(':type', $type);
	$req->bindValue(':modif', $modif);
	$req->bindValue(':datem', $datem);
	$req->bindValue(':idm', $idmembre);
	$req->execute();
	$req->closeCursor();
}