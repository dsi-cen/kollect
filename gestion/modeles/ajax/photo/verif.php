<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function verifun($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT DISTINCT p.cdnom, nom, nomvern FROM site.photo AS p
						INNER JOIN $nomvar.liste ON liste.cdnom = p.cdnom
						WHERE observatoire = :observa AND NOT EXISTS (SELECT cdnom FROM site.photo WHERE photo.cdnom = p.cdnom AND ordre = 1) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function verifdble($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT DISTINCT COUNT(ordre) AS doublon, cdnom, nom, nomvern FROM site.photo
						INNER JOIN $nomvar.liste USING(cdnom)
						GROUP BY ordre, cdnom, nom, nomvern
						HAVING COUNT(ordre) > 1 ") or die(print_r($bdd->errorInfo()));
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}

if(isset($_POST['sel']))
{
	$nomvar = $_POST['sel'];
	
	$un = verifun($nomvar);
	$dble = verifdble($nomvar);
	
	$l = '<h2 class="h5">Numéro ordre 1</h2>';
	if($un != false)
	{
		$l .= '<p>Taxons sans numéro d\'ordre 1.</p>';
		$l .= '<ul>';
		foreach($un as $n)
		{
			$l .= '<li id="u-'.$n['cdnom'].'"><i class="un fa fa-check text-warning curseurlien"></i> <a href="index.php?module=photo&amp;action=photo&amp;cdnom='.$n['cdnom'].'"><i class="fa fa-pencil text-warning"></i></a> <i>'.$n['nom'].'</i> - '.$n['nomvern'].'</li>';			
		}
		$l .= '</ul>';
	}
	else
	{
		$l .= '<p>Toutes les photos de cet observatoire ont bien un numéro 1.</p>';
	}
	$l .= '<h2 class="h5">Doublon dans les numéros</h2>';
	if($dble != false)
	{
		$l .= '<p>Taxons avec doublons dans les numéros.</p>';
		$l .= '<ul>';
		foreach($dble as $n)
		{
			$l .= '<li id="d-'.$n['cdnom'].'"><i class="dbl fa fa-check text-warning curseurlien"></i> <a href="index.php?module=photo&amp;action=photo&amp;cdnom='.$n['cdnom'].'"><i class="fa fa-pencil text-warning"></i></a> <i>'.$n['nom'].'</i> - '.$n['nomvern'].'</li>';			
		}
		$l .= '</ul>';
	}
	else
	{
		$l .= '<p>Aucun doublon dans les numéros.</p>';
	}
	
	$retour['liste'] = $l;
	$retour['statut'] = 'Oui';	
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert">Aucun observatoire de choisit.</div>';
}
echo json_encode($retour);