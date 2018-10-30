<section class="container-fluid mb-3">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<h1 class="h2">Vos types de données</h1>
			</div>
		</div>
	</header>
	<div class="row mt-2">
		<div class="col-md-6 col-lg-6">
			<article class="card card-body">				
				<h2 class="h3">Tableau synthétique</h2>
				<p>Uniquement les données que vous avez vous-même saisies, ou saisies à votre nom</p>
				<table class="table table-sm table-hover">
					<thead>
						<tr>
							<th>Type de données</th>
							<th>Nb de données</th>							
						</tr>
					</thead>
					<tbody>
						<tr><td>Publiques</td><td><?php echo $nbpu;?></td></tr>
						<tr><td>Selon étude</td><td><?php echo $nbac;?></td></tr>
						<tr><td>D'origine privée</td><td><?php echo $nbpr;?></td></tr>
						<tr class="table-active">
							<td>Total</td><td><?php echo $nbtotal;?></td>
						</tr>
					</tbody>
				</table>
				<?php
				if($nbtotal == 0)
				{
					?><p>Vous avez aucune données dans la base</p><?php
				}
				if($nbpr > 0)
				{
					?>
					<h3 class="h5">Détail de vos données d'origine privées</h3>
					<p>Vous pouvez changer le type de floutage attribué à vos données</p>
					<table class="table table-sm table-hover">
						<thead>
							<tr>
								<th>Type de floutage</th>
								<th>Nb de données</th>
								<th></th>	
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Aucune dégradation</td><td><?php echo $nb0;?></td>
								<?php if($nb0 > 0) 
								{ 
									?><td><i class="fa fa-pencil curseurlien" id="nb0"></i></td><?php
								}
								else
								{
									?><td></td><?php
								}
								?>
							</tr>
							<tr>
								<td>Commune/maille 10x10</td><td><?php echo $nb1;?></td>
								<?php if($nb1 > 0) 
								{ 
									?><td><i class="fa fa-pencil curseurlien" id="nb1"></i></td><?php
								}
								else
								{
									?><td></td><?php
								}
								?>
							</tr>
							<tr>
								<td>Maille 10x10</td><td><?php echo $nb2;?></td>
								<?php if($nb2 > 0) 
								{ 
									?><td><i class="fa fa-pencil curseurlien" id="nb2"></i></td><?php
								}
								else
								{
									?><td></td><?php
								}
								?>
							</tr>
							<tr>
								<td>Département</td><td><?php echo $nb3;?></td>
								<?php if($nb3 > 0) 
								{ 
									?><td><i class="fa fa-pencil curseurlien" id="nb3"></i></td><?php
								}
								else
								{
									?><td></td><?php
								}
								?>
							</tr>
							<tr class="table-active">
								<td>Total</td><td><?php echo $nbtotalpr;?></td><td></td>
							</tr>
						</tbody>
					</table>
					<div id="modif">
						<p>Changer en :</p>
						<select id="floutage" class="">
							<option value="0">Pas de dégradation</option>
							<option value="1">Commune/maille 10x10</option>
							<option value="2">Maille 10x10</option>
							<option value="3">Département</option>
						</select>
						<br />
						<button type="button" class="btn btn-success mt-2" id="BttV">Valider</button>
					</div>
					<?php
				}
				?>
			</article>
		</div>		
	</div>	
</section>
<input id="idobser" type="hidden" value="<?php echo $idobser;?>"/>
<script>
var initial;
$(document).ready(function() {
	'use strict'; $('#modif').hide();	
});
$('#nb0').click(function() { 'use strict'; $('#modif').show(); $('#floutage option[value="0"]').prop('disabled', true); initial = 0; });
$('#nb1').click(function() { 'use strict'; $('#modif').show(); $('#floutage option[value="1"]').prop('disabled', true); initial = 1; });
$('#nb2').click(function() { 'use strict'; $('#modif').show(); $('#floutage option[value="2"]').prop('disabled', true); initial = 2; });
$('#nb3').click(function() { 'use strict'; $('#modif').show(); $('#floutage option[value="3"]').prop('disabled', true); initial = 3; });
$('#BttV').click(function() {
	'use strict';
	var sel = $('#floutage option:selected').val(), idobser = $('#idobser').val();
	$.ajax({url: "modeles/ajax/membre/typedon.php", type: 'POST', dataType: "json", data: {sel:sel,initial:initial,idobser:idobser},
		success: function(reponse) {
			if (reponse.statut == 'Oui') {
				location.reload();				
			} else {
				alert('erreur');
			}				
		}
	});
});
</script>