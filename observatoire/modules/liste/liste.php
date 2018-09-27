<?php
$scripthaut = '<script src="../dist/js/jquery.js"></script>';
$script = '<script src="../dist/js/bootstrap.min.js" defer></script>';
$css = '';

include CHEMIN_MODELE.'liste.php';
//vérifier table liste existe
$table = table($nomvar);
if($table > 0)	
{
	$trisys = (isset($rjson_obser['systematique'])) ? 'oui' : 'non' ;	
	$listeok = 'oui';
	
	$choixlatin = (isset($_SESSION['latin'])) ? $_SESSION['latin'] : '';
	if($rjson_obser['latin'] == 'oui' && $choixlatin == 'oui') { $latin = 'nom'; }
	elseif($rjson_obser['latin'] == 'oui' && ($choixlatin == 'defaut' || $choixlatin == '')) { $latin = 'nom'; }
	elseif($rjson_obser['latin'] == 'non' && $choixlatin == 'oui') { $latin = 'nom'; }
	elseif($rjson_obser['latin'] == 'non' || $choixlatin == 'non') { $latin = 'nomvern'; }
	elseif($rjson_obser['latin'] == 'oui' && $choixlatin == 'non') { $latin = 'nomvern'; }
	
	if(isset($_GET['ordre']))
	{
		$ordre = htmlspecialchars($_GET['ordre']);
		$ordret = ($ordre == 'A') ? 'alphabétique' : 'systématique';
		$titre = 'Liste des '.$nomd.' ('.$ordret.')';
		$titrep = 'Liste des '.$nomd.' '.$rjson_site['ad1'].' '.$rjson_site['lieu']; 
		$description = 'Liste '.$ordret.' des '.$nomd.' '.$rjson_site['ad1'].' '.$rjson_site['lieu'].'';		
	}
	else
	{
		$ordre = (isset($rjson_obser['systematique'])) ? 'S' : 'A' ;
		$ordret = ($ordre == 'A') ? 'alphabétique' : 'systématique';
		$titre = 'Liste des '.$nomd.' ('.$ordret.')';
		$titrep = 'Liste des '.$nomd.' '.$rjson_site['ad1'].' '.$rjson_site['lieu']; 
		$description = 'Liste '.$ordret.' des '.$nomd.' '.$rjson_site['ad1'].' '.$rjson_site['lieu'];
		if(isset($rjson_obser['categorie']) && !empty($rjson_obser['categorie']))
		{
			$cat = $rjson_obser['categorie'];
			$titre = 'Liste des '.$nomd;
			$description = 'Liste des '.$nomd.' '.$rjson_site['ad1'].' '.$rjson_site['lieu'];
			$script .= '<script src="../dist/js/liste.js" defer></script>';
					
			include CHEMIN_VUE.'choixliste.php';
		}		
	}
	if(!isset($cat))
	{
		$famille = recherche_famille($nomvar,$ordre);				
		$taxon = recherche_tax($nomvar,$latin,$ordre);
		
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
		}
		include CHEMIN_VUE.'liste.php';		
	}
}
else
{
	$titre = 'Liste des '.$nomd;
	$titrep = 'Liste des '.$nomd.' '.$rjson_site['ad1'].' '.$rjson_site['lieu'];
	$description = 'Liste des '.$nomd.' '.$rjson_site['ad1'].' '.$rjson_site['lieu'];
	$ordret = '';
	include CHEMIN_VUE.'liste.php';
}