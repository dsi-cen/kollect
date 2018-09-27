<section class="container-fluid mb-3">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<h1 class="h2">Télécharger la liste des <?php echo $nomd;?> <?php echo $rjson_site['ad1'];?> <?php echo $rjson_site['lieu'];?></h1>
			</div>
		</div>		
	</header>
	<div class="row mt-2">
		<div class="col-md-12 col-lg-12">
			<div class="card card-body">
				<p>Liste établie à partir des données contenu dans la base</p>
				<div>
					<table id="tblliste" class="table table-sm table-striped" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>Nom</th>
								<th>Nom fr</th>
								<th>Famille</th>
								<th>Première</th>
								<th>Dernière</th>
								<th>Nb obs</th>
								<th>Nb site</th>
								<th>Indice</th>
							</tr>
						</thead>
					</table>		
				</div>
			</div>
		</div>		
	</div>
</section>
<script>
$(document).ready(function() {
	'use strict';
	var sel = $('#obser').val(), choix = 'NR';
	recupliste(sel,choix);
});
function recupliste(sel,choix) {
	$.ajax({
		url: 'modeles/ajax/liste/tele.php', type: 'POST', dataType: "json", data: {sel:sel,choix:choix},
		success: function(reponse) {
			if (reponse.statut == 'Oui') { 
				if (reponse.data) { remplirtable(reponse.data); }
			}
		}
	});		
}
function remplirtable(data) {
	var table = $('#tblliste').DataTable({
		language : { url: "../dist/js/datatables/france.json" },
		data : data, deferRender: true, scrollY: 600, scrollCollapse: true, scroller: true,
		buttons: [ { extend: 'csvHtml5', title:'liste' },{ extend: 'excelHtml5', title:'liste' } ],
		initComplete: function () { setTimeout( function () { table.buttons().container().appendTo( '#tblliste_wrapper .col-md-6:eq(0)' ); }, 10 ); }
	});
}
</script>