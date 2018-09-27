<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="author" content="Denis Vandromme">
		<meta name="robots" content="noindex,nofollow" />
		<title>Installation 3</title>
		<link href="../dist/css/gestion.css" rel="stylesheet">
		<style type="text/css">
			#container {height:500px;}
			body {padding-top: 0px;}		
		</style>
	</head>
	<body>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Connexion à la base, création de l'application</a></li>
			<li class="breadcrumb-item"><a href="#">Emprise du site</a></li>
			<li class="breadcrumb-item active">Vérification et maillage</li>
			<li class="breadcrumb-item"><a href="#">Configuration du site</a></li>
		</ol>		
		<?php
			$json = file_get_contents('../emprise/emprise.json');
			$rjson = json_decode($json, true);
			$emp = $rjson['emprise'];
		?>
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12 col-lg-12">
					<header>
						<h1 class="text-xs-center">Installation de l'application</h1>
						<hr />
					</header>
				</div>
			</div>
			<div class="row">
				<div class="col-md-7 col-lg-7">
					<h2>Vérification emprise</h2>
					<div id="container"></div>
				</div>
				<div class="col-md-5 col-lg-5">
					<p>Si l'emprise correspond à votre configuration, vous pouvez continuer l'installation.<br />
					<span class="small">(Si après modification de l'emprise, la carte ne change pas faite ctrl F5).</span></p>					
					<?php 
					if ($emp == 'dep' OR $emp == 'parc')
					{
						$contour = $rjson['contour2'];
						if ($contour == 'oui')
						{
							$json2 = file_get_contents('../emprise/contour2.geojson');
						}
						else { $json2 = '{"contour2":"non"}'; }
						?>
						<input id="contour" type="hidden" value="<?php echo $contour;?>"/>
						<a class="btn btn-warning" href="emprise.php">Modifier l'emprise</a> <button type="button" class="btn btn-success" id="BttM">Génération des mailles</button>
						<?php
					}
					else
					{
						$json2 = '{"contour2":"non"}';
						?>
						<input id="contour" type="hidden" value="non"/>
						<a class="btn btn-warning" href="emprise.php">Modifier l'emprise</a> <button type="button" class="btn btn-success" id="BttM">Génération des mailles</button>
						<?php						
					}
					?>
					<input id="choixemp" type="hidden" value="<?php echo $emp;?>"/>
					<div id="maille">
						<p>Par défaut le maillage est la grille national lambert93 10 x 10 km </p> 
						<div class="form-check">
							<label class="form-check-label"><input class="form-check-input" type="checkbox" id="utm" value="utm"> Cocher si vous préférez utiliser le maillage MGRS (UTM)</label>
						</div>
						<?php
						if ($emp != 'fr')
						{
							?>
							<div class="form-check">
								<label class="form-check-label"><input class="form-check-input" type="checkbox" id="l5" value="l5"> Cocher si vous voulez aussi utiliser la grille lambert93 en 5 x 5 km.</label>
							</div>
							<?php
						}
						?>
						<div class="form-check">
							<label class="form-check-label"><input class="form-check-input" type="checkbox" id="biogeo" value="biogeo"> Cocher si vous voulez la représentation par zone biogéographique</label>
						</div>
						<p>
							<b>Récupération des coordonnées de l'emprise (lambert 93)</b><br />
							<span id="emprisel"></span>
						</p>
						<form class="form">
							<div class="form-group row">
								<label for="hg" class="col-sm-3 col-form-label">Maille haut gauche</label>
								<div class="col-sm-3">
									<input type="text" class="form-control" id="hg">
								</div>
							</div>
							<div class="form-group row">
								<label for="bd" class="col-sm-3 col-form-label">Maille bas droit</label>
								<div class="col-sm-3">
									<input type="text" class="form-control" id="bd">
								</div>
							</div>
							<div class="form-group row">
								<div class="col-sm-5">
									<?php
									if ($emp != 'fr')
									{
										?>
										<button type="button" class="btn btn-success" id="BttB">Calcul du maillage et enregistrement dans la base</button>
										<?php
									}
									else
									{
										?>
										<button type="button" class="btn btn-success" id="BttBfr">Enregistrement de l'emprise</button>
										<?php
									}
									?>
								</div>
							</div>
							<input id="NE" type="hidden"/><input id="SW" type="hidden"/><input id="lng" type="hidden"/><input id="lat" type="hidden"/><input id="xg" type="hidden"/><input id="xd" type="hidden"/><input id="yh" type="hidden"/><input id="yb" type="hidden"/>
							<div id="valajax"><progress></progress></div>
							<div id="mes"></div>							
						</form>
						<?php
						if ($emp != 'fr')
						{
							?>
							<div id="supmaille">
								<p>Vous pouvez supprimer au besoin des mailles (mailles ne recoupant pas la carte).<br />Cliquer sur chaque maille à supprimer (maj + clique pour sélectionner plusieurs mailles à la fois)</p>
								<div id="listemaille"></div>
								<button type="button" class="btn btn-success" id="BttS">Supprimer les mailles séléctionnées</button>
								<a class="btn btn-success" id="BttC" href="config.php">-> Configuration du site</a>
								<div id="messup"></div>
								<input id="listemailles" type="hidden"/>
							</div>
							<?php
						}
						else
						{
							?>
							<div id="supmaille">
								<a class="btn btn-success" id="BttC" href="config.php">-> Configuration du site</a>
							</div>
							<?php
						}
						?>
					</div>
					<div class="row">
						<div class="col-md-12 col-lg-12 mt-2">
							<div class="progress">
								<div id="av" class="progress-bar bg-success" style="width: 50%" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
						</div>
					</div>
				</div>				
			</div>
		</div>
		<script src="../dist/js/jquery.js"></script>  
		<script type="text/javascript" src="../dist/js/highmaps.js"></script>
		<script type="text/javascript" src="proj4js.js"></script><script type="text/javascript" src="EPSG2154.js"></script>
		<script>			
			$(document).ready(function() {
				$('#maille').hide(); $('#valajax').hide(); $('#supmaille').hide();
				var maille = 'non', l93 = '', cartoid = '';
				carte(maille,l93,cartoid);
			});
			function carte(maille,l93,cartoid) {
				Highcharts.setOptions({
					lang: {zoomIn: "Zoom +",zoomOut: "Zoom -",loading: "Chargement..."}
				});
				$.getJSON('../emprise/contour.geojson', function (geojson) {
					var emp = $("#contour").val();
					if (emp == 'oui') {
						var tmpdep = <?php echo $json2;?>;
						var dep = Highcharts.geojson(tmpdep, 'mapline');	
					} else {
						var dep = '';
					}
					var com = Highcharts.geojson(geojson, 'mapline');
					if (maille == 'non') {
						$('#container').highcharts('Map', {
							title : {text : ''},
							credits: {enabled: false},
							legend : {enabled: false},
							mapNavigation: { enableMouseWheelZoom: false, enabled: true, buttonOptions: {verticalAlign: 'bottom',align: 'right'} },
							series : [{
								data: com,
								type: 'mapline',
								lineWidth: 0.5,
								color: 'black',
								enableMouseTracking: false
							},{	
								data: dep,
								type: 'mapline',
								lineWidth: 1.5,
								color: 'black',
								enableMouseTracking: false
							}]
						});							
					} else {
						var maillel93 = Highcharts.geojson(l93, 'map'); 
						$('#container').highcharts('Map', {
							title : {text : ''},
							credits: {enabled: false},
							legend : {enabled: false},
							mapNavigation: { enableMouseWheelZoom: false, enabled: true, buttonOptions: {verticalAlign: 'bottom',align: 'right'} },
							tooltip: {pointFormat:'<b>{point.id}</b>'},
							plotOptions: { series: { point: { events: { select: function () { var maille = this.id; ajoutmaille(maille); } } } } },
							series : [{							
								name: 'Maille', mapData: maillel93, type: 'map', data: cartoid, joinBy: ['id', 'id'],
								allowPointSelect: true, borderColor: 'black', borderWidth: 0.5, cursor: 'pointer',							
								states: { select: {color: '#a4edba',dashStyle: 'dot'},hover: {color: '#FFFFFF',borderWidth: 1.5} },
								enableMouseTracking: true
							}, {
								data: com, type: 'mapline', lineWidth: 0.5, color: 'black', enableMouseTracking: false
							},{	
								data: dep, type: 'mapline', lineWidth: 1.5, color: 'black', enableMouseTracking: false
							}]
						});					
					}					
				});
				$('#BttM').click(function () {
					var chart = $('#container').highcharts(),
						xExt = chart.xAxis[0].getExtremes(),
						yExt = chart.yAxis[0].getExtremes();
					var x = Math.round(xExt.min), xh = x.toString();
					var y = Math.round(yExt.min), yh = y.toString();
					var x = Math.round(xExt.max), xb = x.toString();
					var y = Math.round(yExt.max), yb = y.toString();
					var n = xh.length;
					var xc = (xExt.min + xExt.max)/2;
					var yc = (-yExt.min + -yExt.max)/2;
					if (n == 5) {
						var hg = 'E00'+ xh.substring(0,1) +'N'+ yh.substring(1,4); $('#xg').val(xh.substring(0,1));
					} 
					if (n == 6) {
						var hg = 'E0'+ xh.substring(0,2) +'N'+ yh.substring(1,4); $('#xg').val(xh.substring(0,2));
					} 
					if (n == 7) {
						var hg = 'E'+ xh.substring(0,3) +'N'+ yh.substring(1,4); $('#xg').val(xh.substring(0,3));
					}
					var n = xb.length;
					if (n == 5) {
						var bd = 'E00'+ xb.substring(0,1) +'N'+ yb.substring(1,4); $('#xd').val(xb.substring(0,1));
					} 
					if (n == 6) {
						var bd = 'E0'+ xb.substring(0,2) +'N'+ yb.substring(1,4); $('#xd').val(xb.substring(0,2));
					} 
					if (n == 7) {
						var bd = 'E'+ xb.substring(0,3) +'N'+ yb.substring(1,4); $('#xd').val(xb.substring(0,3));
					}	
					$('#maille').show();					
					var emprisel = '<b>x :</b> ' + 'min: '+ xExt.min +' - max: '+ xExt.max +'<br>' + '<b>y :</b> ' + 'min: '+ -yExt.max +' - max: '+ -yExt.min;							
					$('#emprisel').html(emprisel); $('#hg').val(hg);$('#bd').val(bd); $('#yh').val(yh.substring(1,4)); $('#yb').val(yb.substring(1,4));
					centrew84(xc,yc);
					var sw = latlng(xExt.min,-yExt.min), ne = latlng(xExt.max,-yExt.max);
					$('#SW').val(sw); $('#NE').val(ne);
				});				
			}
			$($('#utm')).change(function() {	
				if (this.checked) { $('#l5').prop('checked', false); } 			
			});
			$($('#l5')).change(function() {	
				if (this.checked) { $('#utm').prop('checked', false); } 			
			});			
			$(function() {
				$('#BttB').click(function() {
					$('#messup').html(''); $('#valajax').show();
					var utm = ($('#utm').is(':checked')) ? 'oui' : 'non';
					var l5 = ($('#l5').is(':checked')) ? 'oui' : 'non';
					var biogeo = ($('#biogeo').is(':checked')) ? 'oui' : 'non';
					var emp = $("#choixemp").val(), contour = $("#contour").val();
					var lat = $("#lat").val(), lng = $("#lng").val(), ne = $("#NE").val(), sw = $("#SW").val();
					var xg = $('#xg').val(),xd = $('#xd').val(),yh = $('#yh').val(),yb = $('#yb').val();
					$.ajax({
						url: 'ajaxmaille.php', type: 'POST', dataType: "json",
						data: {xg:xg,xd:xd,yh:yh,yb:yb,utm:utm,l5:l5,lat:lat,lng:lng,ne:ne,sw:sw,emp:emp,contour:contour,biogeo:biogeo},
						success: function(reponse) {
							var ok = reponse.statut;
							if (ok == 'Oui') {
								$('#mes').html(reponse.mes);
								$('#container').html('<div class="loading" style="margin-top:10em;text-align:center;"><progress></progress></div>');
								$('#av').css('width', '75%');
								var l93 = reponse.carto;
								var cartoid = reponse.cartoid;
								var maille = 'oui';
								carte(maille,l93,cartoid);
								$('#supmaille').show();								
							} else {
								$('#mes').html(reponse.mes);
							}
							$('#valajax').hide();
						}
					});
				});
			});
			$('#BttBfr').click(function(){
				$('#valajax').show();
				if ($('#utm').is(':checked') ){
					var utm = 'oui';
				} else {
					var utm = 'non';
				}
				var lat = $("#lat").val(), lng = $("#lng").val(), ne = $("#NE").val(), sw = $("#SW").val();
				$.ajax({ url: 'ajaxmaillefr.php', type: 'POST', dataType: "json", data: {utm:utm,lat:lat,lng:lng,ne:ne,sw:sw},
					success: function(reponse) {
						var ok = reponse.statut;
						if (ok == 'Oui') {
							$('#av').css('width', '75%'); $('#supmaille').show();	
						} else {
							$('#mes').html(reponse.mes);
						}
						$('#valajax').hide();
					}
				});
			});
			function ajoutmaille(maille) {
				var liste = $('#listemailles').val();
				$('#listemaille').append(maille+ ', ');
				if (!liste) {
					$("#listemailles").val("'"+maille+"'");
				} else {
					$("#listemailles").val(liste+",'"+maille+"'");
				}				
			}
			$('#BttS').click(function(){
				if ($('#utm').is(':checked')) { var utm = 'oui'; } else { var utm = 'non'; }
				var maille = $('#listemailles').val();
				$.ajax({
					url: 'ajaxsupmaille.php', type: 'POST', dataType: "json", data: {maille:maille,utm:utm},
					success: function(reponse) {
						var ok = reponse.statut;
						if (ok == 'Oui') {
							$('#messup').html(reponse.mes);
							$('#container').html('<div class="loading" style="margin-top:10em;text-align:center;">' + '<img src="../img/attenteajax.gif">' + '</div>');
							$('#listemailles').val('');$('#listemaille').html('');
							var l93 = reponse.carto;
							var cartoid = reponse.cartoid;
							var maille = 'oui';
							carte(maille,l93,cartoid);							
						} else {
							$('#messup').html(reponse.mes);
						}
					}
				});
			});
			function latlng(x,y){
				var xy = x+','+y;
				var source = new Proj4js.Proj('EPSG:2154');
				var dest = new Proj4js.Proj('WGS84');
				var pointSource = new Proj4js.Point(xy);
				var pointDest = Proj4js.transform(source, dest, pointSource);
				var xm = pointDest.x, ym = pointDest.y;
				var lat1 = parseFloat(ym), lng1 = parseFloat(xm);
				var lat = Math.round(lat1*1000)/1000, lng = Math.round(lng1*1000)/1000;
				var coord = lat+','+lng;
				return coord;
			}			
			function centrew84(xc,yc) {
				var xy = xc+','+yc;
				var source = new Proj4js.Proj('EPSG:2154');
				var dest = new Proj4js.Proj('WGS84');
				var pointSource = new Proj4js.Point(xy);
				var pointDest = Proj4js.transform(source, dest, pointSource);
				var xm = pointDest.x, ym = pointDest.y;
				var lat1 = parseFloat(ym), lng1 = parseFloat(xm);
				var lat = Math.round(lat1*10000)/10000, lng = Math.round(lng1*10000)/10000;
				$("#lat").val(lat);$("#lng").val(lng);			
			}
		</script>
	</body>
</html>