<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';	

function nonexiste($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->prepare("SELECT DISTINCT obs.cdref, nom, observa, rang FROM obs.obs
						INNER JOIN referentiel.taxref ON taxref.cdnom = obs.cdref
						WHERE NOT EXISTS (SELECT * FROM referentiel.liste WHERE liste.cdnom = obs.cdref) AND observa = :observa ");
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$nbresultats = $req->rowCount();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return array($nbresultats, $resultat);	
}
function nbspobs($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->prepare("SELECT COUNT(DISTINCT cdref) FROM obs.obs 
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE observa = :observa AND (rang = 'ES' OR rang = 'SSES') ");
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;	
}
function locale($nomvar,$locale)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->prepare("SELECT COUNT(DISTINCT cdref) AS nb FROM $nomvar.liste
						WHERE locale = :locale AND cdref = cdnom AND (rang = 'ES' OR rang = 'SSES')  ");
	$req->bindValue(':locale', $locale);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;	
}

if(isset($_POST['sel']))
{
	$nomvar = ($_POST['sel']);
	$liste = nonexiste($nomvar);
	
	if($liste[0] >= 1)
	{
		$l = '<ul>';
		foreach($liste[1] as $n)
		{
			if($n['rang'] == 'GN')
			{
				$l .= '<li>'.$n['nom'].' - <a href="../observatoire/index.php?module=fiche&amp;action=ficheg&amp;d='.$nomvar.'&amp;id='.$n['cdref'].'">Voir la fiche</a></li>';
			}
			else
			{
				$l .= '<li>'.$n['nom'].' - <a href="../observatoire/index.php?module=fiche&amp;action=fiche&amp;d='.$nomvar.'&amp;id='.$n['cdref'].'">Voir la fiche</a></li>';
			}			
		}
		$l .= '</ul>';
		$retour['mes'] = '<div class="alert alert-warning" role="alert">La liste ci-dessous liste les espèces ayant au moins une observation dans la base, mais notée comme non présente dans la page "gestion des taxons".</div>';
		$retour['liste'] = $l;
	}
	else
	{
		$retour['mes'] = '<div class="alert alert-success" role="alert">Aucun incohérence de trouvée pour cet observatoire.</div>';
	}
	
	$nbspobs = nbspobs($nomvar);
	$locale = 'oui';
	$nboui = locale($nomvar,$locale);
	$locale = 'non';
	$nbnon = locale($nomvar,$locale);
	$nbtotal = $nbnon + $nboui;
	
	$ll = '<p>';
	$ll .= '<b>'.$nbspobs.'</b> espèces ont été observées sur les <b>'.$nboui.'</b> notées comme présentes.';
	$ll .= '<br />-> <b>'.$nboui.'</b> espèces (sur les '.$nbtotal.' de France) sont susceptibles d\'être observées sur l\'emprise';
	if($nbnon == 0)
	{
		$ll .= '<br />Il semblerai que la liste des espèces n\'a pas été filtrée sur la page <a href="index.php?module=observatoire&amp;action=liste">gestion des taxons</a>';
	}
	$ll .= '</p>';

	if($nboui > $nbspobs)
	{
		$ll .= '<button type="button" id="BttV" class="btn btn-success">Liste des espèces notées présentes mais sans données</button>';
	}
	
	$retour['stat'] = $ll;
	
	$retour['statut'] = 'Oui';
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert">Aucun observatoire de choisit.</div>';
}
echo json_encode($retour);