<?php 
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';
	
function recherche($idbiblio)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT titre, idauteur, nom, prenom, typep, publi, annee, tome, fascicule, page, resume, plusauteur, observa, url, isbn FROM biblio.biblio
						INNER JOIN biblio.auteurs USING(idauteur)
						LEFT JOIN biblio.biblioobserva USING(idbiblio)
						LEFT JOIN biblio.lienexterne USING(idbiblio)
						WHERE idbiblio = :idbiblio ");
	$req->bindValue(':idbiblio', $idbiblio, PDO::PARAM_INT);
	$req->execute();
	$biblio = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $biblio;		
}
function recherche_auteur($idbiblio)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT nom, prenom, idauteur FROM biblio.plusauteur
						INNER JOIN biblio.auteurs USING(idauteur)
						WHERE idbiblio = :idbiblio ");
	$req->bindValue(':idbiblio', $idbiblio, PDO::PARAM_INT);
	$req->execute();
	$auteur = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $auteur;
}
function commune($idbiblio)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT codecom, commune FROM biblio.bibliocom
						INNER JOIN referentiel.commune USING(codecom)
						WHERE idbiblio = :idbiblio ");
	$req->bindValue(':idbiblio', $idbiblio, PDO::PARAM_INT);
	$req->execute();
	$biblio = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $biblio;		
}
function taxon($idbiblio)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT cdnom, nom, nomvern FROM biblio.bibliotaxon
						INNER JOIN referentiel.liste USING(cdnom)
						WHERE idbiblio = :idbiblio ");
	$req->bindValue(':idbiblio', $idbiblio, PDO::PARAM_INT);
	$req->execute();
	$biblio = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $biblio;		
}
function motcle($idbiblio)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idmc, mot FROM biblio.bibliomc
						INNER JOIN biblio.motcle USING(idmc)
						WHERE idbiblio = :idbiblio ");
	$req->bindValue(':idbiblio', $idbiblio, PDO::PARAM_INT);
	$req->execute();
	$biblio = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $biblio;		
}
	
if(isset($_POST['id']))
{
	$idbiblio = $_POST['id'];
	
	$biblio = recherche($idbiblio);
	
	if($biblio['plusauteur'] == 'oui')
	{
		$plusauteur = recherche_auteur($idbiblio);
		foreach($plusauteur as $n)
		{
			$tabid[] = $n['idauteur'];
			$tabnom[] = $n['nom'].' '.$n['prenom'];
		}
		$retour['idauteur'] = $biblio['idauteur'].', '.implode(", ",$tabid);
		$retour['auteur'] = $biblio['nom'].' '.$biblio['prenom'].', '.implode(", ",$tabnom).', ';
	}
	else
	{
		$retour['idauteur'] = $biblio['idauteur'];
		$retour['auteur'] = $biblio['nom'].' '.$biblio['prenom'].', ';
	}
	
	$com = commune($idbiblio);
	if($com != false)
	{
		$c = null;
		foreach($com as $n)
		{
			$c .= '<li id="'.$n['codecom'].'"><i class="fa fa-trash curseurlien text-danger"></i> '.$n['commune'].'</li>';
			$tabcom[] = $n['codecom'];
		}
		$retour['commune'] = $c;
		$retour['codecom'] = implode(",", $tabcom);
	}
	$taxon = taxon($idbiblio);
	if($taxon != false)
	{
		$t = null;
		foreach($taxon as $n)
		{
			$t .= '<li id="'.$n['cdnom'].'"><i class="fa fa-trash curseurlien text-danger"></i> <i>'.$n['nom'].'</i> '.$n['nomvern'].'</li>';
			$tabcdnom[] = $n['cdnom'];
		}
		$retour['taxon'] = $t;
		$retour['cdnom'] = implode(",", $tabcdnom);
	}
	$motcle = motcle($idbiblio);
	if($motcle != false)
	{
		$mc = null;
		foreach($motcle as $n)
		{
			$mc .= '<li id="'.$n['idmc'].'"><i class="fa fa-trash curseurlien text-danger"></i> '.$n['mot'].'</li>';
			$tabmc[] = $n['idmc'];
		}
		$retour['motcle'] = $mc;
		$retour['idmc'] = implode(",", $tabmc);
	}
	
	$retour['biblio'] = $biblio;
	$retour['statut'] = 'Oui';	
}
else
{
	$retour['statut'] = 'Non';
}
echo json_encode($retour);
?>