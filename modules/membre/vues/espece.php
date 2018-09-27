<section class="container-fluid mb-3">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<header>
					<h1 class="h2">Votre liste d'espèces (<?php echo $nbliste;?>)</h1>									
				</header>
			</div>
		</div>
	</div>
	<div class="card card-body mt-2">
		<div class="row">
			<div class="col-md-12 col-lg-12">
				<?php
				if(isset($tab))
				{
					?>
					<table id="statcom" class="table table-hover table-sm" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>Observatoire</th><th>Nom</th><th>Nom français</th><th title="Observation">Nb d'obs</th><th title="% des observations totales">%</th><th>Indice</th><th>Votre dernière obs</th>
							</tr>
						</thead>
						<tbody>
						<?php
						foreach($tab as $n)
						{
							?>
							<tr>
								<td><?php echo $n['observa'];?></td>
								<td><a href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d=<?php echo $n['nomvar'];?>&amp;id=<?php echo $n['cdref'];?>"><i><?php echo $n['nom'];?></i></a></td>
								<td><?php echo $n['nomvern'];?></td><td><?php echo $n['nb'];?></td><td><?php echo $n['pourcent'];?></td>
								<td><?php echo $n['ir'];?></td><td><?php echo $n['date'];?></td>
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
	</div>	
</section>
<script>
$(document).ready(function() {
	'use strict';
	var table = $('#statcom').DataTable({
		language : { url: "dist/js/datatables/france.json" },
		order: [],
		"scrollY": "600px", "scrollCollapse": true, "paging": false, "columnDefs": [{ "orderable": false, "targets": 6 }], 
		buttons: [ { extend: 'csvHtml5', title:'liste' },{ extend: 'excelHtml5', title:'liste' } ],
		initComplete: function () {
			setTimeout( function () {
				table.buttons().container().appendTo( '#statcom_wrapper .col-md-6:eq(0)' );
			}, 10 );
		}
	});	
});
</script>