<section class="container-fluid mb-3">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<header>
					<h1 class="h2">Vos notifications</h1>
				</header>
			</div>
		</div>
	</div>
	<div class="row mt-2">
		<div class="col-md-12 col-lg-12">
			<div class="card card-body">
				<h2 class="h3"><?php echo $titreh2;?></h2>
				<?php
				if(isset($tabcomobs))
				{
					?>
					<h3 class="h4 mt-2">Commentaire(s) sur observation</h3>
					<ul>
						<?php
						foreach($tabcomobs as $n)
						{
							?>					
							<li><a href="index.php?module=observation&amp;action=detail&amp;idobs=<?php echo $n['idobs'];?>&amp;idnotif=oui"><?php echo $n['nb'];?> sur l'observation n° <?php echo $n['idobs'];?></a></li>
							<?php
						}
						?>
					</ul>
					<?php
				}
				if(isset($tabdet))
				{
					?>
					<h3 class="h4 mt-2">Sur les demandes de détermination</h3>
					<ul>
						<?php
						foreach($tabdet as $n)
						{
							?>					
							<li><a href="index.php?module=det&amp;action=suivi&amp;id=<?php echo $n['idpdet'];?>&amp;idnotif=oui"><?php echo $n['nb'];?> sur demande n° <?php echo $n['idpdet'];?></a></li>
							<?php
						}
						?>
					</ul>
					<?php
				}
				if(isset($tabvali))
				{
					?>
					<h3 class="h4 mt-2">Commentaire(s) sur la validation d'observation</h3>
					<ul>
						<?php
						foreach($tabvali as $n)
						{
							?>					
							<li><a href="index.php?module=observation&amp;action=detail&amp;idobs=<?php echo $n['idobs'];?>&amp;idnotif=oui&amp;vali=oui"><?php echo $n['nb'];?> sur l'observation n° <?php echo $n['idobs'];?></a></li>
							<?php
						}
						?>
					</ul>
					<?php
				}
				?>				
			</div>
		</div>		
	</div>
</section>