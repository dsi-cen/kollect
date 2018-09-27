<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';	
function taxon($id,$nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->prepare("SELECT nom, nomvern FROM statut.statut
						INNER JOIN $nomvar.liste ON liste.cdnom = statut.cdnom
						WHERE cdprotect = :id AND locale = 'oui'
						ORDER BY nom ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id);
	$req->execute();
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}

if (isset($_POST['id']))
{
	$id = $_POST['id'];
	$nomvar = $_POST['sel'];
	
	$taxon = taxon($id,$nomvar);
	
	
	$liste = null;
	if(count($taxon) > 0)
	{
		$liste .= '<h3 class="h4">Liste des taxons concernés</h3>';
		$liste .= '<ul>';
		foreach($taxon as $n)
		{
			$liste .= '<li><i>'.$n['nom'].'</i> '.$n['nomvern'].'</li>';
		}
		$liste .= '</ul>';
	}
	else
	{
		$liste .= '<div class="alert alert-danger" role="alert">Aucun taxon dans cet observatoire pour ce statut</div>';
	}
	
	$retour['liste'] = $liste;
	$retour['statut'] = 'Oui';	
	
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Aucun observatoire de choisit.</p></div>';
}
echo json_encode($retour);