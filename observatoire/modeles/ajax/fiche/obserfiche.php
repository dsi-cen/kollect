<?php 
include '../../../../global/configbase.php';
include '../../../lib/pdo2.php';

function listeobs($cdnom,$rang,$nomvar,$genre)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	if($rang == 'oui')
	{
		$req = $bdd->prepare("SELECT DISTINCT observateur.nom, prenom FROM obs.fiche 
							INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
							LEFT JOIN obs.plusobser on plusobser.idfiche = fiche.idfiche
							INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser OR observateur.idobser = plusobser.idobser 
							INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
							WHERE obs.cdref = :cdnom OR cdsup = :cdnom
							ORDER BY nom ") or die(print_r($bdd->errorInfo()));		
	}
	elseif($rang == 'non' )
	{
		$req = $bdd->prepare("SELECT DISTINCT observateur.nom, prenom FROM obs.fiche 
							INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
							LEFT JOIN obs.plusobser on plusobser.idfiche = fiche.idfiche
							INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser OR observateur.idobser = plusobser.idobser 
							WHERE cdref = :cdnom
							ORDER BY nom ") or die(print_r($bdd->errorInfo()));		
	}
	elseif($rang == 'GN')
	{
		$req = $bdd->prepare("SELECT DISTINCT observateur.nom, prenom FROM obs.fiche 
							INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
							LEFT JOIN obs.plusobser on plusobser.idfiche = fiche.idfiche
							INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser OR observateur.idobser = plusobser.idobser 
							INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
							WHERE genre = :genre OR obs.cdref = :cdnom
							ORDER BY nom ") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':genre', $genre);
	}
	$req->bindValue(':cdnom', $cdnom);
	$req->execute();
	$observateur = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $observateur;
}	

if(isset($_POST['cdnom'])) 
{
	$cdnom = $_POST['cdnom'];
	$rang = $_POST['rang'];
	$nomvar = $_POST['nomvar'];
	$genre = (isset($_POST['nom'])) ? $_POST['nom'] : '';
	$observateur = listeobs($cdnom,$rang,$nomvar,$genre);
	
	foreach($observateur as $n)
	{
		$tabobs[] = $n['prenom'].' <b>'.$n['nom'].'</b>';				
	}
	$nbobser = count($observateur);
	$obs = implode(", ", $tabobs);
	
	$listeobser = null;
	$listeobser .= '<hr />';	
	$listeobser .= '<p>'.$obs.'</p>';

	$retour['statut'] = 'Oui';
	$retour['nb'] = $nbobser;
	$retour['listeobser'] = $listeobser;
}
else
{
	$retour['statut'] = 'Non';
}	
echo json_encode($retour);
