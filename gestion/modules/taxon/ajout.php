<?php
$titre = 'Ajout espèce';
$description = 'Ajout especes et complexe sur le site';
$script = '<script type="text/javascript" src="../dist/js/jquery.js" defer></script>
<script src="../dist/js/bootstrap.min.js" defer></script>
<script src="../dist/js/jquery-auto.js" defer></script>
<script src="dist/js/ajoutsp.js" defer></script>';
$css = '';

if ($_SESSION['droits'] >= 3)
{
	include CHEMIN_MODELE.'observatoire.php';
	if($_SESSION['droits'] == 3)
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
		}
	}
	else
	{
		if(isset($rjson_site['observatoire']))
		{
			foreach($rjson_site['observatoire'] as $n)
			{
				$menuobservatoire[] = array('nom'=>$n['nom'],'nomvar'=>$n['nomvar']);	
			}
		}		
	}
	
	include CHEMIN_VUE.'ajout.php';	
}