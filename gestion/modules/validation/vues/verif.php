<section class="container-fluid blanc">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header>	
				<h1 class="h2">Vérification</h1>
			</header>					
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<p>
				Vous pouvez effectuer une recherche de données pouvant être "incohérentes" en selectionnant un stade et un nombre de décades minimum séparant les observations<br />
			</p>
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
			<div id="verif" class="mt-3">
				<p>Commencer par indiquer un nombre assez élevé (en général supérieur à 10), puis affiner en baissant ce chiffre.</p>
				<form>
					<div class="form-inline">
						<label for="stade">Choisir un stade</label>
						<select id="stade" class="form-control ml-2"></select>
						<label for="dec" class="ml-3 mr-2">Un nombre de décade</label>
						<input type="number" class="form-control" min="0" max="36" id="dec" pattern="^\d*">
						<button type="button" class="ml-3 btn btn-success" id="BttV">Valider</button>
					</div>
				</form>
			</div>
			<div id="liste" class="mt-3"></div>
		</div>
	</div>
</section>