<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="author" content="Denis Vandromme">
		<meta name="robots" content="noindex,nofollow" />
		<title>Installation 1</title>
		<link href="../dist/css/gestion.css" rel="stylesheet">
		<style type="text/css">
			body {padding-top: 0px;}
		</style>
	</head>
	<body>
		<ol class="breadcrumb">
			<li class="breadcrumb-item active">Connexion à la base, création de l'application</li>
			<li class="breadcrumb-item"><a href="#">Emprise du site</a></li>
			<li class="breadcrumb-item"><a href="#">Vérification et maillage</a></li>
			<li class="breadcrumb-item"><a href="#">Configuration du site</a></li>
		</ol>
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-lg-12">
					<header>
						<h1 class="text-xs-center">Installation de l'application</h1>
					</header>
					<hr />				
					<form id="base">
						<h2>Vos identifiants</h2>
						<p>Remplir le formulaire avec les informations fournis par votre hébergeur</p>
						<div class="form-group row">
							<label for="dbname" class="col-sm-2 col-form-label">Nom de la base</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="dbname" required>
							</div>
						</div>
						<div class="form-group row">
							<label for="host" class="col-sm-2 col-form-label">Hôte/Serveur</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="host" required>
							</div>
						</div>
						<div class="form-group row">
							<label for="username" class="col-sm-2 col-form-label">Utilisateur/login</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="username" required>
							</div>
						</div>
						<div class="form-group row">
							<label for="password" class="col-sm-2 col-form-label">Mot de passe</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="password" required>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-offset-2 col-sm-5">
								<button type="submit" class="btn btn-success" id="BttV">Valider</button>
							</div>
						</div>
					</form>
					<form id="membre">
						<h2>Inscription</h2>
						<p>Insciption sur le site en tant qu'admin.</p>
						<div class="form-group row">
							<label for="prenom" class="col-sm-2 col-form-label">Prénom</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="prenom" required>
							</div>
						</div>
						<div class="form-group row">
							<label for="nom" class="col-sm-2 col-form-label">Nom</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="nom" onChange="javascript:this.value=this.value.toUpperCase();" required>
							</div>
						</div>
						<div class="form-group row">
							<label for="mdp" class="col-sm-2 col-form-label">Votre mot de passe</label>
							<div class="col-sm-5">
								<input type="password" class="form-control" id="mdp" required>
							</div>
						</div>
						<div class="form-group row">
							<label for="mdp1" class="col-sm-2 col-form-label">Retapez votre mot de passe</label>
							<div class="col-sm-5">
								<input type="password" class="form-control" id="mdp1" required>
							</div>
						</div>
						<div class="form-group row">
							<label for="mail" class="col-sm-2 col-form-label">Votre mail</label>
							<div class="col-sm-5">
								<input type="email" class="form-control" id="mail" required>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-offset-2 col-sm-5">
								<button type="submit" class="btn btn-success" id="BttVm">Valider</button>
							</div>
						</div>
					</form>
					<div id="mes"></div>
					<button type="button" class="btn btn-success mb-2" id="BttB">Installer les tables</button>
					<div id="valajax"><progress></progress></div>
				</div>
			</div>
			<div class="row" id="mestable">
				<div class="col-md-6 col-lg-6 mt-1">
					<div class="progress">
						<div id="barre" class="progress-bar progress-bar-striped bg-success" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 col-lg-12 mt-1">
					<div class="progress">
						<div id="av" class="progress-bar bg-success" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 col-lg-12 mt-2">
					<a class="btn btn-success" id="BttE" href="emprise.php">-> Emprise du site</a>
				</div>
			</div>
		</div>
		<script src="../dist/js/jquery.js"></script> 
		<script>
			$(document).ready(function() {
				$('#BttB').hide();$('#mestable').hide();
				$('#BttE').hide();$('#membre').hide();
				$('#valajax').hide();
			});
			$('#base').on('submit', function() {
				valider();	
				return false; 
			});			
			$('#membre').on('submit', function() {
				membre();
				return false; 
			});
			function membre() {
				$('#valajax').show();
				var nom = $('#nom').val(), prenom = $('#prenom').val(), mdp = $('#mdp').val(), mdp1 = $('#mdp1').val(), mail = $('#mail').val();
				$.ajax({
					url: 'ajaxmembre.php',
					type: 'POST', 
					dataType: "json",
					data: {nom:nom, prenom:prenom, mdp:mdp, mdp1:mdp1, mail:mail},
					success: function(reponse) {
						var ok = reponse.statut;
						if (ok == 'Oui') {
							$('#mes').html(reponse.mes);
							$('#av').css('width', '25%');
							$('#BttE').show();
						} else {
							$('#mes').html(reponse.mes);
						}
						$('#valajax').hide();
					}
				});
			}			
			function valider() {
				$('#valajax').show();
				var dbname = $('#dbname').val();var host = $('#host').val();
				var username = $('#username').val();var password = $('#password').val();
				$.ajax({
					url: 'ajaxbase.php', type: 'POST', dataType: "json", data: {db:dbname, host:host, user:username, pass:password},
					success: function(reponse) {
						var ok = reponse.statut;
						if (ok == 'Oui') {
							$('#base').hide();
							$('#mes').html(reponse.mes);
							$('#BttB').show();
							$('#av').css('width', '10%');
						} else {
							$('#mes').html(reponse.mes);
						}
						$('#valajax').hide();
					}
				});
			}
			$('#BttB').click(function(){
				var finish = false;
				$('#mestable').show();
				$('#valajax').show();
				setInterval(function(){
					if (!finish) {
						$.ajax({
							url: "ajaxattente.php", dataType: "json",
							success: function(reponse) {
								$('#mes').html('<p>Traitement en cours... <br /><span class="text-primary">'+ reponse.mes +'</span></p>');
								$('#barre').css('width', ''+ reponse.b +'%');					
							}
						});
					}
				},2000);			
				$.ajax({
					url: 'ajaxtable.php', dataType: "json", 
					success: function(reponse) {
						var ok = reponse.statut;
						if (ok == 'Oui') {
							finish = true;
							$('#mes').html(reponse.mes);
							$('#BttB').hide();
							$('#membre').show();
							$('#av').css('width', '20%');							
						} else {
							$('#mes').html(reponse.mes);
						}
						$('#mestable').hide();
						$('#valajax').hide();
					}
				});
			});
		</script>
	</body>
</html>