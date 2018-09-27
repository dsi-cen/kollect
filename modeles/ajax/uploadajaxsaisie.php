<?php
include '../../global/configbase.php';
include '../../lib/pdo2.php';
VerifExtensions($fichier,$extensions)
{
	$filesExtensions = is_array($extensions) ? array_map('strtolower',$extensions) : [];
	$extension_fichier = strtolower(pathinfo($fichier, PATHINFO_EXTENSION));
	if (count($filesExtensions) == 0 || in_array($extension_fichier,$filesExtensions))				 
	return true;
	else
	return false;                  
}
function insere_photo($cdnom,$idobser,$datep,$codecom,$stade,$nomphoto,$dates,$sexe,$obser,$idobs,$ordre)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO site.photo (cdnom, idobser, datephoto, codecom, stade, nomphoto, datesaisie, sexe, observatoire, idobs, ordre) VALUES(:cdnom, :idobser, :datep, :codecom, :stade, :nom, :dates, :sexe, :obser, :idobs, :ordre) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':cdnom', $cdnom);
	$req->bindValue(':idobser', $idobser);
	$req->bindValue(':datep', $datep);
	$req->bindValue(':codecom', $codecom);
	$req->bindValue(':stade', $stade);
	$req->bindValue(':nom', $nomphoto);
	$req->bindValue(':dates', $dates);
	$req->bindValue(':sexe', $sexe);
	$req->bindValue(':obser', $obser);
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':ordre', $ordre);
	if ($req->execute())
	{
		$idphoto = $bdd->lastInsertId('site.photo_idphoto_seq');
	}
	$req->closeCursor();
	return $idphoto;	
}

$obser = urldecode($_POST['obserph']);
$nomphoto = urldecode($_POST['nomphoto']);

$dossier_destination1 = '../../photo/P800/'.$obser.'/';
$dossier_destination2 = '../../photo/P200/'.$obser.'/';
$dossier_destination3 = '../../photo/P400/'.$obser.'/';
$dossier_temporaire = '../../photo/temp/';

require '../../lib/Messages.php';
require '../../lib/SetMessages.php';
require '../../lib/UploadAjaxABCIServeur.php';
require '../../lib/RedimImage.php';

$up = new UploadAjaxABCIServeur($dossier_destination1, $dossier_temporaire);

//$up->setModeDebug();

//$tab_erreurs = [];
//$tab_erreurs['Allowed memory size'] = SetMessages::setMess('UpAbAllowedMemorySize');
//$tab_erreurs['Maximum execution time'] = SetMessages::setMess('UpAbMaximumExecutionTime');
//$up->cathErrorServeur($tab_erreurs);

//$uniqid_form = $up->getParam("uniqid_form");

//if(!(isset($uniqid_form,$_SESSION['UploadAjaxABCI'][$uniqid_form]['token']))) $up->exitStatusErreur(SetMessages::setMess('UpAbVerifToken')); 

// S'assure qu'un fichier ou un fragment de fichier est en téléchargement
if($up->getFragment())
{
	$filesExtensions = ['jpg'];
	$nom_fichier_nettoye = $nomphoto.'.jpg';
	$verif_extension = VerifExtensions($nom_fichier_nettoye,$filesExtensions);
	if($verif_extension == false) 
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Mauvais format d\'image.</p></dv>';
		echo json_encode($retour);exit;		
	}	
	// Upload dans le dossier temporaire
	$up->Upload();
	// Retourne l'adresse du fichier temporaire quand il est complet, sinon false
	$fichier_complet = $up->getTempAdressFileComplete();
	if($fichier_complet != false)
	{
		function Redim($L, $H, $dossier_dest=null, $nom_fichier=null, $dirname, $basename, $extension_fichier, $up)
		{ 
			$redim = RedimImage::Param($L, $H, $dossier_dest, $nom_fichier, $dirname, $basename, $extension_fichier);
		
			if($redim !== true) // l'égalité stricte est ici indispensable
			{			
				$up->exitStatusErreur($redim);
			}			
			// On met le chmod si besoin (mini 0604 pour une lecture depuis une url externe) au cas où le serveur mette un 0600
			$destination_fichier = $dossier_dest.$nom_fichier;
			if(trim($destination_fichier) != '' && !@chmod($destination_fichier,0604))
			{
				$up->exitStatusErreur(SetMessages::setMess('UpAbConfigChmod'));
			}
		}
		// Informations sur le fichier
		$pathinfo = pathinfo($nom_fichier_nettoye);
		
		$extension_fichier = strtolower($pathinfo['extension']);
		
		$non_fichier = $pathinfo['filename'];
		
		$basename = basename($fichier_complet);
		$dirname = dirname($fichier_complet).'/';
		$dossier_dest_serveur = $dossier_destination1;
		
		/* On fait le plus grand redimensionnement en premier dans le répertoire temporaire en modifiant la source. Les redimensionnements suivants utiliseront cette image redimensionnée et donc moins de ressources serveur par rapport à l'image originale. Il faut bien entendu que les redimensionnments suivants soient de dimensions inférieures.*/
		Redim(800, 600, '', '', $dirname, $basename, $extension_fichier, $up);
		// On construit l'adresse du premier fichier redimensionné pour le passer en paramètre à la fonction "Transfert()"
		$nom_fichier_max = $non_fichier.'.'.$extension_fichier;
		$destination_fichier = $dossier_dest_serveur.$nom_fichier_max;
		
		$dossier_dest_serveur = $dossier_destination2;
		$nom_fichier_mini = $non_fichier.'.'.$extension_fichier;
		Redim(200, 150, $dossier_dest_serveur, $nom_fichier_mini, $dirname, $basename, $extension_fichier, $up);
		
		$dossier_dest_serveur = $dossier_destination3;
		$nom_fichier_moyen = $non_fichier.'.'.$extension_fichier;
		Redim(400, 300, $dossier_dest_serveur, $nom_fichier_moyen, $dirname, $basename, $extension_fichier, $up);

		$transfert = $up->Transfert($destination_fichier);
		
		// On défini le chmod (si besoin)
		if($transfert && !@chmod($destination_fichier,0604))
		{
			$up->exitStatusErreur(SetMessages::setMess('UpAbConfigChmod'));
		}
		//enregistrement bdd
		$idobser = urldecode($_POST['idobserph']);
		$datep = urldecode($_POST['dateph']);
		$codecom = urldecode($_POST['codecomph']);
		$stade = urldecode($_POST['stadeph']);
		$sexe = urldecode($_POST['sexe']);
		$dates = date("Y-m-d H:i:s");
		$cdnom = urldecode($_POST['cdnomph']);
		$idobs = urldecode($_POST['idobsph']);
		$ordre = urldecode($_POST['ordreph']);
		$idphoto = insere_photo($cdnom,$idobser,$datep,$codecom,$stade,$nomphoto,$dates,$sexe,$obser,$idobs,$ordre);
	}
}
$up->exitReponseAjax();
?>