<section class="container-fluid mb-3">
	<div class="row">
		<div class="col-md-12 col-lg-9 mt-3">
			<header class="row">
				<div class="col-md-12 col-lg-12">					
					<div class="card-body">						
						<h1><?php echo $titre;?></h1>	
					</div>
				</div>
			</header>
			<div class="row mt-2">
				<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
					<div class="card card-body">
						<?php
						{
							echo $stitre;
							if(!empty($article['idarticle']))
							{
								?><span class="text-right"><a href="index.php?module=article&amp;action=article&amp;id=<?php echo $article['idarticle'];?>">Lire la suite</a></span><?php
							}
						}
						?>						
					</div>
				</div>
				<div class="col-md-12 mt-3">
					<div class="pb-2">
						<div class="card-header">
							<span class="btn color1_bg blanc float-right">
								<a href="index.php?module=bilan&amp;action=evolution" title="Evolution des données"><i class="float-right fa fa-line-chart fa-lg blanc"></i></a>
							</span>
							<h2 class="h5">Kollect en chiffres</h2>
						</div>
						<div class="card-body pb-0">
							<div class="row row-eq-height">
								<div class="col-md-3 pr-0">
									<div class="card text-center stats raisin">
										<span class="medium display-4"><?php echo $nbsp;?></span>													
										<h3 class="stats-title">Espèces</h3>
										<div style="#element { margin: auto; }">
												<i class="fe-heterop fa-3x"></i><i class="fe-mamm fa-3x"></i><i class="fe-arb2 fa-3x"></i>
										</div>													
									</div>
								</div>
								<div class="col-md-3">
									<a href="index.php?module=observation&action=observation">
										<div class="card color3_bg text-center blanc stats">
											<span class="medium display-4"><?php echo $nbobs;?></span>													
											<h3 class="stats-title">Données</h3>
											<div style="#element { margin: auto; }">
												<i class="fa fa-eye fa-3x"></i>
											</div>													
										</div>
									</a>
								</div>
								<div class="col-md-3 pr-0">
									<div class="card text-center stats marron">
										<span class="medium display-4"><?php echo $nbdonneespriv;?></span>													
										<h3 class="stats-title">Données d'origine privée</h3>
										<div style="#element { margin: auto; }">
												<i class="fa fa-list-alt fa-3x"></i>	
										</div>													
									</div>
								</div>
								<div class="col-md-3">
									<div class="card text-center stats turquoise">
										<span class="medium display-4"><?php echo $nbetudes;?></span>													
										<h3 class="stats-title">Nombre d'études</h3>	
										<div style="#element { margin: auto; }">
												<i class="fa fa-pencil-square-o fa-3x"></i>
										</div>												
									</div>
								</div>																
							</div>
							<div class="row mt-2 row-eq-height">
								<div class="col-md-3 pr-0">
									<a href="index.php?module=photo&amp;action=dernierephoto">
										<div class="card color2_bg text-center blanc stats">
											<span class="medium display-4"><?php echo $nbphoto;?></span>													
											<h3 class="stats-title">Photos</h3>	
											<div style="#element { margin: auto; }">
													<i class="fa fa-camera fa-3x"></i>
											</div>												
										</div>
									</a>
								</div>
								<div class="col-md-3">
									<a href="index.php?module=observateurs&action=observateurs">
										<div class="card color6_bg text-center blanc stats">
											<span class="medium display-4"><?php echo $nbobser;?></span>													
											<h3 class="stats-title">Observateurs</h3>
											<div style="#element { margin: auto; }">
													<i class="fa fa-users fa-3x"></i>
											</div>													
										</div>
									</a>
								</div>
								<div class="col-md-3 pr-0">
									<a href="#">
										<div class="card text-center stats prune">
											<span class="medium display-4"><?php echo $nbdonneespub;?></span>													
											<h3 class="stats-title">Données publiques</h3>
											<div style="#element { margin: auto; }">
													<i class="fa fa-check-circle fa-3x"></i>
											</div>														
										</div>
									</a>
								</div>
								<div class="col-md-3">
									<a href="biblio/">
										<div class="card text-center stats tomate">
											<span class="medium display-4"><?php echo $nbbiblio;?></span>													
											<h3 class="stats-title">Références biblio</h3>	
											<div style="#element { margin: auto; }">
													<i class="fa fa-book fa-3x"></i>
											</div>												
										</div>
									</a>
								</div>								
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12 col-lg-12 mt-3">
					<div class="text-center p-2 card-header">
						<a href="index.php?module=decade&amp;action=decade" title="Espèce de la décade"><span class="btn color1_bg blanc btn-sm float-right"><i class="fa fa-calendar fa-lg"></i></span></a>
						<h2 class="h5">Les espèces du moment <small class="text-muted">(<?php echo $dec1;?>)</small></h2>							
					</div>
					<div class="card-body">
						<div class="row">
							<?php
							if(isset($tabdecade))
							{
								foreach($tabdecade as $n)
								{
									?>
									<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4 col-xl-3 figure">
										<figure class="card cardombre">											
											<?php
											if($n['nomphoto'] != '')
											{
												?><img class="img-fluid" alt="<?php echo $n['nom'];?>" title="<?php echo $n['nom'];?> - <?php echo $n['nb'];?> observations (photo de <?php echo $n['prenom'];?> <?php echo $n['obsern'];?>)" src="photo/P200/<?php echo $n['nomvar'];?>/<?php echo $n['nomphoto'];?>.jpg"><?php
											}
											else
											{
												?><img class="img-fluid" alt="" title="<?php echo $n['nom'];?> - <?php echo $n['nb'];?> observations" src="dist/img/pasimage.png"><?php
											}
											?>
											<figcaption class="card-body p-1">
												<?php
												if($n['nomphoto'] != '')
												{
													?><small class="pl-1 xsmall">&copy; <?php echo $n['prenom'];?> <?php echo $n['obsern'];?></small><br /><?php
												}
												else
												{
													?><br /><?php
												}
												?>
												<h3 class="h6 pl-1">
													<?php
													if($n['latin'] == 'oui')
													{
														?><i><?php echo $n['nom'];?></i><?php
													}
													else
													{
														if($n['nomvern'] != '')
														{
															?><?php echo $n['nomvern'];?><?php
														}
														else
														{
															?><i><?php echo $n['nom'];?></i><?php
														}
													}
													?>
												</h3>												
												<div class="pl-1 my-1">
													<a href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d=<?php echo $n['nomvar'];?>&amp;id=<?php echo $n['cdref'];?>" title="fiche de <?php echo $n['nom'];?>"><span class="badge color1_bg p-1 blanc">fiche de l'espèce</span></a>
													<a class="float-right" href="observatoire/index.php?d=<?php echo $n['nomvar'];?>" title="<?php echo $n['disc'];?>"><i class="<?php echo $n['icon'];?> fa-2x" style="color:<?php echo $n['color'];?>"></i></a>
												</div>
											</figcaption>
										</figure>
									</div>
									<?php
								}
							}
							else
							{
								?>
								<div class="col-sm-12">
									<div class="text-uppercase p-1">
										<p>Aucune espèce observée en ce moment</p>									
									</div>
								</div>
								<?php
							}
							?>
						</div>
					</div>
				</div>				
			</div>
		</div>
		<div class="col-md-12 col-lg-3">
			<div class="row">		
				<?php
				if(isset($tabobs))
				{
					?>
					<div class="col-md-6 col-lg-12">
						<h2 class="h6 mt-3"><i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;LES DERNIERES OBSERVATIONS </h2>
						<div class="card bg cardcarousel">
							<?php
							if($nbphoto > 0)
							{
								?>
								<div id="carousel" class="carousel slide text-center" data-ride="carousel">
									<ol class="carousel-indicators">
										<?php
										$i = -1;
										foreach($photo as $n)
										{
											$class = (++$i == 0) ? 'class="active"' : '';
											?><li data-target="#carousel" data-slide-to="<?php echo $i;?>" <?php echo $class;?>></li><?php
										}
										?>
									</ol>
									<div class="carousel-inner">
										<?php									
										$i = -1;
										foreach($photo as $n)
										{
											$class = (++$i == 0) ? 'carousel-item active' : 'carousel-item';
											?>
											<div class="<?php echo $class;?>">
												<a class="mx-auto" href="index.php?module=observation&amp;action=detail&amp;idobs=<?php echo $n['idobs'];?>">
													<img title="<?php echo $n['lat'];?> - <?php echo $n['nomvern'];?>" alt="<?php echo $n['nomphoto'];?>" src="photo/P400/<?php echo $n['observatoire'];?>/<?php echo $n['nomphoto'];?>.jpg" class="d-block img-fluid" style="margin:0px auto;">
													<div class="carousel-caption d-none d-md-block font13">
														&copy; <?php echo $n['prenom'];?> <?php echo $n['nom'];?> <?php echo $n['datefr'];?>
													</div>
												</a>
											</div>
											<?php
										}
										?>											
									</div>
								</div>
								<?php
							}
							?>
						</div>
						<div class="v-timeline mt-3">
							<?php
							foreach($tabobs as $n)
							{
								?>	
								<div class="vertical-timeline-block">
									<a href="index.php?module=observation&amp;action=detail&amp;idobs=<?php echo $n['idobs'];?>">
										<div class="vertical-timeline-icon iconesdroite fondblanc">
											<i class="<?php echo $n['icon'];?> fa-lg"></i>
										</div>
										<div class="vertical-timeline-content">
											<span class="vertical-date float-right"><?php echo $n['datefr'];?></span>
											<p class="font13">
												<?php
												if ($n['latin'] == 'oui')
												{
													?><span class="font-italic"><?php echo $n['nomlat'];?></span><?php
												}
												else
												{
													if($n['nomfr'] != '')
													{
														?><span class="font-weight-normal"><?php echo $n['nomfr'];?></span><?php
													}
													else
													{
														?><span class="font-weight-bold"><?php echo $n['nomlat'];?></span><?php
													}
												}												
												?>
											</p>
										</div>
									</a>
								</div>
								<?php		
							}
							?>
							<a href="index.php?module=observation&amp;action=observation"><span class="font-weight-bold btn color1_bg blanc float-right"> <i class="fa fa-plus-square-o"></i> Voir les dernières observations</span></a>
						</div>						
					</div>
					<?php
				}
				if(isset($actu))
				{
					?>
					<div class="col-md-6 col-lg-12">
						<h2 class="h6 mt-3"><i class="fa fa-calendar"></i>&nbsp;&nbsp;LES ACTUALITES DU SITE </h2>
						<div class="v-timeline pb-3">
							<?php
							foreach($actu as $n)
							{
								?>	
								<div class="vertical-timeline-block">
									<a href="index.php?module=actu&amp;action=article&amp;idactu=<?php echo $n['idactu'];?>">
										<div class="vertical-timeline-icon iconesdroite fondblanc">
											<?php
											if ($n['icon'] != 'NR')
											{
												?><i class="<?php echo $n['icon'];?> fa-lg"></i><?php
											}
											else
											{
												?><i class="fe-webobs fa-lg"></i><?php
											}
											?>
										</div>
										<div class="vertical-timeline-content">
											<span class="vertical-date float-right"><?php echo $n['datefr'];?></span>
											<p>
												<span class="font-weight-bold"><?php echo $n['titre'];?></span><br />
												<span><?php echo $n['soustitre'];?></span>										
											</p>
										</div>
									</a>
								</div>
								<?php
							}
							?>				
							<a href="index.php?module=actu&amp;action=actu"><span class="font-weight-bold btn color1_bg blanc float-right"><i class="fa fa-plus-square-o"></i> Voir toutes les actualités </span></a>
						</div>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</section>