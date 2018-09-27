<section class="container-fluid mb-3">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<h1 class="h2">
					Mise à jour de TAXREF 
					<?php
					if($rtaxref['maj'] == 'oui')
					{
						?>- version <?php echo $rtaxref['version'];?><?php
					}
					?>
				</h1>				
			</div>
		</div>		
	</header>
	<div class="row mt-2">
		<div class="col-md-12 col-lg-12">
			<div class="card card-body">					
				<?php
				if($rtaxref['maj'] == 'oui')
				{
					?>
					<h2 class="h4">Changements entre TAXREF <?php echo $versionp;?> et TAXREF <?php echo $rtaxref['version'];?> sur le site</h2>
					<p>Sont listés ci-dessous uniquement les changements de nom valide apportés par la nouvelle version (sous espèces, espèces, genres)<br /><a href="https://inpn.mnhn.fr/programme/referentiel-taxonomique-taxref">En savoir plus sur TAXREF</a></p>
					<?php
					foreach($rjson_site['observatoire'] as $d)
					{
						$couleurnomvar = (!empty($d['couleur'])) ? $d['couleur'] : '';
						?>
						<h3 class="h5"><?php echo $d['nom'];?> <i class="<?php echo $d['icon'];?>" style="color:<?php echo $couleurnomvar;?>"></i></h3>
							<ul>
							<?php
							foreach($tab as $n)
							{
								if($d['nomvar'] == $n['observatoire'])
								{
									?>
									<li><i><?php echo $n['ancnom'];?></i> <?php echo $n['ancvern'];?> (<?php echo $n['ancrang'];?>) devient synonyme de <a href="<?php echo $n['lien'];?>"><i><?php echo $n['nom'];?></i> <?php echo $n['nomvern'];?></a> (<?php echo $n['rang'];?>)</li>
									<?php
								}
							}
							?>
						</ul>
						<?php
					}
				}
				else
				{
					?><p>Aucune mise à jour de TAXREF sur le site. La version utilisée sur le site est la version <b><?php echo $rtaxref['version'];?></b><br /><a href="https://inpn.mnhn.fr/programme/referentiel-taxonomique-taxref">En savoir plus sur TAXREF</a></p><?php
				}
				?>
			</div>
		</div>
	</div>		
</section>