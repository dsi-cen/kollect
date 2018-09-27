<?php
$titre = 'Import';
$description = 'Import de données';
$script = '<script type="text/javascript" src="../dist/js/jquery.js" defer></script>
<script src="../dist/js/bootstrap.min.js" defer></script>
<script type="text/javascript" src="dist/js/import.js" defer></script>';
$css = '';

function return_bytes($FormattedSize)
{
   $FormattedSize = trim($FormattedSize);
   $Size = floatval($FormattedSize);
   $MultipSize = strtoupper(substr($FormattedSize, -1));
 
   if($MultipSize == "G") $Size *= pow(1024, 3);
   else if($MultipSize == "M") $Size *= pow(1024, 2);
   else if($MultipSize == "K") $Size *= 1024;
 
   return $Size;
}
if ($_SESSION['droits'] >= 3)
{
	$id = $_SESSION['idmembre'];
	if ($_SESSION['droits'] == 3)
	{
		$gestionobs = gestion($id);
		$disc = explode(", ", $gestionobs['gestionobs']);
		$nbdisc = count($disc);
		if ($nbdisc >= 1)
		{
			foreach ($rjson_site['observatoire'] as $n)
			{
				foreach ($disc as $n1)
				{
					if ($n['nomvar'] == $n1)
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
		if (isset ($rjson_site['observatoire']))
		{
			foreach ($rjson_site['observatoire'] as $n)
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
	$maxup = ini_get('upload_max_filesize');
	$maxupbyte = return_bytes($maxup);
	include CHEMIN_VUE.'import2.php';	
}