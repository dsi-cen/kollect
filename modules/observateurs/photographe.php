<?php 
$titre = 'Liste des contributeurs';
$description = 'Liste des observateurs et photographes du site '.$rjson_site['titre'].'';
$script = '<script src="dist/js/jquery.js" defer></script>
<script src="dist/js/bootstrap.min.js" defer></script>';
$css = '';
include CHEMIN_MODELE.'observateurs.php';

if(isset($_GET['idobser'])) 
{
	$idobser = htmlspecialchars($_GET['idobser']);
	$observateur = cherche_observateur($idobser);
	$titre = 'Photos de '.$observateur['prenom'].' '.$observateur['nom'];
	$description = 'Photos de '.$observateur['prenom'].' '.$observateur['nom'].' du site '.$rjson_site['titre'].' par observatoire';
	
	$observa = observa_photographe($idobser);
	
	if($observa != false)
	{
		foreach($observa as $n)
		{
			$tabnb[$n['observatoire']] = $n['nb'];
			$tabobserva[$n['observatoire']] = $n['observatoire'];
		}
		foreach($rjson_site['observatoire'] as $n)
		{
			if(isset($tabobserva[$n['nomvar']]))
			{
				$nbphoto = $tabnb[$n['nomvar']];
				$libphoto = ($nbphoto > 1) ? 'photos' : 'photo';
				$tab[] = ['observa'=>$n['nom'],'nb'=>$nbphoto,'icon'=>$n['icon'],'var'=>$n['nomvar'],'couleur'=>$n['couleur'],'lib'=>$libphoto];
			}		
		}
	}
	
	include CHEMIN_VUE.'photographe.php';
}
?>