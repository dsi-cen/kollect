<?php
$titre = 'Actualités';
$description = 'Les dernières actualités du site '.$rjson_site['titre'];
$script = '<script src="dist/js/jquery.js" defer></script>
<script src="dist/js/bootstrap.min.js" defer></script>';
$css = '';
 
include CHEMIN_MODELE.'actu.php';

if(isset($_GET['theme']))
{
	$idtheme = htmlspecialchars($_GET['theme']);
	$listeactu = listeactutheme($idtheme);
	$listetag = touslestagtheme($idtheme);
	foreach ($rjson_site['observatoire'] as $n)
	{
		if ($idtheme == $n['nomvar'])
		{
			$titre = 'Actualités : '.$n['nom'];
			$description = 'Les dernières actualités concernant les '.$n['nom'];
			$actutheme = $n['nom'];
		}
	}
	$listecompte = listecomptetheme($idtheme);
	$nbarticle = nbarticletheme($idtheme);	
} 
else
{
	$listeactu = listeactu();
	$listetag = touslestag();
	$listecompte = listecompte();
	$nbarticle = nbarticle();	
}

if(count($listeactu) > 0)
{	
	foreach ($listeactu as $n)
	{
		if (!empty ($n['tag']))
		{
			$tag2 = rtrim($n['tag'], ", ");
			$tag1 = explode(", ", $tag2);
			$tag = null;
			foreach ($tag1 as $a)
			{
				$tag[] = $a;
			}
		}
		else
		{$tag = '';}
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
		$actu[] = array('tag'=>$tag, 'idactu'=>$n['idactu'], 'titre'=>$n['titre'], 'stitre'=>$n['soustitre'], 'datefr'=>$n['datefr'], 'icon'=>$iconactu, 'photo'=>$n['nom']);
	}
	//les tags
	if(count($listetag) > 0)
	{
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
		$min = 1;
		$max = 1;
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
	}
}
include CHEMIN_VUE.'actu.php';
