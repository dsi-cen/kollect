<?php
if($_SESSION['droits'] >= 2 || isset($_SESSION['virtuel']))
{
	?>	
	<!DOCTYPE html>
	<html lang="fr">
		<head>
			<meta charset="utf-8">
			<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
			<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
			<meta http-equiv="x-ua-compatible" content="ie=edge">
			<meta name="author" content="Denis Vandromme">
			<meta name="robots" content="noindex,nofollow">
			<title><?= $titre ?></title>
			<link href="../dist/css/gestion.css" rel="stylesheet">
			<?php echo $css;?>
			<?php if(isset($scripthaut)){echo $scripthaut;}?>
		</head>
		<body class="fond">
		<?php include 'global/menu.php';
}
else
{
	header('location:../index.php');
}	
?>