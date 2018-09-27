<?php 
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function mod_actu($idactu,$titre,$stitre,$actu,$tag,$theme,$lien,$visible)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("UPDATE actu.actu SET titre =:titre, soustitre =:stitre, actu =:actu, tag =:tag, theme =:theme, url =:lien, visible =:visible WHERE idactu = :id ");
	$req->bindValue(':id', $idactu);
	$req->bindValue(':titre', $titre);
	$req->bindValue(':stitre', $stitre);
	$req->bindValue(':actu', $actu);
	$req->bindValue(':tag', $tag);
	$req->bindValue(':theme', $theme);
	$req->bindValue(':lien', $lien);
	$req->bindValue(':visible', $visible);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;
}
function cherchephoto($idactu)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idphoto, nom FROM actu.photoactu WHERE idactu = :id ");
	$req->bindValue(':id', $idactu, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function supprime_photo($idphoto)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("DELETE FROM actu.photoactu WHERE idphoto = :id ");
	$req->bindValue(':id', $idphoto);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;
}
function insere_photo($idactu,$nomphoto,$auteur,$info)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO actu.photoactu (idactu, nom, auteur, info) VALUES(:idactu, :nom, :auteur, :info) ");
	$req->bindValue(':idactu', $idactu);
	$req->bindValue(':nom', $nomphoto);
	$req->bindValue(':auteur', $auteur);
	$req->bindValue(':info', $info);
	$req->execute();
	$req->closeCursor();
}
function mod_photo($idphoto,$auteur,$info)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("UPDATE actu.photoactu SET auteur =:auteur, info =:info WHERE idphoto = :id ");
	$req->bindValue(':id', $idphoto);
	$req->bindValue(':auteur', $auteur);
	$req->bindValue(':info', $info);
	$req->execute();
	$req->closeCursor();	
}
function insere_pdf($idactu,$nomdoc)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO actu.docactu (nomdoc, idactu) VALUES(:nom, :idactu) ");
	$req->bindValue(':idactu', $idactu);
	$req->bindValue(':nom', $nomdoc);
	$req->execute();
	$req->closeCursor();
}

