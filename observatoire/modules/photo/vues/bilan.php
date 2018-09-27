<section class="container-fluid mb-3">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<h1 class="h3">Bilan des clichés déposés et des espèces non encore illustrées de <?php echo $nomd;?></h1>
			</div>
		</div>
	</header>
	<div class="row mt-2">
		<div class="col-md-6 col-lg-6">
			<div class="card card-body">
				<h2 class="h4">Photos de <?php echo $nomd;?></h2>
				<p>Actuellement <?php echo $libnbphoto;?> sur le site concernant <?php echo $libnbsp;?> de <?php echo $nomd;?></p>
				<?php
				if($nbphoto > 0)
				{
					?>
					<h3 class="h5">Liste des espèces illustrées</h3>
					<div class="row mt-2">
						<div class="col-md-12 col-lg-12">
							<table id="photo" class="table table-hover table-sm" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th></th><th>Nom</th><th>Nom français</th><th>Nb</th>
									</tr>
								</thead>
								<tbody>
								<?php
								foreach($photo as $n)
								{
									?>
									<tr>
										<td><a href="index.php?module=fiche&amp;action=fiche&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $n['cdref'];?>"><i class="fa fa-file-text-o color1" title="Fiche"></i></a></td>
										<td><i><?php echo $n['nom'];?></i></td><td><?php echo $n['nomvern'];?></td>
										<td><?php echo $n['nb'];?> <a href="index.php?module=photo&amp;action=taxon&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $n['cdref'];?>"><i class="fa fa-camera color1" title="Voir les photos"></i></a></td>
									</tr>
									<?php
								}						
								?>
								</tbody>
							</table>
						</div>
					</div>
					<?php
				}
				?>
			</div>
		</div>
		<div class="col-md-6 col-lg-6">
			<div class="card card-body">
				<h2 class="h4">Espèces sans photos</h2>
				<?php
				if($sanphoto > 0)
				{
					?>
					<p>Actuellement <?php echo $libsansp;?> (<?php echo $pcent;?> %)</p>
					<div class="row mt-2">
						<div class="col-md-12 col-lg-12">
							<table id="sphoto" class="table table-hover table-sm" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th></th><th>Nom</th><th>Nom français</th>
									</tr>
								</thead>
								<tbody>
								<?php
								foreach($sphoto as $n)
								{
									?>
									<tr>
										<td><a href="index.php?module=fiche&amp;action=fiche&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $n['cdref'];?>"><i class="fa fa-file-text-o color1" title="Fiche"></i></a></td>
										<td><i><?php echo $n['nom'];?></i></td><td><?php echo $n['nomvern'];?></td>
									</tr>
									<?php
								}						
								?>
								</tbody>
							</table>
						</div>
					</div>					
					<?php
				}
				else
				{
					?><p>Aucune</p><?php
				}
				?>
			</div>
		</div>
	</div>
</section>
<script>
$(document).ready(function() {
	'use strict';
	$('#photo').DataTable({ "language": { "url":"../dist/js/datatables/france.json" },
		"order": [],
		"columnDefs": [{ "orderable": false, "targets": 0 }], "paging":   true, "ordering": true, "info": true, "searching": true
	});
	$('#sphoto').DataTable({ "language": { "url":"../dist/js/datatables/france.json" },
		"order": [],
		"columnDefs": [{ "orderable": false, "targets": 0 }], "paging":   true, "ordering": true, "info": true, "searching": true
	});
});
</script>