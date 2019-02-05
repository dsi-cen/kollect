<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="description" content="<?= $description?>">
		<meta name="keywords" content="<?= $metakey?>">
		<?php
		if(isset($rjson_site['orga']))
		{
			?><meta name="author" content="<?php echo $rjson_site['orga']['nom'];?> - ObsNat"><?php
		}
		else
		{
			?><meta name="author" content="ObsNat"><?php
		}
		?>	
		<title><?= $titre ?></title>
		<link href="../dist/css/style.css" rel="stylesheet">
		<link href="../dist/css/color.css" rel="stylesheet">
		<!-- Google icons -->
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<link rel="stylesheet" href="../dist/css/google-icons.css">
		<!-- Googles icons -->
		<?php if(isset($scripthaut)){echo $scripthaut;}?>
		<?php echo $css;?>		
	</head>  
	<body class="color4_bg color_body">
		<?php 
		if(isset($pasdemenu))
		{
			?><div id="wrapper-top" class="bg"><?php
		}		
		elseif(isset($sansheader))
		{
			include 'global/menu.php'; 
			?><div id="wrapper-top" class="bg margemenu"><?php
		}
		else
		{
			include 'global/menu.php'; 
			?><div id="wrapper" class="bg observatoire"><?php
		}
		?>	