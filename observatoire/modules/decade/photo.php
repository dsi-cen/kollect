<?php
$scripthaut = '<script src="../dist/js/jquery.js"></script>';
$script = '<script src="../dist/js/bootstrap.min.js" defer></script>
<script src="../dist/js/popup-image.js" defer></script>
<script src="../dist/js/masonry.js" defer></script>';
$css = '<link rel="stylesheet" href="../dist/css/popup.css" type="text/css">';

function pagination($nbpage,$pageaffiche,$lien)
{
	$prec = $pageaffiche - 1;
	$suiv = $pageaffiche + 1;
	$avdern = $nbpage - 1;
	$adj = 2;
	$listp = '';
	if($nbpage > 1)
	{
		$listp .= '<ul class="pagination">';
		if($pageaffiche == 2)
		{
			$listp .= '<li class="page-item"><a href="'.$lien.'1" class="page-link">&laquo;</a></li>';
		}
		elseif($pageaffiche > 2)
		{
			$listp .= '<li class="page-item"><a href="'.$lien . $prec.'" class="page-link">&laquo;</a></li>';
		}		
		if($nbpage < 7 + ($adj * 2))
		{
			$listp .= ($pageaffiche == 1) ? '<li class="page-item active"><a class="page-link">1</a></li>' : '<li class="page-item"><a href="'.$lien.'1" class="page-link">1</a></li>';
			for($i=2; $i<=$nbpage; $i++)
			{
				$listp .= ($i == $pageaffiche) ? '<li class="page-item active"><a class="page-link">'.$i.'</a></li>' : '<li class="page-item"><a href="'.$lien.''.$i.'" class="page-link">'.$i.'</a></li>';
			}
		}
		else
		{
			if($pageaffiche < 2 + ($adj * 2))
			{
				$listp .= ($pageaffiche == 1) ? '<li class="page-item active"><a class="page-link">1</a></li>' : '<li class="page-item"><a href="'.$lien.'1" class="page-link curseurlien">1</a></li>';
				for($i=2; $i <= 4 + ($adj * 2); $i++)
				{
					$listp .= ($i == $pageaffiche) ? '<li class="page-item active"><a class="page-link">'.$i.'</a></li>' : '<li class="page-item"><a href="'.$lien.''.$i.'" class="page-link">'.$i.'</a></li>';
				}
				$listp .= '<li class="page-item"><span class="page-link">&hellip;</span></li>';
				$listp .= '<li class="page-item"><a href="'.$lien . $avdern.'" class="page-link">'.$avdern.'</a></li>';
				$listp .= '<li class="page-item"><a href="'.$lien . $nbpage.'" class="page-link">'.$nbpage.'</a></li>';
			}
			elseif((($adj * 2) + 1 < $pageaffiche) && ($pageaffiche < $nbpage - ($adj * 2)))
			{
				$listp .= '<li class="page-item"><a href="'.$lien.'1" class="page-link">1</a></li>';
				$listp .= '<li class="page-item"><a href="'.$lien.'2" class="page-link">2</a></li>';
				$listp .= '<li class="page-item"><span class="page-link">&hellip;</span></li>';
				for($i = $pageaffiche - $adj; $i <= $pageaffiche + $adj; $i++) 
				{
					$listp .= ($i == $pageaffiche) ? '<li class="page-item active"><a class="page-link">'.$i.'</a></li>' : '<li class="page-item"><a href="'.$lien . $i.'" class="page-link">'.$i.'</a></li>';
				}
				$listp .= '<li class="page-item"><span class="page-link">&hellip;</span></li>';
				$listp .= '<li class="page-item"><a href="'.$lien . $avdern.'" class="page-link">'.$avdern.'</a></li>';
				$listp .= '<li class="page-item"><a href="'.$lien . $nbpage.'" class="page-link">'.$nbpage.'</a></li>';
			}
			else
			{
				$listp .= '<li class="page-item"><a href="'.$lien.'1" class="page-link">1</a></li>';
				$listp .= '<li class="page-item"><a href="'.$lien.'2" class="page-link">2</a></li>';
				$listp .= '<li class="page-item"><span class="page-link">&hellip;</span></li>';
				for($i = $nbpage - (2 + ($adj * 2)); $i <= $nbpage; $i++)
				{
					$listp .= ($i == $pageaffiche) ? '<li class="page-item active"><a class="page-link">'.$i.'</a></li>' : '<li class="page-item"><a href="'.$lien . $i.'" class="page-link">'.$i.'</a></li>';
				}
			}			
		}
		if($pageaffiche != $nbpage)
		{
			$listp .= '<li class="page-item"><a href="'.$lien . $suiv.'" class="page-link">&raquo;</a></li>';			
		}			
		$listp .= '</ul>';
	}
	return $listp;
}

