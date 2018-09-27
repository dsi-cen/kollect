<?php
$titre = 'Information et aide';
$description = 'Toutes les informations, aides, tutoriel sur le site '.$rjson_site['titre']; 
$script = '<script src="dist/js/jquery.js" defer></script>
<script src="dist/js/bootstrap.min.js" defer></script>';
$css = '';

include CHEMIN_MODELE.'info.php';

$tuto = tuto();

if(count($tuto) > 0)
{
	foreach($tuto as $n)
	{
		$file = 'tuto/'.$n['nomdoc'];
		$pdf = ($n['format'] == 'pdf') ? '<i class="fa fa-file-pdf-o"></i>' : '';
		if(file_exists($file))
		{
			$taille = filesize($file);
			if($taille >= 1048576) 
			{
				$taille = round($taille/1048576 * 100)/100 . ' Mo'; 
			}
			elseif ($taille >= 1024) 
			{ 
				$taille = round($taille/1024) . ' Ko'; 
			}
		}
		else
		{
			$taille = null;
		}
		$tab[] = ['nomdoc'=>$n['nomdoc'],'descri'=>$n['descri'],'taille'=>$taille,'pdf'=>$pdf];
	}
}

include CHEMIN_VUE.'info.php';
