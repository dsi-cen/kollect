<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="author" content="Denis Vandromme">
		<meta name="robots" content="noindex,nofollow" />
		<title>Installation 4</title>
		<link href="../dist/css/gestion.css" rel="stylesheet">
		<style type="text/css">
			body {padding-top: 0px;}
			.checkbox label{font-weight: 600;}
		</style>
	</head>
	<body>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Connexion à la base, création de l'application</a></li>
			<li class="breadcrumb-item"><a href="#">Emprise du site</a></li>
			<li class="breadcrumb-item"><a href="#">Vérification et maillage</a></li>
			<li class="breadcrumb-item active">Configuration du site</li>
		</ol>
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-lg-12">
					<header>
						<h1 class="">Installation de l'application</h1>
						<hr />
					</header>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 col-lg-12">
					<h2>Configuration du site</h2>
					<p>Fichier site.json dans répertoire "json"</p>
					<form class="form" id="site">						
						<div class="form-group row">
							<label for="mail" class="col-sm-1 col-form-label">email</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="mail">
								<span class="text-muted">Adresse mail de contact.</span>
							</div>							
						</div>
						<div class="form-group row">
							<label for="titre" class="col-sm-1 col-form-label">Titre du site</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="titre">
								<span class="text-muted">Titre qui apparaîtra aussi dans les moteurs de recherches.</span>
							</div>							
						</div>
						<div class="form-group row">
							<label for="stitre" class="col-sm-1 col-form-label">Sous-Titre</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="stitre">
								<span class="text-muted">Sous titre du site.</span>
							</div>
						</div>
						<div class="form-group row">
							<label for="description" class="col-sm-1 col-form-label">Description</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="description">
								<span class="text-muted">Description qui apparaîtra dans les moteurs de recherche (il est recommandé de pas dépasser 200 caractères).</span>
							</div>
						</div>
						<div class="form-group row">
							<label for="metakey" class="col-sm-1 col-form-label">Mots-clés</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="metakey">
								<span class="text-muted">Mettre les principaux mots-clés séparés par une virgule ex : (Papillons, Oiseaux).</span>
							</div>
						</div>
						<div class="form-group row">
							<label for="lien" class="col-sm-1 col-form-label">Lien</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="lien">
								<span class="text-muted">Si vous avez déjà un site, vous pouvez mettre son lien ici. Autrement laisser vide.</span>
							</div>
						</div>
						<div class="form-group row">
							<div class="offset-sm-1 col-sm-8">
								<div class="checkbox">
									<label><input type="checkbox" id="biblio" value="biblio"> Cocher si vous voulez utiliser le module bibliographie</label><br />
									<label><input type="checkbox" id="actu" value="actu"> Cocher si vous voulez utiliser le système d'actualités pour le site</label><br />
								</div>
							</div>
						</div>
						<div class="form-group row">
							<div class="offset-sm-1 col-sm-8">
								<button type="button" class="btn btn-success" id="BttV">Valider</button>								
							</div>							
						</div>
						<p>Le reste de la configuration (création d'observatoires etc...) se fait en administration</p>						
					</form>
				</div>
			</div>
			<div id="info">
				<?php
				if(!isset($_SESSION['idmembre']))
				{
					$_SESSION['idmembre'] = 1;					
				}
				?>					
				<p class="mt-2">	
						
									
					En cliquant sur le lien vous allez être redirigé vers la partie administration du site.<br />
					Le module d'installation sera supprimé automatiquement.<br /><br />
					<a class="btn btn-success" id="" href="../gestion/" rel="nofollow">-> Administration du site</a>						
				</p>
			</div>			
			<div id="valajax"><progress></progress></div>
			<button type="button" class="btn btn-success" id="BttS">Dernière étape</button>
			<div id="mes"></div>
			<div class="row">
				<div class="col-md-12 col-lg-12 mt-1">
					<div class="progress">
						<div id="av" class="progress-bar bg-success" style="width: 75%" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
				</div>
			</div>			
		</div>
		<script src="../dist/js/jquery.js"></script>  
		<script>
			$(document).ready(function() {
				$('#valajax').hide(); $('#BttS').hide(); $('#info').hide();			
			});
			$('#BttV').click(function(){
				$('#valajax').show();
				var biblio = ($('#biblio').is(':checked')) ? 'oui' : 'non';
				var actu = ($('#actu').is(':checked')) ? 'oui' : 'non';
				var mail = $("#mail").val(), titre = $("#titre").val(), stitre = $("#stitre").val(), descri = $("#description").val(), metakey = $("#metakey").val(), lien = $("#lien").val();
				$.ajax({
					url: 'ajaxconfig.php', type: 'POST', dataType: "json", data: {mail:mail,titre:titre,stitre:stitre,descri:descri,metakey:metakey,lien:lien,biblio:biblio,actu:actu},
					success: function(reponse) {
						var ok = reponse.statut;
						if (ok == 'Oui') {
							$('#mes').html(reponse.mes); $('#BttS').show(); $('#av').css('width', '87%');	
						} else {
							$('#mes').html(reponse.mes);
						}
						$('#valajax').hide();
					}
				});
			});
			$('#BttS').click(function () {
				$('#av').css('width', '100%'); $('#site').hide(); $('#BttS').hide(); $('#info').show();
			});			
		</script>
	</body>
</html>