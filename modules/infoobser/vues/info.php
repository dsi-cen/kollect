<section class="container-fluid mb-3">
	<header class="row justify-content-md-center">
		<div class="col-md-10 col-lg-10 mt-3">
			<div class="card card-body">
				<h1 class="h2"><?php echo $titre;?> <?php echo $favatar;?></h1>
			</div>
		</div>
	</header>
	<div class="row mt-2 justify-content-md-center">		
		<div class="col-md-10 col-lg-10">
			<div class="card card-body">
				<h2 class="h4">Bilan général</h2>
				<div id="bilan"><p class="text-warning text-center"><span class="fa fa-spin fa-spinner fa-2x"></span></p></div>
				<h2 class="h4 mt-2">Evolution des données</h2>
				<div class="row mt-2">
					<div class="col-sm-12">			
						<ul class="nav nav-tabs" id="onglet">
							<li class="nav-item color4_bg"><a class="nav-link active" href="#obs" data-toggle="tab" data-id="obs">Nombre d'observations</a></li>
							<li class="nav-item color4_bg"><a class="nav-link" href="#espece" data-toggle="tab" data-id="espece">Nombre d'espèces</a></li>
							<li class="nav-item color4_bg"><a class="nav-link" href="#new" data-toggle="tab" data-id="new">Nouvelle espèce</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane fade show active" id="obs">
								<h3 class="h5">Evolution du nombre d'observations (de <?php echo $anmin;?> à <?php echo $annéeactuelle;?>)</h3>
								<figure>
									<div id="graph1" class="cartebilan"></div>
								</figure>
							</div>
							<div class="tab-pane fade" id="espece">
								<h3 class="h5">Evolution du nombre d'espèces (de <?php echo $anmin;?> à <?php echo $annéeactuelle;?>)</h3>
								<figure>
									<div id="graph2" class="cartebilan">
										<div class="mt-1"><p class="text-warning text-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement du graph...</p></div>
									</div>
								</figure>
							</div>
							<div class="tab-pane fade" id="new">
								<div id="listenew" class="mt-2"></div>
							</div>
						</div>	
					</div>
				</div>				
			</div>					
		</div>		
    </div>
	<input id="nbligne" type="hidden" value="<?php echo $nbligne;?>"/><input id="idobser" type="hidden" value="<?php echo $idobser;?>"/>
</section>
<script>
var nbligne = $('#nbligne').val(), gespece, gobserva, couleur1, couleur2, varespece;
$(document).ready(function() {
	'use strict';
	couleur1 = $('#couleur1').css('backgroundColor'), couleur2 = $('#menu').css('backgroundColor');
	Highcharts.setOptions({
		lang: {contextButtonTitle: "Menu exportation",downloadPNG: "Télécharger au format PNG",downloadJPEG: "Télécharger au format JPG",downloadPDF: "Télécharger au format PDF",downloadSVG: "Télécharger au format SVG",exportButtonTitle: "Exporter image ou document",printChart: "Imprimer",zoomIn: "Zoom +",zoomOut: "Zoom -",loading: "Chargement..."},
		navigation: {buttonOptions: {verticalAlign: 'top',align: 'right',width: 28,height:28,symbolX: 14,symbolY: 14,symbolStroke:'white',theme: {fill: couleur1,'stroke-width': 0,r: 0,states: {hover: {fill: '#ccc'},select: {stroke: '#039',fill: '#ccc'}}}}}
	});
	var idobser = $('#idobser').val();
	bilan(idobser);
});
function bilan(idobser) {
	'use strict';
	$.ajax({
		url: 'modeles/ajax/infoobser/infoobser.php', type: 'POST', dataType: "json", data: {idobser:idobser},
		success: function(reponse) { 
			if (reponse.statut == 'Oui') { $('#bilan').html(reponse.bilan); }			
		}
	});
}
$(function () {	
	$('#graph1').highcharts({
		title: {text: ''}, credits: {enabled: false},
		xAxis: {categories: [<?php echo join($annee, ','); ?>], title: {text: 'Années'}, labels: {staggerLines: nbligne}},
		yAxis: [{ gridLineWidth: 0, title: {text: 'Nombre d\'observations'}, labels: {format: '{value}'} },{ title: {text: 'Cumul'},opposite: true, labels: {format: '{value}'} }],
		tooltip: {shared: true},
		series: [{
			type: 'area', name: 'cumul', data: [<?php echo join($obscumul, ','); ?>], yAxis: 1	
		},{
			type: 'line', name: 'Nb d\'observations', data: [<?php echo join($nb, ','); ?>]
		}]
	});	
});
function graph(idobser) {
	'use strict';
	$.ajax({
		url: 'modeles/ajax/membre/graphespece.php', type: 'POST', dataType: "json", data: {idobser:idobser},
		success: function(reponse) { 
			if (reponse.statut == 'Oui') { graphespece(reponse.annee,reponse.nb,reponse.cumul); }			
		}
	});
}
function graphespece(annee,nb,cumul) {
	'use strict';
	new Highcharts.Chart({
		chart: {renderTo : 'graph2'}, title: {text: ''}, credits: {enabled: false},
		xAxis: {categories: annee, title: {text: 'Années'}, labels: {staggerLines: nbligne}},
		yAxis: [{ gridLineWidth: 0, title: {text: 'Nombre d\'espèces'}, labels: {format: '{value}'} },{ title: {text: 'Cumul'},opposite: true, labels: {format: '{value}'} }],
		tooltip: {shared: true},
		series: [{
			type: 'area', name: 'cumul', data: cumul, yAxis: 1	
		},{
			type: 'line', name: 'Nb d\'espèces', data: nb
		}]
	});
}
function newsp(idobser) {
	'use strict';
	$.ajax({
		url: 'modeles/ajax/membre/nouvsp.php', type: 'POST', dataType: "json", data: {idobser:idobser},
		success: function(reponse) { 
			if (reponse.statut == 'Oui') { $('#listenew').html(reponse.listenew); }			
		}
	});
}
$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
	'use strict';
	var onglet = $(e.target).attr("data-id"), idobser = $('#idobser').val();
	if (onglet == 'espece') { if (varespece != 'oui') { varespece = 'oui'; graph(idobser); }}
	if (onglet == 'new') { newsp(idobser); }
})
$('#listenew').on('click', '#voir', function() {
	'use strict';
	$('.listefamille .collapse').show(); $('.idfam span').removeClass('fa-plus').addClass('fa-minus');
});
$('#listenew').on('click', '#pasvoir', function() {
	'use strict';
	$('.listefamille .collapse').hide(); $('.idfam span').removeClass('fa-minus').addClass('fa-plus');
});
$('#listenew').on('click', '.idfam', function() {
	'use strict';
	var sel = $(this).attr('id');
	if ($(this).children().hasClass('fa-plus')) {
		$(this).children().removeClass('fa-plus').addClass('fa-minus');
	} else {
		$(this).children().removeClass('fa-minus').addClass('fa-plus');
	}
	$('#f'+ sel).toggle();
});
</script>