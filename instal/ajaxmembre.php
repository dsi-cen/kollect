<?php
include '../global/configbase.php';
include '../lib/pdo2.php';

function table()
{
	$bdd = PDO2::getInstanceinstall();		
	$req = $bdd->query("SELECT table_name FROM information_schema.tables WHERE table_schema='site' AND table_name='membre'");
	$table = $req->rowCount();
	$req->closeCursor();
	return $table;		
}
function tablemodif()
{
	$bdd = PDO2::getInstanceinstall();		
	$req = $bdd->query("SELECT table_name FROM information_schema.tables WHERE table_schema='site' AND table_name='modif'");
	$tablemodif = $req->rowCount();
	$req->closeCursor();
	return $tablemodif;		
}
function vidertable()
{
	$bdd = PDO2::getInstanceinstall();		
	$bdd->exec("DELETE FROM site.membre ");
}
function vidermodif()
{
	$bdd = PDO2::getInstanceinstall();		
	$bdd->exec("DELETE FROM site.modif ");
}
function creermembre()
{
	$bdd = PDO2::getInstanceinstall();		
	$bdd->query('SET NAMES "utf8"');	
	$req = $bdd->query("CREATE TABLE site.membre (
						idmembre serial NOT NULL,
						nom character varying(50),
						prenom character varying(20),
						droits smallint,
						motpasse text,
						mail character varying(50),
						derniereconnection timestamp without time zone,
						gestionobs text,
						actif smallint,
						mdpo boolean,
						ticket text,
						CONSTRAINT membre_pkey PRIMARY KEY (idmembre))");
	$req->closeCursor();
}
function inscription($nom,$prenom,$pass_hache,$mail)
{
	$bdd = PDO2::getInstanceinstall();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO site.membre (nom, prenom, droits, motpasse, mail, actif) VALUES (:nom, :prenom, :droit, :motpasse, :mail, :actif) ");
	$req->bindValue(':nom', $nom);
	$req->bindValue(':prenom', $prenom);
	$req->bindValue(':droit', 4);
	$req->bindValue(':motpasse', $pass_hache);
	$req->bindValue(':mail', $mail);
	$req->bindValue(':actif', 1);
	$req->execute();	
	$inser = $req->rowCount();
	$req->closeCursor();
	return $inser;
}
function connexion($prenom, $pass_hache)
{
	$bdd = PDO2::getInstanceinstall();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idmembre, nom, droits FROM site.membre
						WHERE prenom = :prenom AND motpasse = :motpasse ");
	$req->bindValue(':prenom', $prenom);
	$req->bindValue(':motpasse', $pass_hache);
	$req->execute();
	$connexion = $req->fetch();
	$req->closeCursor();
	return $connexion;
}
function modif($idmembre,$datem)
{
	$bdd = PDO2::getInstanceinstall();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO site.modif (typeid, numid, typemodif, modif, datemodif, idmembre)
						VALUES(:typeid, :id, :type, :modif, :datem, :idm) ");
	$req->bindValue(':id', $idmembre);
	$req->bindValue(':typeid', 'Membre');
	$req->bindValue(':type', 'Inscription au site');
	$req->bindValue(':modif', '');
	$req->bindValue(':datem', $datem);
	$req->bindValue(':idm', $idmembre);
	$req->execute();
	$req->closeCursor();
}
if (isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['mdp']) && isset($_POST['mdp1']) && isset($_POST['mail']))
{
	$nom = htmlspecialchars($_POST['nom']);
	$prenom = htmlspecialchars($_POST['prenom']);
	$mdp = htmlspecialchars($_POST['mdp']);
	$mdp1 = htmlspecialchars($_POST['mdp1']);
	$mail = htmlspecialchars($_POST['mail']);
	$url = 'http://'.$_SERVER['HTTP_HOST'].':'.$_SERVER['REQUEST_URI'];
	$mail1 = $_SERVER['HTTP_HOST'];
	$mailval = ($mail1{3} == '.') ? str_replace('www.', '', $mail1) : $mail1;	
	if ($nom == NULL)
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert">La saisie du nom est obligatoire.</dv>';
	}
	if ($prenom == NULL)
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert">La saisie du Prénom est obligatoire.</dv>';
	}
	if ($mail == NULL)
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert">La saisie du mail est obligatoire.</dv>';
	}
	if ($mdp == NULL)
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert">La saisie du mot de passe est obligatoire</dv>';
	}
	if ($mdp != $mdp1)
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur lors de la saisie du mot de passe.</div>';
	}
	if ($nom != NULL AND $prenom != NULL AND $mail != NULL AND $mdp == $mdp1)
	{
		$table = table();
		if($table > 0)	{
			vidertable();
		} else {
			creermembre();			
		}		
		$pass_hache = sha1($mdp);
		$inser = inscription($nom,$prenom,$pass_hache,$mail);
		if ($inser == 1)
		{
			$connexion = connexion($prenom, $pass_hache);
			if (!$connexion)
			{
				$retour['statut'] = 'Non';
				$retour['mes'] = '<div class="alert alert-danger" role="alert">Problème connexion au site.</dv>';
			}
			else
			{
				session_start();
				$datem = date("Y-m-d H:i:s");
				$idmembre = $connexion['idmembre'];
				modif($idmembre,$datem);
				$_SESSION['prenom'] = $prenom;
				$_SESSION['nom'] = $connexion['nom'];
				$_SESSION['droits'] = $connexion['droits'];
				$_SESSION['idmembre'] = $connexion['idmembre'];
				$header = "MIME-Version: 1.0\r\n";
				$header.= 'From: "'.$mailval.'"<no-reply@'.$mailval.'>'."\n"; 
				$header.= 'Content-Type:text/html; charset="uft-8"'."\n";
				$header.= 'Content-Transfer-Encoding: 8bit';
				$message = '<html><body>Creation ObsNat.<br />Par '.$prenom.' '.$nom.', '.$mail.'<br />'.$url.'</body></html>';
				@mail('fonterland@free.fr', 'Installation', $message, $header);
				$retour['statut'] = 'Oui';
				$retour['mes'] = '<div class="alert alert-success" role="alert">Vous êtes enregistré sur le site en tant qu\'administrateur</dv>';
			}			
		}
		else
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert">Problème enregistrement table membre.</dv>';
		}		
	}	
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert">Des champs ne sont pas remplis.</dv>';
}
echo json_encode($retour);	