<section class="container-fluid">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<h1 class="h2">Information et aide sur le site</h1>				
			</div>
		</div>		
	</header>
	<div class="row mt-2">
		<div class="col-md-6 col-lg-6">
			<div class="card card-body">
				<h2 class="h4">Information</h2>
				<ul>
					<li><a href="index.php?module=cgu&amp;action=cgu">Conditions d'utilisation</a></li>
					<li><a href="index.php?module=taxref&amp;action=taxref">Le référentiel taxonomique TAXREF</a></li>
					<li><a href="index.php?module=validation&amp;action=validation">La validation des données</a></li>
				</ul>
			</div>
		</div>
		<div class="col-md-6 col-lg-6">
			<div class="card card-body">
				<h2 class="h4">Aide</h2>
				<ul>
					<?php
					if(isset($tab))
					{
						foreach($tab as $n)
						{
							?><li><?php echo $n['descri'];?> ( <?php echo $n['pdf'];?> <?php echo $n['taille'];?> ) <a href="tuto/<?php echo $n['nomdoc'];?>"><i class="fa fa-download fa-lg color1"></i></a></li><?php
						}
					}
					?>
				</ul>
			</div>
		</div>
	</div>
</section>
	