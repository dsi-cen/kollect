<section class="container-fluid mb-3">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<h1 class="h3">Dernières photos de <?php echo $nomd;?> déposées</h1>
			</div>
		</div>
	</header>
	<div class="row mt-2">
		<div class="col-md-12 col-lg-12">
			<div class="card card-body">
				<?php
				if(isset($filtre))
				{
					?>
					<ul class="list-inline simplefilter">
						<li class="list-inline-item active" data-filter="*">Tous</li>
						<?php
						foreach($filtre as $n)
						{
							?><li class="list-inline-item" data-filter="<?php echo $n['idobser'];?>"><?php echo $n['nom'];?></li><?php
						}
						?>
					</ul>
					<?php
				}				
				?>
				<div class="row no-gutters photo-grid pop">
					<div class="grid-sizer col-sm-6 col-md-4 col-xl-3"></div>
					<?php
					foreach($liste as $n)
					{
						?>
						<div class="grid-item col-sm-6 col-md-4 col-xl-3 <?php echo $n['idobser'];?>">
							<a class="agrand" href="../photo/P800/<?php echo $n['observatoire'];?>/<?php echo $n['nomphoto'];?>.jpg" title="<?php echo $n['sp'];?> <?php echo $n['nomvern'];?> - photo de <?php echo $n['prenom'];?> <?php echo $n['nom'];?> prise le <?php echo $n['datefr'];?>">
								<img class="img-thumbnail img-fluid mx-auto d-block" src="../photo/P400/<?php echo $n['observatoire'];?>/<?php echo $n['nomphoto'];?>.jpg" alt="<?php echo $n['sp'];?>">
							</a>
							<span class="item-desc"><a class="blanc" href="index.php?module=fiche&amp;action=fiche&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $n['cdnom'];?>"><i><?php echo $n['sp'];?></i> <?php echo $n['nomvern'];?></a> <a class="blanc" title="Voir l'observation" href="../index.php?module=observation&amp;action=detail&amp;idobs=<?php echo $n['idobs'];?>"><i class="fa fa-eye"></i></a></span>
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
	$('.pop').magnificPopup({
		delegate: '.agrand', type: 'image', tLoading: 'Chargement image #%curr%...', 	mainClass: 'mfp-img-mobile',
		gallery: {enabled: true, navigateByImgClick: true, preload: [0,1]},
		image: {tError: '<a href="%url%">Cette image #%curr%</a> est absente..', titleSrc: function(item) { return item.el.attr('title'); }}
	});
	isophoto();
});
function isophoto() {
	'use strict';
	var $grid = $('.photo-grid').isotope({ itemSelector: '.grid-item', filter: '*', percentPosition: true, masonry: { columnWidth: '.grid-sizer' } });
	$grid.imagesLoaded().progress( function() { $grid.isotope('layout'); });
	$('.simplefilter').on('click', 'li', function() {
		var filterc = $(this).attr('data-filter');
		if (filterc == '*') { $grid.isotope({ filter: '*' }); } else { $grid.isotope({ filter: '.'+ filterc }); }		
	});
	$('.simplefilter li').click(function() {
        $('.simplefilter li').removeClass('active'); $(this).addClass('active');
    });	
}
</script>