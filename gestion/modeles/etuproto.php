<?php
function protocole()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT idprotocole, protocole, libelle, url FROM referentiel.protocole ORDER BY idprotocole ");
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function etude()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT etude.idetude, etude, libelle, masquer, string_agg(organisme.organisme, ', ') as organisme 
                                    FROM referentiel.etude 
                                    LEFT JOIN referentiel.etude_organisme ON etude.idetude = etude_organisme.idetude
                                    LEFT JOIN referentiel.organisme ON etude_organisme.idorg = organisme.idorg
                                    group by etude.idetude, etude, libelle, masquer
                                    ORDER BY idetude");
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}