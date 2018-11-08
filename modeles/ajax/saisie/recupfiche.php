<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function fiche($idfiche)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idfiche, coordonnee.lat, coordonnee.lng, localisation, idobser, plusobser, floutage, typedon, source, idpreci, idorg, fiche.idetude as idetude, fiche.iddep, fiche.idcoord, fiche.codecom, idsite, commune, site, to_char(date1, 'DD/MM/YYYY') AS date1fr, to_char(date2, 'DD/MM/YYYY') AS date2fr, geo, hdebut, hfin, tempdebut, tempfin FROM obs.fiche
						INNER JOIN referentiel.commune USING(codecom)
						LEFT JOIN obs.coordonnee USING(idcoord)
						LEFT JOIN obs.site USING(idsite)
						LEFT JOIN obs.coordgeo ON coordgeo.idcoord = coordonnee.idcoord
						LEFT JOIN obs.fichesup USING(idfiche)
						WHERE idfiche = :idfiche ");
	$req->bindValue(':idfiche', $idfiche, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function plusobser($idfiche)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idobser, nom, prenom FROM obs.plusobser 
						INNER JOIN referentiel.observateur USING(idobser)
						WHERE idfiche = :idfiche ");
	$req->bindValue(':idfiche', $idfiche, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}

if(isset($_POST['idfiche']))
{	
	$idfiche = $_POST['idfiche'];
	$fiche = fiche($idfiche);
	if($fiche['plusobser'] == 'oui')
	{
		$plusobser = plusobser($idfiche);
		foreach($plusobser as $n)
		{
			$tabid[] = $n['idobser'];
			$tabnom[] = $n['nom'].' '.$n['prenom'];
		}
		$retour['idobser'] = $fiche['idobser'].', '.implode(", ",$tabid);
		$retour['obser'] = implode(", ",$tabnom).', ';
	}
	

	$retour['fiche'] = $fiche;	
	$retour['statut'] = 'Oui';
}
else
{
	$retour['statut'] = 'Non';	
}
echo json_encode($retour);	
?>