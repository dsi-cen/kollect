<?php 
$script = '<script src="js/jquery.js" defer></script>
<script src="js/bootstrap.min.js" defer></script>
<script src="js/webobs.js" defer></script>';
$css = '';

if(isset($_GET['idobser'])) 
{
	include CHEMIN_MODELE.'infoobser.php';
	$idobser = htmlspecialchars($_GET['idobser']);
	$observateur = cherche_observateur($idobser);
	
	$titre = $observateur['prenom'].' '.$observateur['nom'];
	$description = 'Fiche de '.$titre.' du site '.$rjson_site['titre'];
	
	//avatar
	$cheminavatar = 'photo/avatar/'.$observateur['prenom'].''.$observateur['idm'].'.jpg';
	$favatar = (file_exists($cheminavatar)) ? '<img src="photo/avatar/'.$observateur['prenom'].''.$observateur['idm'].'.jpg" width=36 height=36 alt="" class="img-circle"/>' : '<img src="photo/avatar/usera.jpg" width=36 height=36 alt="" class="img-circle"/>';
	
	//nb observations
	$nbobs = nbobs($idobser);
	$nbobs1 = 0;
	$nbsp = 0;
	foreach($rjson_site['observatoire'] as $n)
	{
		foreach($nbobs as $a)
		{
			if($a['observa'] == $n['nomvar'])
			{
				$tab[] = array('nom'=>$n['nom'],'icon'=>$n['icon'],'nb'=>$a['nb'],'nbsp'=>$a['nbsp']);
				$nbobs1 = $nbobs1 + $a['nb'];
				$nbsp = $nbsp + $a['nbsp'];
			}
		}
	}
	
	include CHEMIN_VUE.'info.php';
}
?>