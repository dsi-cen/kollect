<section class="container-fluid mb-3">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<h1 class="h2">Resultat de votre recherche</h1>				
			</div>
		</div>		
	</header>
	<div class="row mt-2">
		<div class="col-md-6 col-lg-6">
			<div class="card card-body">
				<h2 class="h5"><?php echo $librech;?></h2>	
				<?php
				if($result[0] >= 1)
				{
					?><ul><?php
					foreach($tabr as $n)
					{
						?><li><?php echo $n;?></li><?php						
					}
					?></ul><?php
				}				
				?>				
			</div>
		</div>
		<div class="col-md-6 col-lg-6">
			<div class="card card-body">
				<h2 class="h3">Espèce</h2>	
				<p>La recherche peut se faire sur le nom latin ou français, avec le nom complet ou seulement une partie</p>
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
	</div>
</section>
	