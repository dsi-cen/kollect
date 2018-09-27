<?php
function fctcropimage($W_fin, $H_fin, $rep_Dst, $img_Dst, $rep_Src, $img_Src) 
{
	$condition = 0;
	if ($rep_Dst == '') {$rep_Dst = $rep_Src;} // (même répertoire)
	if ($img_Dst == '') {$img_Dst = $img_Src;} // (même nom)
	if (file_exists($rep_Src.$img_Src)) 
	{ 
		$extension_Allowed = 'jpg,jpeg';	// (sans espaces)
		$extension_Src = strtolower(pathinfo($img_Src,PATHINFO_EXTENSION));
		if(in_array($extension_Src, explode(',', $extension_Allowed))) 
		{
			$img_size = getimagesize($rep_Src.$img_Src);
			$W_Src = $img_size[0]; // largeur
			$H_Src = $img_size[1]; // hauteur
			// D- crop "carre" a la plus petite dimension de l'image source
			if ($W_fin==0 && $H_fin==0) 
			{
				if ($W_Src >= $H_Src) 
				{
					$W = $H_Src;
					$H = $H_Src;
				} 
				else 
				{
					$W = $W_Src;
					$H = $W_Src;
				}   
			}
			switch($extension_Src) 
			{
				case 'jpg':
				case 'jpeg':
					$Ress_Src = imagecreatefromjpeg($rep_Src.$img_Src);
				break;
			}
            switch($extension_Src) 
			{
				case 'jpg':
				case 'jpeg':
					$Ress_Dst = imagecreatetruecolor($W,$H);
					// fond blanc
					$blanc = imagecolorallocate ($Ress_Dst, 255, 255, 255);
					imagefill ($Ress_Dst, 0, 0, $blanc);
				break;      
			}
            if ($W_fin==0) 
			{
				if ($H_fin==0 && $W_Src < $H_Src) 
				{
					$X_Src = 0;
					$X_Dst = 0;
					$W_copy = $W_Src;
				 } 
				 else 
				 {
					$X_Src = 0;
					$X_Dst = ($W - $W_Src) /2;
					$W_copy = $W_Src;
				 }
			} 
			else 
			{
				 if ($W_Src > $W) 
				 {
					$X_Src = ($W_Src - $W) /2;
					$X_Dst = 0;
					$W_copy = $W;
				 } 
				 else 
				 {
					$X_Src = 0;
					$X_Dst = ($W - $W_Src) /2;
					$W_copy = $W_Src;
				 }
			}
            if ($H_fin==0) 
			{
				 if ($W_fin==0 && $H_Src < $W_Src) 
				 {
					$Y_Src = 0;
					$Y_Dst = 0;
					$H_copy = $H_Src;
				 } 
				 else 
				 {
					$Y_Src = 0;
					$Y_Dst = ($H - $H_Src) /2;
					$H_copy = $H_Src;
				 }
			} 
			else 
			{
				 if ($H_Src > $H) 
				 {
					$Y_Src = ($H_Src - $H) /2;
					$Y_Dst = 0;
					$H_copy = $H;
				 } 
				 else 
				 {
					$Y_Src = 0;
					$Y_Dst = ($H - $H_Src) /2;
					$H_copy = $H_Src;
				 }
			}
		  	imagecopyresampled($Ress_Dst,$Ress_Src,$X_Dst,$Y_Dst,$X_Src,$Y_Src,$W_copy,$H_copy,$W_copy,$H_copy);
			switch ($extension_Src) 
			{ 
				case 'jpg':
				case 'jpeg':
					imagejpeg ($Ress_Dst, $rep_Dst.$img_Dst);
				break;				
			}
            imagedestroy ($Ress_Src);
			imagedestroy ($Ress_Dst);
			$condition = 1;
		}
	}
	if ($condition == 1 && file_exists($rep_Dst.$img_Dst)) { return true; }
	else { return false; }	
}
function fctredimimage($W_max, $H_max, $rep_Dst, $img_Dst, $rep_Src, $img_Src) 
{
	$condition = 0;
	if ($rep_Dst=='') { $rep_Dst = $rep_Src; }
	if ($img_Dst=='') { $img_Dst = $img_Src; }
	if (file_exists($rep_Src.$img_Src) && ($W_max!=0 || $H_max!=0)) 
	{ 
		$extension_Allowed = 'jpg,jpeg';
		$extension_Src = strtolower(pathinfo($img_Src,PATHINFO_EXTENSION));
		if(in_array($extension_Src, explode(',', $extension_Allowed))) 
		{
			$img_size = getimagesize($rep_Src.$img_Src);
			$W_Src = $img_size[0];
			$H_Src = $img_size[1];
            if ($W_max!=0 && $H_max==0) 
			{
				$W = $W_max;
				$H = $W * ($H_Src / $W_Src);         
				$condition = ($W_Src > $W_max); 
			}
            if ($condition==1) 
			{
				switch($extension_Src) 
				{
					case 'jpg':
					case 'jpeg':
						$Ress_Src = imagecreatefromjpeg($rep_Src.$img_Src);
					break;
				}
				switch($extension_Src) 
				{
					case 'jpg':
					case 'jpeg':
						$Ress_Dst = imagecreatetruecolor($W,$H);
					break;
				}
				imagecopyresampled($Ress_Dst, $Ress_Src, 0, 0, 0, 0, $W, $H, $W_Src, $H_Src); 
				switch ($extension_Src) 
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
	if ($condition==1 && file_exists($rep_Dst.$img_Dst)) { return true; }
	else { return false; }
}
if (isset($_FILES['file']['name']))
{
	$idm = $_POST['idm'];
	$prenom = $_POST['prenom'];
	if($_FILES['file']['size'] > 512000)
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Fichier trop lourd. (il doit faire au maximun 500 ko).</p></dv>';
		echo json_encode($retour);	
		exit;
	}
	$imageSource = $_FILES['file']['name'];
	$repSource = $_FILES['file']['tmp_name'];
	$imageDest = $prenom.$idm.'.jpg';
		
	$destination = '../../../photo/avatar/tmp/' . $imageSource; 
	$location = $_FILES['file']['tmp_name'];
	$ok = move_uploaded_file($repSource, $destination);
	
	if ($ok == true)
	{
		$repSource = '../../../photo/avatar/tmp/';
		$repDest = '../../../photo/avatar/tmp/';
		$redim = fctcropimage(0,0,$repDest,$imageDest,$repSource,$imageSource);
		$repDest = '../../../photo/avatar/';
		$redim = fctredimimage(36,0,$repDest,'',$repSource,$imageDest);
		if ($redim == true) 
		{ 
			$retour['statut'] = 'Oui';
			$retour['mes'] = '<div class="alert alert-success" role="alert">Votre avatar a bien été téléchargé et redimensionné.</dv>';
			unlink('../../../photo/avatar/tmp/'.$imageDest);
		}
		else
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur ! Problème lors du redimensionnement.</dv>';
		}
		unlink('../../../photo/avatar/tmp/'.$imageSource);		
	}	
	else
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur ! fichier non téléchargé.</dv>';
	}		
}
else
{
	$retour['statut'] = 'Non';	
}
echo json_encode($retour);