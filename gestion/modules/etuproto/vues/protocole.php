<section class="container-fluid blanc">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header>	
				<h1>Gestion des protocoles</h1>
			</header>
		</div>		
	</div>
	<div class="row mt-2">
		<div class="col-md-8 col-lg-8">
			<button type="button" class="btn btn-success float-right" id="BttA">Ajouter un protocole</button>
			<h2>Liste des protocoles</h2>			
			<table class="table table-hover table-sm mt-3">
				<thead>
					<tr>
						<th></th>
						<th>Id</th>
						<th>Protocole</th>
						<th>Libellé</th>
						<th>Lien</th>						
					</tr>
				</thead>
				<tbody id="liste">
					<?php
					foreach($protocole as $n)
					{
						?>
						<tr class="mod" id="<?php echo $n['idprotocole'];?>" data-proto="<?php echo $n['protocole'];?>" data-lib="<?php echo $n['libelle'];?>" data-url="<?php echo $n['url'];?>">
							<td><i class="fa fa-pencil curseurlien text-warning" title="modifier le protocole"></i></td><td><?php echo $n['idprotocole'];?></td><td><?php echo $n['protocole'];?></td><td><?php echo $n['libelle'];?></td><td><?php echo $n['url'];?></td>
						</tr>
						<?php
					}
					?>
				</tbody>				
			</table>
		</div>
		<div class="col-md-4 col-lg-4">
			<div id="ajoutproto">
				<h2>Ajouter / modifier un protocole</h2>
				<form id="Ajout" class="mt-3">
					<div class="form-group row">
						<label for="idproto" class="col-sm-2 col-form-label">Id</label>
						<div class="col-sm-3"><input type="text" class="form-control" id="idproto" disabled></div>							
					</div>
					<div class="form-group">
						<label for="proto">Protocole</label>
						<input type="text" class="form-control" id="proto" placeholder="Nom du protocole">							
					</div>
					<div class="form-group">
						<label for="lib">Libellé</label>
						<input type="text" class="form-control" id="lib"  placeholder="Description du protocole">						
					</div>
					<div class="form-group">
						<label for="url">Lien</label>
						<input type="text" class="form-control" id="url" placeholder="Lien du site du protocole">							
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
	'use strict'; $('#ajoutproto').hide();
});
$('#liste').on('click', '.mod', function() {
	'use strict';
	$('#idproto').val($(this).attr('id'));
	var $this = $(this);
	$('#proto').val($this.data('proto')); $('#lib').val($this.data('lib')); $('#url').val($this.data('url'));	
	$('#ajoutproto').show(); $('#mes').html(''); $('#typeval').val('mod');
});
//Ajout de protocole
$('#BttA').click(function() {
	'use strict'; $('#ajoutproto').show(); $('#typeval').val('ajout'); $('#mes').html(''); 
	$('#idproto').val($('#maxid').val()); $('#proto').val(''); $('#lib').val(''); $('#url').val('');
});
$('#BttV').click(function() {
	'use strict';
	var id = $('#idproto').val(), proto = $('#proto').val(), lib = $('#lib').val(), url = $('#url').val(), typeval = $('#typeval').val();
	$.ajax({
		url: 'modeles/ajax/etuproto/protoajout.php', type: 'POST', dataType: "json", data: {proto:proto,id:id,lib:lib,url:url,typeval:typeval},
		success: function(reponse) {
			if (reponse.statut == 'Oui') {
				$('#mes').html(reponse.mes); $('#ajoutproto').hide();
				if (reponse.liste) { $('#liste').html(reponse.liste); $('#maxid').val(reponse.maxid);}
			} else {
				$('#mes').html(reponse.mes);
			}
		}
	});	
});
</script>