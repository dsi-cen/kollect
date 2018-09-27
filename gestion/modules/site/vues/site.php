<section class="container blanc">
	<div class="row">	
		<div class="col-md-12 col-lg-12 mt-3">
			<header>		
				<h1>Gestion du site</h1>
			</header>
			<hr />
			<div id="mes"></div>
			<form class="form" id="site" method="post">
				<div class="form-group row">
					<label for="titre" class="col-sm-2 col-form-label">Titre du site</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="titre" id="titre" value="<?php echo $rjson_site['titre'];?>">
						<span class="text-muted">Attention à ne pas modifier le titre trop longtemps après l'installation. Cela pourra pénalisé le référencement.</span>
					</div>							
				</div>
				<div class="form-group row">
					<label for="stitre" class="col-sm-2 col-form-label">Sous-Titre</label>
					<div class="col-sm-10">
						<textarea class="form-control" name="stitre" id="stitre" rows="1"><?php echo $rjson_site['stitre'];?></textarea>
						<span class="text-muted">Texte de présentation (Pas trop long ni trop court ! -> vérifier sur page accueil).</span>
					</div>
				</div>
				<div class="form-group row">
					<label for="descri" class="col-sm-2 col-form-label">Description</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="descri" id="descri" value="<?php echo $rjson_site['description'];?>">
						<span class="text-muted">Description qui apparaîtra dans les moteurs de recherche (il est recommandé de pas dépasser 200 caractères).</span>
					</div>
				</div>
				<div class="form-group row">
					<label for="metakey" class="col-sm-2 col-form-label">Mots-clés</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="metakey" id="metakey"  value="<?php echo $rjson_site['metakey'];?>">
						<span class="text-muted">Mettre les principaux mots-clés séparés par une virgule ex : (Papillons, Oiseaux).</span>
					</div>
				</div>
				<div class="form-group row">
					<label for="lien" class="col-sm-2 col-form-label">Adresse</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="adresse" id="adresse" value="<?php echo $adresse;?>">
						<span class="text-muted">Indiquez le lien du site ex : https://obsnat.fr/</span>
					</div>
				</div>
				<div class="form-group row">
					<label for="lien" class="col-sm-2 col-form-label">Lien</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="lien" id="lien" value="<?php echo $liensite;?>">
						<span class="text-muted">Si vous avez déjà un site, vous pouvez mettre son lien ici. Autrement laisser "non".</span>
					</div>
				</div>
				<div class="form-group row">
					<label for="logo" class="col-sm-2 col-form-label">Logo</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="logo" id="logo" value="<?php echo $logosite;?>">
						<span class="text-muted">Si vous avez un logo, mettez ici le nom du fichier avec son extension (ex : logo.png). Si vous avez renseigné le lien au dessus, le logo pointera dessus. Le fichier est à mettre via FTP dans le répertoire "dist/img"</span>
					</div>
				</div>
				<div class="form-group row">
					<label for="mail" class="col-sm-2 col-form-label">Mail</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="mail" id="mail" value="<?php echo $rjson_site['email'];?>">
						<span class="text-muted">Adresse mail de contact.</span>
					</div>
				</div>
				<div class="form-group row">
					<label for="biblio1" class="col-sm-1 col-form-label">Biblio</label>
					<div class="col-sm-1"><input type="text" class="form-control" id="biblio1" value="<?php echo $rjson_site['biblio'];?>" disabled></div>
					<label for="actu1" class="col-sm-1 col-form-label">Actualités</label>
					<div class="col-sm-1"><input type="text" class="form-control" id="actu1" value="<?php echo $rjson_site['actu'];?>" disabled></div>					
					<?php
					if ($rjson_site['actu'] == 'oui')
					{
						?>
						<label for="nbactu" class="col-sm-2 col-form-label">Nombre d'actu par page</label>
						<div class="col-sm-1"><input type="number" min="1" max="5" class="form-control" name="nbactu" id="nbactu" value="<?php echo $rjson_site['nbactu'];?>"></div>
						<?php
					}
					?>
				</div>
				<input name="biblio" id="biblio" type="hidden" value="<?php echo $rjson_site['biblio'];?>"/><input name="actu" id="actu" type="hidden" value="<?php echo $rjson_site['actu'];?>"/>
				<p>Indiquer la bonne combinaison pour votre emprise :</p>
				<div class="form-group row">
					<label for="lieu" class="col-sm-1 col-form-label">Lieu</label>
					<?php
					if(isset($rjson_site['lieu']))
					{
						?>
						<div class="col-sm-3"><input type="text" class="form-control" name="lieu" id="lieu" value="<?php echo $rjson_site['lieu'];?>" placeholder="Ex : Indre"></div>
						<div class="col-sm-3"><input type="text" class="form-control" name="ad1" id="ad1" value="<?php echo $rjson_site['ad1'];?>" placeholder="Ex : de l'"></div>
						<div class="col-sm-3"><input type="text" class="form-control" name="ad2" id="ad2" value="<?php echo $rjson_site['ad2'];?>" placeholder="Ex : dans l'"></div>
						<?php
					}
					else
					{
						?>
						<div class="col-sm-3"><input type="text" class="form-control" name="lieu" id="lieu" placeholder="Ex : Indre"></div>
						<div class="col-sm-3"><input type="text" class="form-control" name="ad1" id="ad1" placeholder="Ex : de l'"></div>
						<div class="col-sm-3"><input type="text" class="form-control" name="ad2" id="ad2" placeholder="Ex : dans l'"></div>
						<?php
					}
					?>
				</div>
				<div class="form-group row">
					<div class="col-sm-offset-1 col-sm-11"><span id="combi"></span></div>
				</div>
				<p>Indiquer ici les informations de votre organisme si nécessaire (Contributeurs / Organismes) :</p>
				<div class="form-inline">
					<label for="idorg">id</label>
					<input type="number" class="form-control ml-2 mr-3" name="idorg" id="idorg" value="<?php echo $idorg;?>">
					<label for="org">Organisme</label>
					<input type="text" class="form-control ml-2" name="org" id="org" value="<?php echo $org;?>">
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
	$(document).ready(function () {
		'use strict'; 
		CKEDITOR.replace('stitre', {uiColor: '#FFCC99'});
	});
	function CKupdate () {
		for ( instance in CKEDITOR.instances ) { CKEDITOR.instances[instance].updateElement(); }		
	}
	$('#site').on('submit', function(e) {
		'use strict';
		CKupdate();
		var data = $(this).serialize();
		$.ajax({
			url: 'modeles/ajax/site/general.php', type: 'POST', dataType: "json", data : data,
			success: function(reponse) {
				var ok = reponse.statut;
				if (ok == 'Oui') {
					$('#mes').html(reponse.mes);					
				} else {
					$('#mes').html(reponse.mes);					
				}
				$('html, body').animate({scrollTop:0}, 'slow');
			}
		});
		return false;
	});
	$('#ad1').keyup(function () {
		'use strict'; 
		var lieu = $('#lieu').val(), ad1 = $('#ad1').val();
		var combi = '<b>Vérification</b> Ex : Liste des papillons <b>'+ ad1 + ' '+ lieu +'</b>';
		$('#combi').html(combi);
	});
	$('#ad2').keyup(function () {
		'use strict'; 
		var lieu = $('#lieu').val(), ad2 = $('#ad2').val();
		var combi = '<b>Vérification</b> Ex : Les dernières observations de papillons <b>'+ ad2 + ' '+ lieu +'</b>';
		$('#combi').html(combi);
	});
</script>