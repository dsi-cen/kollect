<section class="container blanc">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header>	
				<h1>Gestion des catégories</h1>
			</header>		
			<?php
			if ($nbobservatoire == 0)
			{
				?><p class="text-warning">Aucun observatoire pour l'instant sur le site</p><?php
			}
			else
			{
				?><p>Vous avez <?php echo ''.$nbobservatoire.' '.$libnbobser.'';?> à gérer.</p><?php
			}
			?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 col-lg-12">
			<p>
				Vous pouvez "catégoriser" les familles de votre observatoire. Ceci permet de regrouper plusieurs familles (et donc taxons) ensemble.<br />
				Exemple pour un observatoire sur les lépidoptères, vous pourriez avoir : Rhopalocères (papillons de jours), Hétérocères (papillons de nuit)<br />
				Sur les oiseaux : Rapaces, Passereaux, etc..<br />
				Si vous faite le choix d'utiliser les catégories, toutes les familles de l'observatoire doivent être affectées à une catégorie.
			</p>
		</div>
	</div>
	<br />
	<div class="row">
		<div class="col-md-12 col-lg-12">				
			<form class="form">
				<div class="form-group row">
					<label for="theme" class="col-sm-1 col-form-label">Observatoire</label>
					<div class="col-sm-3">
						<select id="choix" class="form-control">
							<option value="NR" name="theme">--choisir--</option>
							<?php
							foreach ($menuobservatoire as $n)
							{
								?>
								<option value="<?php echo $n['nomvar'];?>" name="theme"><?php echo $n['nom'];?></option>
								<?php
							}
							?>
						</select>
					</div>
				</div>
			</form>	
			<hr />
			<div id="valajax"><progress></progress></div><div id="mes"></div>
		</div>		
	</div>
	<div class="row">
		<div class="col-md-6 col-lg-6">
			<div id="tabfam"></div>
		</div>
		<div class="col-md-6 col-lg-6">
			<div id="tabcat"></div>
			<button type="button" class="btn btn-success" id="BttC">Créer une catégorie</button>
			<hr />
			<form id="crecat" class="form">
				<p>Ex : identifiant : RA, Nom : Rapaces</p> 
				<div class="form-group row">
					<label for="idcat" class="col-sm-3 col-form-label">Identifiant</label>
					<div class="col-sm-3"><input type="text" class="form-control input-sm" id="idcat" placeholder="3 lettres max"></div>
				</div>
				<div class="form-group row">
					<label for="libcat" class="col-sm-3 col-form-label">Nom catégorie</label>
					<div class="col-sm-8"><input type="text" class="form-control input-sm" id="libcat"></div>
				</div>
				<div class="form-group row">
					<div class="col-sm-offset-3 col-sm-5">
						<button type="button" class="btn btn-success" id="BttV">Valider</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</section>