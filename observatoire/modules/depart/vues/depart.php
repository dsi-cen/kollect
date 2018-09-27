<section class="container-fluid mb-3">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<header>
					<ol class="breadcrumb float-right">
						<li class="breadcrumb-item active"><?php echo $dep['departement'];?></li>
						<li class="breadcrumb-item"><a href="index.php?module=statut&amp;action=statut&amp;d=<?php echo $nomvar;?>&amp;iddep=<?php echo $iddep;?>">Liste statuts</a></li>
					</ol>
					<h1 class="h2 text-center"><?php echo $titrep;?></h1>									
				</header>
			</div>
		</div>
	</div>
	<div class="row mt-2">
		<div class="col-md-6 col-lg-6">
			<div class="card card-body">
				<?php
				if(isset($taxon))
				{
					if(isset($new))
					{
						?>
						<div>
							<h2 class="h5">
								<button id="new" class="btn btn-sm color1_bg new" type="button"><span class="fa fa-plus blanc"></span></button>
								<?php echo $libnouv;?>
							</h2>
						</div>
						<ul id="fnew" class="collapse mb-3">
							<?php
							foreach($new as $n)
							{
								?><li><?php echo $n['taxon'];?><?php								
							}
							?>
						</ul>
						<?php
					}
					?>
					<h2 class="h4">Liste par famille</h2>
					<div class="mb-3">
						<button type="button" id="voir" class="btn btn-sm btn-outline-secondary">Tout afficher</button>
						<button type="button" id="pasvoir" class="btn btn-sm btn-outline-secondary">Tout cacher</button>
					</div>
					<?php
					foreach($fam as $f)
					{
						?>
						<div class="listefamille">
							<div>
								<h3 class="h5">
									<button id="<?php echo $f['famille'];?>" class="btn btn-sm color1_bg idfam" type="button"><span class="fa fa-plus blanc"></span></button>
									<?php echo $f['nom'];?> (<?php echo $f['nb'];?>)
								</h3>
							</div>
							<ul id="f<?php echo $f['famille'];?>" class="collapse mb-3">
								<?php
								foreach($taxon as $n)
								{
									if($n['famille'] == $f['famille'])
									{
										?><li><?php echo $n['taxon'];?><?php										
									}
								}
								?>
							</ul>
						</div>
						<?php
					}
					?>
					<p class="font-weight-bold mt-3"><a href="index.php?module=statut&amp;action=statut&amp;d=<?php echo $nomvar;?>&amp;iddep=<?php echo $iddep;?>">Liste avec statuts</a></p>
					<?php
				}
				?>
			</div>
		</div>
		<div class="col-md-6 col-lg-6">
			<div class="card card-body">
				<h2 class="h4">Observateurs (<?php echo $nblisteobser;?>)</h2>
				<?php
				if($nblisteobser > 0)
				{
					?>
					<p>
						(Classé par nombre d'observation)<br />
						<?php						
						foreach($listeobser as $n)
						{
							?>
							<?php echo $n['prenom'];?> <span class="font-weight-bold" title="<?php echo $n['nb'];?> obs."><?php echo $n['nom'];?></span>,							
							<?php						
						}
						?>
					</p><?php
				}
				?>
			</div>
		</div>
	</div>	
</section>
<script>
$('.idfam').click(function() {
	'use strict';
	var sel = $(this).attr('id');
	if ($(this).children().hasClass('fa-plus')) { $(this).children().removeClass('fa-plus').addClass('fa-minus'); } else { $(this).children().removeClass('fa-minus').addClass('fa-plus'); }
	$('#f'+ sel).toggle();
});
$('.new').click(function() {
	'use strict';
	var sel = $(this).attr('id');
	if ($(this).children().hasClass('fa-plus')) { $(this).children().removeClass('fa-plus').addClass('fa-minus'); } else { $(this).children().removeClass('fa-minus').addClass('fa-plus'); }
	$('#f'+ sel).toggle();
});
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
</script>