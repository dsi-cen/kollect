<?php
$scripthaut = '<script src="dist/js/jquery.js"></script>';
$script = '<script src="dist/js/bootstrap.min.js" defer></script>
<script src="dist/js/highcharts.js" defer></script>
<script src="dist/js/modules/exportingoff.js" defer></script>';
$css = '';
$titre = 'Grpah par décade';
$description = 'Graphique par décade des espèces '.$rjson_site['ad2'].' '.$rjson_site['lieu'];

include CHEMIN_MODELE.'decade.php';

if(isset($_GET['decade']))
{
	$decade = htmlspecialchars($_GET['decade']);
}	
	
$liste = graph();
for($i=1;$i<=36;$i++) 
{
	$iddecade[] = $i;
}
foreach($liste as $n)	
{
	$tabobserva[] = $n['observa'];
	$tabobservaobs[$n['observa']][] = $n['iddecade'];
}
foreach($rjson_site['observatoire'] as $o)
{
	$observa = $o['nomvar'];
	$nomaff = $o['nom'];
	$color = $o['couleur'];
	$data[$observa] = array('name' => $nomaff, 'color' => $color, 'data' => array());		
	if(in_array($o['nomvar'], $tabobserva))
	{
		foreach($iddecade as $a)
		{
			if(in_array($a, $tabobservaobs[$o['nomvar']]))
			{
				foreach($liste as $e)	
				{
					if($a == $e['iddecade'] && $e['observa'] == $observa)	
					{
						$data[$observa]['data'][] = $e['nb'];
					}						
				}
			}
			else
			{
				$data[$observa]['data'][] = 0;
			}
		}						
	}		
}	
$data = array_values($data);

$data = json_encode($data, JSON_NUMERIC_CHECK);

include CHEMIN_VUE.'graph.php';