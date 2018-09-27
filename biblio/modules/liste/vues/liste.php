<section>
	<div class="d-flex flex-row header p-2">
		<div class="container">
			<div class="row">
				<header class="col-md-12 col-lg-12">
					<div class="d-flex justify-content-start">
						<h1 class="h4 text-uppercase ctitre">bibliographie <?php echo $labbib;?></h1>
						<ol class="breadcrumb ml-auto mb-0 p-1 small">							
							<li class="breadcrumb-item"><a href="index.php?module=recherche&amp;action=recherche">Recherche</a></li>
							<?php echo $bread;?>
							<li class="breadcrumb-item active"><?php echo $nom;?></li>
						</ol>
					</div>
				</header>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row mt-5">
			<div class="col-md-12">
				<h2 class="h4 ctitre mb-4">Résultats pour <?php echo $nom;?></h2>				
				<?php
				if(isset($bib))
				{
					?>
					<table id="liste" class="table table-hover table-sm" cellspacing="0" width="100%">
						<thead class="small">
							<tr>
								<th>Auteur(s)</th>
								<th>Année</th>
								<th>Titre</th>
								<th>Publication</th>
								<th>Volume</th>								
							</tr>
						</thead>
						<tbody class="small">
							<?php
							foreach($bib as $n)
							{
								?>
								<tr>
									<td><?php echo $n['auteur'];?></td><td><?php echo $n['annee'];?></td><td><a href="index.php?module=biblio&amp;action=biblio&amp;id=<?php echo $n['idbiblio'];?>"><?php echo $n['titre'];?></a></td><td><?php echo $n['publi'];?></td><td><?php echo $n['tome'];?></td>
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
					?><p>Aucun résultat</p><?php
				}
				?>				
			</div>
		</div>
	</div>
	<input type="hidden" value="<?php echo $nom;?>" id="nom">
</section>
<script>
$(document).ready(function() {
	'use strict';
	var nom = $('#nom').val();
	var table = $('#liste').DataTable({
		language : { url: "../dist/js/datatables/france.json" },
		"scrollY": "600px", "scrollCollapse": true, "paging": false,
		buttons: [ { extend: 'csvHtml5', title:'Biblio-'+ nom },{ extend: 'excelHtml5', title:'Biblio-'+ nom } ],
		initComplete: function () {
			setTimeout( function () { table.buttons().container().appendTo( '#liste_wrapper .col-md-6:eq(0)' ); }, 10 );
		}
	});	
});
</script>