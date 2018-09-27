<section class="container blanc">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header>	
				<h1 class="h2">Vérification des photos</h1>
			</header>					
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<form>
				<div class="form-inline">
					<label for="choix">Observatoire</label>
					<select id="choix" class="form-control ml-2">
						<option value="NR" name="theme">--choisir--</option>
						<?php
						foreach($menuobservatoire as $n)
						{
							?>
							<option value="<?php echo $n['nomvar'];?>" name="theme"><?php echo $n['nom'];?></option>
							<?php
						}
						?>
					</select>				
				</div>
			</form>
			<p class="mt-2">
				Au fil des suppression/ajout il est possible d'avoir des incohérences dans les numéros d'ordre des photos. Cette page permet de repérer ces incohérences.<br />
				- Pour chaque taxon ayant au moins une photo sur le site, il faut que l'une d'entre elle est le numéro 1. Par défaut le numéro 1 est attribué à la première photo téléchargée<br />
				- Chaque photo téléchargée par la suite pour la même espèce est attribuée d'un numéro d'ordre incrémenté de 1.<br />
				Si des incohérences sont trouvées vous pouvez :<br />
				-> Soit en cliquant sur <i class="fa fa-pencil text-warning"></i> accéder à la page "Gestion des photos" de l'espèce et corriger manuellement.<br />
				-> Soit en cliquant sur <i class="fa fa-check text-warning"></i>, lancer une correction automatique ; Les numéros seront rétablis en fonction des dates d'insertion des photos. 
			</p>
			<div id="verif" class="mt-3">				
			</div>
		</div>
	</div>
</section>