<section class="login-container">
	<div class="row">
		<div class="col-md-12 mt-2">
			<div class="card">
				<div class="card-header">
					<h1>Mot de passe oublié</h1>					
				</div>
				<div class="card-body">
					<?php
					if ($ok != 'oui')
					{
						?>	
						<form action="#" method="post">
							<div class="form-group">
								<label for="prenom">Prénom</label>
								<input type="text" class="form-control" id="prenom" name="prenom" required >
							</div>
							<div class="form-group">
								<label for="mail">Votre mail</label>
								<input type="email" class="form-control" id="mail" name="mail" required >
							</div>
							<button type="submit" class="btn btn-success">Valider</button>							
						</form>				
						<p><?php echo $message;?></p>					
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
<script type="text/javascript">document.getElementById('prenom').focus();</script>