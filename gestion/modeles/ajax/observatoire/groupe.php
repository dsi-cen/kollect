<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';	
function table($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT table_name FROM information_schema.tables WHERE table_schema='$nomvar' AND table_name='liste'") or die(print_r($bdd->errorInfo()));
	$table = $req->rowCount();
	$req->closeCursor();
	return $table;		
}
/*function listegroupe()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT groupe, COUNT(DISTINCT cdref) AS nb FROM referentiel.taxref
						WHERE rang = 'ES'
						GROUP BY groupe ") or die(print_r($bdd->errorInfo()));
	$listegroupe = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $listegroupe;
}*/
function listegroupe()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT DISTINCT groupe FROM referentiel.taxref ORDER BY groupe ") or die(print_r($bdd->errorInfo()));
	$listegroupe = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $listegroupe;
}
function listeordre($selgrpe)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT ordre, COUNT(DISTINCT cdref) AS nb FROM referentiel.taxref 
						WHERE groupe = :groupe AND rang = 'ES'
						GROUP BY ordre ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':groupe', $selgrpe);
	$req->execute();
	$listeordre = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $listeordre;
}
function listefamille($selfam)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT famille, COUNT(DISTINCT cdref) AS nb FROM referentiel.taxref 
						WHERE ordre = :ordre AND rang = 'ES'
						GROUP BY famille ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':ordre', $selfam);
	$req->execute();
	$listefamille = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $listefamille;
}
if (isset($_POST['sel']) and isset($_POST['re']))
{
	if ($_POST['re'] == 'groupe')
	{
		$nomvar = ($_POST['sel']);
		$table = table($nomvar);
		if ($table > 0)	
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-info" role="alert">La liste des espèces est déjà configurée pour cet observatoire</div>';
		}
		else
		{
			$listegroupe = listegroupe();
			$retour['statut'] = 'Oui';
			$retour['groupe'] = $listegroupe;
		}	
	}
	elseif ($_POST['re'] == 'ordre')
	{
		$selgrpe = ($_POST['sel']);
		$listeordre = listeordre($selgrpe);
		$retour['statut'] = 'Oui';
		$retour['ordre'] = $listeordre;		
	}
	elseif ($_POST['re'] == 'famille')
	{
		$selfam = ($_POST['sel']);
		$listefamille = listefamille($selfam);
		$retour['statut'] = 'Oui';
		$retour['famille'] = $listefamille;		
	}
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert">Aucun observatoire de choisit.</div>';
}
echo json_encode($retour);