<section class="<?php echo $classcontainer;?>">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<h1 class="h2"><?php echo $titre;?></h1>				
			</div>
		</div>		
	</header>
	<div class="row mt-2 mb-3">
		<?php	
		if(!empty($article['article']))
		{
			?>
			<div class="col-md-12">
				<div class="card card-body">
					<?php echo $article['article'];?>
				</div>
			</div>
			<?php
		}
		?>
	</div>
</section>