if(isset($_GET['p'])) 
{
	$page = intval($_GET['p']);
	$cat = 'tous';
	
	if(isset($_GET['jrs']) && !empty($_GET['jrs']) && !empty($_GET['mois'])) 
	{
		$mem = 'oui';
		$DMois = htmlspecialchars($_GET['mois']);
		$j = htmlspecialchars($_GET['jrs']);
		if($DMois == 'Ja') { $CMois = 'Janvier'; $m = 1; } 
		elseif($DMois == 'Fe') { $CMois = 'Février'; $m = 2; }
		elseif($DMois == 'Ma') { $CMois = 'Mars'; $m = 3; }
		elseif($DMois == 'Av') { $CMois = 'Avril'; $m = 4; }
		elseif($DMois == 'M') { $CMois = 'Mai'; $m = 5; }
		elseif($DMois == 'Ju') { $CMois = 'Juin'; $m = 6; }
		elseif($DMois == 'Jl') { $CMois = 'Juillet'; $m = 7; }
		elseif($DMois == 'A') { $CMois = 'Août'; $m = 8; }
		elseif($DMois == 'S') { $CMois = 'Septembre'; $m = 9; }
		elseif($DMois == 'O') { $CMois = 'Octobre'; $m = 10; }
		elseif($DMois == 'N') { $CMois = 'Novembre'; $m = 11; }
		elseif($DMois == 'D') { $CMois = 'Décembre'; $m = 12; }
		unset($_GET['jrs']);
		unset($_GET['mois']);
		$a = date('Y');
		$date = $j.'-'.$m.'-'.$a;
	}
	else
	{
		$date = date('d-m-Y');
		list($j,$m,$a) = explode("-",$date);
		switch($m)
		{
			case 1:$DMois = 'Ja'; $CMois = 'Janvier'; break;
			case 2:$DMois = 'Fe'; $CMois = 'Février'; break;
			case 3:$DMois = 'Ma'; $CMois = 'Mars'; break;
			case 4:$DMois = 'Av'; $CMois = 'Avril'; break;
			case 5:$DMois = 'M'; $CMois = 'Mai'; break;
			case 6:$DMois = 'Ju'; $CMois = 'Juin'; break;
			case 7:$DMois = 'Jl'; $CMois = 'Juillet'; break;
			case 8:$DMois = 'A'; $CMois = 'Août'; break;
			case 9:$DMois = 'S'; $CMois = 'Septembre'; break;
			case 10:$DMois = 'O'; $CMois = 'Octobre'; break;
			case 11:$DMois = 'N'; $CMois = 'Novembre'; break;
			case 12:$DMois = 'D'; $CMois = 'Décembre'; break;
		}
	}
	if($j >= 1 && $j <= 10) { $Djrs = '1'; $dec = 'première'; $dec1 = 'Du 1er au 10'; }
	elseif ($j >= 11 && $j <= 20) { $Djrs = '2'; $dec = 'deuxième'; $dec1 = 'Du 11 au 20'; }
	elseif($j >= 21 && $j <= 31)
	{ 
		$datetime1 = new DateTime($date);
		$dernierjrs = $datetime1->format('t');
		$Djrs = '3'; $dec = 'troisième'; $dec1 = 'Du 21 au '.$dernierjrs; 
	}
	$decade = $DMois . $Djrs;

	$titre = 'Photo '.$nomd.' décade '.$decade.'-'.$page;
	$description = 'Photo des '.$nomd.' '.$rjson_site['ad2'].' '.$rjson_site['lieu'].' durant la '.$dec.' décade de '.$CMois.'';

	include CHEMIN_MODELE.'decade.php';

	if(isset($_GET['cat'])) 
	{
		$cat = htmlspecialchars($_GET['cat']);
	}
	
	//pagination
	$nbsp = ($cat == 'tous') ? nb_sp($nomvar,$decade) : nb_spcat($nomvar,$decade,$cat);
	$nbpage = ceil($nbsp/20);	
	$pageaffiche = ($page > $nbpage) ? $nbpage : $page;
	$debut = ($pageaffiche * 20 - 20);
	$lien = 'index.php?module=decade&amp;action=photo&amp;jrs='.$j.'&amp;mois='.$DMois.'&amp;d='.$nomvar.'&amp;p=';
	$pagination = pagination($nbpage,$pageaffiche,$lien);
	
	$photo = ($cat == 'tous') ? photo($nomvar,$decade,$debut) : photocat($nomvar,$decade,$debut,$cat);	

	include CHEMIN_VUE.'photo.php';
}