<section class="container blanc">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<h1>Gestion des structures / organismes</h1>
		</div>
	</header>
	<div class="row">
		<div class="col-md-12 col-lg-12">
			<p>Ajouter un organsime en cliquant sur <span id="BttP" class="curseurlien" title="Créer un organisme"><i class="fa fa-plus text-success"></i></span></p>
			<h2>Liste des organismes <small id="nb"></small></h2>
			<table id="liste" class="table table-hover table-sm" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th></th>
						<th>Id</th>
						<th>Nom</th>
						<th>Description</th>
						<th class="">Nb fiche</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</section>
<!-- Boites de dialogues -->
<div id="dia1" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Modifier un organisme</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<form class="form">
							<div class="form-group row">
								<label for="orga" class="col-sm-2 col-form-label">Organisme</label>
								<div class="col-sm-10"><input type="text" class="form-control" id="orga"></div>
							</div>
							<div class="form-group row">
								<label for="descri" class="col-sm-2 col-form-label">Description</label>
								<div class="col-sm-10"><input type="text" class="form-control" id="descri"></div>
							</div>
							<input id="idobsdia1" type="hidden">
						</form>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Annuler</button>
				<button type="button" class="btn btn-success" id="bttdia1">Valider</button>
			</div>
		</div>
	</div>
</div>
<div id="dia4" class="modal" tabindex="-1" role="dialog" aria-labelledby="Modalajoutobs" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Ajouter un organisme</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<form class="form">
							<div class="form-group row">
								<label for="orgas" class="col-sm-2 col-form-label">Organisme</label>
								<div class="col-sm-10"><input type="text" class="form-control" id="orgas"></div>
							</div>
							<div class="form-group row">
								<label for="descris" class="col-sm-2 col-form-label">Description</label>
								<div class="col-sm-10"><input type="text" class="form-control" id="descris"></div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Annuler</button>
				<button type="button" class="btn btn-success" id="bttdia4">Valider</button>
			</div>
		</div>
	</div>
</div>
<script>
var table;
$(document).ready(function() {
	'use strict'; organisme();
});
function organisme() {
	'use strict';
	$.post('modeles/ajax/organisme/listeorga.php', {}, function(reponse) {
		$('#nb').html(' ('+ reponse.nb +')');
		if ($.fn.dataTable.isDataTable( '#liste' )) {
			table.destroy(); 
			table = $('#liste').DataTable({
				language : { url: "../dist/js/datatables/france.json" },
				data : reponse.liste, deferRender: true, scrollY: 500, scrollCollapse: true, scroller: true,
				"columnDefs": [{ "orderable": false, "targets": 0 }, {"className": "dt-center", "targets": [4] }],
				buttons: [ { extend: 'csvHtml5', title:'liste organisme' },{ extend: 'excelHtml5', title:'liste organisme' } ],
				initComplete: function () {
					setTimeout( function () { table.buttons().container().appendTo( '#liste_wrapper .col-md-6:eq(0)' ); }, 10 );
				}
			});
		} else {
			table = $('#liste').DataTable({
				language : { url: "../dist/js/datatables/france.json" },
				data : reponse.liste, deferRender: true, scrollY: 500, scrollCollapse: true, scroller: true,
				"columnDefs": [{ "orderable": false, "targets": 0 }, {"className": "dt-center", "targets": [4] }],
				buttons: [ { extend: 'csvHtml5', title:'liste organisme' },{ extend: 'excelHtml5', title:'liste organisme' } ],
				initComplete: function () {
					setTimeout( function () { table.buttons().container().appendTo( '#liste_wrapper .col-md-6:eq(0)' ); }, 10 );
				}
			});
		}	
	});
}
//modifier
function modifier(id) {
	'use strict';
	$.ajax({
		url: "modeles/ajax/organisme/chercheorga.php", type: 'POST', dataType: "json", data: {id:id},
		success: function(reponse) {
			if (reponse.statut == 'Ok') {
				$('#orga').val(reponse.info.organisme); $('#descri').val(reponse.info.descri); $('#idobsdia1').val(reponse.info.idorg); $('#dia1').modal('show');
			} else {
				alert (reponse.statut);
			}
		},
		error: function(err) {
			alert("Une erreure est survenue");
		}
	});	
}
$('#bttdia1').click(function () { 
	'use strict';
	$('#dia1').modal('hide');
	var orga = $('#orga').val(), descri = $('#descri').val(), id = $('#idobsdia1').val();
	$.ajax({
		url: "modeles/ajax/organisme/modorga.php", type: 'POST', dataType: "json", data: {id:id,orga:orga,descri:descri},
		success: function(reponse) {
			if (reponse.statut == 'Ok') {
				organisme();
			} else {
				alert (reponse.statut);
			}
		},
		error: function(err) {
			alert("Une erreure est survenue");
		}
	});		
});		
//ajout
$('#BttP').click(function(){
	'use strict';
	$("#orgas").val(''); $("#descris").val(''); $('#dia4').modal('show');
});
$('#bttdia4').click(function () { 
	'use strict';
	$('#dia4').modal('hide');
	var orga = $("#orgas").val(), descri = $("#descris").val();
	inserorga(orga,descri);	
});
function inserorga(orga,descri) {
	'use strict';
	$.ajax({
		url: 'modeles/ajax/organisme/inserorga.php', type: 'POST', dataType: "json", data: {orga:orga,descri:descri},
		success: function(reponse) {
			if (reponse.statut == 'Ok') {
				organisme();
			} else {
				alert (reponse.statut);
			}
		},
		error: function(err) { alert("Une erreure est survenue"); }
	});
}
//supprimer
function sup(id) {
	'use strict';
	$.ajax({
		url: 'modeles/ajax/organisme/suporga.php', type: 'POST', dataType: "json", data: {id:id},
		success: function(reponse) {
			if (reponse.statut == 'Oui') { organisme();	} 	
		}
	});	
}	
</script>