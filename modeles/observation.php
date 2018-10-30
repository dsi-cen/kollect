<?php

function cherche_observateur($idfiche)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT nom, prenom, idm, observateur.idobser FROM obs.plusobser
						INNER JOIN referentiel.observateur ON observateur.idobser = plusobser.idobser
						WHERE idfiche = :idfiche
						ORDER BY idplus ");
	$req->bindValue(':idfiche', $idfiche, PDO::PARAM_INT);
	$req->execute();
	$obsplus = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $obsplus;
}
function recherche_obs($idobs,$biblio)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	if($biblio == 'oui')
	{
		$req = $bdd->prepare("SELECT to_char(date1, 'DD/MM/YYYY') AS datefr, to_char(date2, 'DD/MM/YYYY') AS datefr2, site, commune, fiche.codecom, fiche.iddep, liste.nom, nomvern, nb, fiche.floutage, sensible, localisation, observateur.prenom, observateur.nom AS nomobs, fiche.idobser, obs.cdnom, obs.cdref, observa, fiche.idcoord, plusobser, idm, iddet, fiche.idfiche, statutobs, rqobs, validation, to_char(datesaisie, 'DD/MM/YYYY') AS dates, rang, iddetcol, typedet, organisme, idmor, idbiblio, etude FROM obs.fiche
							INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
							LEFT JOIN referentiel.commune ON commune.codecom = fiche.codecom
							LEFT JOIN obs.site ON site.idsite = fiche.idsite
							INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
							INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser
							LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref
							LEFT JOIN obs.obscoll ON obscoll.idobs = obs.idobs
							LEFT JOIN referentiel.organisme ON organisme.idorg = fiche.idorg
							LEFT JOIN referentiel.etude on etude.idetude = obs.idetude
							LEFT JOIN biblio.bibliofiche ON bibliofiche.idfiche = fiche.idfiche
							WHERE obs.idobs = :idobs ");
	}
	elseif($biblio == 'non')
	{
		$req = $bdd->prepare("SELECT to_char(date1, 'DD/MM/YYYY') AS datefr, to_char(date2, 'DD/MM/YYYY') AS datefr2, site, commune, fiche.codecom, fiche.iddep, liste.nom, nomvern, nb, fiche.floutage, sensible, localisation, observateur.prenom, observateur.nom AS nomobs, fiche.idobser, obs.cdnom, obs.cdref, observa, fiche.idcoord, plusobser, idm, iddet, fiche.idfiche, statutobs, rqobs, validation, to_char(datesaisie, 'DD/MM/YYYY') AS dates, rang, iddetcol, typedet, organisme, idmor, etude FROM obs.fiche
						INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
						LEFT JOIN referentiel.commune ON commune.codecom = fiche.codecom
						LEFT JOIN obs.site ON site.idsite = fiche.idsite
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser
						LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref
						LEFT JOIN obs.obscoll ON obscoll.idobs = obs.idobs
						LEFT JOIN referentiel.organisme ON organisme.idorg = fiche.idorg
						LEFT JOIN referentiel.etude USING(idetude)
						WHERE obs.idobs = :idobs ");
	}
	$req->bindValue(':idobs', $idobs, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_obs_inv($idobs,$biblio)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	if($biblio == 'oui')
	{
		$req = $bdd->prepare("SELECT to_char(date1, 'DD/MM/YYYY') AS datefr, to_char(date2, 'DD/MM/YYYY') AS datefr2, site, commune, fiche.codecom, fiche.iddep, taxref.nom, nomvern, nb, fiche.floutage, sensible, localisation, observateur.prenom, observateur.nom AS nomobs, fiche.idobser, obs.cdnom, obs.cdref, observa, fiche.idcoord, plusobser, idm, iddet, fiche.idfiche, statutobs, rqobs, validation, to_char(datesaisie, 'DD/MM/YYYY') AS dates, rang, idbiblio FROM obs.fiche
							INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
							LEFT JOIN referentiel.commune ON commune.codecom = fiche.codecom
							LEFT JOIN obs.site ON site.idsite = fiche.idsite
							INNER JOIN referentiel.taxref ON taxref.cdnom = obs.cdref
							INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser
							LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref
							LEFT JOIN biblio.bibliofiche ON bibliofiche.idfiche = fiche.idfiche
							WHERE idobs = :idobs ");
	}
	elseif($biblio == 'non')
	{	
		$req = $bdd->prepare("SELECT to_char(date1, 'DD/MM/YYYY') AS datefr, to_char(date2, 'DD/MM/YYYY') AS datefr2, site, commune, fiche.codecom, fiche.iddep, taxref.nom, nomvern, nb, fiche.floutage, sensible, localisation, observateur.prenom, observateur.nom AS nomobs, fiche.idobser, obs.cdnom, obs.cdref, observa, fiche.idcoord, plusobser, idm, iddet, fiche.idfiche, statutobs, rqobs, validation, to_char(datesaisie, 'DD/MM/YYYY') AS dates, rang FROM obs.fiche
							INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
							LEFT JOIN referentiel.commune ON commune.codecom = fiche.codecom
							LEFT JOIN obs.site ON site.idsite = fiche.idsite
							INNER JOIN referentiel.taxref ON taxref.cdnom = obs.cdref
							INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser
							LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref							
							WHERE idobs = :idobs ");
	}
	$req->bindValue(':idobs', $idobs, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_det($iddet)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT nom, prenom FROM referentiel.observateur WHERE idobser = :iddet ");
	$req->bindValue(':iddet', $iddet);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_sp($cdnom,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT nom FROM $nomvar.liste WHERE cdnom = :cdnom ");
	$req->bindValue(':cdnom', $cdnom);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_com($idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idm, commentaire, prenom, nom, to_char(datecom, 'DD/MM/YYYY - HH24:MI') AS datefr FROM site.comobs 
						INNER JOIN site.membre ON membre.idmembre = comobs.idm
						WHERE idobs = :idobs 
						ORDER BY datecom ");
	$req->bindValue(':idobs', $idobs);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_ligne($idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT stade.stade, ndiff, male, femelle, denom, nbmin, nbmax, tdenom, idetatbio, methode, prospection, statutbio, comportement.libcomp FROM obs.ligneobs
						INNER JOIN referentiel.stade ON stade.idstade = ligneobs.stade
						INNER JOIN referentiel.methode USING(idmethode)
						INNER JOIN referentiel.prospection USING(idpros)
						INNER JOIN referentiel.occstatutbio USING(idstbio)
						INNER JOIN referentiel.comportement USING(idcomp)
						WHERE idobs = :idobs ");
	$req->bindValue(':idobs', $idobs);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function cherche_photo($idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT observateur AS auteur, nomphoto, observatoire, stade.stade, idphoto FROM site.photo
						INNER JOIN referentiel.observateur USING(idobser)
						LEFT JOIN referentiel.stade ON stade.idstade = photo.stade
						WHERE idobs = :idobs ");
	$req->bindValue(':idobs', $idobs);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function cherche_son($idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT observateur AS auteur, nomson, descri FROM site.son
						INNER JOIN referentiel.observateur USING(idobser)
						WHERE idobs = :idobs ");
	$req->bindValue(':idobs', $idobs);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function supnotif($idobs,$idm)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("DELETE FROM site.notif WHERE idtype = :idobs AND idm = :idm ");
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':idm', $idm);
	$req->execute();
	$req->closeCursor();
}
function recherche_obs_fiche($idfiche)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT liste.nom, nomvern, rang, observa, auteur, obs.cdref, idobs, sensible, validation, nb FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref
						WHERE idfiche = :idfiche AND observa != 'aucun'
						ORDER BY observa, liste.nom ");
	$req->bindValue(':idfiche', $idfiche);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_photo_fiche($idfiche)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT nomphoto, photo.observatoire, observateur AS auteur, liste.nom, nomvern FROM site.photo
						INNER JOIN obs.obs USING(idobs)
						INNER JOIN referentiel.liste ON liste.cdnom = photo.cdnom
						INNER JOIN referentiel.observateur USING(idobser)
						WHERE idfiche = :idfiche
						ORDER BY observatoire ");
	$req->bindValue(':idfiche', $idfiche);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_observa($idfiche)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT COUNT(DISTINCT cdref) AS nb, observa FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE idfiche = :idfiche AND observa != 'aucun'
						GROUP BY observa ");
	$req->bindValue(':idfiche', $idfiche);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function info_fiche($idfiche,$biblio)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	if($biblio == 'oui')
	{
		$req = $bdd->prepare("SELECT to_char(date1, 'DD/MM/YYYY') AS datefr, to_char(date2, 'DD/MM/YYYY') AS datefr2, site, commune, fiche.codecom, fiche.iddep, fiche.floutage, localisation, observateur.prenom, observateur.nom, fiche.idobser, fiche.idcoord, plusobser, idm, idbiblio FROM obs.fiche
							LEFT JOIN referentiel.commune ON commune.codecom = fiche.codecom
							LEFT JOIN obs.site ON site.idsite = fiche.idsite
							INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser
							LEFT JOIN biblio.bibliofiche USING(idfiche)
							WHERE idfiche = :idfiche ");
	}
	elseif($biblio == 'non')
	{
		$req = $bdd->prepare("SELECT to_char(date1, 'DD/MM/YYYY') AS datefr, to_char(date2, 'DD/MM/YYYY') AS datefr2, site, commune, fiche.codecom, fiche.iddep, fiche.floutage, localisation, observateur.prenom, observateur.nom, fiche.idobser, fiche.idcoord, plusobser, idm FROM obs.fiche
							LEFT JOIN referentiel.commune ON commune.codecom = fiche.codecom
							LEFT JOIN obs.site ON site.idsite = fiche.idsite
							INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser						
							WHERE idfiche = :idfiche ");
	}
	$req->bindValue(':idfiche', $idfiche, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function sensible($idfiche)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT sensible, sensible.cdnom FROM obs.obs
						LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref
						WHERE idfiche = :idfiche ");
	$req->bindValue(':idfiche', $idfiche, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function cherche_vali($idm,$observa)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idmembre FROM site.validateur WHERE discipline ILIKE :observa AND idmembre = :id ");
	$req->bindValue(':observa', '%'.$observa.'%');
	$req->bindValue(':id', $idm);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;	
}
function plante($idobs,$tablebota)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	if($tablebota == 'listebota')
	{
		$req = $bdd->prepare("SELECT stade.stade, nb, nom, nomvern FROM obs.obsplte
							INNER JOIN obs.ligneobs USING(idobs)
							INNER JOIN referentiel.stade ON stade.idstade = ligneobs.stade AND stade.idstade = obsplte.stade
							INNER JOIN referentiel.listebota ON listebota.cdnom = obsplte.cdnom
							WHERE obsplte.idobs = :idobs ");
	}
	else
	{
		$req = $bdd->prepare("SELECT stade.stade, nb, nom, nomvern FROM obs.obsplte
							INNER JOIN obs.ligneobs USING(idobs)
							INNER JOIN referentiel.stade ON stade.idstade = ligneobs.stade AND stade.idstade = obsplte.stade
							INNER JOIN referentiel.liste ON liste.cdnom = obsplte.cdnom
							WHERE obsplte.idobs = :idobs ");
	}	
	$req->bindValue(':idobs', $idobs);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function aves($idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT code FROM obs.aves WHERE idobs = :idobs ");
	$req->bindValue(':idobs', $idobs);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_membre($idm)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT prenom, nom FROM site.membre WHERE idmembre = :idm ");
	$req->bindValue(':idm', $idm);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}