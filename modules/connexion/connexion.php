<?php
$titre = 'Connexion';
$description = 'Connexion au site';
$script = '';
$css = '';
$pasdemenu = 'oui';
$pasdebdp = 'oui';
if(isset($_POST['mail']) AND isset ($_POST['mdp']))
{
	$mail = htmlspecialchars($_POST['mail']);
	$motpasse = htmlspecialchars($_POST['mdp']);
		
	if($mail != NULL AND $motpasse != NULL)
	{
		include CHEMIN_MODELE.'inscription.php';
		$hashed_passwd = get_db_password($mail);

		if (!password_verify($motpasse, $hashed_passwd[0])) {
			$ok = 'non';
			$message = '<div class="alert alert-danger mt-1" role="alert">Mauvais identifiant ou mot de passe !</div>';
			include CHEMIN_VUE.'connexion.php';
		}
		else
		{
			$connexion = connexion($mail);
			if($connexion['actif'] == 1)
			{		
				$_SESSION['prenom'] = $connexion['prenom'];
				$_SESSION['nom'] = $connexion['nom'];
				$_SESSION['droits'] = $connexion['droits'];
				$_SESSION['idmembre'] = $connexion['idmembre'];
				$_SESSION['latin'] = $connexion['latin'];
				$_SESSION['obser'] = $connexion['obser'];
				$_SESSION['flou'] = $connexion['floutage'];
				$_SESSION['couche'] = $connexion['couche'];
				$_SESSION['typedon'] = $connexion['typedon'];
				$_SESSION['idorg'] = $connexion['org'];
				
				if(!empty($_POST['case'])) 
				{
					setcookie('idp',  $_POST['prenom'], time() + 90*24*3600, null, null, false, true);
					setcookie('idn',  $connexion['nom'], time() + 90*24*3600, null, null, false, true);
					setcookie('idd',  $connexion['droits'], time() + 90*24*3600, null, null, false, true);
					setcookie('idm',  $connexion['idmembre'], time() + 90*24*3600, null, null, false, true);
					header('location:'.$_SESSION['url'].'');
				}
				else
				{
					header('location:'.$_SESSION['url'].'');
				}
			}
			else
			{
				$ok = 'non';
				$message = '<div class="alert alert-danger" role="alert"><p>Ce compte n\'est pas activé !</p></div>';
				include CHEMIN_VUE.'connexion.php';
			}
		}
	}
	else
	{
		$ok = 'non';
		$message = '<div class="alert alert-danger" role="alert">Vous devez remplir les champs Mail et Mot de passe.</div>';
		include CHEMIN_VUE.'connexion.php';
	}	
}
else
{
	if(isset($_GET['s'])) {
        switch ($_GET['s']) {
            case "o":
                $_SESSION['url'] = 'index.php?module=saisie&action=saisie';
                break;
            case "a":
                $_SESSION['url'] = 'index.php';
                break;
            case "c":
                $_SESSION['url'] = 'index.php?module=consultation&action=consultation';
                break;
            case "l":
                $_SESSION['url'] = 'index.php?module=stations&action=liste';
                break;
            case "s":
                $_SESSION['url'] = 'index.php?module=stations&action=saisie';
                break;
        }
    }
	else
	{
		$_SESSION['url'] = $_SERVER['HTTP_REFERER'];
	}	
	$ok = 'non';
	$message = '';
	include CHEMIN_VUE.'connexion.php';
}
?>	