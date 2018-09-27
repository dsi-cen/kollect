<section class="container-fluid mb-3">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<h1 class="h2 text-center">Liste des contributeurs</h1>
			</div>
		</div>
	</header>
	<div class="row mt-2">
		<div class="col-md-6 col-lg-6">
			<div class="card card-body">				
				<article class="flex-none">				
					<header><h2 class="h4">Liste des observateurs (<?php echo $nbobser;?>)</h2></header>
					<hr>
					<p>
						<?php
						foreach($listeobser[1] as $n)
						{
							?>
							<a href="index.php?module=infoobser&amp;action=info&amp;idobser=<?php echo $n['idobser'];?>"><?php echo $n['prenom'];?> <b><?php echo $n['nom'];?></b></a>,
							<?php
						}
						?>
					</p>				
				</article>
			</div>
		</div>
		<div class="col-md-6 col-lg-6">
			<div class="card card-body">
				<article class="flex-none">		
					<header><h2 class="h4">Liste des photographes (<?php echo $nbphoto;?>)</h2></header>
					<hr>
					<p>
						<?php
						foreach($listephoto[1] as $n)
						{
							?>
							<a href="index.php?module=observateurs&amp;action=photographe&amp;idobser=<?php echo $n['idobser'];?>"><?php echo $n['prenom'];?> <b><?php echo $n['nom'];?></b></a>,
							<?php
						}
						?>
					</p>
				</article>
			</div>
		</div>
    </div>	
</section>