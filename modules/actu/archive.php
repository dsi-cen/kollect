<?php 
$script = '<script src="dist/js/jquery.js" defer></script>
<script src="dist/js/bootstrap.min.js" defer></script>';
$css = '';

include CHEMIN_MODELE.'actu.php';

if(isset($_GET['theme']))
{
	$idtheme = htmlspecialchars($_GET['theme']);
	$nbarticle = nbarticletheme($idtheme);
}
else
{
	$nbarticle = nbarticle();
}

$formatter = new IntlDateFormatter('fr_FR',IntlDateFormatter::LONG,IntlDateFormatter::NONE,'Europe/Paris',IntlDateFormatter::GREGORIAN );

if(isset($_GET['min']))
{
	$min = htmlspecialchars($_GET['min']);
	if ($min < $nbarticle)
	{
		$article = (isset($idtheme)) ? articletheme($min,$idtheme) : article($min);
		$max = count($article)+$min;
		$min2 = $min - 10;
		$maxmin2 = maxmin($min2);
		$datemax2 = new DateTime($maxmin2['max']);
		$datemax2 = $formatter->format($datemax2);
		$datemin2 = new DateTime($maxmin2['min']);
		$datemin2 = $formatter->format($datemin2);
		if ($min2 >= 4)
		{
			$titrep2 = (isset($idtheme)) ? '<a href="index.php?module=actu&amp;action=archive&amp;min='.$min2.'&amp;theme='.$idtheme.'">Actualités du '.$datemin2.' au '.$datemax2.'</a>' : '<a href="index.php?module=actu&amp;action=archive&amp;min='.$min2.'">Actualités du '.$datemin2.' au '.$datemax2.'</a>';
		}		
	}
}
else
{
	$min = 4;
	$article = (isset($idtheme)) ? articletheme($min,$idtheme) : article($min);
	$max = 14;
}

if(isset($idtheme))
{
	$maxmin = maxmintheme($min,$idtheme);
	foreach ($rjson_site['observatoire'] as $n)
	{
		if ($idtheme == $n['nomvar'])
		{
			$titrep = 'Actualités : '.$n['nom'];
			$actutheme = $n['nom'];
		}
	}			
}
else
{
	$maxmin = maxmin($min);
	$titrep = 'Actualités';
}

$datemax = new DateTime($maxmin['max']);
$datemax = $formatter->format($datemax);
$datemin = new DateTime($maxmin['min']);
$datemin = $formatter->format($datemin);
$titre = 'Actualités du '.$datemin.' au '.$datemax;
$description = 'Les actualités du site '.$rjson_site['titre'].' du '.$datemin.' au '.$datemax;

if ($nbarticle > 10)
{
	$nbarticle1 = $nbarticle - $max;
	if ($nbarticle1 >= 10)
	{
		$nbarticle2 = (isset($idtheme)) ? '<a href="index.php?module=actu&amp;action=archive&amp;min='.$max.'&amp;theme='.$idtheme.'">Voir les dix actualités précédendes</a>' : '<a href="index.php?module=actu&amp;action=archive&amp;min='.$max.'">Voir les dix actualités précédendes</a>';
	}
	elseif ($nbarticle1 > 1 && $nbarticle1 < 10)
	{
		$nbarticle2 = (isset($idtheme)) ? '<a href="index.php?module=actu&amp;action=archive&amp;min='.$max.'&amp;theme='.$idtheme.'">Voir les '.$nbarticle1.' actualités précédendes</a>' : '<a href="index.php?module=actu&amp;action=archive&amp;min='.$max.'">Voir les '.$nbarticle1.' actualités précédendes</a>';
	}
	elseif ($nbarticle1 == 1)
	{
		$nbarticle2 = (isset($idtheme)) ? '<a href="index.php?module=actu&amp;action=archive&amp;min='.$max.'&amp;theme='.$idtheme.'">Voir l\'actualité précédende</a>' : '<a href="index.php?module=actu&amp;action=archive&amp;min='.$max.'">Voir l\'actualité précédende</a>';
	}	
}
foreach ($article as $n)
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

include CHEMIN_VUE.'archive.php';
