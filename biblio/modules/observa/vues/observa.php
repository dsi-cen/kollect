<section>
	<div class="d-flex flex-row header p-2">
		<div class="container">
			<div class="row">
				<header class="col-md-12 col-lg-12">
					<div class="d-flex justify-content-start">
						<h1 class="h4 text-uppercase ctitre">Recherche par observatoire</h1>
						<ol class="breadcrumb ml-auto mb-0 p-1 small">							
							<li class="breadcrumb-item"><a href="index.php?module=recherche&amp;action=recherche">Recherche</a></li>
							<li class="breadcrumb-item active">Par observatoire</li>
						</ol>
					</div>
				</header>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row mt-5">
			<?php
			foreach($tab as $n)
			{
				?>
				<div class="col-md-4 col-sm-6">
					<?php
					if($n['nb'] > 0)
					{
						$lib = ($n['nb'] > 1) ? 'références' : 'référence';
						?>
						<h2 class="h4 ctitre text-uppercase"><a href="index.php?module=liste&amp;action=liste&amp;choix=observa&amp;id=<?php echo $n['var'];?>"><i class="<?php echo $n['icon'];?>" style="color:<?php echo $n['couleur'];?>"></i> <?php echo $n['observa'];?></a></h2>
						<p>Recherchez les références associées à cet observatoire. <span class="small font-weight-bold">(<?php echo $n['nb'];?> <?php echo $lib;?>)</span></p>
						<?php
					}
					else
					{
						?>
						<h2 class="h4 ctitre text-uppercase"><i class="<?php echo $n['icon'];?>" style="color:<?php echo $n['couleur'];?>"></i> <?php echo $n['observa'];?></h2>
						<p>Aucune référence pour cet observatoire</p>
						<?php
					}
					?>
				</div>
				<?php
			}
			?>		
		</div>
	</div>
</section>