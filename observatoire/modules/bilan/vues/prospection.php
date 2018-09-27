<section class="container-fluid">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<div class="d-flex justify-content-start">
					<h1 class="h2">Aide à la prospection des <?php echo $nomd;?> <?php echo $rjson_site['ad2'];?> <?php echo $rjson_site['lieu'];?></h1>
					<ol class="breadcrumb ml-auto mb-0">
						<li class="breadcrumb-item"><a href="index.php?module=bilan&amp;action=bilan&amp;d=<?php echo $nomvar;?>">Bilan</a></li>
						<li class="breadcrumb-item active">Aide prospection</li>
						<li class="breadcrumb-item"><a href="index.php?module=bilan&amp;action=evolution&amp;d=<?php echo $nomvar;?>">Evolution des données</a></li>
					</ol>
				</div>
			</div>
		</div>		
	</header>
	<div class="row mt-2">
		<div class="col-md-6 col-lg-6">
			<div class="card card-body">
				<h2 class="h5"><span id="titrecarte"><?php echo $titrecarte;?> </span></h2>
				<p>Affichage des mailles ayant au plus : <b><span id="nbaffiche"></span></b> espèces.</p>
				<figure>
					<div id="container" class="cartebilan">
						<div class="mt-2"><p class="text-warning text-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement de la carte...</p></div>
					</div>
					<div class="mt-2" id="slider">
						<p class="curseurlien">
							<span>0&nbsp;&nbsp;&nbsp;&nbsp;</span>
							<input id="sliderControl" type="text" />
							<span id="nbmax"></span>
						</p>
						<p>Nombre d'espèces</p>
					</div>										
				</figure>
			</div>
		</div>
		<div class="col-md-6 col-lg-6">
			<div class="card card-body">
				<div id="carteleaflet" class="cartebilan"></div>
			</div>
		</div>			
	</div>
	<input id="nomvar" type="hidden" value="<?php echo $nomvar;?>"/><input id="choixcarte" type="hidden" value="<?php echo $choixcarte;?>"/><input id="contour2" type="hidden" value="<?php echo $emprise['contour2'];?>"/>
	<input id="emp" type="hidden" value="<?php echo $emprise['emprise'];?>"/><input id="maxcolor" type="hidden" value="<?php echo $maxcolor;?>"/>
</section>