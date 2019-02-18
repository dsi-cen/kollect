<?php
function rechercheobservateurid($idm)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idobser FROM referentiel.observateur WHERE idm = :idm ");
	$req->bindValue(':idm', $idm);
	$req->execute();
	$idobser = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $idobser;		
}
function doublon($nom,$prenom)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT COUNT(*) AS Nb FROM referentiel.observateur WHERE nom = :nom AND prenom = :prenom ");
	$req->bindValue(':nom', $nom);
	$req->bindValue(':prenom', $prenom);
	$req->execute();
	$nbresultats = $req->fetchColumn();
	$req->closeCursor();
	return $nbresultats;
}
function rechercheobservateur($nom,$prenom)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idobser FROM referentiel.observateur WHERE nom = :nom AND prenom = :prenom ");
	$req->bindValue(':nom', $nom);
	$req->bindValue(':prenom', $prenom);
	$req->execute();
	$idobser = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $idobser;
}
function insere_observateurs($nom,$prenom,$idm)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO referentiel.observateur (observateur, nom, prenom, idm) VALUES (:observateur, :nom, :prenom, :idm) ");
	$req->bindValue(':nom', $nom);
	$req->bindValue(':prenom', $prenom);
	$req->bindValue(':observateur', ''.$nom.' '.$prenom.'');
	$req->bindValue(':idm', $idm);
	if ($req->execute())
	{
		$vali = $bdd->lastInsertId('referentiel.observateur_idobser_seq');
	} 
	$req->closeCursor();
	return $vali;	
}
function updateobservateur($idm,$idobser)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE referentiel.observateur SET idm = :idm WHERE idobser = :idobser ");
	$req->bindValue(':idm', $idm);
	$req->bindValue(':idobser', $idobser);
	$req->execute();
}
function modif($idm,$idobser,$modif,$datem)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO site.modif (typeid, numid, typemodif, modif, datemodif, idmembre)
						VALUES(:typeid, :id, :type, :modif, :datem, :idm) ");
	$req->bindValue(':id', $idobser);
	$req->bindValue(':typeid', 'Observateur');
	$req->bindValue(':type', 'Ajout observateur');
	$req->bindValue(':modif', $modif);
	$req->bindValue(':datem', $datem);
	$req->bindValue(':idm', $idm);
	$req->execute();
	$req->closeCursor();
}
function habitat()
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT cdhab, lbcode, lbhabitat FROM referentiel.eunis WHERE locale = 'oui' AND niveau = 1 ORDER BY lbcode ");
	$resultats = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultats;
}
function etude()
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT idetude, etude FROM referentiel.etude WHERE masquer = 'oui' ORDER BY etude ");
	$resultats = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultats;
}

function organisme()
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->prepare("SELECT organisme.idorg, organisme
                                        FROM referentiel.organisme
                                        LEFT JOIN referentiel.observateur_organisme ON observateur_organisme.idorg = organisme.idorg
                                        LEFT JOIN referentiel.observateur ON observateur.idobser = observateur_organisme.idobser
                                        LEFT JOIN site.membre ON membre.idmembre = observateur.idm
                                        WHERE observateur.idm = :idmembre
                                        ORDER BY organisme ");
    $req->bindValue(':idmembre', $_SESSION['idmembre']);
    $req->execute();
	$resultats = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultats;
}

function liste_precision()
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->query("SELECT idpreci, lbpreci
                                    FROM referentiel.coordprecision ");
    $resultats = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $resultats;
}

// Stations

function liste_typestation()
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->query("SELECT idtypestation, libtypestation, mdtypestation
                                  FROM referentiel_station.typestation; ");
    $resultats = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $resultats;
}

function liste_typemare()
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->query("SELECT idtypemare, libtypemare, mdtypemare
                                  FROM referentiel_station.typemare ");
    $resultats = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $resultats;
}


function liste_environnement()
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->query("SELECT idenvironnement, libenvironnement, mdenvironnement
                                  FROM referentiel_station.environnement ");
    $resultats = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $resultats;
}
function liste_menaces()
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->query("SELECT idmenaces, libmenaces, mdmenaces
                                  FROM referentiel_station.menaces ");
    $resultats = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $resultats;
}

function liste_vegaquatique()
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->query("SELECT idvegaquatique, libvegaquatique, mdvegaquatique
                                  FROM referentiel_station.vegaquatique ");
    $resultats = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $resultats;
}
function liste_vegsemiaquatique()
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->query("SELECT idvegsemiaquatique, libvegsemiaquatique, mdvegsemiaquatique
                                  FROM referentiel_station.vegsemiaquatique ");
    $resultats = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $resultats;
}
function liste_vegrivulaire()
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->query("SELECT idvegrivulaire, libvegrivulaire, mdvegrivulaire
                                  FROM referentiel_station.vegrivulaire ");
    $resultats = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $resultats;
}
function liste_typeexutoire()
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->query("SELECT idtypeexutoire, libtypeexutoire, mdtypeexutoire
                                  FROM referentiel_station.typeexutoire ");
    $resultats = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $resultats;
}
function liste_taillemare()
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->query("SELECT idtaillemare, libtaillemare, mdtaillemare
                                  FROM referentiel_station.taillemare ");
    $resultats = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $resultats;
}
function liste_couleureau()
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->query("SELECT idcouleureau, libcouleureau, mdcouleureau
                                  FROM referentiel_station.couleureau ");
    $resultats = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $resultats;
}
function liste_naturefond()
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->query("SELECT idnaturefond, libnaturefond, mdnaturefond
                                  FROM referentiel_station.naturefond ");
    $resultats = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $resultats;
}
function liste_recouvrberge()
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->query("SELECT idrecberge, librecberge, mdrecberge
                                  FROM referentiel_station.recberge ");
    $resultats = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $resultats;
}
function liste_profondeureau()
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->query("SELECT idprofondeureau, libprofondeureau, mdprofondeureau
                                  FROM referentiel_station.profondeureau ");
    $resultats = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $resultats;
}
function liste_alimeau()
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->query("SELECT idalimeau, libalimeau, mdalimeau
                                  FROM referentiel_station.alimeau ");
    $resultats = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $resultats;
}

function liste_departements()
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->query("SELECT iddep, departement, lat, lng FROM referentiel.departement; ");
    $resultats = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $resultats;
}

function liste_type_stations()
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->query("SELECT idtypestation, libtypestation FROM referentiel_station.typestation ");
    $resultats = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $resultats;
}
