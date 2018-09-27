<section class="container blanc">
	<div class="row">	
		<div class="col-md-12 col-lg-12 mt-3">
			<header>		
				<h1>Gestion module biblio</h1>
			</header>
			<hr />
			<div id="mes"></div>
			<form class="form" id="biblio" method="post">
				<div class="form-group row">
					<label for="titre" class="col-sm-2 col-form-label">Titre biblio</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="titre" id="titre" value="<?php echo $rjson['titre'];?>">
						<span class="text-muted">Attention à ne pas modifier le titre trop longtemps après l'installation. Cela pourra pénalisé le référencement.</span>
					</div>							
				</div>
				<div class="form-group row">
					<label for="descri" class="col-sm-2 col-form-label">Description</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="descri" id="descri" value="<?php echo $rjson['description'];?>">
						<span class="text-muted">Description qui apparaîtra dans les moteurs de recherche (il est recommandé de pas dépasser 200 caractères).</span>
					</div>
				</div>
				<div class="form-group row">
					<label for="metakey" class="col-sm-2 col-form-label">Mots-clés</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="metakey" id="metakey"  value="<?php echo $rjson['metakey'];?>">
						<span class="text-muted">Mettre les principaux mots-clés séparés par une virgule ex : (Papillons, Oiseaux).</span>
					</div>
				</div>
				<div class="form-group row mt-3">
					<div class="col-sm-8">
						<button type="submit" class="btn btn-success" id="BttV">Valider les modifications</button>
					</div>							
				</div>	
			</form>
		</div>
	</div>	
</section>
<script>
$('#biblio').on('submit', function(e) {
	'use strict';
	var data = $(this).serialize();
	$.ajax({
		url: 'modeles/ajax/biblio/general.php', type: 'POST', dataType: "json", data : data,
		success: function(reponse) { $('#mes').html(reponse.mes); }
	});
	return false;
});	
</script>