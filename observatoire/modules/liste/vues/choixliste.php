<section class="container-fluid mb-3">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<h1 class="h2"><?php echo $titrep;?></h1>
			</div>
		</div>		
	</header>	
	<div class="row mt-2">
		<div class="col-md-5 col-lg-5">
			<div class="card card-body">
				<?php
				if(isset($listeok))
				{
					foreach($cat as $n)
					{
						?>
						<h2 class="h4"><?php echo $n['cat'];?></h2>
						<ul>
							<?php
							if($trisys == 'oui')
							{
								?>
								<li><a href="index.php?module=liste&amp;action=liste&amp;d=<?php echo $nomvar;?>&amp;cat=<?php echo $n['id'];?>&amp;ordre=A">par ordre alphabétique</a></li>
								<li><a href="index.php?module=liste&amp;action=liste&amp;d=<?php echo $nomvar;?>&amp;cat=<?php echo $n['id'];?>&amp;ordre=S">suivant la systématique</a></li>
								<?php
							}
							else
							{
								?>
								<!--<li><a href="index.php?module=liste&amp;action=liste&amp;d=<?php echo $nomvar;?>&amp;cat=<?php echo $n['id'];?>&amp;ordre=A">par ordre alphabétique</a></li>-->
								<li class="curseurlien afliste" id="A-<?php echo $n['id'];?>">par ordre alphabétique</li>
								<?php
							}
							?>							
						</ul>						
						<?php						
					}					
				}
				else
				{
					?><p>La liste des taxons de cet observatoire n'est pas paramétrée.</p><?php					
				}
				?>
				<input type="hidden" id="cat" value="<?php echo htmlspecialchars(json_encode($cat));?>"><input type="hidden" id="latin" value="<?php echo $latin;?>">
			</div>
		</div>
		<div class="col-md-7 col-lg-7" id="choixliste">
			<div class="card card-body">
				<div id="liste"></div>
			</div>
		</div>		
	</div>	
</section>