<?php
if(isset($_GET['idactu'])) 
{
	$script = '<script src="dist/js/bootstrap.min.js" defer></script>
	<script src="dist/js/popup-image.js" defer></script>';
	$scripthaut = '<script src="dist/js/jquery.js"></script>';
	$css = '<link rel="stylesheet" href="dist/css/popup.css" type="text/css">';
	
	if(isset($_GET['ret'])) 
	{
		$retour = htmlspecialchars($_GET['ret']);
	}
	include CHEMIN_MODELE.'actu.php';
	
	$choix = htmlspecialchars($_GET['idactu']);
	$actu = actu($choix);
	
	$titre = $actu['titre'];
	$description = $actu['soustitre'];
	
	if(!empty ($actu['theme']))
	{
		foreach ($rjson_site['observatoire'] as $n)
		{
			if($actu['theme'] == $n['nomvar'])
			{
				$actutheme = $n['nom'];
			}			
		}		
	}
	$auteuractu = $actu['prenom'].' '.$actu['nomm'];	
		
	//compteur lecture
	$compte = ($actu['compte'] != '') ? $actu['compte'] + 1 : 1;
	modif_actu($choix,$compte);	
	
	if (!empty ($actu['tag']))
	{
		$tag2 = rtrim($actu['tag'], ", ");
		$tag1 = explode(", ", $tag2);
		foreach ($tag1 as $a)
		{
			$tag[] = $a;
		}
	}
	if (!empty ($actu['nomdoc']))
	{
		$file = 'docactu/'.$actu['nomdoc'];
		if (file_exists($file))
		{
			$tel = 'oui';
			$taille = filesize($file);
			if ($taille >= 1048576) 
			{
				$taille = round($taille/1048576 * 100)/100 . ' Mo'; 
			}
			elseif ($taille >= 1024) 
			{ 
				$taille = round($taille/1024) . ' Ko'; 
			}
		}
	}
	$url = 'http://'.$_SERVER['HTTP_HOST'].''.$_SERVER['REQUEST_URI'];
	
	include CHEMIN_VUE.'article.php';
}