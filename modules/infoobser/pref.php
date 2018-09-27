<?php 
$script = '<script src="js/jquery.js" defer></script>
<script src="js/bootstrap.min.js" defer></script>
<script src="js/webobs.js" defer></script>
<script src="js/social.js" defer></script>';
$css = '';

if(isset($_GET['idobser'])) 
{
    $titre = 'à faire';
    $description = 'à faire';
	include CHEMIN_VUE.'pref.php';
	
}
?>