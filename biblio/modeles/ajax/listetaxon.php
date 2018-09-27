<?php
include '../../../global/configbase.php';
include '../../lib/pdo2.php';

function liste_alpha($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT DISTINCT liste.cdnom, nom, observatoire FROM biblio.biblio
						LEFT JOIN biblio.bibliofiche USING(idbiblio)
						LEFT JOIN obs.obs USING(idfiche)
						LEFT JOIN biblio.bibliotaxon USING(idbiblio)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref OR liste.cdnom = bibliotaxon.cdnom
						WHERE nom ILIKE :recherche 
						ORDER BY nom ");
	$req->bindValue(':recherche', ''.$id.'%');
	$req->execute();
	$liste = $req->fetchAll();
	$req->closeCursor();
	return $liste;
}
function liste_alpha_fr($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT DISTINCT liste.cdnom, nomvern AS nom, observatoire FROM biblio.biblio
						LEFT JOIN biblio.bibliofiche USING(idbiblio)
						LEFT JOIN obs.obs USING(idfiche)
						LEFT JOIN biblio.bibliotaxon USING(idbiblio)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref OR liste.cdnom = bibliotaxon.cdnom
						WHERE nomvern ILIKE :recherche 
						ORDER BY nomvern ");
	$req->bindValue(':recherche', ''.$id.'%');
	$req->execute();
	$liste = $req->fetchAll();
	$req->closeCursor();
	return $liste;
}
if(isset($_POST['id'])) 
{	
	$id = htmlspecialchars($_POST['id']);
	$nbl = strlen($id);
	if($nbl > 1) 
	{
		$id = mb_strtolower($id, 'UTF-8');
		if($nbl == 4) { $id = substr($id, -1); }
		if($nbl == 5) { $id = substr($id, 3, 2); }
		$liste = liste_alpha_fr($id);
	}
	else
	{
		$liste = liste_alpha($id);
	}
	
	$nb = count($liste);
	
	$listealpha = '<h3 class="h5 ctitre">Résultats... </h3><hr />';
	
	if($nb > 0)
	{
		$json_site = file_get_contents('../../../json/site.json');
		$rjson = json_decode($json_site, true);
		
		foreach($liste as $n)
		{
			$tabnb[$n['observatoire']][] = ['cdnom'=>$n['cdnom'],'nom'=>$n['nom']];
			$tabobserva[$n['observatoire']] = $n['observatoire'];
		}
		foreach($rjson['observatoire'] as $n)
		{
			if(isset($tabobserva[$n['nomvar']]))
			{
				$listealpha .= '<h4 class="h6 mb-0 mt-2"><i class="'.$n['icon'].'" style="color:'.$n['couleur'].'"></i> '.$n['nom'].'</h4>';
				foreach($tabnb[$n['nomvar']] as $t)
				{
					if($nbl > 1)
					{
						$listealpha .= '<a href="index.php?module=liste&amp;action=liste&amp;choix=taxon&amp;id='.$t['cdnom'].'">'.$t['nom'].'</a><br />';
					}
					else
					{
						$listealpha .= '<a href="index.php?module=liste&amp;action=liste&amp;choix=taxon&amp;id='.$t['cdnom'].'"><i>'.$t['nom'].'</i></a><br />';
					}
				}				
			}
		}	
	}
	else
	{
		$listealpha .= 'Aucun taxon pour la lettre '.$id;	
	}
		
	echo $listealpha;	
}
?>