<section class="container-fluid blanc">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<h1 class="h2 text-center">Gestion des actualités</h1>
		</div>
	</header>
	<div class="row mt-2">
		<div class="col-md-5 col-lg-5">
			<h2 class="h3">Liste des actualités <small>(<?php echo $liste[0];?>)</small></h2>
		</div>
		<div class="col-md-2 col-lg-2">
			<button type="button" class="btn btn-success" id="BttN">Ajouter une actu</button>
		</div>
	</div>
	<div class="row mt-3">
		<div class="col-md-12 col-lg-12">
			<table id="liste" class="table table-hover table-sm" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th></th>
						<th></th>
						<th>Date</th>
						<th>Auteur</th>
						<th>Obs</th>
						<th>Titre</th>
						<th class="text-center">Photo</th>
						<th>Pdf</th>
						<th>Pub</th>
						<th>Vu</th>											
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($liste[1] as $n)
					{
						?>
						<tr id="<?php echo $n['idactu'];?>">
							<td><i class="fa fa-trash curseurlien text-danger" title="Supprimer" onclick="sup(<?php echo $n['idactu'];?>)"></i></td><td><i class="fa fa-pencil curseurlien text-warning" title="Modifier/corriger" onclick="mod(<?php echo $n['idactu'];?>)"></i></td>
							<td><?php echo $n['datefr'];?></td><td><?php echo $n['prenom'];?>&nbsp;<?php echo $n['nom'];?></td><td><?php echo $n['theme'];?></td><td><?php echo $n['titre'];?></td>
							<?php
							if($n['nomphoto'] != '')
							{
								?><td class="text-center"><img src="../photo/article/P200/<?php echo $n['nomphoto'];?>.jpg" style="height:30px;" alt="<?php echo $n['nom'];?>"/></td><?php
							}
							else
							{
								?><td></td><?php
							}
							if($n['iddoc'] != '')
							{
								?><td><i class="fa fa-file-pdf-o fa-lg text-success" title="pdf (ou zip)"></i></td></td><?php
							}
							else
							{
								?><td></td><?php
							}
							if($n['visible'] == 1)
							{
								?><td><i class="fa fa-check text-success" title="Oui"></i></td><?php
							}
							else
							{
								?><td></td><?php
							}
							?>
							<td><?php echo $n['compte'];?></td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</section>
<script>
	$(document).ready(function() {
		'use strict';
		$('#liste').DataTable({ "language": { "url":"../dist/js/datatables/france.json" },
			"order": [],
			"columnDefs": [{ "orderable": false, "targets": 0 },{ "orderable": false, "targets": 1 }],
			"paging":   true,
			"ordering": true,
			"info":     true,
			"searching": true
		});
	});
	$('#BttN').click(function(){
		document.location.href="index.php?module=actu&action=actu";
	});
	function sup(idactu) {
		'use strict';
		$.ajax({url: 'modeles/ajax/actu/actusup.php', type: 'POST', dataType: "json", data: {idactu: idactu},
			success: function (reponse) {
				if (reponse.statut == 'Oui') {
					$('#'+ idactu).hide();
				} else {
					alert(statut);
				}
			},
			error: function (err) {
				alert("Une erreure est survenue");
			}
		});
	}
	function mod(idactu) {
		document.location.href="index.php?module=actu&action=actumod&idactu="+idactu;
	} 
</script>