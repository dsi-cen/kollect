<?php
if(isset($_SESSION['prenom']))
{
	$scripthaut = '<script src="dist/js/jquery.js"></script>';
	$script = '<script src="dist/js/bootstrap.min.js" defer></script>
	<script type="text/javascript" src="dist/js/jquery.dataTables.min.js" defer></script>
	<script type="text/javascript" src="dist/js/datatables/dataTables.buttons.min.js" defer></script>
	<script type="text/javascript" src="dist/js/datatables/jszip.min.js" defer></script>
	<script type="text/javascript" src="dist/js/datatables/buttons.html5.min.js" defer></script>';
	$css = '<link rel="stylesheet" type="text/css" href="dist/css/dataTables.bootstrap4.css">
	<link rel="stylesheet" type="text/css" href="dist/css/buttons.bootstrap4.min.css">';
	$titre = 'Liste espèces';
	$description = 'Votre liste d\'espèces';
	
	include CHEMIN_MODELE.'membre.php';
	
	$cherchereobseridm = rechercheobservateurid($_SESSION['idmembre']);
	
	if($cherchereobseridm['idobser'] != '')
	{
		$liste = liste_espece($cherchereobseridm['idobser']);
		$nbliste = count($liste);
		if($liste != false)
		{
			foreach($menuobservatoire as $n)
			{
				$nomobser[$n['var']] = $n['nom'];
			}
			foreach($liste as $n)
			{
				$pourcent = round($n['nb']/$n['nbt'] * 100,1);
				$observa = (isset($nomobser[$n['observa']])) ? $nomobser[$n['observa']] : '';
				$tab[] = ['observa'=>$observa, 'cdref'=>$n['cdref'], 'nom'=>$n['nom'], 'nomvar'=>$n['observa'], 'nomvern'=>$n['nomvern'], 'ir'=>$n['ir'], 'nb'=>$n['nb'], 'pourcent'=>$pourcent, 'date'=>$n['max']];
			}
		}
	}
	include CHEMIN_VUE.'espece.php';		
}
else
{
	header('location:index.php');
}