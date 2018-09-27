<?php
$titre = 'Validation';
$description = 'Validation de l\'inscription au site';
$script = '<script src="js/jquery.js" defer></script>
<script src="js/webobs.js" defer></script>
<script src="js/bootstrap.min.js" defer></script>';
$css = '';
if(isset($_GET['p']) AND !empty($_GET['p']) AND isset($_GET['id']) AND !empty($_GET['id']))
{
	include CHEMIN_MODELE.'inscription.php';
	$prenom = htmlspecialchars($_GET['p']);
	$id = $_GET['id'];	
	$validation = validation($prenom,$id);	
	if($validation['actif'] == 1)
	{
		$message = '<div class="alert alert-warning" role="alert"><p>Votre compte est déjà actif !</p></div>';
		$ok = 'oui';
	}
	else
	{
		if($validation['idmembre'] == $id)
		{
			modif_membre($id);
			$datem = date("Y-m-d H:i:s");
			$idmembre = $id;
			$type = 'Inscription au site - validation';
			$modif = 'Compte activé';
			modif($idmembre,$type,$modif,$datem);
			$ok = 'oui';
			$message = '<div class="alert alert-success" role="alert"><p>Votre compte a bien été activé !</p></div>';
			
			$mail = $rjson_site['email'];
			$mail1 = $_SERVER['HTTP_HOST'];
			$mailval = ($mail1{3} == '.') ? str_replace('www.', '', $mail1) : $mail1;
			$meshtml = '<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" /></head>
			<body>Inscription sur '.$rjson_site['titre'].'.<br /> 
			Id du membre = '.$id.'<br />
			Vérifier si ce membre doit-être rattaché à un compte observateur (page observateur en gestion).';
			$mestxt = 'Inscription sur '.$rjson_site['titre'].'. Id du membre = '.$id.'. Vérifier si ce membre doit-être rattaché à un compte observateur (page observateur en gestion).';
			$frontiere = '-----=' . md5(uniqid(mt_rand()));
			
			$headers ='From: "'.$mailval.'"<no-reply@'.$mailval.'>'."\n"; 
			$headers .= 'MIME-Version: 1.0'."\n";
			$headers .= 'Content-Type: multipart/alternative; boundary="'.$frontiere.'"'."\n";
			$messagem = 'This is a multi-part message in MIME format.'."\n\n";
			$messagem .= '--'.$frontiere."\n";
			$messagem .= 'Content-Type: text/plain; charset=UTF-8'."\n";
			$messagem .= 'Content-Transfer-Encoding: 8bit'."\n\n";
			$messagem .= $mestxt."\n\n";
			$messagem .= '--'.$frontiere."\n";
			$messagem .= 'Content-Type: text/html; charset=UTF-8'."\n";
			$messagem .= 'Content-Transfer-Encoding: 8bit'."\n\n";
			$messagem .= $meshtml."\n\n";
			$messagem .= '--'.$frontiere.'--'."\n";	
			mail($mail, 'Inscription', $messagem, $headers);
			
		}
		else
		{
			$ok = 'non';
			$message = '<div class="alert alert-danger" role="alert"><p>Erreur ! Votre compte ne peut être activé...</p></div>';
		}
	}
	include CHEMIN_VUE.'validation.php';
}
else
{
	$ok = 'non';
	$message = '<div class="alert alert-danger" role="alert"><p>Erreur ! Assurez vous que le lien est entier</p></div>';
	include CHEMIN_VUE.'validation.php';
}
?>
	
	