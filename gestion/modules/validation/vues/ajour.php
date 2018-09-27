<section class="container blanc">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header>	
				<h1 class="h2">Grille de validation</h1>
			</header>					
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<p>
				Cette page permet de mettre à jour la grille de validation du site. Cela est nécessaire par exemple lors d'import de données, si votre import comporte des observations déjà validées en 1 (Certain - très probable) ou 2 (Probable).<br />
				En cliquant sur le bouton la grille de validation sera mise à jour avec les nouvelles observations.				
			</p>
			<p>Requête qui construit la grille :</p>
			<pre>
INSERT INTO vali.grille
SELECT cdref, COUNT(idobs) AS nb, array_agg(DISTINCT codel93) AS codel93, array_agg(DISTINCT decade) AS decade, array_agg(DISTINCT idobser) AS obser FROM obs.obs
INNER JOIN obs.fiche USING(idfiche)
INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
INNER JOIN obs.ligneobs USING(idobs)
INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
WHERE (validation = 1 OR validation = 2) AND date1 = date2 AND idetatbio != 3 AND vali != 0
GROUP BY cdref
			</pre>
			<button type="button" class="btn btn-success" id="BttV">Mettre à jour la grille</button>
			<div id="valajax" class="mt-1"><progress></progress></div>
			<div id="mes" class="mt-3"></div>
		</div>
	</div>
</section>
<script>
$(document).ready(function() {
	'use strict'; $('#valajax').hide();
});
$('#BttV').click(function() {
	'use strict'; $('#valajax').show();
	$.ajax({
		url: 'modeles/ajax/validation/ajour.php', type: 'POST', dataType: "json", data: {},
		success: function(reponse) {
			$('#valajax').hide();
			if (reponse.statut == 'Oui') {
				$('#mes').html('<div class="alert alert-success" role="alert">'+ reponse.vider +' lignes supprimées de la grille, puis '+ reponse.ajour +' lignes insérées</div>');			
			} 
		}
	});	
});
</script>