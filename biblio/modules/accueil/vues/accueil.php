<section>
	<div class="d-flex flex-row header p-2">
		<div class="container">
			<div class="row">
				<header class="col-md-12 col-lg-12">
					<h1 class="h4 text-uppercase ctitre"><?php echo $rjson_biblio['titre'];?></h1>
				</header>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row mt-5">
			<div class="col-md-8 col-lg-8 biblio">
				<ul class="nav nav-tabs nav-justified text-uppercase biblio">
					<li class="nav-item"><a class="nav-link active" href="#publi" data-toggle="tab" data-id="publi"><h2 class="h6 p-1">Dernières références</h2></a></li>
					<li class="nav-item"><a class="nav-link" href="#taxon" data-toggle="tab" data-id="taxon"><h2 class="h6 p-1">Derniers taxons cités</h2></a></li>
					<li class="nav-item"><a class="nav-link" href="#fichier" data-toggle="tab" data-id="fichier"><h2 class="h6 p-1">Derniers dépots</h2></a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane fade show active" id="publi">
						<ul class="list-unstyled">
							<?php
							if(!empty($ref))
							{
								foreach($ref as $n)
								{
									?>									
									<li class="pb-1 mb-1">
										<a href="index.php?module=biblio&amp;action=biblio&amp;id=<?php echo $n['idbiblio'];?>"><h3 class="h6 f400 mb-0"><?php echo $n['ref'];?></h3></a>
										<small class="text-muted"><?php echo $n['auteur'];?> - Saisie le <?php echo $n['date'];?></small><br />
										<?php
										if(!empty($n['commune']))
										{
											foreach($n['commune'] as $c)
											{
												?>
												<a href="index.php?module=liste&amp;action=liste&amp;choix=com&amp;id=<?php echo $c['codecom'];?>" class="mt-2 badge tagbiblio p-2 rounded-0"><i class="fa fa-tag"></i>&nbsp;<?php echo $c['commune'];?></a>
												<?php
											}
										}
										?>
									</li>
									<hr class="mt-0"/>
									<?php
								}
							}
							?>
						</ul>						
					</div>
					<div class="tab-pane fade" id="taxon">
						<ul class="list-unstyled">
							<?php
							foreach($taxon as $n)
							{
								?>									
								<li class="pb-1 mb-1">
									<a href="index.php?module=biblio&amp;action=biblio&amp;id=<?php echo $n['idbiblio'];?>"><h3 class="h6 f400 mb-0"><?php echo $n['nomvern'];?> <i><?php echo $n['nom'];?></i> <?php echo $n['auteur'];?></h3></a>
									<small class="text-muted">Ajout le <?php echo $n['datefr'];?></small><br />									
								</li>
								<hr class="mt-0"/>
								<?php
							}
							?>
						</ul>
					</div>
					<div class="tab-pane fade" id="fichier">
						
					</div>
				</div>
			</div>
			<div class="col-md-4 col-lg-4">
				<div class="card">
					<img class="card-img-top" src="dist/img/accueilbiblio.jpg">
				</div>
				<div class="card-body text-center color4_bg blanc">
					<div class="row text-uppercase">
						<div class="col-sm-4">
							<span class="h5"><?php echo $nbref;?></span><br />
							<span class="small">références</span>
						</div>
						<div class="col-sm-4">
							<span class="h5"><?php echo $nbtaxon;?></span><br />
							<span class="small">taxons</span>
						</div>
						<div class="col-sm-4">
							<span class="h5"><?php echo $nbauteur;?></span><br />
							<span class="small">auteurs</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>