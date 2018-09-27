<?php
include '../../../../global/configbase.php';
include '../../../lib/pdo2.php';

function recherche_taxcat($observa,$latin,$ordre,$cat)
{
	$bdd = PDO2::getInstance();
	if($ordre == 'A')
	{
		$req = $bdd->prepare("SELECT distinct(liste.cdnom), nom, liste.famille, liste.nomvern, liste.auteur, COUNT(idobs) AS nb, liste.rang FROM $observa.liste 
							INNER JOIN obs.obs ON obs.cdref = liste.cdnom
							LEFT JOIN $observa.categorie ON categorie.famille = liste.famille
							WHERE liste.cdref = liste.cdnom AND liste.rang != 'GN' AND liste.locale = 'oui' AND cat = :cat 
							GROUP BY liste.cdnom, liste.nom, liste.famille, liste.nomvern, liste.auteur, liste.rang
							ORDER BY $latin ");
	}
	elseif($ordre == 'S')
	{
		$req = $bdd->prepare("SELECT distinct(liste.cdnom), ordre, nom, famille, liste.nomvern, liste.auteur, COUNT(idobs) AS nb, liste.rang FROM $observa.liste 
							LEFT JOIN $observa.systematique ON systematique.cdnom = liste.cdnom
							INNER JOIN obs.obs ON obs.cdref = liste.cdnom
							LEFT JOIN $observa.categorie ON categorie.famille = liste.famille
							WHERE liste.cdref = liste.cdnom AND liste.rang != 'GN' AND liste.locale = 'oui' AND cat = :cat 
							ORDER BY ordre, $latin ");
	}
	$req->bindValue(':cat', $cat);
	$req->execute();
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;	
}
function recherche_famillecat($observa,$cat,$ordre)
{
	$bdd = PDO2::getInstance();
	if($ordre == 'A')
	{	
		$req = $bdd->prepare("SELECT cdnom, famille.famille, auteur, nomvern FROM $observa.famille 
							INNER JOIN $observa.categorie ON categorie.famille = famille.cdnom
							WHERE cat = :cat AND locale = 'oui'
							ORDER BY famille");
	}
	elseif($ordre == 'S')
	{
		$req = $bdd->prepare("SELECT famille.cdnom, famille.famille, auteur, nomvern FROM $observa.famille 
							INNER JOIN $observa.categorie ON categorie.famille = famille.cdnom
							LEFT JOIN $observa.systematique ON systematique.cdnom = famille.cdnom
							WHERE cat = :cat AND locale = 'oui'
							ORDER BY systematique.ordre, famille");
	}
	$req->bindValue(':cat', $cat);
	$req->execute();
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}

if(isset($_POST['observa']))
{
	$observa = htmlspecialchars($_POST['observa']);
	$cat = $_POST['cat'];
	$ordre = $_POST['ordre'];
	$latin = $_POST['latin'];
	$objcat = json_decode($_POST['objcat'], true);
	$ordret = ($ordre == 'A') ? 'alphabétique' : 'systématique';
	
	foreach($objcat as $n)
	{
		if($n['id'] == $cat) { $titrecat = $n['cat']; }
	}	
	
	$taxon = recherche_taxcat($observa,$latin,$ordre,$cat);
	
	$l = '<h2 class="h4">Liste des '.$titrecat.' <small>('.$ordret.')</small></h2>';
	
	if(count($taxon) > 0 )
	{
		$nbsp = 0; $nbssp = 0; $nbcom = 0;
		foreach($taxon as $n)
		{
			$tabf[] = $n['famille'];
			if($n['rang'] == 'ES') { $nbsp++; }
			elseif($n['rang'] == 'SSES') { $nbssp++; }
			elseif($n['rang'] == 'COM') { $nbcom++; }
		}
		$lib = ($nbsp > 1) ? $nbsp.' espèces' : $nbsp.' espèce';
		if(isset($nbssp) && $nbssp != 0)
		{
			$lib .= ($nbssp > 1) ? ', '.$nbssp.' sous espèces' : ', '.$nbssp.' sous espèce';
		}
		if(isset($nbcom) && $nbcom != 0) 
		{
			$lib .= ($nbcom > 1) ? ', '.$nbcom.' complexes d\'espèces' : ', '.$nbcom.' complexe d\'espèce';
		}
		
		$l .= '<p>'.$lib.'</p>';
		$lib = ($nbsp > 1) ? $nbsp.' espèces' : $nbsp.' espèce';
		if(isset($nbssp) && $nbssp != 0)
		{
			$lib .= ($nbssp > 1) ? ' '.$nbssp.' sous espèces' : ' '.$nbssp.' sous espèce';
		}
		if(isset($nbcom) && $nbcom != 0) 
		{
			$lib .= ($nbcom > 1) ? ' '.$nbcom.' complexes d\'espèces' : ' '.$nbcom.' complexe d\'espèce';
		}
		
		
		$l .= '<div class="mb-3"><button type="button" id="voir" class="btn btn-outline-secondary btn-sm">Tout afficher</button> <button type="button" id="pasvoir" class="btn btn-outline-secondary btn-sm">Tout cacher</button></div>';
		
		$famille = recherche_famillecat($observa,$cat,$ordre);
					
		if($nbsp > 0)
		{
			$tabf = array_flip($tabf);
			$nbfam = null;
			foreach($famille as $f)
			{
				if(isset($tabf[$f['cdnom']]))
				{
					foreach($taxon as $n)
					{
						if($n['famille'] == $f['cdnom'])
						{						
							$nbfam++;
							if($n['rang'] == 'COM') { $nbfam--; }
						}
					}
					$tabfam[] = array('famille'=>$f['famille'],'nbfam'=>$nbfam,'cdnom'=>$f['cdnom'],'nomvern'=>$f['nomvern']);
					$nbfam = null;
				}
			}
			foreach($tabfam as $f)
			{
				if(isset($tabf[$f['cdnom']]))
				{
					$l .= '<div class="listefamille">';
					$l .= '<div>';
					$l .= '<h3 class="h5">';
					$l .= '<button id="'.$f['famille'].'" class="btn btn-sm color1_bg idfam" type="button"><span class="fa fa-plus blanc"></span></button>';
					$l .= ' <a href="index.php?module=famille&amp;action=famille&amp;d='.$observa.'&amp;id='.$f['cdnom'].'">'.$f['famille'];
					if($f['nomvern'] != '') { $l .= ' - '.$f['nomvern']; }
					$l .= ' ('.$f['nbfam'].')</a></h3>';					
					$l .= '</div>';
					$l .= '<ul id="f'.$f['famille'].'" class="collapse min">';
					foreach($taxon as $t)
					{
						if($t['famille'] == $f['cdnom'])
						{
							$nbobservation = ($t['nb'] > 1) ? $t['nb'].' observations' : $t['nb'].' observation';
							if($t['rang'] == 'COM')
							{
								$l .= '<li>Complexe : <span class="font-weight-bold"><a href="index.php?module=fiche&amp;action=fichec&amp;d='.$observa.'&amp;id='.$t['cdnom'].'"><i>'.$t['nom'].'</i></a></span> - '.$nbobservation.' - <a href="index.php?module=observation&amp;action=observation&amp;d='.$observa.'&amp;id='.$t['cdnom'].'"><i class="fa fa-eye" title="Voir les observations"></i></a></li>';
							}
							else
							{
								if($latin == 'nom')
								{
									$l .= '<li><span class="font-weight-bold"><a href="index.php?module=fiche&amp;action=fiche&amp;d='.$observa.'&amp;id='.$t['cdnom'].'"><i>'.$t['nom'].'</i></a></span>&nbsp;'.$t['auteur'].'&nbsp;'.$t['nomvern'].' - '.$nbobservation.' - <a href="index.php?module=observation&amp;action=observation&amp;d='.$observa.'&amp;id='.$t['cdnom'].'"><i class="fa fa-eye" title="Voir les observations"></i></a></li>';
								}
								else
								{
									if($t['nomvern'] != '')
									{
										$l .= '<li><span class="font-weight-bold"><a href="index.php?module=fiche&amp;action=fiche&amp;d='.$observa.'&amp;id='.$t['cdnom'].'">'.$t['nomvern'].'</a></span>&nbsp;<i>'.$t['nom'].'&nbsp;'.$t['auteur'].'</i> - '.$nbobservation.' - <a href="index.php?module=observation&amp;action=observation&amp;d='.$observa.'&amp;id='.$t['cdnom'].'"><i class="fa fa-eye" title="Voir les observations"></i></a></li>';
										
									}
									else
									{
										$l .= '<li><span class="font-weight-bold"><a href="index.php?module=fiche&amp;action=fiche&amp;d='.$observa.'&amp;id='.$t['cdnom'].'"><i>'.$t['nom'].'</i></a></span>&nbsp;'.$t['auteur'].'&nbsp;'.$t['nomvern'].' - '.$nbobservation.' - <a href="index.php?module=observation&amp;action=observation&amp;d='.$observa.'&amp;id='.$t['cdnom'].'"><i class="fa fa-eye" title="Voir les observations"></i></a></li>';
										
									}														
								}
							}
						}
					}
					$l .= '</ul>';
					$l .= '</div>';
				}
			}
		}		
	}
	else
	{
		$l = '<h2 class="h4">Liste des '.$titrecat.' <small>('.$ordret.')</small></h2>';
		$l .= '<p>Aucun taxon pour cet observatoire.</p>';
	}
	
	$retour['l'] = $l;
	$retour['statut'] = 'Oui';
}
else
{
	$retour['statut'] = 'Non';
}
echo json_encode($retour);