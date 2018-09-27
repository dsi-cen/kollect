<?php
$scripthaut = '<script src="../dist/js/jquery.js"></script>';
$script = '<script src="../dist/js/bootstrap.min.js" defer></script>
<script type="text/javascript" src="../dist/js/jquery.dataTables.min.js" defer></script>';
$css = '<link rel="stylesheet" type="text/css" href="../dist/css/dataTables.bootstrap4.css">';
$titre = 'Bilan photo - '.$nomd;
$description = 'Bilan des photos de '.$nomd.' '.$rjson_obser['titre']; 

include CHEMIN_MODELE.'photo.php';

$nbespece = nbespece($nomvar);
$nbphoto = nbphoto($nomvar);
$nbespecep = nbespecep($nomvar);

$libnbphoto = ($nbphoto > 1) ? $nbphoto.' photos' : $nbphoto.' photo';
$libnbsp = ($nbespecep > 1) ? $nbespecep.' espèces' : $nbespecep.' espèce';
$sanphoto = $nbespece - $nbespecep;
$libsansp = ($sanphoto > 1) ? $sanphoto.' espèces' : $sanphoto.' espèce';
$pcent = round($sanphoto / $nbespece * 100,2);

if($nbphoto > 0)
{
	$photo = listephoto($nomvar);
}
if($sanphoto > 0)
{
	$sphoto = sansphoto($nomvar);
}

include CHEMIN_VUE.'bilan.php';