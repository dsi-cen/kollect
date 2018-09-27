<section class="container blanc">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header>	
				<h1>Vérification cohérence des listes</h1>
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
		</div>
	</div>
	<div class="row mt-3" id="ok">
		<div class="col-md-6 col-lg-6">
			<div id= "mes"></div>
			<div id="nonexist"></div>
		</div>
		<div class="col-md-6 col-lg-6">
			<div id="nbspobs"></div>
			<div id="listeoui"></div>
		</div>
	</div>
</section>