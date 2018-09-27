<section class="container-fluid mb-3">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<h1 class="h2">Liste des contributeurs - <?php echo $nomd;?></h1>
			</div>
		</div>
	</header>
	<div class="row mt-2">
		<div class="col-md-8 col-lg-8">
			<div class="card card-body">				
				<article>
					<header><h2 class="h4">Liste des observateurs (<?php echo $nbobser;?>)</h2></header>
					<hr class="">
					<p>
						<?php
						foreach($listeobser[1] as $n)
						{
							?>
							<a href="../index.php?module=infoobser&amp;action=info&amp;idobser=<?php echo $n['idobser'];?>"><?php echo $n['prenom'];?> <b><?php echo $n['nom'];?></b></a>,
							<?php
						}
						?>
					</p>
				</article>
			</div>
		</div>
		<div class="col-md-4 col-lg-4">
			<div class="card card-body">	
				<article>	
					<header><h2 class="h4">Liste des photographes (<?php echo $nbphoto;?>)</h2></header>
					<hr>
					<table class="table table-sm table-striped">
						<tbody>
							<?php
							foreach($listephoto[1] as $n)
							{
								?>
								<tr>
									<td><a href="index.php?module=galerie&amp;action=galerie&amp;d=<?php echo $nomvar;?>&amp;idobser=<?php echo $n['idobser'];?>"><?php echo $n['prenom'];?> <b><?php echo $n['nom'];?></b></a></td><td><?php echo $n['nb'];?></td>
								</tr>
								<?php
							}
							?>
						</tbody>
					</table>					
				</article>
			</div>
		</div>
    </div>	
</section>