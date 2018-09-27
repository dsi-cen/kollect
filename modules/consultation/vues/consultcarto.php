<section class="container-fluid mb-3">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<header>
					<?php
					if(isset($_SESSION['idmembre']))
					{
						?>
						<ol class="breadcrumb float-right">
							<li class="breadcrumb-item"><a href="index.php?module=observation&amp;action=observation">Observations</a></li>
							<li class="breadcrumb-item"><a href="index.php?module=consultation&amp;action=consultation" rel="nofollow">Consultation</a></li>
							<li class="breadcrumb-item active">Consultation carto</li>
						</ol>
						<?php
					}
					?>
					<h1 class="h2 text-center">Recherche sur carte</h1>
				</header>
				<?php
				if(isset($rjson_site['observatoire']))
				{
					?>
					<ul class="list-inline">						
						<?php
						if($obser == 'aucun')
						{
							?><li id="aucun" class="list-inline-item idvar color1"><i class="cercleicone fe-webobs fa-2x curseurlien" title="Tous"></i></li><?php
						}
						else
						{
							?><li id="aucun" class="list-inline-item idvar"><i class="cercleicone fe-webobs fa-2x curseurlien" title="Tous"></i></li><?php
						}
						foreach ($menuobservatoire as $n)
						{
							if($n['var'] == $obser)
							{
								?><li id="<?php echo $n['var'];?>" class="list-inline-item idvar color1"><i class="cercleicone <?php echo $n['icon'];?> fa-2x curseurlien" title="<?php echo $n['nom'];?>"></i></li><?php
							}
							else
							{
								?><li id="<?php echo $n['var'];?>" class="list-inline-item idvar"><i class="cercleicone <?php echo $n['icon'];?> fa-2x curseurlien" title="<?php echo $n['nom'];?>"></i></li><?php
							}								
						}
						?>
					</ul>							
					<?php
				}
				?>
			</div>
		</div>
	</div>
	<div class="row mt-2">
		<div class="col-md-6 col-lg-6">
			<div class="card card-body">
				<form class="form-inline">
					<label for="rayon">Indiquer une distance (5 km max)</label>
					<input type="number" class="form-control ml-2 mr-2" id="rayon" min="0" max="5" value="1" pattern="^\d*">
					et cliquer sur la carte.
				</form>
				<div id="carte" class="cartefiche mt-2"></div>
			</div>
		</div>
		<div class="col-md-6 col-lg-6">
			<div class="card card-body">
				<p>On peut imaginer tout un tas de type de retour. A voir <br />
				-> au clique sur un site d'observation -> récupération des obs ?<br />
				-> Affichage ici de la liste des taxons ? (trié par observatoire si pas de selection préalable), possibilité d'export si ses propre obs ou droits le permettant
				</p>
				<p>
				Mettre possibilité de selection avec polygon
				</p>
				<div id="retourliste"></div>
			</div>
		</div>
	</div>
	<input id="sel" name="sel" type="hidden" value="<?php echo $obser;?>"/>
</section>
