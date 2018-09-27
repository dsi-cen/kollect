<section class="container blanc">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<h1>Gestion des auteurs</h1>
		</div>
	</header>	
	<div class="row">
		<div class="col-md-12 col-lg-12">
			<p>Ajouter un auteur en cliquant sur <span id="BttP" class="curseurlien" title="Créer un auteur"><i class="fa fa-plus text-success"></i></span></p>
			A finir
			<h2>Liste des auteurs <span id="nb"></span></h2>
			<table id="liste" class="table table-hover table-sm" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th></th>
						<th></th>
						<th>Id</th>
						<th>Nom</th>
						<th>Prénom</th>
						<th>Prénom ab.</th>
					</tr>
				</thead>
				<tbody id="listeauteur"></tbody>
			</table>
		</div>
	</div>
</section>
<div id="dia1" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Ajouter un auteur</h4>
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
								<label for="ab" class="col-sm-3 col-form-label">Prénom ab.</label>
								<div class="col-sm-5"><input type="text" class="form-control" id="ab"></div>
							</div>
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
<!--<div id="dia1" class="dialogue" title="Ajouter un auteur">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">		
				<form class="form-horizontal">
					<div class="form-group">
						<label for="Noma" class="col-sm-3 control-label">Nom</label>
						<div class="col-sm-9"><input type="text" class="form-control" id="Noma"></div>
					</div>
					<div class="form-group">
						<label for="Prenoma" class="col-sm-3 control-label">Prénom</label>
						<div class="col-sm-9"><input type="text" class="form-control" id="Prenoma"></div>
					</div>
					<div class="form-group">
						<label for="Prenomab" class="col-sm-5 control-label">Prénom ab.</label>
						<div class="col-sm-7"><input type="text" class="form-control" id="Prenomab"></div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<div id="dia2" class="dialogue" title="Doublon auteur">
	<p id="doublon"></p>
	<p>Voulez vous vraiment l'insérer ?</p>
</div>
<div id="dia3" class="dialogue" title="Suppression">
	Voulez vraiment supprimer cet auteur ?
</div>
<div id="dia4" class="dialogue" title="Modifier un auteur">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">		
				<form class="form-horizontal">
					<div class="form-group">
						<label for="Nom" class="col-sm-3 control-label">Nom</label>
						<div class="col-sm-9"><input type="text" class="form-control" id="Nom"></div>
					</div>
					<div class="form-group">
						<label for="Prenom" class="col-sm-3 control-label">Prénom</label>
						<div class="col-sm-9"><input type="text" class="form-control" id="Prenom"></div>
					</div>
					<div class="form-group">
						<label for="Prenomb" class="col-sm-5 control-label">Prénom ab.</label>
						<div class="col-sm-7"><input type="text" class="form-control" id="Prenomb"></div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<div id="dia5" class="dialogue" title="Information">
	<p>
	Pour des raisons de cohérence il n'est pas possible de le supprimer.<br />
	Il faut auparavant le supprimer des références biblio suivantes : <b><span id="idbiblio"></span></b>
	</p>
