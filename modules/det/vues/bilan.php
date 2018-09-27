<section class="container mb-3">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<div class="d-flex justify-content-start">
					<h1 class="h2">Bilan des déterminations</h1>
					<ol class="breadcrumb ml-auto mb-0">
						<li class="breadcrumb-item active"><a href="index.php?module=det&amp;action=det">Détermination</a></li>
						<li class="breadcrumb-item active">Bilan</li>
					</ol>
				</div>
			</div>
		</div>		
	</header>
	<div class="row mt-2">
		<div class="col-md-12 col-lg-12">
			<div class="card card-body">					
				<div class="row">
					<div class="col-md-7 col-lg-7">
						<h2 class="h5"><?php echo $nbdemande;?> <?php echo $libnbdemande;?> de détermination</h2>
						<div id="graph1" class="cartebilan"></div>
					</div>
					<div class="col-md-5 col-lg-5">
						<h2 class="h5" id="titregraph2"></h2>
						<div id="graph2" class="minigraph cartefiche"></div>
					</div>
				</div>
			</div>
		</div>		
	</div>		
</section>
<script>
$(document).ready(function() {
	'use strict'; graph();
});
function graph() {
	'use strict';
	$('#graph1').highcharts({		
		chart: {type: 'pie'}, credits: {enabled: false}, title: {text: ''},
		tooltip: {pointFormat: '<b>{point.y}</b>', valueSuffix: ' demande(s) <br>({point.percentage:.1f}%) <br>Cliquer pour plus de détail'},
		plotOptions: {
			pie: {allowPointSelect: false, cursor: 'pointer', showInLegend: false},
			series: {point: {events: {click: function (event) { detail(this.var,this.name); }}}}
		},
		series: [{data : <?php echo $data;?>}]
	});	
}
function graphdet(data,nom) {
	'use strict';
	$('#titregraph2').html(nom +' - Détail');
	$('#graph2').highcharts({	
		chart: {type: 'pie'}, credits: {enabled: false}, title: {text: ''},
		tooltip: {pointFormat: '<b>{point.y}</b>', valueSuffix: ' demande(s) <br>({point.percentage:.1f}%)'},
		plotOptions: { pie: {allowPointSelect: true, cursor: 'pointer', showInLegend: false} },
		series: [{data : data}]
	});	
}
function detail(observa,nom) {
	'use strict';
	$.ajax({
		url: 'modeles/ajax/det/detail.php', type: 'POST', dataType: "json", data: {observa:observa},
		success: function(reponse) {
			if (reponse.statut == 'Oui') {
				if (reponse.graph) {
					graphdet(reponse.data,nom);
				} else {
					$('#titregraph2').html(nom +' - Détail'); $('#graph2').html(reponse.data);
				}				
			}			
		}
	});	
}
</script>