<section class="container mb-3">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<h1 class="h2">Espèces de <?php echo $nomd;?> à retrouver <?php echo $rjson_site['ad2'];?> <?php echo $rjson_site['lieu'];?></h1>
			</div>
		</div>		
	</header>
	<div class="row mt-2">
		<div class="col-md-12 col-lg-12">
			<div class="card card-body">
				<p class="mb-0">Non observé depuis :</p>
				<form>
					<?php
					foreach($taban as $n)
					{
						?>						
						<div class="custom-control custom-radio custom-control-inline">
							<input class="custom-control-input" type="radio" name="radioan" id="<?php echo $n;?>" value="<?php echo $n;?>"> 
							<label class="custom-control-label" for="<?php echo $n;?>"><?php echo $n;?></label>
						</div>						
						<?php					
					}
					?>
				</form>
				<div id="liste"></div>
			</div>
		</div>		
	</div>
</section>
<input id="sel" type="hidden" value="<?php echo $nomvar;?>"/>
<script>
$(document).ready(function() {
	'use strict';
	$('input[type=radio][name=radioan]:last').attr('checked', true);
	var choix = $('input[type=radio][name=radioan]:checked').val();
	var sel = $('#sel').val();
	recupliste(sel,choix);
});
$('input[type=radio][name=radioan]').change(function() {
	'use strict';
	var choix = this.value, sel = $('#sel').val(); 
	recupliste(sel,choix);	
});
function recupliste(sel,choix) {
	$.ajax({
		url: 'modeles/ajax/atrouver/liste.php', type: 'POST', dataType: "json", data: {sel:sel,choix:choix},
		success: function(reponse) {
			if (reponse.statut == 'Oui') {
				$('#liste').html(reponse.liste); remplirtable(reponse.data);
			} else {
				$('#liste').html(''); 
			}
		}
	});		
}
function remplirtable(data) {
	$('#tblliste').DataTable({
		language : { url: "../dist/js/datatables/france.json" },
		data : data, deferRender: true, scrollY: 600, scrollCollapse: true, scroller: true,
		columns: [{ data: 0 },{ data: 1 },{ data: 2 },{ data: 3 },{ data: { _: "4.date", sort:"4.tri" }}]
	});
}
</script>