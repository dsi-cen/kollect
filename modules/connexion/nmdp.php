<?php
$titre = 'Votre mot de passe';
$description = 'Validation de l\'inscription au site';
$script = '<script src="js/jquery.js" defer></script>
<script src="js/jquery-auto.js" defer></script>
<script src="js/webobs.js" defer></script>
<script src="js/bootstrap.min.js" defer></script>';
$css = '';
if (isset($_GET['id']))
{
	include CHEMIN_MODELE.'inscription.php';
	$id = $_GET['id'];	
	$verifticket = verif_ticket($id);
	if (!isset($_POST['mdp']) AND !isset($_POST['mdp1']))
	{
		if ($verifticket['mdpo'] == 1)
		{
			$ok = 'oui';
			$message = '';
		}
		else
		{
			$ok = 'non';
			$message = '<div class="alert alert-danger" role="alert"><p>Erreur ! Ce lien n\'est plus valide<br /><a href="index.php?module=connexion&amp;action=mdpoubli">Regnérer un lien</a></p></div>';
		}		
	}
	elseif (isset($_POST['mdp']) AND isset($_POST['mdp1']))
	{
		$mdp = htmlspecialchars($_POST['mdp']);
		$mdp1 = htmlspecialchars($_POST['mdp1']);
		if ($mdp != $mdp1)
		{
			$ok = 'oui';
			$message = '<div class="alert alert-danger" role="alert"><p>Erreur lors de la saisie du mot de passe</p></div>';
		}
		else
		{
			$pass_hache = password_hash($mdp, PASSWORD_BCRYPT);
			$idmembre = $verifticket['idmembre'];
			modif_mdp($idmembre,$pass_hache);
			$datem = date("Y-m-d H:i:s");
			$type = 'Nouveau mot de passe';
			$modif = 'Nouveau mot de passe validé';
			modif($idmembre,$type,$modif,$datem);
			$ok = 'oui';
			$message = '<div class="alert alert-success" role="alert"><p>Vous pouvez vous <a href="index.php?module=connexion&amp;action=connexion&amp;s=a">connecter</a> avec votre nouveau mot de passe.</p></div>';
		}				
	}	
	include CHEMIN_VUE.'nmdp.php';	
}
else
{
	$ok = 'non';
	$message = '<div class="alert alert-danger" role="alert"><p>Erreur ! Assurez vous que le lien est entier</p></div>';
	include CHEMIN_VUE.'nmdp.php';
}
?>
	
	
