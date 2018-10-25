<section class="container-fluid blanc">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<h1>Gestion des membres</h1>
			<?php 
			if (isset($_SESSION['virtuel']))
			{
				?>
				<p>
					Vous êtes actuellement connecté sur une session "virtuel" en tant que <b><?php echo ''.$_SESSION['prenom'].' '.$_SESSION['nom'].'';?></b><br />
					Vous ne pouvez pas modifier/supprimer de membre sous cette session.
				</p>
				<?php
			}
			?>
		</div>
	</header>
	<div class="row">
		<div class="col-md-12 col-lg-12">
			<h2 class="h3">Définition des droits</h2>
			<ul>
				<li>0 : Simple membre</li>
				<li>1 : Peut exporter des données autres que les siennes</li>
				<li>2 : Validateur d'un ou plusieurs observatoires</li>
				<li>3 : Gestion des espèces, des actualités, des photos, de la biblio. Peux être également validateur</li>
				<li>4 : Administrateur du site (vous)</li>
			</ul>
			<p>Actif : oui = inscription confirmée.</p>
			<h2 class="h3">Liste des membres <small id="nb"></small></h2>
			<table id="liste" class="table table-hover table-sm" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th></th>
						<th></th>
						<th></th>
						<th>Membre</th>
						<th>Id</th>
						<th>Droits</th>
                        <th>Structure(s)</th>
						<th>Validateur</th>
						<th>Gestion</th>
						<th>Floutage</th>
						<th>TypeDon</th>
						<th>latin</th>
						<th>Dernière connection</th>
						<th>Actif</th>
						<th>Mail</th>
					</tr>
				</thead>
				<tbody id="listemembre"></tbody>
			</table>
		</div>
	</div>
</section>
<!-- Boites de dialogues -->
<div id="dia1" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Modifier un membre</h4>
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
								<label for="mail" class="col-sm-2 col-form-label">mail</label>
								<div class="col-sm-8"><input type="text" class="form-control" id="mail"></div>
							</div>
							<div class="form-group row">
								<label for="droits" class="col-sm-2 col-form-label">Droits</label>
								<div class="col-sm-3"><input type="number" min="0" max="4" class="form-control" id="droits"></div>
							</div>
                            <div class="form-group row ui-front">
                                <label for="structures" class="col-sm-2 col-form-label">Structure(s)</label>
                                <div class="col-sm-8"><input type="text" class="form-control" id="structures" disabled></div>
                            </div>
							<div class="form-group row ui-front">
								<label for="theme" class="col-sm-2 col-form-label">Validateur</label>
								<div class="col-sm-8"><input type="text" class="form-control" id="theme"></div>
							</div>
							<div class="form-group row ui-front">
								<label for="gestion" class="col-sm-2 col-form-label">Gestion</label>
								<div class="col-sm-8"><input type="text" class="form-control" id="gestion"></div>
							</div>	
							<input id="idmembredia1" type="hidden">
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
				<input id="idmembredia2" type="hidden"><input id="nomdia2" type="hidden"><input id="prenomdia2" type="hidden">
				<input id="droitsdia2" type="hidden"><input id="obserdia2" type="hidden"><input id="latindia2" type="hidden"><input id="floutagedia2" type="hidden">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Annuler</button>
				<button type="button" class="btn btn-success" data-dismiss="modal" id="bttdia2">Oui</button>
			</div>
		</div>
	</div>
