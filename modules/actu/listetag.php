<?php
$script = '<script src="dist/js/jquery.js" defer></script>
<script src="dist/js/bootstrap.min.js" defer></script>
<script src="dist/js/webobs.min.js" defer></script>';
$css = ''; 
if(isset($_GET['choix'])) 
{
	include CHEMIN_MODELE.'actu.php';
	
	$tag = htmlspecialchars($_GET['choix']);
	$titre = 'ACTUALITES : '.$tag;
	$description = 'Les dernières actualités concernant les '.$tag;
	
	$liste = listetag($tag);
	foreach($liste as $n)
	{
		if ($n['theme'] != 'NR')
		{
			if (isset ($rjson_site['observatoire']))
			{
				if (in_array($n['theme'], $theme))
				{
					foreach ($rjson_site['observatoire'] as $a)
					{
						if ($n['theme'] == $a['nomvar'])
						{
							$iconactu = $a['icon'];
						}
					}					
				}
				else
				{
					$iconactu = 'NR';
				}
			}
			else
			{
				$iconactu = 'NR';
			}
		}
		else
		{
			$iconactu = 'NR';
		}
		$actu[] = array('idactu'=>$n['idactu'], 'titre'=>$n['titre'], 'stitre'=>$n['soustitre'], 'datefr'=>$n['datefr'], 'icon'=>$iconactu, 'photo'=>$n['nom']);
	}	
	//les tags
	$listetag = touslestag();
	foreach ($listetag as $n)
	{
		$tag3 = rtrim($n['tag'], ", ");
		$tag4 = explode(", ", $tag3);
		foreach ($tag4 as $a)
		{
			if($a != '')
			{
				$tag5[] = $a;
			}	
		}	
	}
	sort($tag5);
	foreach ($tag5 as $n)
	{
		if(isset($tag6[$n]))
		{
			$tag6[$n]['nb'] += 1;
		}
		else
		{
			$tag6[$n]['nb'] = 1;
			$tag6[$n]['nom'] = $n;
		}
	}
	$min = $max = 1;
	foreach ($tag6 as $n)
	{
		if ($n['nb'] < $min) $min = $n['nb'];
		if ($n['nb'] > $max) $max = $n['nb'];
		$tags[] = $n;
	}
	$min_size = 60;
	$max_size = 150;
	foreach ($tags as $n) 
	{
		if($min == $max)
		{
			$n['size'] = intval($min_size + (($n['nb'] - $min) * (($max_size - $min_size) / (1))));
		}
		else
		{
			$n['size'] = intval($min_size + (($n['nb'] - $min) * (($max_size - $min_size) / ($max - $min))));
		}			
		$tab_tag[] = $n;
	}
		
	include CHEMIN_VUE.'listetag.php';
}