<section class="container-fluid mb-3">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<div class="d-flex justify-content-start">
					<h1 class="h3">Galerie photo des <?php echo $famille['famille'];?>
						<?php 
						if(isset($observateur))
						{
							?>de <?php echo $observateur['prenom'].' '.$observateur['nom'];?><?php
						}	
						?>
					</h1>
					<ol class="breadcrumb ml-auto mb-0">
						<?php 
						if(isset($observateur))
						{
							?>
							<li class="breadcrumb-item"><a href="index.php?module=observateurs&amp;action=observateurs&amp;d=<?php echo $nomvar;?>">Contributeurs</a></li>
							<li class="breadcrumb-item"><a href="index.php?module=galerie&amp;action=galerie&amp;d=<?php echo $nomvar;?>&amp;idobser=<?php echo $idobser;?>"><?php echo $observateur['prenom'].' '.$observateur['nom'];?></a></li>
							<?php
						}
						else
						{
							?><li class="breadcrumb-item"><a href="index.php?module=galerie&amp;action=galerie&amp;d=<?php echo $nomvar;?>">Galerie</a></li><?php
						}
						?>
						<li class="breadcrumb-item active"><?php echo $famille['famille'];?></li>
					</ol>
				</div>
			</div>
		</div>
	</header>
	<div class="row mt-2">
		<div class="col-md-12 col-lg-12">
			<div class="card card-body min">
				<?php
				if(isset($liste) && !empty($liste))
				{
					?>
					<h2 class="h5">Sous famille</h2>
					<div class="grid">
						<?php 
						foreach($liste as $n)
						{
							?>
							<div class="card cardombre grid-item w200 mb-2">
								<img class="mx-auto d-block" alt="<?php echo $n['sousfamille'];?>" title="Photo de <?php echo $n['obser'];?>" src="../photo/P200/<?php echo $nomvar;?>/<?php echo $n['nomphoto'];?>.jpg">
								<div class="card-body text-center p-1">
									<?php
									if(!isset($observateur))
									{
										?><a href="index.php?module=galerie&amp;action=sfamille&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $n['cdnom'];?>"><?php echo $n['sousfamille'];?></a><?php
									}	
									else
									{
										?><a href="index.php?module=galerie&amp;action=sfamille&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $n['cdnom'];?>&amp;idobser=<?php echo $idobser;?>"><?php echo $n['sousfamille'];?></a><?php
									}
									?>
								</div>							
							</div>
							<?php
						}
						?>
					</div>
					<?php
				}
				if(isset($autre))
				{
					if(isset($liste) && !empty($liste))
					{
						?><h2 class="h5">Espèces n'appartenant pas à une sous famille</h2><?php
					}
					?>
					<div class="grid">
						<?php 
						foreach($autre as $n)
						{
							?>
							<div class="card cardombre grid-item w200 mb-2">
								<img class="mx-auto d-block" alt="<?php echo $n['nom'];?>" title="Photo de <?php echo $n['obser'];?>" src="../photo/P200/<?php echo $nomvar;?>/<?php echo $n['nomphoto'];?>.jpg">
								<div class="card-body text-center p-1">
									<?php
									if(!isset($observateur))
									{
										?>
										<a href="index.php?module=photo&amp;action=taxon&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $n['cdnom'];?>&amp;f=<?php echo $fam;?>">
											<i><?php echo $n['nom'];?></i><br />
											<?php echo $n['nomvern'];?>
										</a>
										<?php
									}
									else
									{
										?>
										<a href="index.php?module=photo&amp;action=taxon&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $n['cdnom'];?>&amp;f=<?php echo $fam;?>&amp;idobser=<?php echo $idobser;?>">
											<i><?php echo $n['nom'];?></i><br />
											<?php echo $n['nomvern'];?>
										</a>
										<?php
									}
									?>
								</div>							
							</div>
							<?php
						}
						?>
					</div>
					<?php
				}
				?>
			</div>
		</div>		
    </div>	
</section>
<script>
$(document).ready(function() {
	'use strict';
	var $grid = $('.grid').masonry({ itemSelector: '.grid-item', columnWidth: 200, gutter:10, horizontalOrder:true });
	$grid.imagesLoaded().progress( function() { $grid.masonry('layout'); });	
});
</script>