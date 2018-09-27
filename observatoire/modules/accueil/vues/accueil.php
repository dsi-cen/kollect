<section class="container-fluid mb-3">
	<div class="row">
		<div class="col-sm-8 col-lg-8">
			<header class="row">
				<div class="col-md-12 col-lg-12 p-0">					
					<div class="card card-body color1_bg blanc">
						<h1><?php echo $titre;?></h1>
					</div>
				</div>
			</header>
			<div class="row mt-3">
				<div class="col-sm-12">
					<div class="card card-body">
						<?php
						echo $rjson_obser['description'];
						if(!empty($article['idarticle']))
						{
							?><span class="text-right"><a href="index.php?module=article&amp;action=article&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $article['idarticle'];?>">Lire la suite</a></span><?php
						}
						?>						
					</div>
				</div>
				<div class="col-sm-12 d-lg-none mb-1 mt-2">
					<ul class="nav nav-inline tabs-hor color4_bg">
						<li class="nav-item">
							<a class="nav-link mx-1" href="index.php?module=observateurs&amp;action=observateurs&amp;d=<?php echo $nomvar;?>"><i class="fa fa-users fa-lg"></i><br />
								<span class="blanc"><?php echo $nbobser;?></span>
							</a>					
						</li>
						<li class="nav-item">
							<a class="nav-link mr-1" href="../index.php?module=observation&amp;action=observation&amp;d=<?php echo $nomvar;?>"><i class="fa fa-bar-chart fa-lg"></i><br />
								<span class="blanc"><?php echo $nbobs;?></span>
							</a>					
						</li>
						<li class="nav-item">
							<a class="nav-link mr-1" href="index.php?module=liste&amp;action=liste&amp;d=<?php echo $nomvar;?>"><i class="<?php echo $rjson_obser['icon'];?> fa-lg"></i><br />
								<span class="blanc"><?php echo $nbsp;?></span>
							</a>					
						</li>
						<li class="nav-item">
							<a class="nav-link mr-1" href="#"><i class="fa fa-camera fa-lg"></i><br />
								<span class="blanc"><?php echo $nbphoto;?></span>
							</a>					
						</li>
					</ul>
				</div>
				<?php
				if(count($listedecade) > 0)
				{
					?>
					<div class="col-sm-12 mt-3">
						<div class="text-uppercase p-2 card-header">
							<a href="index.php?module=decade&amp;action=decade&amp;d=<?php echo $nomvar;?>" title="Espèces de la décade"><span class="btn color1_bg blanc btn-sm float-right"><i class="fa fa-calendar fa-lg"></i></span></a>
							<h2 class="h5">Les espèces du moment <small class="text-muted">(<?php echo $dec1;?>)</small></h2>								
						</div>
						<div class="card-body pb-0">
							<div class="row">						
								<?php
								foreach($listedecade as $n)
								{
									?>
									<div class="col-sm-6 col-md-6 col-lg-4 col-xl-3">
										<figure class="card cardombre">
											<?php
											if($n['nomphoto'] != '')
											{
												?><img class="img-fluid" alt="<?php echo $n['nom'];?>" title="<?php echo $n['nom'];?> - <?php echo $n['nb'];?> observations (photo de <?php echo $n['prenom'];?> <?php echo $n['obsern'];?>)" src="../photo/P400/<?php echo $nomvar;?>/<?php echo $n['nomphoto'];?>.jpg"><?php
											}
											else
											{
												?><img class="img-fluid mx-auto d-block" alt="" title="<?php echo $n['nom'];?> - <?php echo $n['nb'];?> observations" src="../dist/img/pasimage.png"><?php
											}
											?>
											<figcaption class="card-body p-1">
												<?php
												if($n['nomphoto'] != '')
												{
													?><small class="xsmall">&copy; <?php echo $n['prenom'];?> <?php echo $n['obsern'];?></small><br /><?php
												}
												?>
												<h3 class="h6">
													<?php
													if($nomlatin == 'oui')
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
												<div class="my-1"><a href="index.php?module=fiche&amp;action=fiche&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $n['cdref'];?>" title="fiche de <?php echo $n['nom'];?>"><span class="badge color1_bg blanc p-1">Fiche de l'espèce</span></a></div>																			
											</figcaption>											
										</figure>
									</div>
									<?php
								}
								?>
							</div>
						</div>						
					</div>
					<?php
				}
				else
				{
					?>
					<div class="col-sm-6 mt-3">
						<div class="card text-uppercase p-2">
							<p>Aucune espèce observée en ce moment</p>									
						</div>
					</div>
					<?php
				}
				?>
			</div>
		</div>
		<div class="col-lg-1 d-sm-none d-lg-block p-0 color4_bg">
			<ul class="nav flex-column nav-tabs tabs-left ">
				<li class="nav-item">
					<?php
					if(!empty($counomvar))
					{
						?><a class="nav-link" href="index.php?d=<?php echo $obser;?>" style="color:<?php echo $counomvar;?>"><i class="<?php echo $rjson_obser['icon'];?> fa-5x"></i></a><?php
					}
					else
					{
						?><a class="nav-link" href="index.php?d=<?php echo $obser;?>" class="color1"><i class="<?php echo $rjson_obser['icon'];?> fa-5x blanc"></i></a><?php
					}
					?>	
				</li>
				<li class="nav-item">
					<a class="nav-link" href="index.php?module=observateurs&amp;action=observateurs&amp;d=<?php echo $nomvar;?>"><i class="fa fa-users fa-2x blanc"></i><br />
						<span class="blanc"><?php echo $nbobser;?><br />Observateurs</span>
					</a>					
				</li>
				<li class="nav-item">
					<a class="nav-link" href="../index.php?module=observation&amp;action=observation&amp;d=<?php echo $nomvar;?>"><i class="fa fa-bar-chart fa-2x blanc"></i><br />
						<span class="blanc"><?php echo $nbobs;?><br />Observations</span>
					</a>					
				</li>
				<li class="nav-item">
					<a class="nav-link" href="index.php?module=liste&amp;action=liste&amp;d=<?php echo $nomvar;?>"><i class="<?php echo $rjson_obser['icon'];?> fa-2x blanc"></i><br />
						<span class="blanc"><?php echo $nbsp;?><br />Espèces</span>
					</a>					
				</li>
				<li class="nav-item">
					<a class="nav-link" href="index.php?module=galerie&amp;action=galerie&amp;d=<?php echo $nomvar;?>"><i class="fa fa-camera fa-2x blanc"></i><br />
						<span class="blanc"><?php echo $nbphoto;?><br />Photos</span>
					</a>					
				</li>
				<li class="nav-item">
					<a class="nav-link" href="index.php?module=photo&amp;action=bilan&amp;d=<?php echo $nomvar;?>"><i class="fa fa-camera fa-lg blanc"></i> / <i class="<?php echo $rjson_obser['icon'];?> fa-lg blanc"></i><br />
						<span class="blanc"><?php echo $nbespecep;?><br />Espèces</span>
					</a>					
				</li>
			</ul>			
		</div>
		<div class="col-sm-4 col-md-4 col-lg-3">
			<?php
			if($nbphoto > 0)
			{
				?>			
				<div class="cardcarousel mt-1">								
					<div id="carousel" class="carousel slide" data-ride="carousel">
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
									<a class="mx-auto" href="index.php?module=fiche&amp;action=fiche&amp;d=<?php echo $n['observatoire'];?>&amp;id=<?php echo $n['cdnom'];?>">
										<img alt="" src="../photo/P400/<?php echo $n['observatoire'];?>/<?php echo $n['nomphoto'];?>.jpg" class="d-block img-fluid" style="max-height:235px;margin:0px auto;">
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
				</div>
				<div class="mt-2">
					<a class="ml-3" href="index.php?module=photo&amp;action=dernierephoto&amp;d=<?php echo $nomvar;?>"><span class="font-weight-bold btn color1_bg blanc mt-1"> <i class="fa fa-plus-square-o"></i> dernières photos </span></a>
				</div>
				<?php
			}
			if(count($listeobs) > 0)
			{
				?>
				<div class="card-body">
					<h2 class="h6"><i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;DERNIERES OBSERVATIONS </h2>
					<p class="font13">
						<?php
						foreach ($listeobs as $n)
						{
							?>
							<a href="index.php?module=fiche&amp;action=fiche&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $n['cdref'];?>" title="Fiche de <?php echo $n['nom'];?>"><i class="fa fa-file-o"></i></a>&nbsp;
							<a href="../index.php?module=observation&amp;action=detail&amp;idobs=<?php echo $n['idobs'];?>" title="Détail de l'observation">
								<span class="vertical-date"><?php echo $n['datefr'];?></span>								
								<?php
								if($nomlatin == 'oui')
								{
									?><span class="font-weight-bold"><i><?php echo $n['nom'];?></i></span><?php
								}
								else
								{
									if($n['nomvern'] != '')
									{
										?><span class="font-weight-bold"><?php echo $n['nomvern'];?></span><?php
									}
									else
									{
										?><span class="font-weight-bold"><i><?php echo $n['nom'];?></i></span><?php
									}
								}
								if($n['floutage'] < 2 && $n['sensible'] <= 1)
								{	
									?>
									<span>
										<?php echo $n['commune'];?>
										<?php
										if($emprise['emprise'] == 'fr' || $emprise['contour2'] == 'oui')
										{
											?>(<?php echo $n['iddep'];?>)<?php
										}
										?>
									</span>	
									<?php
								}
								?>																
							</a>
							<br />						
							<?php		
						}
						?>	
					</p>
					<a href="../index.php?module=observation&amp;action=observation&amp;d=<?php echo $nomvar;?>"><span class="font-weight-bold btn color1_bg blanc"> <i class="fa fa-plus-square-o"></i> 100 dernières obs</span></a>
				</div>
				<?php
			}
			if($rjson_site['actu'] == 'oui' and $nblisteactu > 0)
			{
				?>
				<div class="card-body">
					<h2 class="h6"><i class="fa fa-calendar"></i>&nbsp;&nbsp;ACTUALITES</h2>
					<p class="font13">
						<?php					
						foreach ($listeactu as $n)
						{
							?>
							<a href="../index.php?module=actu&amp;action=article&amp;idactu=<?php echo $n['idactu'];?>"><i class="fa fa-file-o"></i>&nbsp;
								<span class="vertical-date"><?php echo $n['datefr'];?></span>
								<?php echo $n['titre'];?>
							</a><br />
							<?php
						}
						?>
					</p>
					<a href="../index.php?module=actu&amp;action=actu&amp;theme=<?php echo $nomvar;?>"><span class="font-weight-bold btn color1_bg blanc"> <i class="fa fa-plus-square-o"></i> Toutes les actus</span></a>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</section>