<?phpif(isset($_SESSION['prenom']) && isset($_SESSION['nom'])){	$titre = 'Maintenance';	$description = 'Site en maintenance.'; 	$script = '<script src="dist/js/jquery.js" defer></script>	<script src="dist/js/bootstrap.min.js" defer></script>';	$css = '';				include CHEMIN_VUE.'maintenance.php';}else{	header('location:index.php?module=connexion&action=connexion&s=o');}