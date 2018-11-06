<?php
include '../../../../global/configbase.php';
include '../../../lib/pdo2.php';

function liste_alpha_nom($id,$observa)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT DISTINCT photo.cdnom, nom FROM $observa.liste 
						INNER JOIN site.photo ON photo.cdnom = liste.cdnom
						WHERE nom ILIKE :recherche AND (rang = 'ES' OR rang = 'SSES')
						ORDER BY nom ");
	$req->bindValue(':recherche', ''.$id.'%');
	$req->execute();
	$liste = $req->fetchAll();
	$req->closeCursor();
	return $liste;
}
function liste_alpha_nomvern($id,$observa)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT DISTINCT photo.cdnom, nom, nomvern FROM $observa.liste 
						INNER JOIN site.photo ON photo.cdnom = liste.cdnom
						WHERE nomvern ILIKE :recherche AND (rang = 'ES' OR rang = 'SSES')
						ORDER BY nomvern ");
	$req->bindValue(':recherche', ''.$id.'%');
	$req->execute();
	$liste = $req->fetchAll();
	$req->closeCursor();
	return $liste;
}
function liste_alpha_nom_auteur($id,$observa,$idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT DISTINCT photo.cdnom, nom FROM $observa.liste 
						INNER JOIN site.photo ON photo.cdnom = liste.cdnom
						WHERE nom ILIKE :recherche AND idobser = :idobser AND (rang = 'ES' OR rang = 'SSES')
						ORDER BY nom ");
	$req->bindValue(':recherche', ''.$id.'%');
	$req->bindValue(':idobser', $idobser, PDO::PARAM_INT);
	$req->execute();
	$liste = $req->fetchAll();
	$req->closeCursor();
	return $liste;
}
function liste_alpha_nomvern_auteur($id,$observa,$idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT DISTINCT photo.cdnom, nom, nomvern FROM $observa.liste 
						INNER JOIN site.photo ON photo.cdnom = liste.cdnom
						WHERE nomvern ILIKE :recherche AND idobser = :idobser AND (rang = 'ES' OR rang = 'SSES')
						ORDER BY nomvern ");
	$req->bindValue(':recherche', ''.$id.'%');
	$req->bindValue(':idobser', $idobser, PDO::PARAM_INT);
	$req->execute();
	$liste = $req->fetchAll();
	$req->closeCursor();
	return $liste;
}

if(isset($_POST['id'])) 
{	
	$id = htmlspecialchars($_POST['id']);
	$tri = $_POST['tri'];
	$observa = htmlspecialchars($_POST['sel']);
	$idobser = htmlspecialchars($_POST['idobser']);
	
	if(empty($idobser))
	{
		$liste = ($tri == 'nom') ? liste_alpha_nom($id,$observa) : liste_alpha_nomvern($id,$observa);
	}
	else
	{
		$liste = ($tri == 'nom') ? liste_alpha_nom_auteur($id,$observa,$idobser) : liste_alpha_nomvern_auteur($id,$observa,$idobser);
	}
	
	$nb = count($liste);
	
	$listealpha = '<h2 class="h5 ctitre">Résultats... </h2><hr />';
	
	if($nb > 0)
	{
		foreach($liste as $n)
		{
			if($tri == 'nom')
			{
				$listealpha .= (empty($idobser)) ? '<a href="index.php?module=photo&amp;action=taxon&amp;d='.$observa.'&amp;id='.$n['cdnom'].'"><i>'.$n['nom'].'</i></a><br />' : '<a href="index.php?module=photo&amp;action=taxon&amp;d='.$observa.'&amp;id='.$n['cdnom'].'&amp;idobser='.$idobser.'"><i>'.$n['nom'].'</i></a><br />';
			}
			else
			{
				$listealpha .= (empty($idobser)) ? '<a href="index.php?module=photo&amp;action=taxon&amp;d='.$observa.'&amp;id='.$n['cdnom'].'">'.$n['nomvern'].' (<i>'.$n['nom'].'</i>)</a><br />' : '<a href="index.php?module=photo&amp;action=taxon&amp;d='.$observa.'&amp;id='.$n['cdnom'].'&amp;idobser='.$idobser.'">'.$n['nomvern'].' (<i>'.$n['nom'].'</i>)</a><br />';
			}
		}		
	}
	else
	{
		$listealpha .= 'Aucune espèce pour la lettre '.$id;	
	}
		
	echo $listealpha;	
}
?>