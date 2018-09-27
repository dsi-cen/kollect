<section class="container-fluid mb-3">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<header>
					<h1 class="h2">Vos sites</h1>
				</header>
			</div>
		</div>
	</div>
	<div class="row mt-2">
		<div class="col-md-12 col-lg-12">
			<div class="card card-body">				
				<p>
					Au fil des saisies de données et imports, il est possible qu'un même site soit dédoublé et se retrouve avec des coordonnées différentes. Au moment de la saisie, ces doublons peuvent "polluer" la liste.<br />
					Cette page vous permet de regrouper si besoin ces doublons. Vous pouvez voir uniquement les sites pour lesquels vous avez au moins une donnée.
				</p>
				<div class="row">
					<div class="col-md-6 col-lg-6">				
						<?php
						if($idobser != 'non')
						{
							?>
							<form>
								<h2 class="h6">Sélectionnez un site avec doublons</h2>
								<div class="form-group row">
									<div class="col-sm-6"><input type="text" class="form-control" id="choixsite" placeholder="Chercher un site "></div>
								</div>
								<input id="idobser" type="hidden" value="<?php echo $idobser;?>"/>
							</form>
							<div id="result"></div>
							<div id="mes" class="mt-3"></div>
							<input id="idgarde" type="hidden"/><input id="idsup" type="hidden"/>
							<?php
						}
						else
						{
							?><p>Vous avez aucune donnée</p><?php
						}
						?>
					</div>
					<div class="col-md-6 col-lg-6">
						<div id="mapdetail"></div>
					</div>
				</div>
			</div>
		</div>		
	</div>
</section>