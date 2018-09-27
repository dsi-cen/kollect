<?php 
// © Jérome Réaux : http://j-reaux.developpez.com - http://www.jerome-reaux-creations.fr   (modifiée)
// ---------------------------------------------------
// Fonction de REDIMENSIONNEMENT physique "PROPORTIONNEL" et Enregistrement 
// ---------------------------------------------------
// retourne : true si le redimensionnement et l'enregistrement ont bien eu lieu, sinon un message textuel
// ---------------------
// La FONCTION : RedimImage ($W_max, $H_max, $rep_Dst, $img_Dst, $rep_Src, $img_Src)
// Les paramètres :
// - $W_max : LARGEUR maxi finale --> ou 0
// - $H_max : HAUTEUR maxi finale --> ou 0
// - $rep_Dst : repertoire de l'image de Destination (déprotégé) --> ou '' (même répertoire)
// - $img_Dst : NOM de l'image de Destination --> ou '' (même nom que l'image Source)
// - $rep_Src : repertoire de l'image Source (déprotégé)
// - $img_Src : NOM de l'image Source
// ---------------------
// 3 options :
// A- si $W_max!=0 et $H_max!=0 : a LARGEUR maxi ET HAUTEUR maxi fixes
// B- si $H_max!=0 et $W_max==0 : image finale a HAUTEUR maxi fixe (largeur auto)
// C- si $W_max==0 et $H_max!=0 : image finale a LARGEUR maxi fixe (hauteur auto)
// Si l'image Source est plus petite que les dimensions indiquées : PAS de redimensionnement.
// ---------------------
// $rep_Dst : il faut s'assurer que les droits en écriture ont été donnés au dossier (chmod)
// - si $rep_Dst = ''   : $rep_Dst = $rep_Src (même répertoire que l'image Source)
// - si $img_Dst = '' : $img_Dst = $img_Src (même nom que l'image Source)
// - si $rep_Dst='' ET $img_Dst='' : on ecrase (remplace) l'image source !
// ---------------------
// NB : $img_Dst et $img_Src doivent avoir la meme extension (meme type mime) !
// Extensions acceptées (traitees ici) : .jpg , .jpeg , .png
// Pour Ajouter d autres extensions : voir la bibliotheque GD ou ImageMagick
// (GD) NE fonctionne PAS avec les GIF ANIMES ou a fond transparent !
// ---------------------
// UTILISATION (exemple) :
// $redimOK = RedimImage(120,80,'reppicto/','monpicto.jpg','repimage/','monimage.jpg','jpg');
// if ($redimOK==true) { echo 'Redimensionnement OK !';  }
// ---------------------------------------------------

