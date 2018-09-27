<?php
$script = '<script type="text/javascript" src="../dist/js/jquery.js" defer></script>
<script src="../dist/js/bootstrap.min.js" defer></script>
<script src="../dist/js/jquery-auto.js" defer></script>
<script src="dist/js/taxon.js" defer></script>';
$css = '';

if($_SESSION['droits'] >= 3)
{
	if(isset($_GET['id'])) 
	{
		include CHEMIN_MODELE.'fiche.php';
		$id = htmlspecialchars($_GET['id']);
		$nomvar = htmlspecialchars($_GET['d']);
	
		$json_obser = file_get_contents('../json/'.$nomvar.'.json');
		$rjson_obser = json_decode($json_obser, true);
		
		$sytema = (isset($rjson_obser['systematique'])) ? 'oui' : 'non';
		$taxon = recherche_fiche($id,$nomvar,$sytema);
		$nom = $taxon['nom'];
		$nomfr = $taxon['nomvern'];
		$inventeur = $taxon['auteur'];
		$famille = $taxon['famille'];
		$rang = ($taxon['rang'] == 'ES') ? 'Espèce' : 'Sous-espèce';
		$locale = $taxon['locale'];
		
		$titre = 'Gestion '.$nom;
		$description = 'Gestion '.$nom.' sur le site';
		
		$listerang = rechercher_rang($nomvar);
		foreach ($listerang as $n)
		{
			if($n['idrang'] == 3) { $rsousgenre = 'oui'; }
			if($n['idrang'] == 7) { $rsousfamille = 'oui'; } 
			if($n['idrang'] == 5) { $rstribu = 'oui'; }
			if($n['idrang'] == 6) { $rtribu = 'oui'; }
		}	
		$idr = ($taxon['rang'] == 'ES') ? $id : recherche_sup($id,$nomvar);
		if(isset($rsousgenre) && isset($rtribu) && isset($rstribu))
		{
			$taxo = recherche_taxo1($idr,$nomvar);
		}
		elseif(isset($rsousgenre) && isset($rtribu))
		{
			$taxo = recherche_taxo2($idr,$nomvar);
		}
		elseif(!isset($rsousgenre) && !isset($rstribu) && isset($rtribu))
		{
			$taxo = recherche_taxo3($idr,$nomvar);
		}
		elseif(!isset($rsousgenre) && !isset($rstribu) && !isset($rtribu) && !isset($rsousfamille))
		{
			//$taxo = recherche_taxo4($idr,$nomvar);
		}
		
		include CHEMIN_VUE.'fiche.php';
	}
}