<?php
function listeactu()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT idactu, titre, soustitre, to_char(datecreation, 'DD/MM/YYYY') AS datefr, tag, theme, nom FROM actu.actu
						LEFT JOIN actu.photoactu USING(idactu)
						WHERE visible = 1
						ORDER BY datecreation DESC LIMIT 4 ") or die(print_r($bdd->errorInfo()));
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function listeactutheme($idtheme)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idactu, titre, soustitre, to_char(datecreation, 'DD/MM/YYYY') AS datefr, tag, theme, nom FROM actu.actu
						LEFT JOIN actu.photoactu USING(idactu)
						WHERE theme = :theme AND visible = 1
						ORDER BY datecreation DESC LIMIT 4 ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':theme', $idtheme);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function actu($choix)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT titre, soustitre, to_char(datecreation, 'DD/MM/YYYY') AS datefr, tag, actu, url, photoactu.nom, auteur, info, compte, theme, nomdoc, membre.nom AS nomm, prenom FROM actu.actu
						LEFT JOIN actu.photoactu USING(idactu)
						LEFT JOIN actu.docactu USING(idactu)
						INNER JOIN site.membre ON idauteur = idmembre
						WHERE idactu = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $choix);
	$req->execute();	
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function listetag($tag)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idactu, titre, soustitre, to_char(datecreation, 'DD/MM/YYYY') AS datefr, nom, theme FROM actu.actu
						LEFT JOIN actu.photoactu USING(idactu)
						WHERE tag ILIKE :recherche AND visible = 1
						ORDER BY datecreation DESC ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':recherche', '%'.$tag.'%');
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function touslestag()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT tag FROM actu.actu WHERE visible = 1 AND tag != '' ") or die(print_r($bdd->errorInfo()));
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function touslestagtheme($idtheme)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT tag FROM actu.actu WHERE theme = :theme AND visible = 1  AND tag != '' ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':theme', $idtheme);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function modif_actu($idactu,$compte)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE actu.actu SET compte = :compte WHERE idactu = :idactu ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idactu', $idactu);
	$req->bindValue(':compte', $compte);
	$req->execute();
	$req->closeCursor();
}
function listecompte()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT idactu, titre, nom, compte FROM actu.actu
						LEFT JOIN actu.photoactu USING(idactu)
						WHERE compte != 0 AND visible = 1
						ORDER BY compte DESC LIMIT 4 ") or die(print_r($bdd->errorInfo()));
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function listecomptetheme($idtheme)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idactu, titre, nom, compte FROM actu.actu
						LEFT JOIN actu.photoactu USING(idactu)
						WHERE compte != 0 AND theme = :theme AND visible = 1
						ORDER BY compte DESC LIMIT 4 ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':theme', $idtheme);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function nbarticle()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT COUNT(*) AS Nb FROM actu.actu WHERE visible = 1 ") or die(print_r($bdd->errorInfo()));
	$nbarticle = $req->fetchColumn();
	$req->closeCursor();
	return $nbarticle;
}
function nbarticletheme($idtheme)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT COUNT(*) AS Nb FROM actu.actu WHERE theme = :theme AND visible = 1 ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':theme', $idtheme);
	$req->execute();
	$nbarticle = $req->fetchColumn();
	$req->closeCursor();
	return $nbarticle;
}
function article($min)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idactu, titre, soustitre, to_char(datecreation, 'DD/MM/YYYY') AS datefr, tag, nom, datecreation, theme FROM actu.actu
						LEFT JOIN actu.photoactu USING(idactu)
						ORDER BY datecreation DESC LIMIT 10 OFFSET :min ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':min', $min, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function articletheme($min,$idtheme)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idactu, titre, soustitre, to_char(datecreation, 'DD/MM/YYYY') AS datefr, tag, nom, datecreation, theme FROM actu.actu
						LEFT JOIN actu.photoactu USING(idactu)
						WHERE theme = :theme
						ORDER BY datecreation DESC LIMIT 10 OFFSET :min ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':min', $min, PDO::PARAM_INT);
	$req->bindValue(':theme', $idtheme);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function maxmin($min)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT MAX(datecreation) AS max, MIN(datecreation) AS min FROM (
							SELECT datecreation FROM actu.actu
							WHERE visible = 1
							ORDER BY datecreation DESC LIMIT 10 OFFSET :min
						) a ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':min', $min, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function maxmintheme($min,$idtheme)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT MAX(datecreation) AS max, MIN(datecreation) AS min FROM (
							SELECT datecreation FROM actu.actu
							WHERE theme = :theme AND visible = 1
							ORDER BY datecreation DESC LIMIT 10 OFFSET :min
						) a ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':min', $min, PDO::PARAM_INT);
	$req->bindValue(':theme', $idtheme);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}