<section class="container-fluid">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<div class="d-flex justify-content-start">
					<h1 class="h3">Nombre d'espèces observées par décade</h1>
					<ol class="breadcrumb ml-auto mb-0">
						<li class="breadcrumb-item"><a href="index.php?module=decade&amp;action=decade">Liste</a></li>
						<li class="breadcrumb-item active">Graph</li>
					</ol>
				</div>				
			</div>
		</div>		
	</header>
	<div class="row mt-2">
		<div class="col-md-12 col-lg-12">
			<div class="card card-body">
				<figure>
					<div id="graph1" class="cartebilan"></div>
				</figure>
			</div>
		</div>		
	</div>
</section>
<script>
var couleur1, couleur2, type = $('#type').val();
$(document).ready(function() {
	'use strict';
	couleur1 = $('#couleur1').css('backgroundColor'), couleur2 = $('#menu').css('backgroundColor');
	Highcharts.setOptions({
		lang: {contextButtonTitle: "Menu exportation",downloadPNG: "Télécharger au format PNG",downloadJPEG: "Télécharger au format JPG",downloadPDF: "Télécharger au format PDF",downloadSVG: "Télécharger au format SVG",exportButtonTitle: "Exporter image ou document",printChart: "Imprimer",zoomIn: "Zoom +",zoomOut: "Zoom -",loading: "Chargement..."},
		navigation: {buttonOptions: {verticalAlign: 'top',align: 'right',width: 28,height:28,symbolX: 14,symbolY: 14,symbolStroke:'white',theme: {fill: couleur1,'stroke-width': 0,r: 0,states: {hover: {fill: '#ccc'},select: {stroke: '#039',fill: '#ccc'}}}}}
	});
});
$(function () {
	'use strict';
	$('#graph1').highcharts({
		chart: {backgroundColor:"rgba(255, 255, 255, 0)", type: 'column'},
		title: {text: ''},
		credits: {enabled: false},
		xAxis: {categories: ['Ja1', 'Ja2', 'Ja3', 'Fe1', 'Fe2', 'Fe3', 'Ma1', 'Ma2', 'Ma3', 'Av1', 'Av2', 'Av3', 'M1', 'M2', 'M3', 'Ju1', 'Ju2', 'Ju3', 'Jl1', 'Jl2', 'Jl3', 'A1', 'A2', 'A3', 'S1', 'S2', 'S3', 'O1', 'O2', 'O3', 'N1', 'N2', 'N3', 'D1', 'D2', 'D3'], 
				title: {text: 'Décades'}},
		yAxis: {title: {text: 'Nombre d\'espèces'}, labels: {format: '{value}'}},
		tooltip: {shared: true},
		/*tooltip: {
			formatter: function () {
				return '<b>'+ this.x +'</b><br/>'+ this.series.name +': '+ this.y +'<br/>Total: '+ this.point.stackTotal;                
            }
		},*/
		plotOptions: {column: {stacking: 'normal', pointPadding: 0, borderWidth: 0}},
		series : <?php echo $data;?>			
	});	
});
</script>