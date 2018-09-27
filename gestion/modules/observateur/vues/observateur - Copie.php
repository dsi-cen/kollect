<section class="container blanc">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<h1>Gestion des observateurs</h1>
			<?php 
			if(isset($_SESSION['virtuel']))
			{
				?>
				<p>
					Vous êtes actuellement connecté sur une session "virtuel" en tant que <b><?php echo ''.$_SESSION['prenom'].' '.$_SESSION['nom'].'';?></b><br />
					Vous ne pouvez pas modifier/supprimer des observayeurs sous cette session.
				</p>
				<?php
			}
			?>
		</div>
	</header>
	<div class="row">
		<div class="col-md-12 col-lg-12">
			<p>Ajouter un observateur en cliquant sur <span id="BttP" class="curseurlien" title="Créer un observateur"><i class="fa fa-plus text-success"></i></span></p>
			<h2>Liste des observateurs <small id="nb"></small></h2>
			<table id="liste" class="table table-hover table-sm" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th></th>
						<th class="">Id</th>
						<th>Nom</th>
						<th>Prénom</th>
						<th>Nb obs</th>
						<th>Idmembre</th>
						<th>Affichage</th>
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
				<h4 class="modal-title">Modifier un observateur</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<form class="form">
							<div class="form-group row">
								<label for="nom" class="col-sm-2 col-form-label">Nom</label>
								<div class="col-sm-8"><input type="text" class="form-control" id="nom"></div>
							</div>
							<div class="form-group row">
								<label for="prenom" class="col-sm-2 col-form-label">Prénom</label>
								<div class="col-sm-8"><input type="text" class="form-control" id="prenom"></div>
							</div>
							<div class="form-group row">
								<label for="idm" class="col-sm-2 col-form-label">Idmembre</label>
								<div class="col-sm-3"><input type="number" class="form-control" id="idm"></div>
							</div>
							<div class="form-group row">
								<label for="aff" class="col-sm-2 col-form-label">Affichage</label>
								<div class="col-sm-3">
									<select id="aff" class="form-control">
										<option value="oui">Oui</option>
										<option value="non">Non</option>
									</select>
								</div>
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
<div id="dia2" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<p>
					Vous aller être connecter en tant que <b><output id="N"></output></b><br />
					Vous devez ensuite vous déconnecter et reconnecter avec vos identifiants
				</p>
				<input id="idobsdia2" type="hidden"><input id="nomdia2" type="hidden"><input id="prenomdia2" type="hidden">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Annuler</button>
				<button type="button" class="btn btn-success" data-dismiss="modal" id="bttdia2">Oui</button>
			</div>
		</div>
	</div>
</div>
<div id="dia3" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<p>Il n'est pas possible de supprimer un observateur ayant des relevés dans la base</p>	
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Fermer</button>				
			</div>
		</div>
	</div>
</div>
<div id="dia4" class="modal" tabindex="-1" role="dialog" aria-labelledby="Modalajoutobs" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Ajouter un observateur</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<form class="form">
							<div class="form-group row">
								<label for="nomobs" class="col-sm-2 col-form-label">Nom</label>
								<div class="col-sm-8"><input type="text" class="form-control" id="nomobs"></div>
							</div>
							<div class="form-group row">
								<label for="prenomobs" class="col-sm-2 col-form-label">Prénom</label>
								<div class="col-sm-8"><input type="text" class="form-control" id="prenomobs"></div>
							</div>
							<div class="form-group row">
								<label for="idmobs" class="col-sm-2 col-form-label">Idmembre</label>
								<div class="col-sm-3"><input type="number" class="form-control" id="idmobs"></div>
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
<div id="dia5" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Information doublon</h4>
			</div>
			<div class="modal-body">
				<p>Attention ! Il existe déjà un observateur <b><output id="doublon"></output></b><br />Cliqué sur "insérer" si il s'agit pas d'un doublon</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Annuler</button>
				<button type="button" class="btn btn-success" data-dismiss="modal" id="bttdia5">Insérer</button>
			</div>
		</div>
	</div>
</div>
<div id="dia6" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<p id="nbiddet"></p>	
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Fermer</button>				
			</div>
		</div>
	</div>
