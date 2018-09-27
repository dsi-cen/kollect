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
					?>
					<ul>
						<?php
						foreach($fam as $n)
						{
							?><li><?php echo $n;?></li><?php	
						}
						foreach($tabr as $n)
						{
							?><li><?php echo $n;?></li><?php						
						}
						?>
					</ul>
					<?php
				}
				if(isset($photo))
				{
					?>
					<ul>
						<?php
						foreach($photo as $n)
						{
							?><li><?php echo $n;?></li><?php						
						}
						?>
					</ul>
					<?php
				}
				if(isset($tabbota))
				{
					?>
					<p>Plantes liées (plantes hôtes ou butinée)</p>
					<ul>
						<?php
						foreach($tabbota as $n)
						{
							?><li><?php echo $n;?></li><?php						
						}
						?>
					</ul>
					<?php					
				}
				if(isset($tabobsbota))
				{
					?>
					<p>Espèces liées</p>
					<ul>
						<?php
						foreach($tabobsbota as $n)
						{
							?><li><?php echo $n;?></li><?php						
						}
						?>
					</ul>
					<?php					
				}
				?>	
			</div>
		</div>
		<div class="col-md-6 col-lg-6">
			<div class="card card-body">
				<h2 class="h3">Espèce</h2>	
				<p>La recherche peut se faire sur le nom latin ou français, avec le nom complet ou seulement une partie</p>
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
	</div>
</section>
	