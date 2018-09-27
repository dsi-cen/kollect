<?php
$titre = 'Choix validation';
$description = 'Configuration du choix de validation des espèces';
$script = '<script type="text/javascript" src="../dist/js/jquery.js" defer></script>
<script src="../dist/js/bootstrap.min.js" defer></script>
<script type="text/javascript" src="../dist/js/jquery.dataTables.min.js" defer></script>
<script type="text/javascript" src="../dist/js/datatables/dataTables.scroller.min.js" defer></script>
<script type="text/javascript" src="dist/js/critere.js" defer></script>';
$css = '<link rel="stylesheet" type="text/css" href="../dist/css/dataTables.bootstrap4.css">
<link rel="stylesheet" type="text/css" href="../dist/css/scroller.bootstrap4.min.css">';

if ($_SESSION['droits'] >= 2)
{
	if($_SESSION['droits'] == 4) 
	{
		if (isset ($rjson_site['observatoire']))
		{
			foreach ($rjson_site['observatoire'] as $n)
			{
				$menuobservatoire[] = array('nom'=>$n['nom'],'nomvar'=>$n['nomvar']);	
			}
		}		
	}
	else
	{
		include CHEMIN_MODELE.'validation.php';
		
		$id = $_SESSION['idmembre'];
		$validateur = validateur($id);
		$disc = explode(", ", $validateur['discipline']);
		foreach($rjson_site['observatoire'] as $n)
		{
			foreach($disc as $n1)
			{
				if($n['nomvar'] == $n1)
				{
					$menuobservatoire[] = ['nom'=>$n['nom'],'nomvar'=>$n['nomvar']];
				}
			}
		}
	}
	include CHEMIN_VUE.'critere.php';	
}

