<?php 
$script = '<script src="dist/js/jquery.js" defer></script>
<script src="dist/js/bootstrap.min.js" defer></script>
<script src="dist/js/jquery-auto.js" defer></script>
<script src="dist/js/recherche.js" defer></script>';
$css = '';

$json_emprise = file_get_contents('emprise/emprise.json');
$rjson_emprise = json_decode($json_emprise, true);
$dep = ($rjson_emprise['emprise'] == 'fr' || $rjson_emprise['contour2'] == 'oui') ? 'oui' : 'non';

if(isset($_GET['recherche'])) 
{
	$recherche = htmlspecialchars($_GET['recherche']);
	if($recherche == '')
	{
		$titre = 'Recherche sur le site';
		$description = 'Recherche sur le site';		
		include CHEMIN_VUE.'recherche.php';
	}
	else
	{
		include CHEMIN_MODELE.'recherche.php';
		
		$titre = 'Resultat de la recherche';
		$description = 'Resultat de la recherche';
		
		$result = rechercher_espece($recherche);
		if($result[0] >= 0)
		{
			$librech = ($result[0] > 1) ? $result[0].' occurences pour <b>'.$recherche.'</b>' : $result[0].' occurence pour <b>'.$recherche.'</b>' ;
		}
		if($result[0] >= 1)
		{
			foreach($result[1] as $n)
			{
				if($n['rang'] == 'COM')
				{
					$taxon = '<a href="observatoire/index.php?module=fiche&amp;action=fichec&amp;d='.$n['observatoire'].'&amp;id='.$n['cdnom'].'"><i>'.$n['nom'].'</i></a>';
				}			
				elseif($n['rang'] == 'GN')
				{
					$taxon = '<a href="observatoire/index.php?module=fiche&amp;action=ficheg&amp;d='.$n['observatoire'].'&amp;id='.$n['cdnom'].'">Genre <i>'.$n['nom'].'</i></a>';
				}
				else
				{
					$taxon = (!empty($n['nomvern'])) ? '<a href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d='.$n['observatoire'].'&amp;id='.$n['cdnom'].'"><i>'.$n['nom'].'</i> - '.$n['nomvern'].'</a>' : '<a href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d='.$n['observatoire'].'&amp;id='.$n['cdnom'].'"><i>'.$n['nom'].'</i></a>';			
				}
				$tabr[] = $taxon;
			}			
		}
	
		include CHEMIN_VUE.'resultat.php';
	}	
}
else
{
	$titre = 'Recherche sur le site';
	$description = 'Recherche sur le site';	
	include CHEMIN_VUE.'recherche.php';
}
