<section class="container blanc">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header>	
				<h1>Divers</h1>
			</header>					
		</div>
	</div>
	<div class="row mt-2">
		<div class="col-md-12 col-lg-12">
			<h2 class="h3">Maintenance</h2>
			<p>
				En cas d'opération de maintenance vous pouvez empêcher la saisie de données, de photos le temps de la maintenance<br />
				Ne pas oublier de remettre ensuite le site en production.<br />
				<b>Etat actuel : <span id="etat"><?php echo $etat ?></span></b>
				<button type="button" class="btn btn-success btn-sm ml-2" id="BttM">Changer</button>
			</p>
		</div>
	</div>	
</section>
<script>
$('#BttM').click(function() {
	'use strict';
	var etat = $('#etat').text();
	if (etat == 'Production') { var choix = 'm'; } else { var choix = 'n'; }
	maintenance(choix);
});
function maintenance(choix) {
	'use strict'; 
	$.ajax({
		url: 'modeles/ajax/maintenance.php', type: 'POST', dataType: "json", data: {choix:choix},
		success: function(reponse) {
			if (reponse.statut == 'Oui') {					
				$('#etat').html(reponse.etat); 
			} else { alert('Erreur'); } 	
		}
	});	
}
</script>