<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function recherche_mail($idm)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT mail, nom, prenom FROM site.membre WHERE idmembre = :idm") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idm', $idm);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_obs($idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT to_char(date1, 'DD/MM/YYYY') AS datefr, commune, fiche.iddep, nom, nomvern, nb FROM obs.fiche
						INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
						LEFT JOIN referentiel.commune ON commune.codecom = fiche.codecom
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE idobs = :idobs ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idobs', $idobs);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
if (isset($_POST['idobs']) && isset($_POST['idmd']) && isset($_POST['idme']))
{
	$idobs = $_POST['idobs'];
	$idme = $_POST['idme'];
	$idmd = $_POST['idmd'];
	$mes = htmlspecialchars($_POST['mes']);
	
	$obs = recherche_obs($idobs);
	
	$mail = recherche_mail($idmd);
	$destinataire = $mail['mail'];
	$mail = recherche_mail($idme);
	$expediteur = $mail['mail'];
	$nomexp = $mail['nom'];
	$prenomexp = $mail['prenom'];
	$url = 'http://'.$_SERVER['HTTP_HOST'].':'.$_SERVER['REQUEST_URI'];
	$urlval = str_replace('modeles/ajax/observation/contact.php', 'index.php?module=observation&action=observation', $url);
	
	$meshtml = '<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" /></head>
				<body>Concernant votre observation n° '.$idobs.' sur le site '.$_SERVER['HTTP_HOST'].' à partir de la page <a href="'.$urlval.'">'.$urlval.'</a>.<br />'.$mes.'<br /><br />
				Votre observation pour rappel<br />
				'.$obs['datefr'].' - '.$obs['commune'].'('.$obs['iddep'].') - '.$obs['nom'].' ('.$obs['nomvern'].').
				</body></html>';
	$mestxt = 'Concernant votre observation n° '.$idobs.' sur le site '.$_SERVER['HTTP_HOST'].' à partir de la page '.$urlval.' '.$mes.' Votre observation pour rappel '.$obs['datefr'].' - '.$obs['commune'].'('.$obs['iddep'].') - '.$obs['nom'].' ('.$obs['nomvern'].').';
	$frontiere = '-----=' . md5(uniqid(mt_rand()));
	$headers ='From: "'.$expediteur.'"<'.$expediteur.'>'."\n"; 
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
	if(mail($destinataire, 'Message de '.$prenomexp.' '.$nomexp.'', $message, $headers))
	{
		$retour['statut'] = 'Oui';
	}
	else
	{
		$retour['statut'] = 'Non';
		$retour['er'] = 'Echec lors de l\'envoi du mail';
	}	
}
else
{
	$retour['statut'] = 'Non';
	$retour['er'] = 'Erreur ! tous les champs ne sont par parvenus';
}
echo json_encode($retour);	