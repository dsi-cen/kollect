<section class="container-fluid mb-3">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<div class="d-flex justify-content-start">
					<h1 class="h3">Photos des <?php echo $nomd;?> observés durant la <?php echo $dec;?> décade du mois de <?php echo $CMois;?></h1>
					<ol class="breadcrumb ml-auto mb-0">
						<?php
						if(isset($mem))
						{
							?><li class="breadcrumb-item"><a href="index.php?module=decade&amp;action=decade&amp;d=<?php echo $nomvar;?>&amp;jrs=<?php echo $j;?>&amp;mois=<?php echo $DMois;?>">Liste</a></li><?php
						}
						else
						{
							?><li class="breadcrumb-item"><a href="index.php?module=decade&amp;action=decade&amp;d=<?php echo $nomvar;?>">Liste</a></li><?php
						}
						?>
						<li class="breadcrumb-item active">Photo</li>
						<li class="breadcrumb-item"><a href="index.php?module=decade&amp;action=graph&amp;d=<?php echo $nomvar;?>">Graph</a></li>
					</ol>
				</div>				
			</div>
		</div>		
	</header>
	<div class="row mt-2">
		<div class="col-md-12 col-lg-12">
			<div class="card card-body min">
				<p class="h6"><?php echo $dec1;?> <?php echo $CMois;?>. Classer par nombre d'observations (cliquer sur les photos pour les agrandir)</p>
				<div class="d-flex justify-content-start">
					<?php
					if(isset($rjson_obser['categorie']))
					{
						?>
						<form class="form-inline">
							<label for="cat">Filtrer</label>
							<select id="cat" class="form-control form-control-sm ml-2">
								<option value="tous">Tous</option>
								<?php
								foreach($rjson_obser['categorie'] as $n)
								{
									if($n['id'] == $cat)
									{
										?><option value="<?php echo $n['id'];?>" selected><?php echo $n['cat'];?></option><?php
									}
									else
									{
										?><option value="<?php echo $n['id'];?>"><?php echo $n['cat'];?></option><?php
									}										
								}
								?>						
							</select>
						</form>
						<?php
					}
					?>
					<div class="ml-auto mb-0">
						<?php echo $pagination;?>
					</div>
				</div>
				<div class="mt-2">		
					<div class="grid">	
						<?php
						foreach($photo as $n)
						{
							?>
							<div class="card cardombre grid-item w200 mb-2">
								<?php
								if(!empty($n['nomphoto']))
								{
									?>
									<a class="agrand" href="../photo/P800/<?php echo $nomvar;?>/<?php echo $n['nomphoto'];?>.jpg" title="<?php echo $n['nom'];?> - <?php echo $n['nb'];?> observations (photo de <?php echo $n['prenom'];?> <?php echo $n['obsern'];?>)">
										<img class="mx-auto d-block" alt="<?php echo $n['nom'];?>" src="../photo/P200/<?php echo $nomvar;?>/<?php echo $n['nomphoto'];?>.jpg">
									</a>
									<?php
								}
								else
								{
									?><img class="mx-auto d-block" alt="" title="<?php echo $n['nom'];?> - <?php echo $n['nb'];?> observations" src="../dist/img/pasimage.png"><?php
								}
								?>
								<div class="card-body p-1">
									<?php
									if($n['nomphoto'] != '')
									{
										?><small class="xsmall">&copy; <?php echo $n['prenom'];?> <?php echo $n['obsern'];?></small><br /><?php
									}
									?>
									<p class="mb-0">
										<small class="d-block"><i><?php echo $n['nom'];?></i></small>
										<?php
										if(!empty($n['nomvern']))
										{
											?><small class="d-block"><?php echo $n['nomvern'];?></small><?php
										}
										?>
										<a href="index.php?module=observation&amp;action=observation&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $n['cdref'];?>">
											<?php echo $n['nb'];?>
											<?php
											if($n['nb'] > 1)
											{
												?>observations<?php
											}
											else
											{
												?>observation<?php
											}
											?>
										</a>
										<?php
										if(!empty($n['nomphoto']))
										{
											?><a class="float-right ml-2" href="index.php?module=photo&amp;action=taxon&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $n['cdref'];?>"><i class="fa fa-camera color1" title="Voir toutes les photos"></i></a><?php
										}
										?>
										<a class="float-right" href="index.php?module=fiche&amp;action=fiche&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $n['cdref'];?>"><i class="fa fa-file-text-o color1" title="Voir la fiche"></i></a>
									</p>
								</div>
							</div>
							<?php
						}
						?>
					</div>
				</div>	
				<div class="d-flex flex-row-reverse">
					<?php //echo $pagination;?>
				</div>				
			</div>
		</div>		
	</div>
</section>
<input type="hidden" id="decobserva" value="<?php echo $nomvar;?>"><input type="hidden" id="jrs" value="<?php echo $j;?>"><input type="hidden" id="mois" value="<?php echo $DMois;?>">
<script>
$(document).ready(function() {
	'use strict';
	$('.grid').magnificPopup({
		delegate: '.agrand', type: 'image', tLoading: 'Chargement image #%curr%...', 	mainClass: 'mfp-img-mobile',
		gallery: {enabled: true, navigateByImgClick: true, preload: [0,1]},
		image: {tError: '<a href="%url%">Cette image #%curr%</a> est absente..', titleSrc: function(item) { return item.el.attr('title'); }}
	});
	var $grid = $('.grid').masonry({ itemSelector: '.grid-item', columnWidth: 200, gutter:10, horizontalOrder:true });
	$grid.imagesLoaded().progress( function() { $grid.masonry('layout'); });	
});
$('#cat').change(function() {
	'use strict';
	var cat = $(this).val(), observa = $('#decobserva').val(), jrs = $('#jrs').val(), mois = $('#mois').val();
	document.location.href='index.php?module=decade&action=photo&d='+ observa +'&jrs='+ jrs +'&mois='+ mois +'&cat='+ cat +'&p=1';
});
$('.pagination').on('click', 'a', function() {
	'use strict';
	var cat = $('#cat').val();
	if (cat && cat != 'tous') {
		event.preventDefault();
		var page = $(this).attr('href'), observa = $('#decobserva').val(), jrs = $('#jrs').val(), mois = $('#mois').val();
		document.location.href='index.php?module=decade&action=photo&d='+ observa +'&jrs='+ jrs +'&mois='+ mois +'&cat='+ cat +'&p='+ page;
	}
});
</script>