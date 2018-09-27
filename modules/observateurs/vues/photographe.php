<section class="container mb-3">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<h1 class="h3">Photos de <?php echo $observateur['prenom'].' '.$observateur['nom'];?> par observatoire</h1>
			</div>
		</div>
	</header>
	<div class="row mt-2">
		<div class="col-md-12 col-lg-12">
			<div class="card card-body">
				<?php
				if(isset($tab))
				{
					?>
					<ul class="list-unstyled">
						<?php
						foreach($tab as $n)
						{
							?>
							<li>
								<a href="observatoire/index.php?module=galerie&amp;action=galerie&amp;d=<?php echo $n['var'];?>&amp;idobser=<?php echo $idobser;?>">
									<i class="<?php echo $n['icon'];?> fa-2x" style="color:<?php echo $n['couleur'];?>"></i> <?php echo $n['observa'];?> - <?php echo $n['nb'];?> <?php echo $n['lib'];?>
								</a>
							</li>
							<?php
						}
						?>
					</ul>
					<?php
				}
				else
				{
					?><p>Aucune photo pour cet observateur</p><?php
				}
				?>
			</div>			
		</div>
    </div>	
</section>