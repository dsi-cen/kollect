<section class="container mb-3">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<h1 class="h2">Nouvelles espèces de <?php echo $nomd;?> <?php echo $rjson_site['ad2'];?> <?php echo $rjson_site['lieu'];?> par années</h1>
			</div>
		</div>		
	</header>
	<div class="row mt-2">
		<div class="col-md-12 col-lg-12">
			<div class="card card-body">
				<p>Liste établie à partir des données contenu dans la base</p>
				<div class="mb-3">
					<button type="button" id="voir" class="btn btn-outline-secondary">Tout afficher</button>
					<button type="button" id="pasvoir" class="btn btn-outline-secondary">Tout cacher</button>
				</div>
				<?php
				foreach($listeannee as $a)
				{
					if(isset($annee[$a['annee']]))
					{
						?>
						<div class="listefamille">
							<div>
								<h2 class="h5">
									<button id="<?php echo $a['annee'];?>" class="btn btn-sm color1_bg idfam" type="button"><span class="fa fa-plus blanc"></span></button>
									<?php echo $a['annee'];?> (<?php echo $a['nb'];?>)									
								</h2>
							</div>
							<ul id="f<?php echo $a['annee'];?>" class="collapse min">
								<?php
								foreach($famille as $f)
								{
									if($f['annee'] == $a['annee'])
									{
										?><li><a href="index.php?module=famille&amp;action=famille&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $f['cdnom'];?>"><?php echo $f['famille'];?></a><ul><?php
										foreach($new as $n)
										{
											if($n['annee'] == $f['annee'] && $n['famille'] == $f['famille'])
											{
												?><li><span class="font-weight-bold"><a href="index.php?module=fiche&amp;action=fiche&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $n['cdref'];?>"><i><?php echo $n['nom'];?></i></a></span>, <?php echo $n['datefr'];?> - <?php echo $n['observateur'];?>, det. <?php echo $n['det'];?> - <a href="../index.php?module=observation&action=detail&amp;idobs=<?php echo $n['idobs'];?>"><i class="fa fa-eye" title="Voir l'observation"></i></a></li><?php
											}										
										}
										?></ul></li><?php									
									}
								}
								?>
							</ul>
						</div>
						<?php
					}
				}				
				?>
			</div>
		</div>		
	</div>
</section>
<script>
$('#voir').click(function(e) {
	'use strict';
	$('.listefamille .collapse').show(); e.preventDefault();
	$('.idfam span').removeClass('fa-plus').addClass('fa-minus');
});
$('#pasvoir').click(function(e) { 
	'use strict';
	$('.listefamille .collapse').hide(); e.preventDefault(); 
	$('.idfam span').removeClass('fa-minus').addClass('fa-plus');
});
$('.idfam').click(function() {
	'use strict';
	var sel = $(this).attr('id');
	if ($(this).children().hasClass('fa-plus')) {
		$(this).children().removeClass('fa-plus').addClass('fa-minus');
	} else {
		$(this).children().removeClass('fa-minus').addClass('fa-plus');
	}
	$('#f'+ sel).toggle();
});
</script>