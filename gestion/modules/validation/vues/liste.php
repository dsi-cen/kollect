<section class="container blanc">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header>	
				<h1>Configuration du type de validation</h1>
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
					<div id="btchoix">
						<button type="button" id="btA" class="ml-3 btn btn-success">Que les Auto</button>
						<button type="button" id="btM" class="ml-2 btn btn-success">Que les Manuelle</button>
						<button type="button" id="btNR" class="ml-2 btn btn-success">Que les NV</button>
						<button type="button" id="btmNR" class="ml-2 btn btn-warning">Mettre tous les NV en Auto</button>
					</div>
				</div>
			</form>
			<div id="liste" class="mt-3"></div>
		</div>
	</div>
	<input id="observa" type="hidden"/>
</section>