if (isset($_POST['titre']) && isset($_POST['actu']))
{
	$idactu = $_POST['idactu'];
	$titre = $_POST['titre'];
	$stitre = $_POST['stitre'];
	$lien = $_POST['lienw'];
	$tag = $_POST['tag'];
	$theme =  $_POST['theme'];
	$actu =  $_POST['actu'];
	$visible = $_POST['visible'];
	$supphoto = $_POST['supphoto'];
	
	$valiactu = mod_actu($idactu,$titre,$stitre,$actu,$tag,$theme,$lien,$visible);
	if($supphoto == 'oui')
	{
		$photo = cherchephoto($idactu);
		$retour['photo'] = $photo['nom'];
		$photosup = supprime_photo($photo['idphoto']);
		$retour['photosup'] = $photosup;
		if($photosup == 'oui')
		{
			unlink('../../../../photo/article/P800/'.$photo['nom'].'.jpg');
			unlink('../../../../photo/article/P400/'.$photo['nom'].'.jpg');
			unlink('../../../../photo/article/P200/'.$photo['nom'].'.jpg');
		}
	}
	else
	{
		if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != '')
		{
			$retour['img'] = $_FILES['image']['name'];
			$extensionsvalides = array( 'jpg' , 'jpeg');
			$extensionimg = strtolower(substr(strrchr($_FILES['image']['name'], '.'),1));
			if (! in_array($extensionimg,$extensionsvalides))
			{
				$retour['statut'] = 'Non';
				$retour['mes'] = '<div class="alert alert-danger" role="alert">Ce type d\'image n\'est pas autorisé.</dv>';
				echo json_encode($retour);	
				exit;
			}
			if($_FILES['image']['error'] == 1)
			{
				$retour['statut'] = 'Non';
				$retour['mes'] = '<div class="alert alert-danger" role="alert">Vos fichiers sont trop lourd et dépasse les limites autorisés par votre serveur.</dv>';
				echo json_encode($retour);	
				exit;
			}
			$dossier_destination1 = '../../../../photo/article/P800/';
			$dossier_destination2 = '../../../../photo/article/P400/';
			$dossier_destination3 = '../../../../photo/article/P200/';
			$dossier_temporaire = '../../../../photo/temp/';
			$repSource = $_FILES['image']['tmp_name'];
			$imageDest = 'actu'.$idactu.'.jpg';
			$destination = $dossier_temporaire . $imageDest; 
			$ok = move_uploaded_file($repSource, $destination);
			if ($ok == true)
			{			
				require '../../../lib/RedimImageJpg.php';
				$repSource = $dossier_temporaire;
				$repDest = $dossier_destination1;
				$redim = fctredimimage(800,0,$repDest,'',$repSource,$imageDest);
				$repSource = $dossier_destination1;
				$repDest = $dossier_destination2;
				$redim = fctredimimage(400,0,$repDest,'',$repSource,$imageDest);
				$repDest = $dossier_destination3;
				$redim = fctredimimage(200,0,$repDest,'',$repSource,$imageDest);
				if ($redim == true) 
				{ 
					$auteur = $_POST['auteurph'];
					$info = $_POST['infoph'];
					$nomphoto = 'actu'.$idactu;
					if($supphoto == 'nouv')
					{
						$photo = cherchephoto($idactu);
						mod_photo($photo['idphoto'],$auteur,$info);
					}
					else
					{
						insere_photo($idactu,$nomphoto,$auteur,$info);		
					}								
				}
				else
				{
					$retour['statut'] = 'Non';
					$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur ! Problème lors du redimensionnement de la photo.</dv>';
					echo json_encode($retour);	
					exit;
				}
				unlink($dossier_temporaire.$imageDest);				
			}			
		}
		else
		{
			if($supphoto == 'photo')
			{
				$auteur = $_POST['auteurph'];
				$info = $_POST['infoph'];
				$photo = cherchephoto($idactu);
				mod_photo($photo['idphoto'],$auteur,$info);
			}
		}
	}
	if (isset($_FILES['pdf']['name']) && $_FILES['pdf']['name'] != '')
	{
		$extensionpdf = strtolower(substr(strrchr($_FILES['pdf']['name'], '.'),1));
		if($extensionpdf == 'pdf' || $extensionpdf == 'zip')
		{
			if($_FILES['pdf']['error'] == 1)
			{
				$retour['statut'] = 'Non';
				$retour['mes'] = '<div class="alert alert-danger" role="alert">Vos fichiers sont trop lourd et dépasse les limites autorisés par votre serveur.</dv>';
				echo json_encode($retour);	
				exit;
			}				
			$dossier_destination = '../../../../docactu/';
			$repSource = $_FILES['pdf']['tmp_name'];
			$pdfDest = ($extensionpdf == 'pdf') ? 'actu'.$idactu.'.pdf' : 'actu'.$idactu.'.zip';
			$destination = $dossier_destination . $pdfDest; 
			$ok = move_uploaded_file($repSource, $destination);
			if ($ok == true)
			{
				$suppdf = $_POST['suppdf'];
				if($suppdf == 'nouveau')
				{
					insere_pdf($idactu,$pdfDest);
				}
				elseif($suppdf == 'change')
				{
					//a faire recherche id + mod
					mod_pdf($idactu,$pdfDest);
				}					
			}
		}
	}
	$retour['statut'] = 'Oui';
	
	
	/*
		
		$datem = date("Y-m-d H:i:s");
		$type = 'Ajout';
		$modif = 'Ajout actualité ('.$theme.')';
		modif($idactu,$idm,$type,$modif,$datem);	
		$retour['statut'] = 'Oui';
	}
	else
	{
		$retour['statut'] = 'Non'; 
	}*/
	echo json_encode($retour);	
}