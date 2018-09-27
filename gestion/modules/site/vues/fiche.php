<section class="container blanc">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header>	
				<h1>Gestion de l'affichage des fiches</h1>
			</header>
			<p>
				Permet de choisir la carte qui s'affichera par défaut dans les fiches.<br />
				Vous pouvez pour la légende des cartes du site choisir de 3 à 6 classes. (cliquer sur "configuration par défaut") pour voir comment remplir.<br />
				Les couleurs doivent-être sous forme hexadécimale (HEX) pour html (#XXXXXX). Pour définir vos couleurs, vous pouvez vous inspirer du site <a href="http://colorbrewer2.org/">ColorBrewer</a>
			</p>
			<div id="mes"></div>
			<form class="form" id="site" method="post">
				<div class="form-group row">
					<legend class="col-form-legend col-sm-3">Choisir l'affichage par défaut</legend>
					<div class="col-sm-5">
						<div class="form-check">
							<label class="form-check-label"><input type="radio" name="cartefiche" id="commune" value="commune" class="form-check-input" checked> Carte communale / départementale</label>
						</div>
						<div class="form-check">
							<label class="form-check-label"><input type="radio" name="cartefiche" id="maille" value="maille" class="form-check-input"> Carte maille</label>						
						</div>
					</div>
				</div>
				<div class="form-group row">
					<label for="nban" class="col-sm-4 col-form-label">Définir le nombre de classes d'années (3 min - 6 max)</label>
					<div class="col-sm-1"><input type="number" min="3" max="6" class="form-control" name="nban" id="nban" value="<?php echo $nban;?>"></div>
					<div class="col-sm-2">
						<button type="button" class="btn btn-info" id="BttD">Configuration par défaut</button>
					</div>
				</div>
				<hr />
				<div class="form-group row">
					<label for="a1" class="col-sm-1 col-form-label">classe 1</label>
					<div class="col-sm-1"><input type="text" class="form-control" id="a1" value="<?php echo date('Y');?>" disabled></div>
					<label for="la1" class="col-sm-1 col-form-label">label</label>
					<div class="col-sm-4"><input type="text" class="form-control" id="la1" value="Observation en <?php echo date('Y');?>" disabled></div>
					<label for="ca1" class="col-sm-1 col-form-label">Couleur</label>
					<div class="col-sm-2"><input type="text" class="form-control" name="ca1" id="ca1" onchange="update(this.value,this.id)" value="<?php echo $ca1;?>"></div>
					<div class="col-sm-1" ><input type="text" class="form-control" id="ca1c"></div>
				</div>
				<div class="form-group row" id="classe2">
					<label for="a2" class="col-sm-1 col-form-label">classe 2</label>
					<div class="col-sm-1"><input type="text" class="form-control" name="a2" id="a2" value="<?php echo $a2;?>"></div>
					<label for="la2" class="col-sm-1 col-form-label">label</label>
					<div class="col-sm-4"><input type="text" class="form-control" name="la2" id="la2" value="<?php echo $la2;?>"></div>
					<label for="ca1" class="col-sm-1 col-form-label">Couleur</label>
					<div class="col-sm-2"><input type="text" class="form-control" name="ca2" id="ca2" onchange="update(this.value,this.id)" value="<?php echo $ca2;?>"></div>
					<div class="col-sm-1" ><input type="text" class="form-control" id="ca2c"></div>
				</div>
				<div class="form-group row" id="classe3">
					<label for="a3" class="col-sm-1 col-form-label">classe 3</label>
					<div class="col-sm-1"><input type="text" class="form-control" name="a3" id="a3" value="<?php echo $a3;?>"></div>
					<label for="la3" class="col-sm-1 col-form-label">label</label>
					<div class="col-sm-4"><input type="text" class="form-control" name="la3" id="la3" value="<?php echo $la3;?>"></div>
					<label for="ca3" class="col-sm-1 col-form-label">Couleur</label>
					<div class="col-sm-2"><input type="text" class="form-control" name="ca3" id="ca3" onchange="update(this.value,this.id)" value="<?php echo $ca3;?>"></div>
					<div class="col-sm-1" ><input type="text" class="form-control" id="ca3c"></div>
				</div>
				<div class="form-group row" id="classe4">
					<label for="a4" class="col-sm-1 col-form-label">classe 4</label>
					<div class="col-sm-1"><input type="text" class="form-control" name="a4" id="a4" value="<?php echo $a4;?>"></div>
					<label for="la4" class="col-sm-1 col-form-label">label</label>
					<div class="col-sm-4"><input type="text" class="form-control" name="la4" id="la4" value="<?php echo $la4;?>"></div>
					<label for="ca4" class="col-sm-1 col-form-label">Couleur</label>
					<div class="col-sm-2"><input type="text" class="form-control" name="ca4" id="ca4" onchange="update(this.value,this.id)" value="<?php echo $ca4;?>"></div>
					<div class="col-sm-1" ><input type="text" class="form-control" id="ca4c"></div>
				</div>
				<div class="form-group row" id="classe5">
					<label for="a5" class="col-sm-1 col-form-label">classe 5</label>
					<div class="col-sm-1"><input type="text" class="form-control" name="a5" id="a5" value="<?php echo $a5;?>"></div>
					<label for="la5" class="col-sm-1 col-form-label">label</label>
					<div class="col-sm-4"><input type="text" class="form-control" name="la5" id="la5" value="<?php echo $la5;?>"></div>
					<label for="ca5" class="col-sm-1 col-form-label">Couleur</label>
					<div class="col-sm-2"><input type="text" class="form-control" name="ca5" id="ca5" onchange="update(this.value,this.id)" value="<?php echo $ca5;?>"></div>
					<div class="col-sm-1" ><input type="text" class="form-control" id="ca5c"></div>
				</div>
				<div class="form-group row" id="classe6">
					<label for="a6" class="col-sm-1 col-form-label">classe 6</label>
					<div class="col-sm-1"><input type="text" class="form-control" name="a6" id="a6" value="<?php echo $a6;?>"></div>
					<label for="la6" class="col-sm-1 col-form-label">label</label>
					<div class="col-sm-4"><input type="text" class="form-control" name="la6" id="la6" value="<?php echo $la6;?>"></div>
					<label for="ca6" class="col-sm-1 col-form-label">Couleur</label>
					<div class="col-sm-2"><input type="text" class="form-control" name="ca6" id="ca6" onchange="update(this.value,this.id)" value="<?php echo $ca6;?>"></div>
					<div class="col-sm-1" ><input type="text" class="form-control" id="ca6c"></div>
				</div>
				<div class="form-group row">
					<label class="col-sm-5">Si vous voulez faire ressortir les 1ere données pour une emprise</label>
					<div class="col-sm-1">
						<div class="form-check">
							<label class="form-check-label"><input class="form-check-input" type="checkbox" name="new" id="new" value="new"> Cocher</label>
						</div>
					</div>
					<label for="canew" class="col-sm-1 ">Couleur</label>
					<div class="col-sm-2"><input type="text" class="form-control" name="canew" id="canew" onchange="update(this.value,this.id)" value="<?php echo $canew;?>"></div>
					<div class="col-sm-1" ><input type="text" class="form-control" id="canewc"></div>
				</div>				
				<div class="form-group row">
					<label for="bilan" class="col-sm-6 col-form-label">Couleur pour le max des cartes bilan (par défaut = couleur année en cours)</label>
					<div class="col-sm-2"><input type="text" class="form-control" name="bilan" id="bilan" onchange="update(this.value,this.id)" value="<?php echo $bilan;?>"></div>
					<div class="col-sm-1" ><input type="text" class="form-control" id="bilanc"></div>
				</div>
				<div class="form-group row">
					<label for="graphdebut" class="col-sm-4 col-form-label">Première année pour les graph (bilan, etc..)</label>
					<div class="col-sm-1"><input type="text" class="form-control" name="graphdebut" id="graphdebut" value="<?php echo $graphdebut;?>"></div>					
				</div>
				<div class="form-group row">
					<label class="col-sm-8">Si vous voulez afficher les altitudes mini et max dans l'onglet info de la fiche espèce</label>
					<div class="col-sm-1">
						<div class="form-check">
							<label class="form-check-label"><input class="form-check-input" type="checkbox" name="alt" id="alt" value="oui"> Cocher</label>
						</div>
					</div>					
				</div>	
				<div class="form-group row">
					<div class="col-sm-offset-1 col-sm-8">
						<button type="submit" class="btn btn-success" id="BttV">Valider les modifications</button>
					</div>							
				</div>
			</form>
			<input id="valcarte" type="hidden" value="<?php echo $valcarte;?>"/><input id="val4" type="hidden" value="<?php echo $val4;?>"/><input id="val5" type="hidden" value="<?php echo $val5;?>"/><input id="val6" type="hidden" value="<?php echo $val6;?>"/>
			<input id="valalt" type="hidden" value="<?php echo $alt;?>"/>
		</div>
	</div>	
</section>
<script>
	$(document).ready(function() {
		'use strict'; 
		if ($('#valcarte').val() == 'maille') { $('#maille').attr('checked','checked') }
		if ($('#val4').val() == "oui") { 
			$('#classe4').show(); $('#nban').val(4);
		} else {
			$('#classe4').hide();
		}
		if ($('#val5').val() == "oui") {
			$('#classe5').show(); $('#nban').val(5);
		} else {
			$('#classe5').hide();
		}
		if ($('#val6').val() == "oui") {
			$('#classe6').show(); $('#nban').val(6);
		} else {
			$('#classe6').hide();
		}
		if ($('#canew').val() != ''){ $('#canewc').css('background-color', $('#canew').val()); $('#new').prop('checked', true); }
		if ($('#ca1').val() != '') { $('#ca1c').css('background-color', $('#ca1').val()); }
		if ($('#ca2').val() != '') { $('#ca2c').css('background-color', $('#ca2').val()); }
		if ($('#ca3').val() != '') { $('#ca3c').css('background-color', $('#ca3').val()); }
		if ($('#ca4').val() != '') { $('#ca4c').css('background-color', $('#ca4').val()); }
		if ($('#ca5').val() != '') { $('#ca5c').css('background-color', $('#ca5').val()); }
		if ($('#ca6').val() != '') { $('#ca6c').css('background-color', $('#ca6').val()); }
		if ($('#bilan').val() != '') { $('#bilanc').css('background-color', $('#bilan').val()); }
		if ($('#valalt').val() == 'oui') { $('#alt').prop('checked', true); }
	});
	$('#BttD').click(function() {
		'use strict'; 
		$('#nban').val(6);
		$('#classe4').show(); $('#classe5').show(); $('#classe6').show();
		$('#ca1').val('#1a9850'); $('#ca1c').css('background-color', '#1a9850');
		$('#a2').val(2010); $('#la2').val('Dernière observation après 2010'); $('#ca2').val('#91cf60'); $('#ca2c').css('background-color', '#91cf60');
		$('#a3').val(2000); $('#la3').val('Dernière observation entre 2000 et 2010'); $('#ca3').val('#d9ef8b'); $('#ca3c').css('background-color', '#d9ef8b');
		$('#a4').val(1980); $('#la4').val('Dernière observation entre 1980 et 2000'); $('#ca4').val('#fee08b'); $('#ca4c').css('background-color', '#fee08b');
		$('#a5').val(1950); $('#la5').val('Dernière observation entre 1950 et 1980'); $('#ca5').val('#fc8d59'); $('#ca5c').css('background-color', '#fc8d59');
		$('#a6').val(1950); $('#la6').val('Dernière observation avant 1950'); $('#ca6').val('#d73027'); $('#ca6c').css('background-color', '#d73027');
		$('#bilan').val('#1a9850'); $('#bilanc').css('background-color', '#1a9850');
		$('#canew').val('#61A9F3'); $('#canewc').css('background-color', '#61A9F3'); $('#new').prop('checked', true);
	});
	function update(color,id) {
		var col = id+'c';
		$('#'+col).css('background-color', color);
		if (col == 'ca1c') {
			$('#bilan').val(color); $('#bilanc').css('background-color', color);
		}
	}
	$('#nban').change(function() {
		'use strict'; 
		var sel = $('#nban').val();
		if (sel == 6) { $('#classe4').show(); $('#classe5').show(); $('#classe6').show(); }
		if (sel == 5) { $('#classe4').show(); $('#classe5').show(); $('#classe6').hide(); }
		if (sel == 4) { $('#classe4').show(); $('#classe5').hide(); $('#classe6').hide(); }
		if (sel == 3) { $('#classe4').hide(); $('#classe5').hide(); $('#classe6').hide(); }
	});
	$('#site').on('submit', function(e) {
		'use strict'; 
		var data = $(this).serialize();
		$.ajax({ url: 'modeles/ajax/site/fiche.php', type: 'POST', dataType: "json", data : data,
			success: function(reponse) {
				if (reponse.statut == 'Oui') {
					$('#mes').html(reponse.mes);					
				} else {
					$('#mes').html(reponse.mes);					
				}
				$('html, body').animate({scrollTop:0}, 'slow');
			}
		});
		return false;
	});
</script>