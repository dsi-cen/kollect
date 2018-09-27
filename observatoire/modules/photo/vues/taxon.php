<section class="container-fluid mb-3">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">				
				<div class="d-flex justify-content-start">
					<h1 class="h3">
						<?php echo $libt;?> de <?php echo $nomstitre;
						if($liste[0] > 1)
						{
							?> (<?php echo $liste[0];?>)<?php
						}
						?>
					</h1>
					<ol class="breadcrumb ml-auto mb-0">						
						<?php
						if(!isset($observateur))
						{
							?><li class="breadcrumb-item"><a href="index.php?module=galerie&amp;action=galerie&amp;d=<?php echo $nomvar;?>">Galerie</a></li><?php
							if(isset($fam))
							{
								?><li class="breadcrumb-item"><a href="index.php?module=galerie&amp;action=famille&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $idfam;?>"><?php echo $fam['famille'];?></a></li><?php
							}
							if(isset($sfam))
							{
								?><li class="breadcrumb-item"><a href="index.php?module=galerie&amp;action=sfamille&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $idsfam;?>"><?php echo $sfam['sousfamille'];?></a></li><?php
							}
						}
						else
						{
							?>
							<li class="breadcrumb-item"><a href="index.php?module=observateurs&amp;action=observateurs&amp;d=<?php echo $nomvar;?>">Contributeurs</a></li>
							<li class="breadcrumb-item"><a href="index.php?module=galerie&amp;action=galerie&amp;d=<?php echo $nomvar;?>&amp;idobser=<?php echo $idobser;?>"><?php echo $observateur['prenom'].' '.$observateur['nom'];?></a></li>
							<?php
							if(isset($fam))
							{
								?><li class="breadcrumb-item"><a href="index.php?module=galerie&amp;action=famille&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $idfam;?>&amp;idobser=<?php echo $idobser;?>"><?php echo $fam['famille'];?></a></li><?php
							}
							if(isset($sfam))
							{
								?><li class="breadcrumb-item"><a href="index.php?module=galerie&amp;action=sfamille&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $idsfam;?>&amp;idobser=<?php echo $idobser;?>"><?php echo $sfam['sousfamille'];?></a></li><?php
							}
						}
						?>
					</ol>
				</div>					
			</div>
		</div>
	</header>
	<div class="row mt-2">
		<div class="col-md-12 col-lg-12">
			<div class="card card-body">
				<span class="listefiltre">
					<?php
					if(count($sexe) > 1)
					{
						?>
						<button data-filter="*" type="button" class="btn btn-success" data-filter-group="sexe">Tous</button>
						<?php
						if(isset($male) && $male == 'oui')
						{
							?><button data-filter=".M" type="button" class="btn btn-secondary" data-filter-group="sexe">Mâle</button><?php
						}
						if(isset($femelle) && $femelle == 'oui')
						{
							?><button data-filter=".F" type="button" class="ml-1 btn btn-secondary" data-filter-group="sexe">Femelle</button><?php
						}
						if(isset($cple) && $cple == 'oui')
						{
							?><button data-filter=".C" type="button" class="ml-1 btn btn-secondary" data-filter-group="sexe">Couple</button><?php
						}
					}
					if(count($stade) > 1)
					{
						?>
						<button data-filter="*" type="button" class="ml-3 btn btn-success" data-filter-group="stade">Tous les stades</button>
						<?php
						foreach($rjson_obser['saisie']['stade'] as $cle => $n)
						{
							foreach($stade as $s)
							{
								if($s['stade'] == $n)
								{
									?><button data-filter=".s<?php echo $n;?>" type="button" class="ml-1 btn btn-secondary" data-filter-group="stade"><?php echo $cle;?></button><?php
								}
							}			
						}	
					}
					?>
				</span>
				<div class="photo-grid mt-3">
					<?php
					foreach($liste[1] as $n)
					{
						?>
						<div class="grid-item w200 mb-2 s<?php echo $n['stade'];?> <?php echo $n['sexe'];?>">
							<a class="agrand" href="../photo/P800/<?php echo $nomvar;?>/<?php echo $n['nomphoto'];?>.jpg" title="<?php echo $n['prenom'];?> <?php echo $n['nom'];?> - <?php echo $n['datefr'];?>">
								<img class="mx-auto d-block" alt="<?php echo $nom;?>" src="../photo/P200/<?php echo $nomvar;?>/<?php echo $n['nomphoto'];?>.jpg">
							</a>
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
	$('.photo-grid').magnificPopup({
		delegate: '.agrand', type: 'image', tLoading: 'Chargement image #%curr%...', mainClass: 'mfp-img-mobile',
		gallery: {enabled: true, navigateByImgClick: true, preload: [0,1]},
		image: {tError: '<a href="%url%">Cette image #%curr%</a> est absente..', titleSrc: function(item) { return item.el.attr('title'); }}
	});
	isophoto();
});
function isophoto() {
	'use strict';
	var filters = {};
	//var $grid = $('.photo-grid').isotope({ itemSelector: '.grid-item', filter: '*', percentPosition: true, masonry: { columnWidth: '.grid-sizer' } });
	var $grid = $('.photo-grid').isotope({ itemSelector: '.grid-item', filter: '*', masonry: { columnWidth: 200, gutter:10, horizontalOrder:true  } });
	$grid.imagesLoaded().progress( function() { $grid.isotope('layout'); });
	$('.listefiltre').on('click', 'button', function() {
		var filterGroup = $(this).attr('data-filter-group');
		filters[ filterGroup ] = $(this).attr('data-filter');
		$('*[data-filter-group=' + filterGroup + ']' + '.btn-success').removeClass('btn-success');
		$(this).addClass('btn-success');
		var filterValue = concatValues( filters );
		$grid.isotope({ filter: filterValue });
	});	
}
function concatValues( obj ) {
	var value = '';
	for ( var prop in obj ) {
		value += obj[ prop ];
	}
	return value;
}
</script>