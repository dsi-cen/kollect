<div class="ml-3 pt-3">
	<a href="index.php" class="color1" title="accueil"><i class="fe-webobs3 fa-4x"></i></a>
</div>
<section class="login-container">
    <div class="row">
        <div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<h1>Se connecter au site</h1>
					<p>Saisissez et accédez à vos données, visualisez et modifier votre profil, ajoutez des photos. </p>
				</div>
                <div class="card-body">
					<?php
					if ($ok != 'oui')
					{
						?>
						<form action="#" id="loginForm" method="post">
							<fieldset class="form-group">
								<label for="mail">Adresse mail</label>
								<input type="text" placeholder="Adresse mail" title="Entrez votre adresse mail" required="" name="mail" id="mail" class="form-control">
							</fieldset>
							<fieldset class="form-group">
								<label for="mdp">Mot de passe</label>
								<input type="password" title="Entrez votre mot de passe" placeholder="******" required="" name="mdp" id="mdp" class="form-control">
							</fieldset>
							<fieldset class="form-group">
								<a href="index.php?module=connexion&amp;action=mdpoubli">Mot de passe oublié ?</a>
							</fieldset>
							<fieldset class="form-group">
								<label class="c-input c-checkbox">
									<input type="checkbox" name="case">
									<span class="c-indicator"></span>
									Se souvenir de moi
								</label>
							</fieldset>
							<button type="submit" class="btn btn-success btn-block">Connexion</button>
							<a class="btn btn-secondary btn-block" href="index.php?module=connexion&amp;action=inscription">S'inscrire sur le site</a>
						</form>
						<?php
						if (isset ($saisies))
						{
							?><p>Pour des données ponctuels, une fiche de saisie simplifié est <a href="index.php?module=saisie&amp;action=saisies">disponible</a> et nécessite pas d'être inscrit sur le site.</p><?php
						}
						?>
						<?php echo $message;?>
						<?php
					}
					?>
                </div>
            </div>
        </div>
    </div>    
</section>
<script>
document.getElementById('mail').focus();
</script>