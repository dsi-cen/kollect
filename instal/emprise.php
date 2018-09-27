<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="author" content="Denis Vandromme">
		<meta name="robots" content="noindex,nofollow" />
		<title>Installation 2</title>
		<link href="../dist/css/gestion.css" rel="stylesheet">
		<link type="text/css" href="../dist/css/jquery-ui.css" rel="stylesheet" />
		<link type="text/css" href="ui.multiselect.css" rel="stylesheet" />	
		<style type="text/css">
			body {padding-top: 0px;}
			.radio label{font-weight: 600;}
			.multiselect {width: 500px;height: 200px;}
			select[multiple] {height: 300px;}
		</style>
		<script src="../dist/js/jquery.js"></script> 
		<script src="../dist/js/jquery-auto.js"></script>
		<script src="ui.multiselect.js"></script>
	</head>
	<body>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Connexion à la base, création de l'application</a></li>
			<li class="breadcrumb-item active">Emprise du site</li>
			<li class="breadcrumb-item"><a href="#">Vérification et maillage</a></li>
			<li class="breadcrumb-item"><a href="#">Configuration du site</a></li>
		</ol>
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-lg-12">
					<header>
						<h1 class="text-xs-center">Installation de l'application</h1>
						<hr />
					</header>
				</div>
			</div>
			<div class="row mt-2">
				<div class="col-md-6 col-lg-6">
					<h2>Emprise du site</h2>
					<br />					
					<div class="form-check">
						<label class="form-check-label"><input class="form-check-input" type="radio" name="radios1" id="fr" value="fr"> France entière.</label>
					</div>
					<div class="form-check">
						<label class="form-check-label"><input class="form-check-input" type="radio" name="radios1" id="reg" value="reg"> Une ou plusieurs régions.</label>
					</div>
					<div class="form-check">
						<label class="form-check-label"><input class="form-check-input" type="radio" name="radios1" id="dep" value="dep"> Un ou plusieurs départements.</label>
					</div>
					<div class="form-check">
						<label class="form-check-label"><input class="form-check-input" type="radio" name="radios1" id="com" value="com"> Une ou plusieurs communes (pas forcément dans le même département)</label>
					</div>
					<div class="form-check">
						<label class="form-check-label"><input class="form-check-input" type="radio" name="radios1" id="pnr" value="pnr"> Un Parc Naturel Régional</label>
					</div>
					<div class="form-check">
						<label class="form-check-label"><input class="form-check-input" type="radio" name="radios1" id="pn" value="pn"> Un Parc National</label>
					</div>
					<div class="form-check">
						<label class="form-check-label"><input class="form-check-input" type="radio" name="radios1" id="massif" value="massif"> Un Massif (emprise DATAR)</label>
					</div>										
				</div>
				<div class="col-md-6 col-lg-6" id="choix">
					<h3>Votre choix</h3>
					<p id="labchoix"></p>
					<form class="form" id="edep">
						<div class="form-group row" id="ereg">
							<label for="rreg" class="col-sm-4 col-form-label">Choisir une régions</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="rreg">
							</div>
						</div>
						<div class="form-group row" id="edep1">
							<label for="rdep" class="col-sm-4 col-form-label">Choisir un département</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="rdep">
							</div>
						</div>
						<div class="form-group row" id="epnr">
							<label for="rpnr" class="col-sm-3 col-form-label">Choisir un PNR</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="rpnr">
							</div>
						</div>
						<div class="form-group row" id="epn">
							<label for="rpn" class="col-sm-4 col-form-label">Choisir un Parc National</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="rpn">
							</div>
						</div>
						<div class="form-group row" id="emassif">
							<label for="rmassif" class="col-sm-4 col-form-label">Choisir un Massif</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="rmassif">
							</div>
						</div>
						<div id="ecom">
							<div class="radio">
								<label><input type="radio" name="inlineRadioOptions" id="toute" value="toute"> Toutes les communes du département</label>
							</div>
							<div class="radio">
								<label><input type="radio" name="inlineRadioOptions" id="plus" value="plus"> Seulement certaines communes de ce département</label>
							</div>
						</div>
						<div id="listeselect"></div>
						<div id="rajparc">
							<button type="button" class="btn btn-success" id="BttParc">Valider</button>
						</div>
						<div id="rajreg">
							<p>Rajouter une région ou valider</p>
							<button type="button" class="btn btn-success" id="BttR">Valider</button>
						</div>
						<div id="rajdep">
							<p>Rajouter un département ou valider</p>
							<button type="button" class="btn btn-success" id="BttD">Valider</button>
						</div>
						<div id="rajcom">
							<p>Rajouter un département et/ou commune ou valider</p>
							<button type="button" class="btn btn-success" id="BttC">Valider</button>
							<button type="button" class="btn btn-success" id="BttCp">Rajouter département/commune</button>
						</div>
					</form>
					<div id="listedep"></div>
					<button type="button" class="btn btn-success" id="BttF">Valider</button>					
					<div id="valajax"><progress></progress></div>
					<div id="mes" class="mt-2"></div>					
				</div>				
			</div>
			<br />
			<div class="row">
				<div class="col-md-12 col-lg-12 mt-2">
					<div class="progress">
						<div id="av" class="progress-bar bg-success" style="width: 25%" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 col-lg-12 mt-2">
					<a class="btn btn-success" id="BttB" href="verifmaille.php">-> Vérification</a>
				</div>
			</div>
		</div>
		<input id="idparc" type="hidden"/><input id="listeidreg" type="hidden"/><input id="listeiddep" type="hidden"/><input id="listeiddep1" type="hidden"/><input id="listecom" type="hidden"/>
		<script>
			$(document).ready(function() {
				'use strict'; $('#BttB').hide(); $('#choix').hide(); $('#valajax').hide();
			});
			$('input[name=radios1]').change(function() {	
				$('#choix').show(); $('#mes').html(''); $('#BttB').hide(); $("#listeiddep").val(''); $("#listeiddep1").val(''); $("#listecom").val(''); $('#listeselect').html(''); $('#rajparc').hide();
				$('#av').css('width', '25%');
				var emp = $('input[name=radios1]:checked').val();
				if (emp == 'fr') {
					$('#labchoix').html('France entière'); $('#BttF').show(); $('#edep').hide(); $('#listedep').hide();
				}
				if (emp == 'reg') {
					$('#labchoix').html('Une ou plusieurs régions.'); $('#BttF').hide(); $('#edep').show(); $('#ereg').show(); $('#edep1').hide(); $('#rajdep').hide(); $('#rajcom').hide(); $('#ecom').hide(); $('#epnr').hide(); $('#epn').hide(); $('#emassif').hide(); $('#rajreg').hide();
					$('#rreg').focus();
				}
				if (emp == 'dep') {
					$('#labchoix').html('Un ou plusieurs départements.'); $('#BttF').hide(); $('#edep').show(); $('#edep1').show(); $('#rajdep').hide(); $('#rajcom').hide(); $('#ecom').hide(); $('#epnr').hide(); $('#epn').hide(); $('#emassif').hide(); $('#ereg').hide(); $('#rajreg').hide();
					$('#rdep').prop("disabled", false).css('cursor','Auto'); $('#rdep').focus();
				}
				if (emp == 'com') {
					$('#labchoix').html('Une ou plusieurs communes.');$('#BttF').hide();$('#edep').show();$('#edep1').show();$('#rajdep').hide();$('#rajcom').hide();$('#ecom').hide();$('#epnr').hide();$('#epn').hide();$('#emassif').hide(); $('#ereg').hide(); $('#rajreg').hide();
					$('#rdep').prop("disabled", false).css('cursor','Auto');$('#rdep').focus();
				}
				if (emp == 'pnr') {
					$('#labchoix').html('Un Parc Naturel Régional.');$('#BttF').hide();$('#edep').show();$('#epnr').show();$('#edep1').hide();$('#rajdep').hide();$('#rajcom').hide();$('#ecom').hide();$('#epn').hide();$('#emassif').hide(); $('#ereg').hide(); $('#rajreg').hide();
				}
				if (emp == 'pn') {
					$('#labchoix').html('Un Parc National.');$('#BttF').hide();$('#edep').show();$('#epn').show();$('#epnr').hide();$('#edep1').hide();$('#rajdep').hide();$('#rajcom').hide();$('#ecom').hide();$('#emassif').hide(); $('#ereg').hide(); $('#rajreg').hide();
				}
				if (emp == 'massif') {
					$('#labchoix').html('Un Massif.');$('#BttF').hide();$('#edep').show();$('#emassif').show();$('#epn').hide();$('#epnr').hide();$('#edep1').hide();$('#rajdep').hide();$('#rajcom').hide();$('#ecom').hide(); $('#ereg').hide(); $('#rajreg').hide();
				}
			});
			$('#BttF').click(function(){
				'use strict';
				$('#valajax').show();
				$.ajax({
					url: 'ajaxempfr.php', type: 'POST', dataType: "json", data: {},
					success: function(reponse) {
						var ok = reponse.statut;
						if (ok == 'Oui') {
							$('#BttF').hide(); $('#BttB').show(); $('#mes').html(reponse.mes); $('#av').css('width', '50%');
						} else {
							$('#mes').html(reponse.mes);
						}
						$('#valajax').hide(); 
					}
				});
			});
			$('#rreg').autocomplete({
				source: function(requete, reponse) {
					$.getJSON('ajaxreg.php', {term: requete.term}, function (data) {
						reponse($.map(data, function (item){
							return {label: item.region,value: item.idreg};
						}));
					});
				},
				select: function (event, ui) {
					$("#rreg").val(''); 
					var idreg = $('#listeidreg').val();
					if (!idreg) {
						$('#listeidreg').val(ui.item.value);
					} else {
						$('#listeidreg').val(idreg +','+ ui.item.value);
					}
					$('#labchoix').html('Une ou plusieurs régions.<br>'+ui.item.label); $('#rajreg').show();
					return false;	
				}
			});
			$('#rdep').autocomplete({
				source: function(requete, reponse) {
					$.getJSON('ajaxdep.php', {term: requete.term}, function (data) {
						reponse($.map(data, function (item){
							return {label: item.departement,value: item.iddep};
						}));
					});
				},
				select: function (event, ui) {
					$("#rdep").val('');
					var iddep = $('#listeiddep').val();
					var emp = $('input[name=radios1]:checked').val();
					if (emp == 'com') {
						$('#ecom').show(); $('#rajdep').hide();
						$('input[name=inlineRadioOptions]').attr('checked', false);
						$('input[name=inlineRadioOptions]').prop("disabled", false).css('cursor','Auto');
						$("#listeiddep").val("'"+ ui.item.value +"'");
					} else {
						$('#ecom').hide();
						if (!iddep) {
							$("#listeiddep").val("'"+ui.item.value+"'");
						} else {
							$("#listeiddep").val(iddep+",'"+ui.item.value+"'");
						}
						$('#rajdep').show(); $('#rajcom').hide();
					}					
					$('#labchoix').append('<br>'+ ui.item.value +' - '+ ui.item.label);					
					return false;	
				}
			});
			$('input[name=inlineRadioOptions]').change(function() {
				'use strict';
				var emp = $('input[name=inlineRadioOptions]:checked').val();
				var iddep = $('#listeiddep').val();
				$('#rdep').prop("disabled", true).css('cursor','Not-Allowed');
				if (emp == 'toute') {
					$('#labchoix').append(' <b>Toutes les communes</b>');	
					var iddep1 = $('#listeiddep1').val();
					if (!iddep1) {
						$("#listeiddep1").val(iddep);
					} else {
						$("#listeiddep1").val(iddep1+','+iddep);
					}
					$('#plus').prop("disabled", true).css('cursor','Not-Allowed');
				} 
				if (emp == 'plus') {					
					var id = iddep.substring(1,3);
					listecom(id);
					$('#labchoix').append(' <b>seulement certaines communes</b>');
					$('#toute').prop("disabled", true).css('cursor','Not-Allowed');
				}
				$('#rajcom').show();
			});
			$('#BttR').click(function() {
				'use strict';
				$('#valajax').show();
				var idreg = $('#listeidreg').val();
				$.ajax({
					url: 'ajaxempreg.php', type: 'POST', dataType: "json", data: {idreg:idreg},
					success: function(reponse) {
						var ok = reponse.statut;
						if (ok == 'Oui') {
							$('#BttR').hide(); $('#mes').html(reponse.mes); $('#BttB').show(); $('#av').css('width', '50%');
						} else {
							$('#mes').html(reponse.mes);
						}
						$('#valajax').hide();
					}
				});
			});
			$('#BttD').click(function() {
				'use strict';
				$('#valajax').show();
				var iddep = $('#listeiddep').val();
				$.ajax({
					url: 'ajaxempdep.php', type: 'POST', dataType: "json", data: {iddep:iddep},
					success: function(reponse) {
						var ok = reponse.statut;
						if (ok == 'Oui') {
							$('#BttD').hide(); $('#mes').html(reponse.mes); $('#BttB').show(); $('#av').css('width', '50%');
						} else {
							$('#mes').html(reponse.mes);
						}
						$('#valajax').hide();
					}
				});
			});
			function listecom(id) {
				'use strict';
				$('#valajax').show();
				var sel = $("#listecom").val();
				$.post('ajaxlistecom.php', {id:id,sel:sel}, function(listeselect){
					$('#listeselect').html(listeselect);
					$(".multiselect").multiselect();
					$('#valajax').hide();
				});				
			}
			$('#BttCp').click(function() {
				'use strict';
				var sel = "'";
				$('#multi option:selected').each(function () {
					sel += $(this).val() + "','";
				});
				if (sel != "'") {
					$("#listecom").val(sel);
				}				
				$('#rdep').prop("disabled", false).css('cursor','Auto'); $('#rdep').focus();
				$('input[name=inlineRadioOptions]').attr('checked', false);
			});
			$('#BttC').click(function() {
				'use strict';
				$('#valajax').show();
				var sel = "'";
				$('#multi option:selected').each(function () {
					sel += $(this).val() + "','";
				});
				if (sel != "'") {
					$("#listecom").val(sel);
				} else {
					$("#listecom").val('');
				}
				var idcom = $('#listecom').val();
				var iddep = $('#listeiddep1').val();
				if (!iddep) {
					if (!idcom) {
						$('#mes').html('<div class="alert alert-danger" role="alert"><p>Aucune commune de sélectionnée</p></div>');
						$('#valajax').hide();
						return false;
					}					
				}
				$.ajax({
					url: 'ajaxempcom.php', type: 'POST', dataType: "json", data: {iddep:iddep,idcom:idcom},
					success: function(reponse) {
						var ok = reponse.statut;
						if (ok == 'Oui') {
							$('#BttC').hide(); $('#BttCp').hide(); $('#mes').html(reponse.mes); $('#BttB').show(); $('#av').css('width', '50%');							
						} else {
							$('#mes').html(reponse.mes);
						}
						$('#valajax').hide();
					}
				});				
			});
			$('#rpnr').autocomplete({
				source: function(requete, reponse) {
					$.getJSON('ajaxpnr.php', {term: requete.term}, function (data) {
						reponse($.map(data, function (item){
							return {label: item.pnr,value: item.id};
						}));
					});
				},
				select: function (event, ui) {
					$("#rpnr").val(''); $('#labchoix').html('Un Parc Naturel Régional.<br>'+ ui.item.label);
					cpnr(ui.item.value);
					return false;	
				}
			});
			function cpnr(pnr) {
				'use strict';
				$('#valajax').show();
				$.post('ajaxpnrcom.php', {pnr:pnr}, function(listeselect){
					$('#listeselect').html(listeselect); $(".multiselect").multiselect(); $('#valajax').hide(); $('#rajparc').show(); $('#idparc').val(pnr);
				});
			}
			$('#BttParc').click(function() {
				'use strict';
				$('#valajax').show();
				var sel = "'";
				$('#multi option:selected').each(function () {
					sel += $(this).val() + "','";
				});
				$("#listecom").val(sel);
				if (sel == "'") {
					$('#mes').html('<div class="alert alert-danger" role="alert"><p>Aucune commune de sélectionnée</p></div>');
					$('#valajax').hide();
					return false;
				}
				var idcom = $('#listecom').val(), idparc = $('#idparc').val();
				$.ajax({ url: 'ajaxempparc.php', type: 'POST', dataType: "json", data: {idcom:idcom,idparc:idparc},
					success: function(reponse) {
						var ok = reponse.statut;
						if (ok == 'Oui') {
							$('#BttParc').hide(); $('#mes').html(reponse.mes); $('#BttB').show(); $('#av').css('width', '50%');
						} else {
							$('#mes').html(reponse.mes);
						}
						$('#valajax').hide();
					}
				});					
			});
			$('#rpn').autocomplete({
				source: function(requete, reponse) {
					$.getJSON('ajaxpn.php', {term: requete.term}, function (data) {
						reponse($.map(data, function (item){
							return {label: item.parc,value: item};
						}));
					});
				},
				select: function (event, ui) {
					$("#rpn").val('');$('#labchoix').html('Un Parc National.<br>'+ui.item.label);
					cpn(ui.item.label);
					return false;	
				}
			});
			function cpn(pn) {
				'use strict';
				$('#valajax').show();
				$.post('ajaxpncom.php', {pn:pn}, function(listeselect) {
					$('#listeselect').html(listeselect); $(".multiselect").multiselect(); $('#valajax').hide(); $('#rajparc').show();
				});
			}
			$('#rmassif').autocomplete({
				source: function(requete, reponse) {
					$.getJSON('ajaxmassif.php', {term: requete.term}, function (data) {
						reponse($.map(data, function (item){
							return {label: item.massif,value: item};
						}));
					});
				},
				select: function (event, ui) {
					$("#rmassif").val(''); $('#labchoix').html('Un Massif.<br>'+ui.item.label);
					cmassif(ui.item.label);
					return false;	
				}
			});
			function cmassif(massif) {
				'use strict';
				$('#valajax').show(); $('#mes').html('Attention le traitement peut-être un peu long');
				$.post('ajaxmassifcom.php', {massif:massif}, function(listeselect) {
					$('#listeselect').html(listeselect); $(".multiselect").multiselect(); $('#valajax').hide();$('#mes').html(''); $('#rajparc').show();
				});
			}			
		</script>
	</body>
</html>