</div>
<script>
var table;
$(document).ready(function() {
	'use strict'; observateur();
});
function observateur() {
	'use strict';
	$.post('modeles/ajax/observateur/listeobservateur.php', {}, function(reponse) {
		$('#nb').html(' ('+ reponse.nb +')');
		if ($.fn.dataTable.isDataTable( '#liste' )) {
			table.destroy(); 
			remplirtable(reponse.liste);
			/*table = $('#liste').DataTable({
				language : { url: "../dist/js/datatables/france.json" },
				data : reponse.liste, deferRender: true, scrollY: 500, scrollCollapse: true, scroller: true,
				"columnDefs": [{ "orderable": false, "targets": 0 }, {"className": "dt-center", "targets": [4,5] }],
				buttons: [ { extend: 'csvHtml5', title:'liste observateurs' },{ extend: 'excelHtml5', title:'liste observateurs' } ],
				initComplete: function () {
					setTimeout( function () { table.buttons().container().appendTo( '#liste_wrapper .col-md-6:eq(0)' ); }, 10 );
				}
			});*/
		} else {
			remplirtable(reponse.liste);
			/*table = $('#liste').DataTable({
				language : { url: "../dist/js/datatables/france.json" },
				data : reponse.liste, deferRender: true, scrollY: 500, scrollCollapse: true, scroller: true,
				"columnDefs": [{ "orderable": false, "targets": 0 }, {"className": "dt-center", "targets": [4,5] }],
				buttons: [ { extend: 'csvHtml5', title:'liste observateurs' },{ extend: 'excelHtml5', title:'liste observateurs' } ],
				initComplete: function () {
					setTimeout( function () { table.buttons().container().appendTo( '#liste_wrapper .col-md-6:eq(0)' ); }, 10 );
				}
			});*/
		}		
	});
}
function remplirtable(data) {	
	table = $('#liste').DataTable({
		language : { url: "../dist/js/datatables/france.json" },
		data : data,
		deferRender: true, scrollY: 600, scrollCollapse: true, scroller: true,
		//order: [],
		buttons: [ { extend: 'csvHtml5', title:'liste observateurs' },{ extend: 'excelHtml5', title:'liste observateurs' } ],
		initComplete: function () {
			setTimeout( function () { table.buttons().container().appendTo( '#liste_wrapper .col-md-6:eq(0)' ); }, 10 );
		}
	});		
}
//modifier un membre
function modifier(id) {
	'use strict';
	$.ajax({
		url: "modeles/ajax/observateur/chercheobser.php", type: 'POST', dataType: "json", data: {id:id},
		success: function(reponse) {
			if (reponse.statut == 'Ok') {
				var info = reponse.info;
				$("#nom").val(info.nom); $("#prenom").val(info.prenom); $("#idm").val(info.idm); $("#idobsdia1").val(info.idobser); $('#aff').val(info.aff); $('#dia1').modal('show');
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
	var nom = $('#nom').val(), prenom = $('#prenom').val(), idm = $('#idm').val(), id = $('#idobsdia1').val(), aff = $('#aff').val();
	$.ajax({
		url: "modeles/ajax/observateur/modobser.php", type: 'POST', dataType: "json", data: {id:id,nom:nom,prenom:prenom,idm:idm,aff:aff},
		success: function(reponse) {
			if (reponse.statut == 'Ok') {
				observateur();
			} else {
				alert (reponse.statut);
			}
		},
		error: function(err) {
			alert("Une erreure est survenue");
		}
	});		
});		
//session virtuel
function virtuel(id) {
	'use strict';
	$.ajax({
		url: "modeles/ajax/observateur/chercheobser.php", type: 'POST', dataType: "json", data: {id:id},
		success: function(reponse) {
			if (reponse.statut == 'Ok') {
				var info = reponse.info;
				var n = info.nom +' '+ info.prenom;
				$("#N").val(n); $("#idobsdia2").val(info.idobser); $("#nomdia2").val(info.nom); $("#prenomdia2").val(info.prenom);
				$('#dia2').modal('show');				
			} else {
				alert (reponse.statut);
			}
		},
		error: function(err) {
			alert("Une erreure est survenue");
		}
	});	
}	
$('#bttdia2').click(function () { 
	var nom = $("#nomdia2").val(), prenom = $("#prenomdia2").val(), idobser = $("#idobsdia2").val();
	$.ajax({
		url: "modeles/ajax/observateur/virtuel.php", type: 'POST', dataType: "json", data: {idmembre:idobser,nom:nom,prenom:prenom},
		success: function(reponse) {
			if (reponse.statut == 'Ok') {
				document.location.href='../';
			}
			else {
				alert(reponse.statut);
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
	$("#nomobs").val(''); $("#prenomobs").val(''); $("#idmobs").val(''); $('#dia4').modal('show');
});
$('#bttdia4').click(function () { 
	'use strict';
	$('#dia4').modal('hide');
	var nom = $("#nomobs").val().toUpperCase(), prenom = $("#prenomobs").val().toLowerCase(), idm = $("#idmobs").val(), doublon = 'non';
	inserobservateur(nom,prenom,idm,doublon);	
});
function inserobservateur(nom,prenom,idm,doublon) {
	'use strict';
	$.ajax({
		url: 'modeles/ajax/observateur/inserobservateur.php', type: 'POST', dataType: "json", data: {nom:nom,prenom:prenom,idm:idm,doublon:doublon},
		success: function(reponse) {
			if (reponse.statut == 'Ok') {
				if (reponse.doublon) {
					endoublon(nom,prenom,idm);
				} else {
					observateur();
				}
			} else {
				alert (reponse.statut);
			}
		},
		error: function(err) { alert("Une erreure est survenue"); }
	});
}
function endoublon(nom,prenom,idm) {
	'use strict';
	$("#doublon").val(nom+' '+prenom); $('#dia5').modal('show');	
};
$('#bttdia5').click(function () { 
	'use strict';
	var nom = $("#nomobs").val().toUpperCase(), prenom = $("#prenomobs").val().toLowerCase(), idm = $("#idmobs").val(), doublon = 'oui';
	inserobservateur(nom,prenom,idm,doublon);	
});	
//supprimer observateur
function supobser(id) {
	'use strict';
	$.ajax({
		url: 'modeles/ajax/observateur/supobserdet.php', type: 'POST', dataType: "json", data: {id:id},
		success: function(reponse) {
			if (reponse.statut == 'Oui') { 
				if (reponse.nbdt > 0 || reponse.nbco > 0) {					
					$('#nbiddet').html('Il n\'est pas possible de supprimer cet observateur (enregistré comme déterminateur pour <b>'+ reponse.nbdt +'</b> observation), et/ou comme co-observateur pour <b>'+ reponse.nbco +'</b> observation.');
					$('#dia6').modal('show');
				} else {
					observateur();
				}				
			} 	
		}
	});	
}
function supobsern(id) {
	$('#dia3').modal('show');	
}		
</script>