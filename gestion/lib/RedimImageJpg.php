<?php
function fctredimimage($W_max, $H_max, $rep_Dst, $img_Dst, $rep_Src, $img_Src) 
{
	$condition = 0;
	// Si certains parametres ont pour valeur '' :
	if ($rep_Dst == '') { $rep_Dst = $rep_Src; } // (meme repertoire)
	if ($img_Dst == '') { $img_Dst = $img_Src; } // (meme nom)
 	// si le fichier existe dans le répertoire, on continue...
	if (file_exists($rep_Src.$img_Src) && ($W_max!=0 || $H_max!=0)) 
	{
		// extensions acceptees : 
		$FILE_EXTENSION_PHOTO = '" jpg jpeg"'; // (l espace avant jpg est important)
		// extension fichier Source
		$tabimage = explode('.',$img_Src);
		$extension = $tabimage[sizeof($tabimage)-1]; // dernier element
		$extension = strtolower($extension); // on met en minuscule
		// extension OK ? on continue ...
		if (strpos($FILE_EXTENSION_PHOTO,$extension) != '') 
		{
			// recuperation des dimensions de l image Src
			$img_size = getimagesize($rep_Src.$img_Src);
			$W_Src = $img_size[0]; // largeur
			$H_Src = $img_size[1]; // hauteur
			// A- LARGEUR ET HAUTEUR maxi fixes
			if ($W_max != 0 && $H_max != 0) 
			{
				$ratiox = $W_Src / $W_max; // ratio en largeur
				$ratioy = $H_Src / $H_max; // ratio en hauteur
				$ratio = max($ratiox,$ratioy); // le plus grand
				$W = $W_Src/$ratio;
				$H = $H_Src/$ratio;   
				$condition = ($W_Src > $W) || ($H_Src > $H); // 1 si vrai (true)
			}
			// B- HAUTEUR maxi fixe
			if ($W_max == 0 && $H_max != 0) 
			{
				$H = $H_max;
				$W = $H * ($W_Src / $H_Src);
				$condition = ($H_Src > $H_max); // 1 si vrai (true)
			}
			// C- LARGEUR maxi fixe
			if ($W_max != 0 && $H_max == 0) 
			{
				$W = $W_max;
				$H = $W * ($H_Src / $W_Src);         
				$condition = ($W_Src > $W_max); // 1 si vrai (true)
			}
			if ($condition == 1) 
			{
				// creation de la ressource-image "Src" en fonction de l extension
				switch($extension) 
				{
					case 'jpg':
					case 'jpeg':
						$Ress_Src = imagecreatefromjpeg($rep_Src.$img_Src);
					break;
				}
				switch($extension) 
				{
					case 'jpg':
					case 'jpeg':
						$Ress_Dst = imagecreatetruecolor($W,$H);
					break;
				}
				imagecopyresampled($Ress_Dst, $Ress_Src, 0, 0, 0, 0, $W, $H, $W_Src, $H_Src); 
				switch ($extension) 
				{ 
					case 'jpg':
					case 'jpeg':
						imagejpeg ($Ress_Dst, $rep_Dst.$img_Dst);
					break;
				}
				imagedestroy ($Ress_Src);
				imagedestroy ($Ress_Dst);
			}
        }
	}
	// si le fichier a bien ete cree
	if ($condition == 1 && file_exists($rep_Dst.$img_Dst)) 
	{ 
		return true; 
	}
	else 
	{ 
		return false; 
	}
}
?>