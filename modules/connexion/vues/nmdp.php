<section class="login-container">
	<div class="row">
		<div class="col-md-12 mt-2">
			<div class="card">
				<div class="card-header">
					<h1>Redéfinir votre mot de passe</h1>					
				</div>
				<div class="card-body">
					<?php
					if ($ok == 'oui')
					{
						?>
						<form action="" method="post">
							<div class="form-group">
								<label for="mdp">Nouveau mot de passe</label>
								<input type="password" class="form-control" id="mdp" name="mdp" required>
							</div>
							<div class="form-group">
								<label for="mdp1">Retapez votre mot de passe</label>
								<input type="password" class="form-control" id="mdp1" name="mdp1" required>
							</div>
							<button type="submit" class="btn btn-success">Valider</button>										
						</form>
						<?php
						echo $message;
					}
					else
					{
						echo $message;
					}
					?>
				</div>
			</div>
		</div>
	</div>
</section>	