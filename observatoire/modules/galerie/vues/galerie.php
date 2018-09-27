<section class="container-fluid mb-3">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<?php 
				if(isset($observateur))
				{
					?>
					<div class="d-flex justify-content-start">
						<h1 class="h3">Galerie photo des <?php echo $nomd;?> de <?php echo $observateur['prenom'].' '.$observateur['nom'];?></h1>
						<ol class="breadcrumb ml-auto mb-0">
							<li class="breadcrumb-item"><a href="index.php?module=observateurs&amp;action=observateurs&amp;d=<?php echo $nomvar;?>">Contributeurs</a></li>
							<li class="breadcrumb-item active"><?php echo $observateur['prenom'].' '.$observateur['nom'];?></li>
						</ol>
					</div>
					<?php
				}
				else
				{
					?><h1 class="h3">Galerie photo des <?php echo $nomd;?></h1><?php
				}
				?>				
			</div>
		</div>
	</header>
	<div class="row mt-2">
		<div class="col-md-6 col-lg-6">
			<div class="card card-body">
				<?php
				if($nbliste > 0)
				{
					?>
					<p>Cliquez sur le nom d'une famille pour accéder aux photos</p>
					<table class="table table-hover table-sm">
						<thead>
							<tr><th>Famille</th><th>Nb de photo</th><th>Nb d'espèce en photo</th></tr>
						</thead>
						<tbody>
							<?php
							foreach($liste as $n)
							{
								?>
								<tr>
									<?php
									if(!isset($observateur))
									{
										?><td><a href="index.php?module=galerie&amp;action=famille&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $n['cdnom'];?>"><?php echo $n['famille'];?></a></td><td><?php echo $n['nb'];?></td><td><?php echo $n['nb1'];?></td><?php
									}
									else
									{
										?><td><a href="index.php?module=galerie&amp;action=famille&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $n['cdnom'];?>&amp;idobser=<?php echo $idobser;?>"><?php echo $n['famille'];?></a></td><td><?php echo $n['nb'];?></td><td><?php echo $n['nb1'];?></td><?php
									}
									?>
								</tr>
								<?php
							}
							?>
						</tbody>
					</table>
					<?php
				}
				?>
			</div>
		</div>
		<div class="col-md-6 col-lg-6">
			<?php
			if(isset($lettre))
			{
				?>
				<div class="card card-body">
					<p>Ou bien cliquez sur une lettre pour accéder directement à une espèce</p>
					<div class="d-flex flex-wrap">
						<?php
						foreach($lettre as $n)
						{
							if(!empty($n['l']))
							{
								?>
								<button type="button" class="btn color1_bg blanc ml-2 mt-2 curseurlien lettre" id="<?php echo $n['l'];?>"><?php echo $n['l'];?></button>
								<?php
							}
						}						
						?>
					</div>
					<div id="listealpa" class="mt-3"></div>
				</div>
				<?php
			}			
			?>
		</div>
    </div>
	<input type="hidden" value="<?php echo $tri;?>" id="tri"><input type="hidden" value="<?php echo $idobser;?>" id="idobser">
</section>
<script>
$('.lettre').click(function(){
	'use strict';
	var id = $(this).attr('id'), tri = $('#tri').val(), sel = $('#obser').val(), idobser = $('#idobser').val();
	$.post('modeles/ajax/galerie/listelettre.php', {id:id,tri:tri,sel:sel,idobser:idobser}, function(listealpha){ $('#listealpa').html(listealpha); });		
});
</script>