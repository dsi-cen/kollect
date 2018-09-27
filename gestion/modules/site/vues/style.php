<section class="container blanc">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header>	
				<h1>Style du site</h1>
			</header>
			<hr />
			<p>Vous utilisez actuellement le style "<b><?php echo $style;?></b>".
			<p>
				Quatre styles sont proposés (automne, hiver, printemps et été).<br />
				Vous pouvez facilement choisir celui qui vous convient le plus.
			</p>
			<form>
				<div class="radio">
					<label><input type="radio" name="optionsRadios" id="automne" value="automne"> Automne</label>
				</div>
				<div class="radio">
					<label><input type="radio" name="optionsRadios" id="hiver" value="hiver"> hiver</label>
				</div>
				<div class="radio disabled">
					<label><input type="radio" name="optionsRadios" id="printemps" value="printemps" disabled> Printemps (à faire)</label>
				</div>
				<div class="radio disabled">
					<label><input type="radio" name="optionsRadios" id="été" value="été" disabled> Eté (à faire)</label>
				</div>
				<button type="button" class="btn btn-success" id="BttV">Valider</button>
			</form>
			<div id="mes"></div>
			<br />
			<p>
				Nb: Après changement, il peut-être nécessaire de vider votre cache (ctrl F5) afin de voir le changement.
			</p>
		</div>
		<input id="style" type="hidden" value="<?php echo $style;?>"/>
	</div>	
</section>
<script>
	$(document).ready(function() {
		'use strict'; 
		var style = $('#style').val();
		if (style == 'automne') {
			$('#automne').attr('checked','checked')
		}
		if (style == 'hiver') {
			$('#hiver').attr('checked','checked')
		}		
	});
	$('#BttV').click(function() {
		'use strict'; 
		var choix = $('input[name=optionsRadios]:checked').val();
		$.ajax({
			url: 'modeles/ajax/site/style.php', type: 'POST', dataType: "json", data: {choix:choix},
			success: function(reponse) {
				if (reponse.statut == 'Oui') {
					$('#mes').html(reponse.mes);
				} else {
					$('#mes').html(reponse.mes);
				}
			}
		});		
	});
</script>