</div>
<script>
$(document).ready(function() {
	'use strict'; membre();
});
function membre() {
	'use strict';
	$.post('modeles/ajax/membre/listemembre.php', {}, function(reponse){
		$('#listemembre').html(reponse.liste); $('#nb').html(' ('+ reponse.nb +')');
		if ($.fn.dataTable.isDataTable( '#liste' )){
			$('#liste').DataTable();
		} else {
			$('#liste').DataTable({
				"language": { "url":"../dist/js/datatables/france.json" },
				"order": [],
				"columnDefs": [{ "orderable": false, "targets": 0 },{ "orderable": false, "targets": 1 },{ "orderable": false, "targets": 2 }]
			});
		}			
	});
}
//modifier un membre
function modifier(id) {
	'use strict';
	$.ajax({
		url: "modeles/ajax/membre/cherchemembre.php", type: 'POST', dataType: "json", data: {id:id},
		success: function(reponse) {
			if (reponse.statut == 'Ok') {
				var info = reponse.info;
				$("#nom").val(info.nom); $("#prenom").val(info.prenom); $("#mail").val(info.mail); $("#droits").val(info.droits); $("#theme").val(info.discipline); $("#gestion").val(info.gestionobs); $("#idmembredia1").val(info.idmembre);
				if (info.droits < 2) {
					$("#theme").hide(); $("#gestion").hide();
				} else {
					if (info.droits < 3) {
						$("#theme").show(); $("#gestion").hide();
					} else {
						$("#theme").show(); $("#gestion").show();
					}					
				}
				$('#dia1').modal('show');					
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
	'use strict'; $('#dia1').modal('hide');
	var id = $("#idmembredia1").val(), nom = $("#nom").val(), prenom = $("#prenom").val(), mail = $("#mail").val(), droits = $("#droits").val(), disc = $("#theme").val(), gestion = $("#gestion").val();
	$.ajax({
		url: "modeles/ajax/membre/modmembre.php", 
		type: 'POST', 
		dataType: "json",
		data: {id:id,nom:nom,prenom:prenom,mail:mail,droits:droits,disc:disc,gestion:gestion},
		success: function(reponse) {
			if (reponse.statut == 'Ok') {
				membre();
			}
			else {
				alert (reponse.statut);
			}
		},
		error: function(err) {
			alert("Une erreure est survenue");
		}
	});		
});
$('#droits').change(function() {
	'use strict';
	var droits = $("#droits").val();
	if (droits < 2) {
		$("#theme").val(''); $("#gestion").val(''); $("#theme").hide(); $("#gestion").hide();		
	} else {
		if (droits < 3) {
			$("#theme").show(); $("#gestion").hide();
		} else {
			$("#theme").show(); $("#gestion").show();
		}					
	}	
});
//auto validateur et gestion
$(function() {
	'use strict';
	function extractLast( term ) {
		return split( term ).pop();
	}
	function split( val ) {
		return val.split( /,\s*/ );
	}
	var choix = <?php echo json_encode($discipline);?>;
   	$('#theme').autocomplete({
		source: function( request, response ) { response( $.ui.autocomplete.filter(choix, extractLast( request.term ))); },
		position : { my : 'bottom', at : 'top' },
		focus : function(){ return false; },
		select: function( event, ui ) {
			var terms = split( this.value );
         	terms.pop();
         	terms.push( ui.item.value );
         	terms.push("");
			this.value = terms.join(", ");
			return false;
        }		 
    });
	$('#gestion').autocomplete({
		source: function( request, response ) { response( $.ui.autocomplete.filter(choix, extractLast( request.term ))); },
		position : { my : 'bottom', at : 'top' },
		focus : function(){ return false; },
		select: function( event, ui ) {
			var terms = split( this.value );
         	terms.pop();
         	terms.push( ui.item.value );
         	terms.push("");
			this.value = terms.join(", ");
			return false;
        }		 
    });
});
//session virtuel
function virtuel(id) {
	'use strict';
	$.ajax({
		url: "modeles/ajax/membre/cherchemembre.php", type: 'POST', dataType: "json", data: {id:id},
		success: function(reponse) {
			if (reponse.statut == 'Ok') {
				var info = reponse.info;
				var n = info.nom +' '+ info.prenom;
				$("#N").val(n); $("#idmembredia2").val(info.idmembre); $("#nomdia2").val(info.nom); $("#prenomdia2").val(info.prenom);
				$("#droitsdia2").val(info.droits); $("#obserdia2").val(info.obser); $("#latindia2").val(info.latin); $("#floutagedia2").val(info.floutage);
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
	'use strict';
	var nom = $("#nomdia2").val(), prenom = $("#prenomdia2").val(), idmembre = $("#idmembredia2").val(), droits = $("#droitsdia2").val(), obser = $("#obserdia2").val(), latin = $("#latindia2").val(), floutage = $("#floutagedia2").val();
	$.ajax({
		url: "modeles/ajax/membre/virtuel.php", type: 'POST', dataType: "json", data: {idmembre:idmembre,nom:nom,prenom:prenom,droits:droits,obser:obser,latin:latin,floutage:floutage},
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
//supprimer membre
function supmembre(id){
	alert('A faire...Pourra permettre de supprimer membre "indésirable". Verification si pas de donnée à faire');	
}		
</script>