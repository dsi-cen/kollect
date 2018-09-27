<section class="container blanc">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header>	
				<h1>Critère requis pour les validations manuelle</h1>
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
			<div id="liste" class="mt-3"></div>
		</div>
	</div>
	<input id="observa" type="hidden"/>
</section>
<div id="dia1" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Modifier les critères</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<form>
							<fieldset>
								<div class="form-inline">
									<label for="cstade" class="">Stade</label>
									<select class="ml-2 form-control" id="cstade"></select>
								</div>
							</fieldset>
							<fieldset class="mt-3">
								<div class="form-inline">
									<label for="photo" class="">photo requise</label>
									<select class="ml-2 form-control" id="photo">
										<option value="Non">Non</option>
										<option value="Oui">Oui</option>
									</select>
									<label for="son" class="ml-3">Son requis</label>
									<select class="ml-2 form-control" id="son">
										<option value="Non">Non</option>
										<option value="Oui">Oui</option>
									</select>									
								</div>
							</fieldset>
							<fieldset class="mt-3">
								<div class="form-inline">
									<label for="loupe" class="">Examen en main / avec loupe</label>
									<select class="ml-2 form-control" id="loupe">
										<option value="Non">Non</option>
										<option value="Oui">Oui</option>
									</select>
								</div>
							</fieldset>
							<fieldset class="mt-3">
								<div class="form-inline">
									<label for="bino" class="">Examen sous binoculaire (genitalia etc..)</label>
									<select class="ml-2 form-control" id="bino">
										<option value="Non">Non</option>
										<option value="Oui">Oui</option>
									</select>
								</div>
							</fieldset>	
						</form>
					</div>
				</div>
				<input id="cdnom" type="hidden"/>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Annuler</button>
				<button type="button" class="btn btn-success" data-dismiss="modal" id="bttdia1">Valider</button>
			</div>
		</div>
	</div>
</div>