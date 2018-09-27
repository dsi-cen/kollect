<?php
if (isset($_SESSION['prenom']))
{
	$titre = 'Vos notifications';
	$description = 'Notifications de '.$_SESSION['prenom'].' '.$_SESSION['nom'].'.';
	$script = '<script src="dist/js/jquery.js" defer></script>
	<script src="dist/js/bootstrap.min.js" defer></script>';
	$css = '';
	
	if($nbnotif > 1)
	{
		$titreh2 = 'Vous avez '.$nbnotif. ' notifications';
	}
	elseif($nbnotif == 1)
	{
		$titreh2 = 'Vous avez '.$nbnotif. ' notification';
	}
	elseif($nbnotif == 0)
	{
		$titreh2 = 'Aucune notification actuellement';
	}
	if($nbnotif > 0)
	{
		include CHEMIN_MODELE.'membre.php';
		
		$idm = $_SESSION['idmembre'];
		$notif = cherche_notif($idm);
		
		foreach($notif as $n)
		{
			if($n['type'] == 'comobs')
			{
				$tabcomobs[] = ['nb'=>$n['nb'],'idobs'=>$n['idtype']];	
			}
			elseif($n['type'] == 'det')
			{
				$tabdet[] = ['nb'=>$n['nb'],'idpdet'=>$n['idtype']];				
			}
			elseif($n['type'] == 'vali')
			{
				$tabvali[] = ['nb'=>$n['nb'],'idobs'=>$n['idtype']];				
			}
		}		
	}
	include CHEMIN_VUE.'notif.php';
}
else
{
	header('location:index.php');
}