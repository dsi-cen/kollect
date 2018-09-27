<section class="container">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<div class="d-flex justify-content-start">
					<h1 class="h2"><?php echo $titre;?></h1>
					<ol class="breadcrumb ml-auto mb-0">
						<li class="breadcrumb-item"><a href="index.php?module=actu&amp;action=actu">Actualités</a></li>
						<li class="breadcrumb-item active"><?php echo $tag;?></li>
					</ol>
					
				</div>
			</div>
		</div>		
	</header>
	<div class="row mt-2">
		<div class="col-md-9 col-lg-9">
			<div class="card">
				<div class="v-timeline ml-2">
					<?php
					foreach ($actu as $n)
					{
						?>
						<div class="vertical-timeline-block">
							<a href="index.php?module=actu&amp;action=article&amp;idactu=<?php echo $n['idactu'];?>&amp;ret=<?php echo urlencode($tag);?>">
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
								<div class="vertical-timeline-content">
									<h2 class="h3"><?php echo $n['titre'];?></h2>
									<p>
										<span class="">Publié le <?php echo $n['datefr'];?></span>
									</p>
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
								</div>
							</a>
						</div>
						<?php
					}		
					?>
				</div>
			</div>
		</div>
		<div class="col-md-3 col-lg-3">
			<?php
			if(isset($tab_tag))
			{
				?>
				<div class="card card-body">
					<h2>Mots-clés </h2>
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
	</div>
	<div class="clearfix mt-2"></div>
</section>