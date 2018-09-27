<section class="container blanc">	
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header>	
				<h1 class="h2">Liste des derniers commentaires de validation</h1>				
			</header>
			<p>les 100 derniers commentaires : </p>
			<?php
			foreach($listeidobs as $n)
			{
				?>
				<ul class="list-unstyled">
					<li><a href="../index.php?module=observation&amp;action=detail&amp;idobs=<?php echo $n['idobs'];?>">Idobs n° <?php echo $n['idobs'];?></a>
					<?php
					foreach($liste as $l)
					{
						if($l['idobs'] == $n['idobs'])
						{
							?><ul class="list-unstyled"><li>- <?php echo $l['nom'];?> le <?php echo $l['datefr'];?>  <?php echo $l['commentaire'];?></li></ul></li><?php
						}
					}
					?>
				</ul>
				<?php
			}
			?>
		</div>
	</div>
</section>