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
function recherche_famille($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT cdnom, famille, nomvern FROM $nomvar.famille ORDER BY famille") or die(print_r($bdd->errorInfo()));
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}
function recherche_famillecat($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT cdnom, famille.famille, nomvern, cat FROM $nomvar.famille 
						LEFT JOIN $nomvar.categorie ON categorie.famille = famille.cdnom
						ORDER BY famille.famille") or die(print_r($bdd->errorInfo()));
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}
if (isset($_POST['sel']))
{
	$nomvar = $_POST['sel'];	
	$table = table($nomvar);
	if ($table > 0)	
	{
		$json = file_get_contents('../../../../json/'.$nomvar.'.json');
		$rjson = json_decode($json, true);
		if (isset($rjson['categorie']))
		{
			$famille = recherche_famillecat($nomvar);
		}
		else
		{
			$famille = recherche_famille($nomvar);
		}
		$nbfamille = count($famille);
		$tab = null;
		$tab .= '<p><b>Liste des familles des '.$rjson['nom'].' ('.$nbfamille.')</b><br />Vous pouvez changer les noms vernaculaires et attribué une catégorie aux familles.</p>';
		$tab .= '<table class="table table-condensed table-hover">';
		$tab .= '<thead><tr><th>Famille</th><th>Vernaculaire</th><th>Catégorie</th></tr></thead><tbody>';
		foreach ($famille as $n)
		{
			if (isset($rjson['categorie']))
			{
				$tab .= '<tr id="'.$n['cdnom'].'"><td>'.$n['famille'].'</td><td><input type="text" value="'.$n['nomvern'].'" class="fam"></td><td>
				<select class="selcat"><option value="'.$n['cat'].'">'.$n['cat'].'</option>';
				foreach ($rjson['categorie'] as $c)
				{
					if ($c['id'] != $n['cat'])
					{
						$tab .= '<option value="'.$c['id'].'">'.$c['id'].'</option>';
					}					
				}
				$tab .= '</select></td></tr>';
			}
			else
			{
				$tab .= '<tr id="'.$n['cdnom'].'"><td>'.$n['famille'].'</td><td><input type="text" value="'.$n['nomvern'].'" class="fam"></td><td>Aucune</td></tr>';
			}			
		}
		$tab .= '</tbody></table>';
		
		$tabcat = null;
		if (isset($rjson['categorie']))
		{
			$tabcat .= '<p><b>Liste des catégories pour les '.$rjson['nom'].'</b><br />Vous pouvez aussi changer le nom des catégories</p>';
			$tabcat .= '<table class="table table-condensed table-hover">';
			$tabcat .= '<thead><tr><th>Id</th><th>Nom catégorie</th></tr></thead><tbody>';
			foreach ($rjson['categorie'] as $c)
			{
				$tabcat .= '<tr id="'.$c['id'].'"><td>'.$c['id'].'</td><td><input type="text" value="'.$c['cat'].'" class="selcat2"></td></tr>';
			}
			$tabcat .= '</tbody></table>';			
		}
		else
		{
			$tabcat .= '<p><b>Liste des catégories pour les '.$rjson['nom'].'</b></p>';
			if ($nbfamille > 1)
			{
				$tabcat .= '<p>Aucune catégorie pour l\'instant</p>';
			}
			else
			{
				$tabcat .= '<p>Il n\'est pas possible de créer des catégories pour un observatoire sur une seule famille</p>';
				$retour['unique'] = 'oui';
			}			
		}				
		$retour['tabcat'] = $tabcat;
		$retour['statut'] = 'Oui';
		$retour['tab'] = $tab;
	}
	else
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Vous devez au péalable choisir <a href="index.php?module=observatoire&amp;action=espece">la liste</a></p></div>';
	}		
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Aucun observatoire de choisit.</p></div>';
}
echo json_encode($retour);