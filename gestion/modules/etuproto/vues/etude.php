<section class="container-fluid blanc">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header>	
				<h1>Gestion des études</h1>
			</header>
		</div>		
	</div>
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-2">
			<p>
				Les études ne sont pas liées à un observatoire particulier. Elles peuvent-être pluridisciplinaire. Les données taguées à une étude, facilite leurs export par la suite afin de produire les documents à réalisés dans le cadre de se travail.<br />
				Dans le champ "Etude", vous pouvez rajouter l'année, si par exemple l'étude porte que sur une année afin de faciliter sa recherche sur la fiche de saisie. Vous pouvez masquer dans la liste de proposition sur la fiche de saisie des études.
		</div>
	</div>
	<div class="row mt-2">
		<div class="col-md-8 col-lg-8">
			<button type="button" class="btn btn-success float-right" id="BttA">Ajouter une étude</button>
			<h2>Liste des études</h2>			
			<table class="table table-hover table-sm mt-3">
				<thead>
					<tr>
						<th></th>
						<th>Id</th>
						<th>Etude</th>
						<th>Libellé</th>
						<th>Voir</th>
					</tr>
				</thead>
				<tbody id="liste">
					<?php
					foreach($etude as $n)
					{
						?>
						<tr class="mod" id="<?php echo $n['idetude'];?>" data-etude="<?php echo $n['etude'];?>" data-lib="<?php echo $n['libelle'];?>" data-voir="<?php echo $n['masquer'];?>">
							<td><i class="fa fa-pencil curseurlien text-warning" title="modifier l'étude"></i></td><td><?php echo $n['idetude'];?></td><td><?php echo $n['etude'];?></td><td><?php echo $n['libelle'];?></td><td><?php echo $n['masquer'];?></td>
						</tr>
						<?php
					}
					?>
				</tbody>				
			</table>
		</div>
		<div class="col-md-4 col-lg-4">
			<div id="ajoutetude">
				<h2>Ajouter / modifier une étude</h2>
				<form id="Ajout" class="mt-3">
					<div class="form-group row">
						<label for="idetude" class="col-sm-2 col-form-label">Id</label>
						<div class="col-sm-3"><input type="text" class="form-control" id="idetude" disabled></div>							
					</div>
					<div class="form-group">
						<label for="etude" class="">Etude</label>
						<input type="text" class="form-control" id="etude" placeholder="Nom de l'étude">						
					</div>
					<div class="form-group">
						<label for="lib" class="">Libellé</label>
						<input type="text" class="form-control" id="lib"  placeholder="Description de l'étude si besoin">							
					</div>
					<div class="form-group row">
						<label for="voir" class="col-sm-4 col-form-label">Masqué à la saisie</label>
						<div class="col-sm-6">
							<select id="voir" class="form-control">
								<option value="oui">Afficher</option>
								<option value="non">Masquer</option>
							</select>
						</div>							
					</div>
					<div class="form-group row">
						<div class="col-sm-8">
							<button type="button" class="btn btn-success" id="BttV">Valider</button>
						</div>							
					</div>					
					<input id="typeval" type="hidden">
				</form>
			</div>
			<div id="mes"></div>		
		</div>
	</div>
	<input id="maxid" type="hidden" value="<?php echo $maxid;?>">
</section>
<script>
$(document).ready(function() {
	'use strict'; $('#ajoutetude').hide();
});
$('#liste').on('click', '.mod', function() {
	'use strict';
	$('#idetude').val($(this).attr('id'));
	var $this = $(this);
	$('#etude').val($this.data('etude')); $('#lib').val($this.data('lib')); $('#voir').val($this.data('voir'));	
	$('#ajoutetude').show(); $('#mes').html(''); $('#typeval').val('mod');
});
//Ajout
$('#BttA').click(function() {
	'use strict'; $('#ajoutetude').show(); $('#typeval').val('ajout'); $('#mes').html(''); 
	$('#idetude').val($('#maxid').val()); $('#etude').val(''); $('#lib').val(''); 
});
$('#BttV').click(function() {
	'use strict';
	var id = $('#idetude').val(), etu = $('#etude').val(), lib = $('#lib').val(), voir = $('#voir').val(), typeval = $('#typeval').val();
	$.ajax({
		url: 'modeles/ajax/etuproto/etudeajout.php', type: 'POST', dataType: "json", data: {etu:etu,id:id,lib:lib,voir:voir,typeval:typeval},
		success: function(reponse) {
			if (reponse.statut == 'Oui') {
				$('#mes').html(reponse.mes); $('#ajoutetude').hide();
				if (reponse.liste) { $('#liste').html(reponse.liste); $('#maxid').val(reponse.maxid);}
			} else {
				$('#mes').html(reponse.mes);
			}
		}
	});	
});
</script>