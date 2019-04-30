<?php
$titre = 'Inscription';
$description = 'Inscription au site';
$scripthaut = '<script src="dist/js/jquery.js"></script>';
$script = '<script src="dist/js/bootstrap.min.js" defer></script>';
$css = '';
$pasdemenu = 'oui';
$pasdebdp = 'oui';

if(isset($_POST['nom']) AND isset($_POST['prenom']) AND isset($_POST['mail'])  AND isset($_POST['mdp']) AND isset($_POST['mdp1']))
{
	$nomm = htmlspecialchars($_POST['nom']);
	$nom = mb_strtoupper($nomm, 'UTF-8');
	$prenomm = htmlspecialchars($_POST['prenom']);
	$mail = htmlspecialchars($_POST['mail']);
	$mdp = htmlspecialchars($_POST['mdp']);
	$mdp1 = htmlspecialchars($_POST['mdp1']);
	
	if ($nom == NULL)
	{
		$ok = 'non';
		$message = '<div class="alert alert-danger" role="alert"><p>La saisie du Nom est obligatoire</p></div>';
	}
	if ($prenomm == NULL)
	{
		$ok = 'non';
		$message = '<div class="alert alert-danger" role="alert"><p>La saisie du Prénom est obligatoire</p></div>';
	}
	if ($mail == NULL)
	{
		$ok = 'non';
		$message = '<div class="alert alert-danger" role="alert"><p>La saisie du mail est obligatoire</p></div>';
	}
	if ($mdp == NULL)
	{
		$ok = 'non';
		$message = '<div class="alert alert-danger" role="alert"><p>La saisie du Mot de passe est obligatoire</p></div>';
	}
	if ($mdp != $mdp1)
	{
		$ok = 'non';
		$message = '<div class="alert alert-danger" role="alert"><p>Erreur lors de la saisie du mot de passe</p></div>';
	}
	if ($nom != NULL AND $prenomm != NULL AND $mdp == $mdp1 and $mail != null)
	{
		include CHEMIN_MODELE.'inscription.php';
		//$prenom = prenom($prenomm);
		$prenom = mb_convert_case($prenomm, MB_CASE_TITLE, "UTF-8");
		$nbresultats = rechercher_membre($nom, $prenom);
		if ($nbresultats == 0) {
            $mailcheck = check_mail($mail);
            if ($mailcheck == 0) {
                $pass_hache = password_hash($mdp, PASSWORD_BCRYPT);
                $insertion = inscription($nom, $prenom, $pass_hache, $mail);
                if ($insertion[0] = 'Oui') {
                    $datem = date("Y-m-d H:i:s");
                    $idmembre = $insertion[1];
                    $type = 'Inscription au site';
                    $modif = 'demande d\'activation envoyé';
                    modif($idmembre, $type, $modif, $datem);
                    $url = 'http://' . $_SERVER['HTTP_HOST'] . ':' . $_SERVER['REQUEST_URI'];
                    $urlval = str_replace('inscription', 'validation', $url);
                    $mail1 = $_SERVER['HTTP_HOST'];
                    $mailval = ($mail1{3} == '.') ? str_replace('www.', '', $mail1) : $mail1;
                    $id = $insertion[1];
                    $meshtml = '<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" /></head>
                    <body>Vous venez de vous inscrire sur ' . $rjson_site['titre'] . '.<br /> 
                    Pour confirmer votre inscription il suffit de cliquer sur le lien <a href="' . $urlval . '&p=' . urlencode($prenom) . '&id=' . $id . '">Valider mon inscription</a> ou le copier/coller dans votre navigateur.<br />				
                    Votre prénom pour vous connecter : ' . $prenom . '<br />
                    --------<br />
                    Ceci est un mail automatique, Merci de ne pas y répondre.</body></html>';
                    $mestxt = 'Vous venez de vous inscrire sur ' . $rjson_site['titre'] . '. 
                    Pour confirmer votre inscription il suffit de cliquer sur le lien ' . $urlval . '&p=' . urlencode($prenom) . '&id=' . $id . ' ou le copier/coller dans votre navigateur.				
                    --------
                    Ceci est un mail automatique, Merci de ne pas y répondre.';
                    $frontiere = '-----=' . md5(uniqid(mt_rand()));
                    //$headers ='From: "Webobs"<no-reply@webobs.fr>'."\n";
                    $headers = 'From: "' . $mailval . '"<no-reply@' . $mailval . '>' . "\n";
                    $headers .= 'MIME-Version: 1.0' . "\n";
                    $headers .= 'Content-Type: multipart/alternative; boundary="' . $frontiere . '"' . "\n";
                    $message = 'This is a multi-part message in MIME format.' . "\n\n";
                    $message .= '--' . $frontiere . "\n";
                    $message .= 'Content-Type: text/plain; charset=UTF-8' . "\n";
                    $message .= 'Content-Transfer-Encoding: 8bit' . "\n\n";
                    $message .= $mestxt . "\n\n";
                    $message .= '--' . $frontiere . "\n";
                    $message .= 'Content-Type: text/html; charset=UTF-8' . "\n";
                    $message .= 'Content-Transfer-Encoding: 8bit' . "\n\n";
                    $message .= $meshtml . "\n\n";
                    $message .= '--' . $frontiere . '--' . "\n";
                    mail($mail, 'Activer votre compte', $message, $headers);

                    $ok = 'oui';
                    $message = 'Votre inscription s\'est bien déroulée.<br />Un mail vous a été envoyé pour valider votre inscription.<br /><strong>N\'hésitez pas à vérifier dans vos SPAM si vous ne le voyez pas dans votre boite de réception.</strong>';
                }
                else
                {
                    $ok = 'non';
                    $message = '<div class="alert alert-danger" role="alert"><p>Une erreur est survenue</p></div>';
                }
            }
            else
            {
                $ok = 'non';
                $message = '<div class="alert alert-danger" role="alert"><p>L\'adresse de courriel: '.$mail.' est déjà inscrite sur Kollect.</p></div>';
            }
		}
		else
		{
			$ok = 'non';
			$message = '<div class="alert alert-danger" role="alert"><p>Il existe déjà un membre : '.$nom.' '.$prenom.'</p></div>';			
		}
	}
}
else
{
	$ok = 'non';
	$message = '';$nom = '';$prenomm = '';$mail = '';	
}
include CHEMIN_VUE.'inscription.php';
