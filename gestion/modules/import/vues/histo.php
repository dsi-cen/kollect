<section class="container blanc">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header">	
				<h1>Historique des imports</h1>
			</header>					
		</div>
	</div>
	<div class="row mt-3">
		<div class="col-md-12 col-lg-12">
			<?php
			if($nbimport > 0)
			{
				?>
				<h2 class="h4 mb-3"><?php echo $libimport;?></h2>
				<table class="table table-sm table-hover">
					<thead>
						<tr>
							<th></th><th>Date</th><th>Description</th><th>Idobs mini</th><th>idobs max</th><th>Admin</th>
						</tr>
					</thead>
					<tbody>
					<?php
					foreach($liste as $n)
					{
						?>
						<tr id="<?php echo $n['id'];?>">
							<td><i class="fa fa-trash curseurlien text-danger sup" title="Supprimer les données de cet import"></i></td><td><?php echo $n['datefr'];?></td><td><?php echo $n['descri'];?></td><td><?php echo $n['idobsdeb'];?></td><td><?php echo $n['idobsfin'];?></td><td><?php echo $n['prenom'];?> <?php echo $n['nom'];?></td>
						</tr>
						<?php
					}
					?>
					</tbody>
				</table>
				<?php
			}
			else
			{
				?><p>Aucun import a été réalisé (via le module d'importation)</p><?php
			}
			?>
		</div>
	</div>	
</section>
<div id="dia1" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Confirmation</h4>
			</div>
			<div class="modal-body">
				<p>
					Voulez-vous vraiment supprimer cet import ?<br />
					-> Les fiches, observations liées à cet import seront supprimées. Les sites, coordonnées et observateurs, seront conservés.
				</p>
				<input id="idsup" type="hidden"/>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal" id="bttandia1">Annuler</button>
				<button type="button" class="btn btn-success" data-dismiss="modal" id="bttdia1">Oui</button>
			</div>
		</div>
	</div>
</div>
<script>
$('.sup').on('click', function() {
	var id = $(this).parent().parent().attr('id');
	$('#idsup').val(id); $('#dia1').modal('show');
	$('#'+ id).css('color','red');
});
$('#bttdia1').click(function () { 
	'use strict';
	var id = $('#idsup').val();
	suppression(id);	
});
$('#bttandia1').click(function () { 
	'use strict';
	var id = $('#idsup').val();
	$('#'+ id).css('color','#292b2c');	
});
function suppression(id) {
	'use strict';	
	$('html').css('cursor','Wait');
	$.ajax({
		url: 'modeles/ajax/import/supimport.php', type: 'POST', dataType: "json", data: {id:id},
		success: function(reponse) {
			if (reponse.statut == 'Oui') {
				$('#'+ id).remove(); $('html').css('cursor','default');
			} else {
				$('html').css('cursor','default');
				alert('erreur lors de la suppression');
				$('#'+ id).css('color','#292b2c');
			}
		}
	});		
}
</script>