<section class="container-fluid mb-3">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<div class="d-flex justify-content-start">
					<h1 class="h2">Bilan des connaissances</h1>
					<ol class="breadcrumb ml-auto mb-0">
						<li class="breadcrumb-item active">Bilan</li>
						<li class="breadcrumb-item"><a href="index.php?module=bilan&amp;action=evolution">Evolution des données</a></li>
					</ol>					
				</div>
			</div>
		</div>		
	</header>
	<div class="row mt-2">
		<div class="col-md-6 col-lg-6">
			<div class="card card-body">					
				<h2 class="h5"><span id="titrecarte"><?php echo $titrecarte;?> </span></h2>
                <div id="selectiondepartement" class="h6">Sélectionner un département :
                    <select id="iddep">
                    </select>
                </div>
				<figure>
					<div id="container" class="cartebilan">
						<div class="mt-2"><p class="text-warning text-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement de la carte...</p></div>
					</div>
					<div class="row">
						<figcaption class="col-md-7">
							<h3 class="h5">Légende</h3>
							<svg id="legendContainer" class=""></svg>
						</figcaption>
						<div class="col-md-5">
							<h3 class="h5">Choix de la carte</h3>
							<div class="form-check">
								<div class="custom-control custom-radio">
									<input type="radio" name="choixcarte" id="commune" value="<?php echo $value;?>" class="custom-control-input" checked> 
									<label class="custom-control-label" for="commune"><?php echo $cartecom;?></label>
								</div>
							</div>
							<div class="form-check">
								<div class="custom-control custom-radio">
									<input type="radio" name="choixcarte" id="maille" value="maille" class="custom-control-input">
									<label class="custom-control-label" for="maille"><?php echo $cartemaille;?></label>
								</div>
							</div>							
							<?php
							if($cartemaille5 != 'non' && $emprise['utm'] == 'non')
							{
								?>
								<div class="form-check">
									<div class="custom-control custom-radio">
										<input type="radio" name="choixcarte" id="maille5" value="maille5" class="custom-control-input">
										<label class="custom-control-label" for="maille5"><?php echo $cartemaille5;?></label>
									</div>
								</div>																
								<?php
							}
							?>
						</div>
					</div>
				</figure>				
			</div>			
		</div>
		<div class="col-md-6 col-lg-6">
			<div class="card card-body">				
				<h2 class="h5"><span id="titregraph"><?php echo $titregraph;?> </span></h2>
				<div class="row">
					<div class="col-md-6 col-lg-6">
						<figure>
							<div id="graphespece" class="minigraph">
								<div class="mt-2"><p class="text-warning text-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement du graph...</p></div>
							</div>
						</figure>
					</div>
					<div class="col-md-6 col-lg-6">
						<figure>
							<div id="grapheobs" class="minigraph">
								<div class="mt-2"><p class="text-warning text-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement du graph...</p></div>
							</div>
						</figure>
					</div>
				</div>
				<div id="lienid"></div>							
			</div>
			<div class="card card-body mt-2" id="cachemap">
				<div id="mapdetail"></div>
			</div>
		</div>
	</div>	
	<input id="choixcarte" type="hidden" value="<?php echo $choixcarte;?>"/><input id="emp" type="hidden" value="<?php echo $emprise['emprise'];?>"/><input id="utm" type="hidden" value="<?php echo $emprise['utm'];?>"/><input id="contour2" type="hidden" value="<?php echo $emprise['contour2'];?>"/><input id="maxcolor" type="hidden" value="<?php echo $maxcolor;?>"/>
</section>