<section class="container-fluid mb-3">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<div class="d-flex justify-content-start">
					<h1 class="h3">Galerie photo des <?php echo $sfamille['sousfamille'];?>
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
							<li class="breadcrumb-item"><a href="index.php?module=galerie&amp;action=famille&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $sfamille['cdnom'];?>&amp;idobser=<?php echo $idobser;?>"><?php echo $sfamille['famille'];?></a></li>
							<?php
						}
						else
						{
							?>
							<li class="breadcrumb-item"><a href="index.php?module=galerie&amp;action=galerie&amp;d=<?php echo $nomvar;?>">Galerie</a></li>
							<li class="breadcrumb-item"><a href="index.php?module=galerie&amp;action=famille&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $sfamille['cdnom'];?>"><?php echo $sfamille['famille'];?></a></li>
							<?php
						}
						?>
						<li class="breadcrumb-item active"><?php echo $sfamille['sousfamille'];?></li>
					</ol>
				</div>
			</div>
		</div>
	</header>
	<div class="row mt-2">
		<div class="col-md-12 col-lg-12">
			<div class="card card-body min">
				<div class="grid">
					<?php
					foreach($liste as $n)
					{
						?>
						<div class="card cardombre grid-item w200 mb-2">
							<img class="mx-auto d-block" alt="<?php echo $n['nom'];?>" title="Photo de <?php echo $n['obser'];?>" src="../photo/P200/<?php echo $nomvar;?>/<?php echo $n['nomphoto'];?>.jpg">
							<div class="card-body text-center p-1">
								<?php
								if(!isset($observateur))
								{
									?>
									<a href="index.php?module=photo&amp;action=taxon&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $n['cdnom'];?>&amp;f=<?php echo $sfamille['cdnom'];?>&amp;sf=<?php echo $sfam;?>">
										<i><?php echo $n['nom'];?></i><br /><?php echo $n['nomvern'];?>
									</a>
									<?php
								}	
								else
								{
									?>
									<a href="index.php?module=photo&amp;action=taxon&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $n['cdnom'];?>&amp;f=<?php echo $sfamille['cdnom'];?>&amp;sf=<?php echo $sfam;?>&amp;idobser=<?php echo $idobser;?>">
										<i><?php echo $n['nom'];?></i><br /><?php echo $n['nomvern'];?>
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