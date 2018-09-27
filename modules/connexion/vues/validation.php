<section class="container">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<h1 class="h2">Validation de votre inscription</h1>
			</div>
		</div>
	</header>
	<div class="row mt-2">
		<div class="col-sm-12 col-md-6 col-lg-6">
			<div class="card card-body">
				<?php
				if ($ok == 'oui')
				{
					echo $message;
					?><a href="index.php?module=connexion&amp;action=connexion&amp;s=a">Vous pouvez vous connecter</a><?php	
				}
				else
				{
					echo $message;
				}
				?>
			</div>
		</div>
	</div>
</section>	