<section class="container-fluid mb-3">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<header>
					<ol class="breadcrumb float-right">
						<?php
						if(isset($statdep))
						{
							?><li class="breadcrumb-item"><a href="index.php?module=statut&amp;action=statut&amp;iddep=<?php echo $dep['iddep'];?>"><?php echo $dep['departement'];?></a></li><?php
						}
						?>
						<li class="breadcrumb-item"><?php echo $lien;?></li>
						<li class="breadcrumb-item active">Liste statuts</li>
					</ol>
					<h1 class="h2 text-center"><?php echo $titrep;?></h1>									
				</header>
			</div>
		</div>
	</div>
	<div class="card card-body mt-2">
		<div class="row">
			<div class="col-md-12 col-lg-12">
				<?php
				if(isset($tab))
				{
					if($droit == 'non')
					{
						?><p>Sauf espèces sensibles et/ou floutées lors de la saisie</p><?php
					}					
					?>
					<table id="stat" class="table table-hover table-sm" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>Observatoire</th><th>Nom</th><th>Nom français</th><th title="Observation">Nb</th><th title="% des observations totales">%</th>
								<?php
								if(!empty($dh))
								{
									?><th>DE</th><?php
								}
								if(!empty($pn))
								{
									?><th>PF</th><?php
								}
								if($emprise['emprise'] != 'fr')
								{
									if(!empty($znieff))
									{
										?><th>ZNIEFF</th><?php
									}
									if(isset($lr))
									{
										?><th>LR Régionale</th><?php
									}									
									?><th>Indice</th><?php
								}
								else
								{
                                    if(isset($lrm))
                                    {
                                        ?><th>LR Mondiale</th><?php
                                    }
								    if(isset($lre))
									{
										?><th>LR Europe</th><?php
									}
									if(isset($lrf))
									{
										?><th>LR France</th><?php
									}
								}
								?>
							</tr>
						</thead>
						<tbody>
						<?php
						foreach($tab as $n)
						{
							?>
							<tr>
								<td><?php echo $n['icon'];?></td>
								<td><a href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d=<?php echo $n['observa'];?>&amp;id=<?php echo $n['cdref'];?>"><i><?php echo $n['nom'];?></i></a></td>
								<td><?php echo $n['nomvern'];?></td><td><?php echo $n['nb'];?></td><td><?php echo $n['pourcent'];?></td>
								<?php
								if(!empty($dh))
								{
									?><td><?php echo $n['dh'];?></td><?php
								}
								if(!empty($pn))
								{
									?><td><?php echo $n['pn'];?></td><?php
								}
								if($emprise['emprise'] != 'fr')
								{
									if(!empty($znieff))
									{
										?><td><?php echo $n['znieff'];?></td><?php
									}
									if(isset($lr))
									{
										?><td><?php echo $n['lr'];?></td><?php
									}
								}
								else
								{
									if(isset($lre))
									{
										?><th><?php echo $n['lre'];?></th><?php
									}
                                    if(isset($lrm))
                                    {
                                        ?><th><?php echo $n['lrm'];?></th><?php
                                    }
									if(isset($lrf))
									{
										?><th><?php echo $n['lrf'];?></th><?php
									}
								}
								?>
								<td><?php echo $n['ir'];?></td>
							</tr>
							<?php
						}						
						?>
						</tbody>
					</table>
					<?php
				}
				?>
			</div>				
		</div>		
	</div>
	<input type="hidden" value="<?php echo $titre;?>" id="com">
</section>
<script>
$(document).ready(function() {
	'use strict';
	var com = $('#com').val();
	var table = $('#stat').DataTable({
		language : { url: "dist/js/datatables/france.json" }, order: [], "scrollY": "600px", "scrollCollapse": true, "paging": false,
		buttons: [ { extend: 'csvHtml5', title:com },{ extend: 'excelHtml5', title:com } ],
		initComplete: function () { setTimeout( function () { table.buttons().container().appendTo( '#stat_wrapper .col-md-6:eq(0)' ); }, 10 ); }
	});	
});
</script>