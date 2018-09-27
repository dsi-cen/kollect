<?php
$scripthaut = '<script src="../dist/js/jquery.js"></script>';
$script = '<script src="../dist/js/bootstrap.min.js" defer></script>
<script src="../dist/js/highcharts.js" defer></script>
<script src="../dist/js/modules/exportingoff.js" defer></script>';
$css = '';
$titre = 'Graph par décade - '.$nomd;
$description = 'Graphique par décade des '.$nomd.' '.$rjson_site['ad2'].' '.$rjson_site['lieu'];

include CHEMIN_MODELE.'decade.php';

if(isset($rjson_obser['categorie']))
{
	$type = 'dec';
	$liste = graphcat($nomvar);
	for($i=1;$i<=36;$i++) 
	{
		$iddecade[] = $i;
	}
	foreach($liste as $n)	
	{
		$tabcat[] = $n['cat'];
		$tabcatobs[$n['cat']][] = $n['iddecade'];
	}
	foreach($rjson_obser['categorie'] as $o)
	{
		$cat = $o['id'];
		$nomaff = $o['cat'];
		$data[$cat] = array('name' => $nomaff, 'data' => array());			
		if(in_array($o['id'], $tabcat))
		{
			foreach($iddecade as $a)
			{
				if(in_array($a, $tabcatobs[$o['id']]))
				{
					foreach($liste as $e)	
					{
						if($a == $e['iddecade'] && $e['cat'] == $cat)	
						{
							$data[$cat]['data'][] = $e['nb'];
						}						
					}
				}
				else
				{
					$data[$cat]['data'][] = 0;
				}
			}						
		}			
	}	
	$data = array_values($data);
}
else
{
	$type = 'un';
	$liste = graph($nomvar);

	for($i=0;$i<36;$i++) 
	{
		$tabgraph[$i] = 0;
	}
	foreach($liste as $n)
	{
		$tabgraph[$n['iddecade']-1] = $n['nb'];	
	}
	$data[] = array('name' => 'Nombre', 'data' => $tabgraph);
}
$data = json_encode($data, JSON_NUMERIC_CHECK);

include CHEMIN_VUE.'graph.php';