</div>-->
<script>
/*$(document).ready(function() {
	auteur();
});
//ajout
$('#bttplus').click(function(){
	$("#Noma").val('');$("#Prenoma").val('');$("#Prenomab").val('');
	$("#dia1").dialog({
		resizable: false,
		dialogClass: "no-close",
		buttons: [{
			text: "Valider",
			click: function() {
				$( this ).dialog( "close" );
				var nom = $("#Noma").val();
				var prenom = $("#Prenoma").val();
				var prenomab = $("#Prenomab").val();
				var dble = 'non';
				inserauteur(nom,prenom,prenomab,dble);						
			},
			"class":"ui-button-success"
		},{
			text: "Annuler",
			click: function() {
				$(this).dialog( "close" );
			},
			"class":"ui-button-warning"
		}]
	});
});
function inserauteur(nom,prenom,prenomab,dble) {
	$.ajax({
		url: "modeles/ajax/biblio/inserauteur.php", 
		type: 'POST', 
		dataType: "json",
		data: {nom:nom,prenom:prenom,prenomab:prenomab,dble:dble},
		success: function(reponse) {
			var statut = reponse.statut;
			if (statut == 'Ok') {
				auteur();
			} else {
				if (reponse.doublon == 'oui'){
					doublon(nom,prenom,prenomab,statut);
				} else {
					alert (statut);
				}
			}
		},
		error: function(err) {
			alert("Une erreure est survenue");
		}
	});		
}
function doublon(nom,prenom,prenomab,statut){
	$('#doublon').html(statut);
	$("#dia2").dialog({
		resizable: false,
		dialogClass: "no-close",
		buttons: [{
			text: "Oui",
			click: function() {
				$( this ).dialog( "close" );
				var dble = 'oui';
				inserauteur(nom,prenom,prenomab,dble);						
			},
			"class":"ui-button-success"
		},{
			text: "Annuler",
			click: function() {
				$(this).dialog( "close" );
			},
			"class":"ui-button-warning"
		}]
	});
}
//supprime
function supobs(id){
	$("#dia3").dialog({
		resizable: false,
		dialogClass: "no-close",
		buttons: [{
			text: "Oui",
			click: function() {
				$( this ).dialog( "close" );
				$.ajax({
					url: "modeles/ajax/biblio/supauteur.php", 
					type: 'POST', 
					dataType: "json",
					data: {id:id},
					success: function(reponse) {
						var statut = reponse.statut.Ok;
						if (statut == 'Ok') {
							auteur();
						} else {
							if (statut == 'non'){
								$('#idbiblio').html(reponse.liste);
								$("#dia5").dialog();
							} else {
								alert('Un problème est survenue');
							}							
						}
					}
				});			
			},
			"class":"ui-button-success"
		},{
			text: "Non",
			click: function() {
				$(this).dialog( "close" );
			},
			"class":"ui-button-warning"
		}]
	});
}
//modifier
function modifier(id,nom,prenom,prenomab) {
	$("#Nom").val(nom);
	$("#Prenom").val(prenom);
	$("#Prenomb").val(prenomab);
	$("#dia4").dialog({
		resizable: false,
		dialogClass: "no-close",
		buttons: [{
			text: "Valider",
			click: function() {
				$( this ).dialog( "close" );
				var nomm = $("#Nom").val();
				var prenomm = $("#Prenom").val();
				var prenommab = $("#Prenomb").val();
				$.ajax({
					url: "modeles/ajax/biblio/modauteur.php", 
					type: 'POST', 
					dataType: "json",
					data: {id:id, nom:nomm, prenom:prenomm, prenomab:prenommab},
					success: function(reponse) {
						var statut = reponse.statut.Ok;
						if (statut == 'Ok') {
							auteur();
						} else {
							var statut = reponse.statut;
							alert (statut);
						}
					},
					error: function(err) {
						alert("Une erreure est survenue");
					}
				});				
			},
			"class":"ui-button-success"
		},{
			text: "Annuler",
			click: function() {
				$(this).dialog( "close" );
			},
			"class":"ui-button-warning"
		}]
	});
}
function auteur() {
	$.ajax({
		url: 'modeles/ajax/biblio/listeauteur.php',
		type: 'POST', 
		success: function(reponse) {
			$('#listeauteur').html(reponse);
			if ($.fn.dataTable.isDataTable( '#liste' )){
				table = $('#liste').DataTable();
			}
			else {
				table = $('#liste').DataTable({
					"language": {
					"url":"../js/france.txt"
					},
					"columnDefs": [
						{ "orderable": false, "targets": 0 },{ "orderable": false, "targets": 1 }
					],					
					"paging":   true,
					"info":     true,
					"searching": true
				});
			}
		},
		error: function(){
			alert('erreure...');
		}			
	});
}	*/
</script>	