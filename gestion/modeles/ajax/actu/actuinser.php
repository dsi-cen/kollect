<?php 
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function insere_actu($titre,$stitre,$actu,$tag,$theme,$date,$lien,$visible,$idm)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO actu.actu (titre, soustitre, actu, tag, theme, datecreation, url, compte, visible, idauteur) VALUES(:titre, :stitre, :actu, :tag, :theme, :date, :lien, :compte, :visible, :idm) ");
	$req->bindValue(':titre', $titre);
	$req->bindValue(':stitre', $stitre);
	$req->bindValue(':actu', $actu);
	$req->bindValue(':tag', $tag);
	$req->bindValue(':theme', $theme);
	$req->bindValue(':date', $date);
	$req->bindValue(':lien', $lien);
	$req->bindValue(':compte', 0);
	$req->bindValue(':visible', $visible);
	$req->bindValue(':idm', $idm, PDO::PARAM_INT);
	if ($req->execute())
	{
		$idactu = $bdd->lastInsertId('actu.actu_idactu_seq');
	} 
	$req->closeCursor();
	return $idactu;
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
	$photo = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $photo;
}
function insere_pdf($idactu,$nomdoc)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO actu.docactu (nomdoc, idactu) VALUES(:nom, :idactu) ");
	$req->bindValue(':idactu', $idactu);
	$req->bindValue(':nom', $nomdoc);
	$pdf = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $pdf;
}
function modif($idactu,$idm,$type,$modif,$datem)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO site.modif (typeid, numid, typemodif, modif, datemodif, idmembre)
						VALUES(:typeid, :id, :type, :modif, :datem, :idm) ");
	$req->bindValue(':id', $idactu);
	$req->bindValue(':typeid', 'Actu');
	$req->bindValue(':type', $type);
	$req->bindValue(':modif', $modif);
	$req->bindValue(':datem', $datem);
	$req->bindValue(':idm', $idm);
	$req->execute();
	$req->closeCursor();
}
if (isset($_POST['titre']) && isset($_POST['actu']))
{
	$titre = $_POST['titre'];
	$stitre = $_POST['stitre'];
	$lien = $_POST['lienw'];
	$tag = $_POST['tag'];
	$theme =  $_POST['theme'];
	$actu =  $_POST['actu'];
	$visible = $_POST['visible'];
	$idm = $_POST['idm'];
	$date = date('Y-m-d');
	
	$idactu = insere_actu($titre,$stitre,$actu,$tag,$theme,$date,$lien,$visible,$idm);
	if (!empty ($idactu))
	{
		if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != '')
		{
			$extensionsvalides = array( 'jpg' , 'jpeg');
			$extensionimg = strtolower(substr(strrchr($_FILES['image']['name'], '.'),1));
			if (! in_array($extensionimg,$extensionsvalides))
			{
				$retour['statut'] = 'Non';
				$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Ce type d\'image n\'est pas autorisé.</p></dv>';
				echo json_encode($retour);	
				exit;
			}
			if($_FILES['image']['error'] == 1)
			{
				$retour['statut'] = 'Non';
				$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Vos fichiers sont trop lourd et dépasse les limites autorisés par votre serveur.</p></dv>';
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
				$redim = fctredimimage(800,600,$repDest,'',$repSource,$imageDest);
				$repSource = $dossier_destination1;
				$repDest = $dossier_destination2;
				$redim = fctredimimage(400,300,$repDest,'',$repSource,$imageDest);
				$repDest = $dossier_destination3;
				$redim = fctredimimage(200,150,$repDest,'',$repSource,$imageDest);
				if ($redim == true) 
				{ 
					$auteur = $_POST['auteurph'];
					$info = $_POST['infoph'];
					$nomphoto = 'actu'.$idactu;
					$photo = insere_photo($idactu,$nomphoto,$auteur,$info);					
				}
				else
				{
					$retour['statut'] = 'Non';
					$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur ! Problème lors du redimensionnement de la photo.</div>';
					echo json_encode($retour);	
					exit;
				}
				unlink($dossier_temporaire.$imageDest);				
			}			
		}
		if(isset($_FILES['pdf']['name']) && $_FILES['pdf']['name'] != '')
		{
			$extensionpdf = strtolower(substr(strrchr($_FILES['pdf']['name'], '.'),1));
			if($extensionpdf == 'pdf' || $extensionpdf == 'zip')
			{
				if($_FILES['pdf']['error'] == 1)
				{
					$retour['statut'] = 'Non';
					$retour['mes'] = '<div class="alert alert-danger" role="alert">Vos fichiers sont trop lourd et dépasse les limites autorisés par votre serveur.</div>';
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
					$pdf = insere_pdf($idactu,$pdfDest);	
				}
			}
		}		
		$datem = date("Y-m-d H:i:s");
		$type = 'Ajout';
		$modif = 'Ajout actualité ('.$theme.')';
		modif($idactu,$idm,$type,$modif,$datem);	
		$retour['statut'] = 'Oui';
	}
	else
	{
		$retour['statut'] = 'Non'; 
	}
	echo json_encode($retour);	
}