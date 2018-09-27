<?php 
$script = '<script src="js/jquery.js" defer></script>
<script src="js/bootstrap.min.js" defer></script>
<script src="js/webobs.js" defer></script>
<script src="js/social.js" defer></script>';
$css = '';

if(isset($_GET['idobser']) && isset($_SESSION['idmembre'])) 
{
    include CHEMIN_MODELE.'infoobser.php';

    $idobser = htmlspecialchars($_GET['idobser']);
	$idm = $_SESSION['idmembre'];
	
	$observateur = cherche_observateur($idobser);
	
    $titre = $observateur['prenom'].' '.$observateur['nom'].' (abonnements)';
    $description = 'Abonnements de '.$titre.' du site '.$rjson_site['titre'];
	
	//récupération de l'avatar si existe
	$cheminavatar = 'photo/avatar/'.$observateur['prenom'].''.$observateur['idm'].'.jpg';
	$favatar = (file_exists($cheminavatar)) ? '<img src="photo/avatar/'.$observateur['prenom'].''.$observateur['idm'].'.jpg" width=36 height=36 alt="" class="img-circle"/>' : '<img src="photo/avatar/usera.jpg" width=36 height=36 alt="" class="img-circle"/>';
		
	//Comparaison d'ID
	$idcompare = chercheid($idobser);
	$idcompare = $idcompare['idm'];
		
	//recherche  ABONNES à la page
	$abo = chercheabo($idcompare);
	// Compte le NOMBRE D'ABONNEMENT de la page
	$folo = cherchefolo($idcompare);	

	//LISTE DES ABONNEment
	if($folo > 0) //si abonnement on charge la liste
	{
		$liste = rechercheabonnent($idcompare);
		foreach ($liste as $n)
		{
			$cheminavatar = 'photo/avatar/'.$n['prenom'].''.$n['idm'].'.jpg';
			$abavatar = (file_exists($cheminavatar)) ? $cheminavatar : 'photo/avatar/usera.jpg';
			$tababonnement[] = array('avatar'=>$abavatar, 'idobser'=>$n['idobser'], 'nom'=>$n['nom'], 'prenom'=>$n['prenom'], 'id'=>$n['id']);
		}
	}
	//LISTE DES ABONNES
	if($abo > 0) //si abonnement on charge la liste
	{
		$liste = rechercheabonnes($idcompare);
		foreach ($liste as $n)
		{
			$cheminavatar = 'photo/avatar/'.$n['prenom'].''.$n['idm'].'.jpg';
			$abavatar = (file_exists($cheminavatar)) ? $cheminavatar : 'photo/avatar/usera.jpg';
			$tababonne[] = array('avatar'=>$abavatar, 'idobser'=>$n['idobser'], 'nom'=>$n['nom'], 'prenom'=>$n['prenom']);
		}
	}	
	
	/*$listeabonnent = '<div class="row">';

	foreach ($liste as $n)
	{
		$abovatar = 'photo/avatar/'.$n['prenom'].''.$n['id_obser'].'.jpg';

		if (file_exists($abovatar))
		{
			$abovatar = 'photo/avatar/'.$n['prenom'].''.$n['id_obser'].'.jpg';
			$abovatar = '<img class="media-object img-circle" src="'.$abovatar.'" width=30 height=30 alt="">';
		}
		else
		{
			$abovatar = 'photo/avatar/usera.jpg';   
			$abovatar = '<img class="media-object img-circle" src="'.$abovatar.'" width=30 height=30 alt="">';
		}
		$listeabonnent .='<div class="col-md-2 col-lg-2">'.$abovatar.'</div><div class="col-md-6 col-lg-6"><a href="index.php?module=infoobser&action=info&idobser='.$n['id_obser'].'"><p>'.$n['prenom'].' '.$n['nom'].'</p></a></div><div class="col-md-4 col-lg-4"><i class="fa fa-trash fa-fw text-danger" ></i></div>';        
	}
	$listeabonnent .= '</div>';
	
	//LISTE DES ABONNES
	$liste = rechercheabonnes($idsession);
	$listeabonnes = '<div class="row">';

	foreach ($liste as $n)
	{
		$abovatar = 'photo/avatar/'.$n['prenom'].''.$n['id_obser'].'.jpg';

		if (file_exists($abovatar))
		{
			$abovatar = 'photo/avatar/'.$n['prenom'].''.$n['id_obser'].'.jpg';
			$abovatar = '<img class="media-object img-circle" src="'.$abovatar.'" width=30 height=30 alt="">';
		}
		else
		{
			$abovatar = 'photo/avatar/usera.jpg';   
			$abovatar = '<img class="media-object img-circle" src="'.$abovatar.'" width=30 height=30 alt="">';
		}
		$listeabonnes .='<div class="col-md-2 col-lg-2">'.$abovatar.'</div><div class="col-md-6 col-lg-6"><a href="index.php?module=infoobser&action=info&idobser='.$n['id_obser'].'"><p>'.$n['prenom'].' '.$n['nom'].'</p></a></div><div class="col-md-4 col-lg-4"><i class="fa fa-trash fa-fw text-danger" ></i></div>';        
	}
	$listeabonnes .= '</div>';
	
	*/
	include CHEMIN_VUE.'infoabo.php';	
}
else
{
	header('location:index.php');
}
?>