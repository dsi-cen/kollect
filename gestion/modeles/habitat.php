<?php
function niveau1()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT cdhab, lbcode, lbhabitat, locale FROM referentiel.eunis WHERE niveau = 1 ORDER BY lbcode ");
	$result = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $result;
}
function niveau2()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT cdhab, lbcode, lbhabitat, locale, cdhabsup FROM referentiel.eunis WHERE niveau = 2 ORDER BY lbcode ");
	$result = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $result;
}
function niveau3()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT cdhab, lbcode, lbhabitat, locale, cdhabsup FROM referentiel.eunis WHERE niveau = 3 ORDER BY lbcode ");
	$result = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $result;
}
function niveau4()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT cdhab, lbcode, lbhabitat, locale, cdhabsup FROM referentiel.eunis WHERE niveau = 4 ORDER BY lbcode ");
	$result = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $result;
}
function niveau5()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT cdhab, lbcode, lbhabitat, locale, cdhabsup FROM referentiel.eunis WHERE niveau = 5 ORDER BY lbcode ");
	$result = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $result;
}
function niveau6()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT cdhab, lbcode, lbhabitat, locale, cdhabsup FROM referentiel.eunis WHERE niveau = 6 ");
	$result = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $result;
}