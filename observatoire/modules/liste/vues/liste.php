<section class="container mb-3">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<h1 class="h2"><?php echo $titrep;?><small> (<?php echo $ordret;?>)</small></h1>
			</div>
		</div>		
	</header>
	<div class="row mt-2">
		<div class="col-md-12 col-lg-12">
			<div class="card card-body">
				<?php
				if(isset($listeok))
				{
					if($trisys == 'oui')
					{
						if($ordre == 'A')
						{
							?><p><a href="index.php?module=liste&amp;action=liste&amp;d=<?php echo $nomvar;?>&amp;ordre=S">Liste par ordre systématique</a></p><?php
						}
						else
						{
							?><p><a href="index.php?module=liste&amp;action=liste&amp;d=<?php echo $nomvar;?>&amp;ordre=A">Liste par ordre alphabétique</a></p><?php
						}						
					}
					if($nbsp > 0)
					{
						?>
						<p><?php echo $lib;?></p>
						<div class="mb-3">
							<button type="button" id="voir" class="btn btn-outline-secondary">Tout afficher</button>
							<button type="button" id="pasvoir" class="btn btn-outline-secondary">Tout cacher</button>
						</div>
						<?php
						foreach($tabfam as $f)
						{
							if(isset($tabf[$f['cdnom']]))
							{
								?>
								<div class="listefamille">
									<div>
										<h2 class="h5">
											<button id="<?php echo $f['famille'];?>" class="btn btn-sm color1_bg idfam" type="button"><span class="fa fa-plus blanc"></span></button>
											<a href="index.php?module=famille&amp;action=famille&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $f['cdnom'];?>"><?php echo $f['famille'];?>
												<?php 
												if($f['nomvern'] != '')
												{
													?> - <?php echo $f['nomvern'];
												}
												?>
												(<?php echo $f['nbfam'];?>)
											</a>
										</h2>
									</div>
									<ul id="f<?php echo $f['famille'];?>" class="collapse min">										
										<?php
										foreach($taxon as $t)
										{
											if($t['famille'] == $f['cdnom'])
											{
												$nbobservation = ($t['nb'] > 1) ? $t['nb'].' observations' : $t['nb'].' observation';
												if($t['rang'] == 'COM')
												{
													?><li>Complexe : <span class="font-weight-bold"><a href="index.php?module=fiche&amp;action=fichec&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $t['cdnom'];?>"><i><?php echo $t['nom'];?></i></a></span> - <?php echo $nbobservation;?> - <a href="index.php?module=observation&amp;action=observation&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $t['cdnom'];?>"><i class="fa fa-eye" title="Voir les observations"></i></a></li><?php
												}
												else
												{
													if($latin == 'nom')
													{
														
														?><li><span class="font-weight-bold"><a href="index.php?module=fiche&amp;action=fiche&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $t['cdnom'];?>"><i><?php echo $t['nom'];?></i></a></span>&nbsp;<?php echo $t['auteur'];?>&nbsp;<?php echo $t['nomvern'];?> - <?php echo $nbobservation;?> - <a href="index.php?module=observation&amp;action=observation&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $t['cdnom'];?>"><i class="fa fa-eye" title="Voir les observations"></i></a></li><?php
													}
													else
													{
														if($t['nomvern'] != '')
														{
															?><li><span class="font-weight-bold"><a href="index.php?module=fiche&amp;action=fiche&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $t['cdnom'];?>"><?php echo $t['nomvern'];?></a></span>&nbsp;<i><?php echo $t['nom'];?>&nbsp;<?php echo $t['auteur'];?></i> - <?php echo $nbobservation;?> - <a href="index.php?module=observation&amp;action=observation&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $t['cdnom'];?>"><i class="fa fa-eye" title="Voir les observations"></i></a></li><?php
														}
														else
														{
															?><li><span class="font-weight-bold"><a href="index.php?module=fiche&amp;action=fiche&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $t['cdnom'];?>"><i><?php echo $t['nom'];?></i></a></span>&nbsp;<?php echo $t['auteur'];?>&nbsp;<?php echo $t['nomvern'];?> - <?php echo $nbobservation;?> - <a href="index.php?module=observation&amp;action=observation&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $t['cdnom'];?>"><i class="fa fa-eye" title="Voir les observations"></i></a></li><?php
														}														
													}
												}
											}
										}
										?>										
									</ul>
								</div>
								<?php
							}
						}
					}
					else
					{
						?><p>Aucune espèce de saisie.</p><?php
					}
				}
				else
				{
					?><p>La liste des taxons de cet observatoire n'est pas paramétrée.</p><?php					
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