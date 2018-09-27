<?php
$titre = 'Connexion 2';
$description = 'Connexion au site 2';
$script = '<script src="js/jquery.js" defer></script>
<script src="js/jquery-auto.js" defer></script>
<script src="js/webobs.js" defer></script>
<script src="js/bootstrap.min.js" defer></script>';
$css = '';
if (isset($_POST['mail']) and isset($_POST['prenom']))
{
	$mail = htmlspecialchars($_POST['mail']);
	$prenom = htmlspecialchars($_POST['prenom']);
			
	if ($mail != null)
	{
		include CHEMIN_MODELE.'inscription.php';
		$nmail = rechercher_mail($mail,$prenom);
		if (!$nmail)
		{
			$ok = 'non';
			$message = '<div class="alert alert-danger" role="alert"><p>Erreur ! Aucun mail <b>'.$mail.'</b> pour le prenom <b>'.$prenom.'</b> dans la base.</p></div>';
			include CHEMIN_VUE.'mdpoubli.php';
		}
		else
		{
			$date = new DateTime();
			$timest = $date->getTimestamp();
			$ticket = md5(''.$timest.''.$nmail['mail'].'');
			$id = $nmail['idmembre'];
			modif_ticket($id,$ticket);
			$datem = date("Y-m-d H:i:s");
			$idmembre = $nmail['idmembre'];
			$type = 'Nouveau mot de passe';
			$modif = 'Demande de nouveau mot de passe';
			modif($idmembre,$type,$modif,$datem);
			$url = 'http://'.$_SERVER['HTTP_HOST'].':'.$_SERVER['REQUEST_URI'];
			$urlval = str_replace('mdpoubli', 'nmdp', $url);
			$mail1 = $_SERVER['HTTP_HOST'];
			$mailval = ($mail1{3} == '.') ? str_replace('www.', '', $mail1) : $mail1;
			$meshtml = '<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" /></head>
			<body>'.$rjson_site['titre'].'.<br /> 
			Pour redéfinir votre mot de passe il suffit de cliquer sur le lien <a href="'.$urlval.'&id='.$ticket.'">Mot de passe</a> ou le copier/coller dans votre navigateur.<br />				
			--------<br />
			Ceci est un mail automatique, Merci de ne pas y répondre.</body></html>';
			$mestxt = ''.$rjson_site['titre'].'. 
			Pour redéfinir votre mot de passe il suffit de cliquer sur le lien '.$urlval.'&id='.$ticket.' ou le copier/coller dans votre navigateur.				
			--------
			Ceci est un mail automatique, Merci de ne pas y répondre.';
			$frontiere = '-----=' . md5(uniqid(mt_rand()));
			//$headers = 'From: "no-reply@'.$mailval.'"<no-reply@'.$mailval.'>'."\n";
			$headers ='From: "'.$mailval.'"<no-reply@'.$mailval.'>'."\n"; 
			//$headers = 'From: "no-reply@'.$mailval.'"'."\n";
			$headers .= 'MIME-Version: 1.0'."\n";
			$headers .= 'Content-Type: multipart/alternative; boundary="'.$frontiere.'"'."\n";
			$message = 'This is a multi-part message in MIME format.'."\n\n";
			$message .= '--'.$frontiere."\n";
			$message .= 'Content-Type: text/plain; charset=UTF-8'."\n";
			$message .= 'Content-Transfer-Encoding: 8bit'."\n\n";
			$message .= $mestxt."\n\n";
			$message .= '--'.$frontiere."\n";
			$message .= 'Content-Type: text/html; charset=UTF-8'."\n";
			$message .= 'Content-Transfer-Encoding: 8bit'."\n\n";
			$message .= $meshtml."\n\n";
			$message .= '--'.$frontiere.'--'."\n";	
			mail($mail, 'Redéfinir votre mot de passe', $message, $headers);
			$ok = 'oui';
			$message = 'Un mail vous a été envoyé afin de redéfinir votre mot de passe.';
			include CHEMIN_VUE.'mdpoubli.php';
		}
	}
	else
	{
		
		$ok = 'non';
		$message = '<div class="alert alert-danger" role="alert"><p>Vous devez rentrer un mail</p></div>';
		include CHEMIN_VUE.'mdpoubli.php';
	}	
}
else
{
	$ok = 'non';
	$message = '';
	include CHEMIN_VUE.'mdpoubli.php';
}
?>
	
	