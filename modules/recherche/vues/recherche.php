<section class="container-fluid">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<h1 class="h2">Recherche sur le site</h1>				
			</div>
		</div>		
	</header>
	<div class="row mt-2">
		<div class="col-md-6 col-lg-6">
			<div class="card card-body">
				<h2 class="h3">Espèce</h2>	
				<p class="mt-2">La recherche peut se faire sur le nom latin ou français, avec le nom complet ou seulement une partie</p>
				<form class="form-inline" id="brtaxnom">
					<input type="text" placeholder="Rechercher par mot" class="form-control mr-1" size="40" id="rtaxnom">
					<button class="btn color1_bg" type="submit"><i class="fa fa-search blanc"></i></button>				
				</form>	
				<p class="mt-2">Vous pouvez aussi rechercher une espèce par autocomplétion</p>
				<form class="form-inline">
					<input type="text" placeholder="Rechercher une espèce" class="form-control" id="rtax" size="40">
				</form>
			</div>
		</div>
		<div class="col-md-6 col-lg-6">
			<div class="card card-body">
				<h2 class="h3">Commune</h2>
				<p class="mt-2">Rechercher une commune par autocomplétion</p>
				<form class="form-inline">
					<input type="text" placeholder="Rechercher une commune" class="form-control" id="rcom" size="40">
				</form>
				<input id="dep" type="hidden" value="<?php echo $dep;?>"/>
			</div>
		</div>
	</div>
</section>
	