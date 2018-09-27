<div class="ml-1 pt-1">
	<a href="index.php" class="color1" title="accueil"><i class="fe-webobs3 fa-4x"></i></a>
</div>
<section class="login-container">
	<div class="row">
        <div class="col-md-12">
            <div class="card">
				<div class="card-header">
					<h1>Inscription au site</h1>
				</div>
                <div class="card-body">
					<?php
					if ($ok != 'oui')
					{
						?>
						<form action="#" id="loginForm" method="post">
							<div class="form-group">
								<label class="control-label" for="nom">Nom</label>
								<input type="text" placeholder="Nom" title="Entrez votre nom" required="" name="nom" id="nom" class="form-control">
							</div>
							<div class="form-group">
								<label class="control-label" for="prenom">Prénom</label>
								<input type="text" placeholder="Prénom" title="Entrez votre prénom" required="" name="prenom" id="prenom" class="form-control">
							</div>
							<div class="form-group">
								<label class="control-label" for="mail">Votre mail</label>
								<input type="email" placeholder="Email" title="Entrez votre mail" required="" name="mail" id="mail" class="form-control">
							</div>
							<div class="form-group">
								<label class="control-label" for="mdp">Mot de passe</label>
								<input type="password" title="Entrez votre mot de passe" placeholder="******" required="" name="mdp" id="mdp" class="form-control">
							</div>
							<div class="form-group">
								<label class="control-label" for="mdp1">Retapez votre mot de passe</label>
								<input type="password" placeholder="******" required="" name="mdp1" id="mdp1" class="form-control">
							</div>
							<p>
								<a href="index.php?module=cgu&amp;action=cgu" target="_blank">Lire les conditions d'utilisation</a>
							</p>
							<div class="form-group row">
								<div class="col-sm-10">
									<div class="form-check">
										<label class="form-check-label">
											<input class="form-check-input" type="checkbox" id="cgu" name="cgu"> J'ai pris connaissance des conditions d'utilisation
										</label>
									</div>
								</div>
							</div>
							<button type="submit" id="bt" class="btn btn-success btn-block">Valider</button>
						</form>
						<?php echo $message;?>
						<?php
					}
					else
					{
						?><div class="alert alert-success" role="alert"><p><?php echo $message;?></p></div><?php
					}
					?>
				</div>
			</div>
		</div>
	</div>	
</section>
<script>
$(document).ready(function() {
	'use strict';
	$('#bt').hide(); 
});
$('#cgu').change(function() { 
	'use strict'; 
	if ($('#cgu').is(':checked')) { $('#bt').show(); } else { $('#bt').hide(); }
});
document.getElementById('nom').focus();
</script>		