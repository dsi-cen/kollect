<section class="container-fluid">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<h1 class="h2">Recherche sur les <?php echo $nomd;?></h1>				
			</div>
		</div>		
	</header>
	<div class="row mt-2">
		<div class="col-md-6 col-lg-6">
			<div class="card card-body">
				<h2 class="h3">Espèce</h2>	
				<p class="mt-2">La recherche peut se faire sur le nom latin ou français, avec le nom complet ou seulement une partie</p>
				<form class="form-inline" id="brtaxnomobserva">
					<input type="text" placeholder="Rechercher par mot" class="form-control mr-1" id="rtaxnomobserva" size="40">
					<button class="btn color1_bg" type="submit"><i class="fa fa-search blanc"></i></button>
				</form>	
				<p class="mt-2">Vous pouvez aussi rechercher une espèce par autocomplémentation</p>
				<form class="form-inline">
					<input type="text" placeholder="Rechercher une espèce" class="form-control" id="rtaxobserva" size="40">
				</form>
			</div>
		</div>
		<div class="col-md-6 col-lg-6">
			<div class="card card-body">
				<h2 class="h3">Commune</h2>
				<p class="mt-2">Rechercher une commune par autocomplétion</p>
				<form class="form-inline">
					<input type="text" placeholder="Rechercher une commune" class="form-control" id="rcomobserva" size="40">
				</form>
				<input id="dep" type="hidden" value="<?php echo $dep;?>"/>
			</div>
		</div>
	</div>
</section>