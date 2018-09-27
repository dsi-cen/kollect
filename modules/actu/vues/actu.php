<section class="container">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<div class="d-flex justify-content-start">
					<h1 class="h2"><?php echo $titre;?></h1>
					<?php
					if($nbarticle > 4)
					{
						?>
						
						<ol class="breadcrumb ml-auto mb-0">
							<li class="breadcrumb-item active"><a href="index.php?module=actu&amp;action=actu">Actualités</a></li>
							<?php
							if(isset($actutheme))
							{
								?>
								<li class="breadcrumb-item"><a href="index.php?module=actu&amp;action=actu&amp;theme=<?php echo $idtheme;?>"><?php echo $actutheme;?></a></li>
								<li class="breadcrumb-item"><a href="index.php?module=actu&amp;action=archive&amp;theme=<?php echo $idtheme;?>">Voir les actualités précédentes</a></li>
								<?php
							}
							else
							{
								?><li class="breadcrumb-item"><a href="index.php?module=actu&amp;action=archive">Voir les actualités précédentes</a></li><?php
							}
							?>						
						</ol>
						<?php
					}
					?>				
				</div>				
			</div>
		</div>		
	</header>
	<div class="row mt-2">
		<?php	
		if(isset($actu))
		{
			?>
			<div class="col-md-9">
				<div class="card">
					<div class="v-timeline ml-2">
						<?php
						foreach ($actu as $n)
						{
							?>
							<div class="vertical-timeline-block">
								<a href="index.php?module=actu&amp;action=article&amp;idactu=<?php echo $n['idactu'];?>">
									<div class="vertical-timeline-icon color3 fondblanc">
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
								</a>
								<div class="vertical-timeline-content">
									<a href="index.php?module=actu&amp;action=article&amp;idactu=<?php echo $n['idactu'];?>"><h2 class="h3"><?php echo $n['titre'];?></h2></a>
									<p>
										<span class="">Publié le <?php echo $n['datefr'];?></span>
										<?php
										if (!empty ($n['tag']))
										{
											?><span class="float-right"><?php
											foreach ($n['tag'] as $t)
											{
												?>
												<a class="badge badge-default" href="index.php?module=actu&amp;action=listetag&amp;choix=<?php echo urlencode($t);?>"><i class="fa fa-tag"></i> <?php echo $t;?></a> 
												<?php
											}
											?></span><?php
										}
										?>
									</p>
									<a href="index.php?module=actu&amp;action=article&amp;idactu=<?php echo $n['idactu'];?>">
										<div class="row">
											<?php
											if (!empty ($n['photo']))
											{
												?>
												<div class="col-sm-4">
													<img src="photo/article/P200/<?php echo $n['photo'];?>.jpg" class="img-fluid" alt="actu <?php echo $n['idactu'];?>">
												</div>
												<?php
											}
											?>
											<div class="col-sm-8">
												<p><?php echo $n['stitre'];?></p>
											</div>
										</div>
									</a>
								</div>							
							</div>							
							<?php
						}
						?>
					</div>						
				</div>
				<div class="row mt-2 mb-3">
					<div class="col-sm-12">
						<div class="p-2 fondblanc">
							<h3 class="h4">Articles populaires</h3>
						</div>
						<div class="card card-body">
							<div class="row">
								<?php
								foreach($listecompte as $n)
								{
									?>
									<figure class="col-xs-6 col-sm-4 col-md-4 col-lg-4 col-xl-3 figure">
										<div class="card cardombre">
											<a href="index.php?module=actu&amp;action=article&amp;idactu=<?php echo $n['idactu'];?>">
												<?php
												if($n['nom'] != '')
												{
													?><img src="photo/article/P200/<?php echo $n['nom'];?>.jpg" class="img-fluid" alt="actu <?php echo $n['idactu'];?>"><?php
												}
												else
												{
													?><img src="dist/img/pasimage.png" class="img-fluid" alt="actu <?php echo $n['idactu'];?>"><?php
												}
												?>
												<div class="card-body">
													<h4 class="h6"><?php echo $n['titre'];?></h4>
												</div>										
											</a>
										</div>
									</figure>
									<?php
								}
								?>
							</div>
						</div>						
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<?php
				if(isset($tab_tag))
				{
					?>
					<div class="card card-body">
						<h2 class="h4">Mots-clés </h2>
						<ul class="list-inline">
							<?php 
							foreach ($tab_tag as $n) 
							{
								?><li class="list-inline-item"><a href="index.php?module=actu&amp;action=listetag&amp;choix=<?php echo urlencode($n['nom']);?>" class="badge color1_bg blanc" style="font-size:<?php echo $n['size'];?>%" title="<?php echo $n['nom'];?>"><?php echo $n['nom'];?></a></li><?php
							}
							?>
						</ul>						
					</div>
					<?php
				}
				?>
			</div>
			<?php
		}
		else
		{
			?>
			<div class="col-md-6">
				<div class="card card-body">
					<p>Aucune actualité</p>
				</div>
			</div>
			<?php
		}
		?>
	</div>
</section>