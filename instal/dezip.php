<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="author" content="Denis Vandromme">
		<meta name="robots" content="noindex,nofollow" />
		<title>Pré - Installation</title>
		<link href="../dist/css/gestion.css" rel="stylesheet">
		<style type="text/css">
			body {padding-top: 0px;}
		</style>
	</head>	
    <body>		
		<div class="container">
			<?php
				$filename = 'instal.zip';
				if (file_exists($filename)) {
					//echo 'le fichier existe';
					//exec('unzip install.zip');
					if (exec('unzip instal.zip'))
					{
						echo 'Le fichier a été dézippé';
					}
					else
					{
						echo 'Le fichier ne peut pas être dézippé. Problème d\'extension';
					}
				} else {
					echo 'le fichier existe pas';
				}
				$filename = 'taxref.zip';
				if (file_exists($filename)) {
					if (exec('unzip taxref.zip'))
					{
						echo 'Le fichier a été dézippé';
					}
					else
					{
						echo 'Le fichier ne peut pas être dézippé. Problème d\'extension';
					}
				} else {
					echo 'le fichier existe pas';
				}
			?>
		</div>
	</body>
</html>