abstract class RedimImage extends SetMessages
{
	public static function Param($W_max, $H_max, $rep_Dst, $img_Dst, $rep_Src, $img_Src, $extension_Src) 
	{
		// Initialise les messages serveur contenus dans "Classes/Messages.php"
		new SetMessages('redimCrop');
		
		try 
		{
				
			//Suppose une image valide. Si l'image n'est pas redimensionnée, elle est simplement optimisée pour le web avec une qualité de 90
		
			$condition = false;
			// Si certains paramètres ont pour valeur '' :
			if ($rep_Dst=='') { $rep_Dst = $rep_Src; } // (même répertoire)
			if ($img_Dst=='') { $img_Dst = $img_Src; } // (même nom)
			
			$ext_redim = ['jpg','jpeg','png','gif'];
			
			if(!in_array($extension_Src,$ext_redim)) throw new Exception(SetMessages::setMess('UpAbExtensionFichier'));
			// ---------------------
			
			// récupération des dimensions de l'image Src
			$img_size = @getimagesize($rep_Src.$img_Src);
			$W_Src = $img_size[0]; // largeur
			$H_Src = $img_size[1]; // hauteur
			
			if(empty($W_Src) || empty($H_Src)) throw new Exception(SetMessages::setMess('UpAbImageDimensions'));
			// ------------------------
			// condition de redimensionnement et dimensions de l'image finale
			// ------------------------
			// A- LARGEUR ET HAUTEUR maxi fixes
			if ($W_max!=0 && $H_max!=0) 
			{
				$ratiox = $W_Src / $W_max; // ratio en largeur
				$ratioy = $H_Src / $H_max; // ratio en hauteur
				$ratio = max($ratiox,$ratioy); // le plus grand
				$W = $W_Src/$ratio;
				$H = $H_Src/$ratio;   
				$condition = ($W_Src > $W) || ($H_Src > $H); //  si vrai : true
			}
			else //  B- HAUTEUR maxi fixe
			if ($W_max==0 && $H_max!=0) 
			{
				$H = $H_max;
				$W = $H * ($W_Src / $H_Src);
				$condition = ($H_Src > $H_max); // si vrai : true
			}
			else //  C- LARGEUR maxi fixe
			if ($W_max!=0 && $H_max==0) 
			{
				$W = $W_max;
				$H = $W * ($H_Src / $W_Src);         
				$condition = ($W_Src > $W_max); // si vrai : true
			}
			
			switch($extension_Src) 
			{
				case 'jpg':
				case 'jpeg': $Ress_Src = imagecreatefromjpeg($rep_Src.$img_Src); 
										 break;
				
				case 'png': $Ress_Src = imagecreatefrompng($rep_Src.$img_Src);
										if(!$condition && is_resource($Ress_Src))
										{
											imagealphablending($Ress_Src, false);
											imagesavealpha($Ress_Src, true);
										}
										break;
			
				case 'gif': $Ress_Src = imagecreatefromgif($rep_Src.$img_Src); 
										break;	
										
				default : $Ress_Src = null;
			}
			
			if(!is_resource($Ress_Src)) throw new Exception(SetMessages::setMess('UpAbImageCreationSource'));
		
			// ---------------------------------------------
			// REDIMENSIONNEMENT si la condition est vraie
			// ---------------------------------------------
			// - Si l'image Source est plus petite que les dimensions indiquées :
			// Par defaut : PAS de redimensionnement mais optimisation pour le web
			// On peut "forcer" le redimensionnement pour agrandissement en ajoutant ici :
			// $condition = 1; (risque de perte de qualité)
			
			// creation de la ressource-image "Src" en fonction de l'extension
			
		
			if ($condition) 
			{
				// creation d une ressource-image "Dst" aux dimensions finales
				// fond noir (par defaut)
				switch($extension_Src) 
				{
					case 'gif':
					case 'jpg':
					case 'jpeg': 	$Ress_Dst = imagecreatetruecolor(round($W),round($H));
									break;
									
					case 'png': 	$Ress_Dst = imagecreatetruecolor(round($W),round($H));
									if(is_resource($Ress_Dst))
									{
										// fond transparent (pour les png avec transparence)
										imagealphablending($Ress_Dst, false);
										imagesavealpha($Ress_Dst, true);
										$trans_color = imagecolorallocatealpha($Ress_Dst, 0, 0, 0, 127);
										imagefill($Ress_Dst, 0, 0, $trans_color);
									}
									break;

					default : $Ress_Dst = null;
				}
				
				if(!is_resource($Ress_Dst)) throw new Exception(SetMessages::setMess('UpAbImageCreationDestination'));
				
				// REDIMENSIONNEMENT (copie, redimensionne, re-echantillonne)
				$redim = imagecopyresampled($Ress_Dst, $Ress_Src, 0, 0, 0, 0, round($W), round($H), round($W_Src), round($H_Src)); 
				if ($redim == false) throw new Exception(SetMessages::setMess('UpAbImageRedimension'));
			}
			else
			{
				$Ress_Dst = $Ress_Src;
			}
			
			// ENREGISTREMENT dans le repertoire (avec la fonction appropriee)
			switch ($extension_Src) 
			{ 
				case 'jpg':
				case 'jpeg': $image = imagejpeg ($Ress_Dst, $rep_Dst.$img_Dst, 90); break;
				case 'png': $image = imagepng ($Ress_Dst, $rep_Dst.$img_Dst,1); break;
				case "gif" : $image = imagegif($Ress_Dst, $rep_Dst.$img_Dst); break;
				
				default : $image = false;
			}
			
			if ($image == false) throw new Exception(SetMessages::setMess('UpAbImageEnregistrement'));
			// ------------------------
			// liberation des ressources-image
			imagedestroy ($Ress_Src);
			imagedestroy ($Ress_Dst);
			
			return true;
		} 
		catch (Exception $e) 
		{
			return $e->getMessage();
		}
	}	
}
?>