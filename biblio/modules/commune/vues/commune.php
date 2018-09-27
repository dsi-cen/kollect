<section>
	<div class="d-flex flex-row header p-2">
		<div class="container">
			<div class="row">
				<header class="col-md-12 col-lg-12">
					<div class="d-flex justify-content-start">
						<h1 class="h4 text-uppercase ctitre">Recherche par commune</h1>
						<ol class="breadcrumb ml-auto mb-0 p-1 small">							
							<li class="breadcrumb-item"><a href="index.php?module=recherche&amp;action=recherche">Recherche</a></li>
							<li class="breadcrumb-item active">Par commune</li>
						</ol>
					</div>
				</header>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row mt-5">
			<div class="col-md-8">
				<h2 class="h4 ctitre">Informations</h2>
				<p>La recherche par commune se fait soit en cliquant sur une lettre ou bien sur une commune de la carte.</p>
				<div class="d-flex flex-wrap">
				<?php
				foreach($lettre as $n)
				{
					?>
					<button type="button" class="btn blanc colorbiblio_bg ml-3 mt-2 curseurlien" id="<?php echo $n['l'];?>"><?php echo $n['l'];?></button>
					<?php
				}						
				?>
				</div>		
			</div>
			<div class="col-md-4">
				<div id="listealpa"></div>
				<div id="container"></div>
			</div>
		</div>
	</div>
	<input id="max" type="hidden" value="<?php echo $max;?>"/>
</section>
<script>
$(document).ready(function() {
	var maxdep = $('#max').val();
	var mapData = Highcharts.geojson(<?php echo json_encode($carto, JSON_NUMERIC_CHECK);?>, 'map'); 
	var data = <?php echo json_encode($carte, JSON_NUMERIC_CHECK);?>;
	$('#container').highcharts('Map', {
		chart: {backgroundColor:"rgba(255, 255, 255, 0)"},
		title : {text : ''}, credits: {enabled: false}, legend : {enabled: false},
		colorAxis: {min: 0, max: maxdep, maxColor: '#0CAD00', minColor: '#FFFFFF'},
		tooltip: {backgroundColor: null,borderWidth: 0,shadow: false,useHTML: true,
			formatter: function () {return '<div class="popupcarte" style="background-color:' + this.point.color + '"><b>' + this.point.nom + '</b><br>' + this.point.info + '</div>';},
		},
		plotOptions: {series: {point: {events: {click: function () { if (this.value > 0) { lien(this.id); }}}}}},
		series : [{
			mapData: mapData, data: data, joinBy: ['id', 'id'], name: 'nom', borderColor: 'black', borderWidth: 1, cursor: 'pointer', states: {hover: {borderWidth: 1.5}}
		}]
	});
});
$('.curseurlien').click(function(){
	'use strict';
	var id = $(this).attr('id');
	$.post('modeles/ajax/listecommune.php', {id:id}, function(listealpha){ $('#listealpa').html(listealpha); });		
});
function lien(id) {
	document.location.href="index.php?module=liste&action=liste&choix=com&id="+ id;
}
	
</script>