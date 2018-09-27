<section class="container-fluid">
	<header class="row">
		<div class="col-sm-12 mt-3">
			<div class="card card-body">
				<div class="d-flex justify-content-start">
					<h1 class="h2">Evolution du nombre de données</h1>
					<ol class="breadcrumb ml-auto mb-0">
						<li class="breadcrumb-item"><a href="index.php?module=bilan&amp;action=bilan">Bilan</a></li>
						<li class="breadcrumb-item active">Evolution des données</li>
					</ol>
				</div>
			</div>
		</div>		
	</header>
	<div class="row mt-2">
		<div class="col-sm-12">			
			<ul class="nav nav-tabs" id="onglet">
				<li class="nav-item color4_bg"><a class="nav-link active" href="#obs" data-toggle="tab" data-id="obs"><i class="fa fa-eye fa-lg"></i> Nombre d'observations</a></li>
				<li class="nav-item color4_bg"><a class="nav-link" href="#espece" data-toggle="tab" data-id="espece"><i class="fa fa-file-text-o fa-lg"></i> Nombre d'espèces</a></li>
				<li class="nav-item color4_bg"><a class="nav-link" href="#disc" data-toggle="tab" data-id="disc"><i class="fa fa-file-text-o fa-lg"></i> Nombre par observatoire</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane fade show active" id="obs">
					<h2 class="h4">Evolution du nombre d'observations (de <?php echo $anneeune;?> à <?php echo $annéeactuelle;?>)</h2>
					<figure>
						<div id="graph1" class="cartebilan"></div>
					</figure>
				</div>
				<div class="tab-pane fade" id="espece">
					<h2 class="h4">Evolution du nombre d'espèces (de <?php echo $anneeune;?> à <?php echo $annéeactuelle;?>)</h2>
					<figure>
						<div id="graph2" class="cartebilan">
							<div class="mt-1"><p class="text-warning text-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement du graph...</p></div>
						</div>
					</figure>
				</div>
				<div class="tab-pane fade" id="disc">
					<h2 class="h4">Evolution du nombre d'espèces par observatoire (de <?php echo $anneeune;?> à <?php echo $annéeactuelle;?>)</h2>
					<figure>
						<div id="graph3" class="cartebilan">
							<div class="mt-1"><p class="text-warning text-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement du graph...</p></div>
						</div>
					</figure>
				</div>
			</div>			
		</div>		
	</div>
	<input id="nbligne" type="hidden" value="<?php echo $nbligne;?>"/><input id="anneeune" type="hidden" value="<?php echo $anneeune;?>"/>
</section>
<script>
var nbligne = $('#nbligne').val(), gespece, gobserva, couleur1, couleur2;
$(document).ready(function() {
	'use strict';
	couleur1 = $('#couleur1').css('backgroundColor'), couleur2 = $('#menu').css('backgroundColor');
	Highcharts.setOptions({
		lang: {contextButtonTitle: "Menu exportation",downloadPNG: "Télécharger au format PNG",downloadJPEG: "Télécharger au format JPG",downloadPDF: "Télécharger au format PDF",downloadSVG: "Télécharger au format SVG",exportButtonTitle: "Exporter image ou document",printChart: "Imprimer",zoomIn: "Zoom +",zoomOut: "Zoom -",loading: "Chargement..."},
		navigation: {buttonOptions: {verticalAlign: 'top',align: 'right',width: 28,height:28,symbolX: 14,symbolY: 14,symbolStroke:'white',theme: {fill: couleur1,'stroke-width': 0,r: 0,states: {hover: {fill: '#ccc'},select: {stroke: '#039',fill: '#ccc'}}}}}
	});
});
$(function () {	
	$('#graph1').highcharts({
		chart: {backgroundColor:"rgba(255, 255, 255, 0)", type: 'column'},
		title: {text: ''},
		credits: {enabled: false},
		xAxis: {categories: [<?php echo join($annee, ','); ?>], title: {text: 'Années'}, labels: {staggerLines: nbligne}},
		yAxis: {title: {text: 'Nombre d\'observations'}, labels: {format: '{value}'}},
		tooltip: {shared: true},
		plotOptions: {column: {pointPadding: 0, borderWidth: 0}},
		series: [{
			name: 'Nb d\'observations',
			data: [<?php echo join($nb, ','); ?>]
		}]			
	});	
});
function graph(choix) {
	'use strict';
	var anneeune = $('#anneeune').val();
	$.ajax({
		url: 'modeles/ajax/bilan/graphevol.php', type: 'POST', dataType: "json", data: {choix:choix,anneeune:anneeune},
		success: function(reponse) {
			if (reponse.statut == 'Oui') {
				if (choix = 'espece') { graphespece(reponse.annee,reponse.sp,reponse.newsp); }
				if (choix = 'observa') { grapheobserva(reponse.annee,reponse.observa); }	
			}			
		}
	});	
}
function graphespece(annee,sp,newsp) {
	'use strict';
	new Highcharts.Chart({
		chart: {renderTo : 'graph2', type: 'column'},
		title: {text: ''},
		credits: {enabled: false},
		xAxis: {categories: annee, title: {text: 'Années'}, labels: {staggerLines: nbligne}},
		yAxis: {title: {text: 'Nombre d\'espèces'}, labels: {format: '{value}'}},
		tooltip: {shared: true},
		plotOptions: {column: {grouping: false, pointPadding: 0, borderWidth: 0}},
		series: [{name: 'Nombre d\'espèces',data: sp},{name: 'Nouvelles espèces',data: newsp,color: '#90ed7d'}]
	});
}
function grapheobserva(annee,data) {
	'use strict';
	$('#graph3').highcharts({		
		chart: {type: 'column'}, title: {text: ''}, credits: {enabled: false},
		exporting:{
			filename: 'spobservatoire',
			chartOptions:{
				title: {text:'Evolution du nombre d\'espèces par observatoire'},
				xAxis: {labels:{ rotation:90 }}
			}
		},
		xAxis: {categories: annee, title: {text: 'Années'}, labels: {staggerLines: nbligne}},
		yAxis: {title: {text: 'Nombre d\'espèces'}, labels: {format: '{value}'}},
		tooltip: {shared: true},
		plotOptions: {column: {stacking: 'normal', pointPadding: 0, borderWidth: 0}},
		series : data
	});
}
$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
	'use strict';
	var onglet = $(e.target).attr("data-id");
	if (onglet == 'espece') { graph('espece'); }
	if (onglet == 'disc')  { graph('observa'); }
})
</script>
	
	