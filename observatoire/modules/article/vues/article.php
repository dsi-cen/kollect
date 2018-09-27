<section class="<?php echo $classcontainer;?> mb-3">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<h1 class="h2"><?php echo $titre;?></h1>				
			</div>
		</div>		
	</header>
	<div class="row mt-2">
		<?php	
		if(!empty($article['article']))
		{
			?>
			<div class="col-md-12">
				<div class="card card-body">
					<?php echo $article['article'];?>
					<?php
					if($article['typear'] == 'ac'.$nomvar)
					{
						?>
						<div class="row">
							<div class="col-md-6">
								<h2 class="h6">Animateur(s)</h2>
								<?php
								if(isset($ouianim))
								{
									?><ul><?php
									foreach($anim as $n)
									{
										?>										
										<li><?php echo $n['prenom'];?> <b><?php echo $n['nom'];?></b></li>
										<?php
									}
									?></ul>
									<p>Une question, suggestion, sur les <?php echo $nomd;?> <a href="../index.php?module=contact&amp;action=contact&amp;d=<?php echo $nomvar;?>">contacter les animateurs</a></p>
									<?php
								}
								else
								{
									?><p>Aucun animateur pour l'instant</p><?php
								}
								?>
							</div>
							<div class="col-md-6">
								<h2 class="h6">Validateur(s)</h2>
								<?php
								if(isset($ouivalid))
								{
									?><ul><?php
									foreach($valid as $n)
									{
										?>										
										<li><?php echo $n['prenom'];?> <b><?php echo $n['nom'];?></b></li>
										<?php
									}
									?></ul><?php
								}
								else
								{
									?><p>Aucun validateur pour l'instant</p><?php
								}
								?>
							</div>
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
</section>