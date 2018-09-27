<?php 
$titre = 'Gestion du site';
$script = '<script type="text/javascript" src="../dist/js/jquery.js" defer></script>
<script src="../dist/js/bootstrap.min.js" defer></script>';
$css = '';

if(isset($rjson_site['config']))
{
	include CHEMIN_MODELE.'sup.php';
	$sch = schema();
	if ($sch > 0)
	{
		sup();
	}
	$dossier = '../instal';
	if(is_dir($dossier)) 
	{
		$repertoire = opendir($dossier);
		while (false !== ($fichier = readdir($repertoire))) 
		{
			$chemin = $dossier."/".$fichier;
 			@unlink($chemin);			
		}
		closedir($repertoire);
		@rmdir($dossier);
	}	
	if(file_exists('../indexor.php')) 
	{
		rename('../indexor.php', '../index.php');
	}
}
include CHEMIN_MODELE.'accueil.php';

$idmembre = $_SESSION['idmembre'];

if(isset($rjson_site['observatoire']))
{
	foreach($rjson_site['observatoire'] as $n)
	{
		$discipline[] = ['nom'=>$n['nom'],'icon'=>$n['icon'],'var'=>$n['nomvar']];
		$theme[] = $n['nomvar'];
	}
	$vali = cherche_vali($idmembre);
	if($vali !== false)
	{
		$vali1 = explode(", ", $vali['discipline']);
		foreach($vali1 as $n)
		{
			$vali2 = nbvali($n);
			$vali7 = nbvali7($n);
			$det = nbdet($n);
			$detvali = nbdetvali($n);
			if(in_array($n, $theme))
			{
				foreach($discipline as $d)
				{
					if($d['var'] == $n)
					{
						$nbdetvali = $det - $detvali;
						$tabvali[] = ['obser'=>$d['nom'],'nb'=>$vali2,'icon'=>$d['icon'],'nomvar'=>$d['var']];
						$tabdet[] = ['obser'=>$d['nom'],'nb'=>$det,'nbv'=>$detvali,'nbn'=>$nbdetvali,'icon'=>$d['icon'],'nomvar'=>$d['var']];
						if($vali7 > 0)
						{
							$tabvali7[] = ['obser'=>$d['nom'],'nb'=>$vali7,'icon'=>$d['icon'],'nomvar'=>$d['var']];
						}					
					}
				}			
			}		
		}	
	}
}
include 'modules/accueil/vues/accueil.php';