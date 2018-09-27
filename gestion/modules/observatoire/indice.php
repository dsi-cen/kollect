<?php
$titre = 'Configuration indice';
$description = 'Configuration des indices de rareté des observatoires';
$script = '<script type="text/javascript" src="../dist/js/jquery.js" defer></script>
<script src="../dist/js/bootstrap.min.js" defer></script>
<script src="dist/js/indice.js" defer></script>';
$css = '';
if ($_SESSION['droits'] >= 3)
{
	include CHEMIN_MODELE.'observatoire.php';
	if ($_SESSION['droits'] == 3)
	{
		$id = $_SESSION['idmembre'];
		$gestionobs = gestion($id);
		$disc = explode(", ", $gestionobs['gestionobs']);
		$nbdisc = count($disc);
		if($nbdisc >= 1)
		{
			foreach($rjson_site['observatoire'] as $n)
			{
				foreach($disc as $n1)
				{
					if($n['nomvar'] == $n1)
					{
						$menuobservatoire[] = array("nom"=>$n['nom'],"nomvar"=>$n['nomvar']);
					}
				}
			}
			$nbobservatoire = count($menuobservatoire);
			$libnbobser = ($nbobservatoire >1) ? 'observatoires' : 'observatoire';
		}
		else
		{
			$nbobservatoire = 0;
		}
	}
	else
	{
		if(isset($rjson_site['observatoire']))
		{
			foreach($rjson_site['observatoire'] as $n)
			{
				$menuobservatoire[] = array("nom"=>$n['nom'],"nomvar"=>$n['nomvar']);	
			}
			$nbobservatoire = count($menuobservatoire);
			$libnbobser = ($nbobservatoire >1) ? 'observatoires' : 'observatoire';
		}
		else
		{
			$nbobservatoire = 0;
		}	
	}
	$json_emprise = file_get_contents('../emprise/emprise.json');
	$emprise = json_decode($json_emprise, true);
	
	$l935 = (isset($emprise['nbmaille5'])) ? $emprise['nbmaille5'] : '';
	//$m = ceil($emprise['nbmaille'] * 0.3);
	 	
	include CHEMIN_VUE.'indice.php';	
}
