<section class="container">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<h1 class="h2">
					Ajouter des sons
					<small class="text-muted"> (Observation n° <?php echo $getidobs;?>)</small>
				</h1>
			</div>
		</div>		
	</header>
	<div class="row mt-2">
		<div class="col-md-12 col-lg-12">
			<div class="card card-body">
				<div id="nbson"></div>
				<p>
					Vous pouvez rajouter des fichiers sons à votre observation.<br />
					- Format accepté : mp3<br />
					- Taille maximum du fichier : 2 Mo					
				</p>
				<form id="ason" enctype="multipart/form-data" method="post">
					<div class="form-group">
						<input class="form-control" type="text" name="descri" placeholder="Brève description - max : 100 caractères (ex : chant d'un mâle)">
					</div>
					<div class="form-group" id="cachemp">	
						<input type="file" id="mp" name="mp" accept=".mp3"/>						
					</div>
					<p id="ok"></p>
					<div class="form-group" id="voirbt">
						<button type="submit" class="btn btn-success ml-3">Télécharger</button>
					</div>
					<input type="hidden" id="idobs" name="idobs" value="<?php echo $getidobs;?>"><input type="hidden" name="cdnom" id="cdnom"><input type="hidden" name="idobser" id="idobser"><input type="hidden" name="dates" id="dates"><input type="hidden" name="nomson" id="nomson">
				</form>
				<div id="valajax"><progress></progress></div><div id="mes"></div>
				<div id="ecoute">
					<h2 class="h6">Vérifier votre enregistrement</h2>
					<audio controls="controls" preload="none" id="nson">
						<source src="" type="audio/mp3"/>
						Votre navigateur n'est pas compatible
					</audio>
				</div>
			</div>
		</div>
	</div>
	<input type="hidden" id="idm" value="<?php echo $_SESSION['idmembre'];?>"><input type="hidden" id="observa"><input type="hidden" id="nouv" value="oui">
</section>
<script>
$(document).ready(function () {
	$('#voirbt').hide(); $('#valajax').hide(); $('#ecoute').hide();
	var idobs = $('#idobs').val(), idm = $('#idm').val();
	if (idobs != '') {
		info(idobs,idm); 
	} else {
		$('#cachemp').hide(); $('#mes').html('<div class="alert alert-danger">La page a été rechargée : impossible de continuer</div>');
	}
});
function info(idobs,idm) {
	'use strict';
	$.ajax({url: "modeles/ajax/son/chercherobs.php", type: 'POST', dataType: "json", data: {idobs:idobs,idm:idm},
		success: function(reponse) {
			if (reponse.statut == 'Oui') {
				$('#idobser').val(reponse.idobser); $('#cdnom').val(reponse.info.cdref); $('#dates').val(reponse.info.date1); $('#observa').val(reponse.info.observa);								
				rson(reponse.info.cdref,reponse.info.observa);
			} else {
				alert('problème...');															
			}		
		}
	});
}
function rson(cdnom,nomvar) {
	'use strict';
	$.ajax({
		url: "modeles/ajax/son/listeson.php", type: 'POST', dataType: "json", data: {cdnom:cdnom,nomvar:nomvar},
		success: function(reponse) {
			var nom = reponse.genre +' '+ reponse.espece, g = reponse.genre.charAt(0), e = reponse.espece, nb = reponse.nb;
			if (nb == 0) {
				$('#nomson').val(g +'-'+ e + cdnom); $('#nbson').html('<p class="text-info"><b>Premier son de '+ nom +' sur le site</b></p>');
			} else {
				var nb1 = parseInt(nb) + 1; 
				$('#nomson').val(g +'-'+ e + cdnom +'-'+ nb); $('#nbson').html('<p class="text-info"><b>'+ nb1 +'ème sons de '+ nom +' sur le site</b></p>');
			}
		}
	});	
}
$('#mp').change(function() {
	'use strict';
	var son = $(this).val();
	if (son.length != 0) {
		var ext = son.split(".");
		if (ext[1] == 'mp3') {
			var taille = this.files[0].size;
			if (taille <= 2000000) {
				$('#ok').html('Fichier '+ this.files[0].type +', '+ taille +' octets, dernière modification le '+ this.files[0].lastModifiedDate.toLocaleDateString()); $('#voirbt').show();
				$('#mes').html('');
				if ($('#nouv').val() == 'non') {
					var cdnom = $('#cdnom').val(), nomvar = $('#observa').val(); $('#ecoute').hide();
					rson(cdnom,nomvar);
				}
			} else {
				$('#mes').html('<div class="alert alert-danger">Votre fichier est trop gros. Il doit faire moins de 2 Mo</div>'); $('#voirbt').hide(); $('#ok').html(''); 
			}		
		} else {
			$('#voirbt').hide(); $('#ok').html(''); 
		}		
	} else {
		$('#voirbt').hide(); $('#ok').html(''); 
	}
});
$('#ason').on('submit', function (e) {
	'use strict';
	$('#valajax').show();
	e.preventDefault();
	var $form = $(this);
	var formdata = (window.FormData) ? new FormData($form[0]) : null;
	var data = (formdata !== null) ? formdata : $form.serialize();
	$.ajax({
		url: 'modeles/ajax/son/inserson.php', type: 'POST', dataType: 'json', data: data, contentType: false, processData: false,
		success: function(reponse) {
			if (reponse.statut == 'Oui') {
				$('#valajax').hide(); $('#mes').html('<div class="alert alert-success">Le fichier à bien été téléchargé et enregistré. Vous pouvez en rajouter un autre si vous le souhaitez pour cette observation</div>');
				$('#nouv').val('non'); $('#voirbt').hide(); $('#nson').attr('src','son/'+ reponse.son +'.mp3'); $('#ecoute').show(); 
			} else {
				$('#mes').html(reponse.mes); $('#valajax').hide();
			}
		}
	});
});

</script>