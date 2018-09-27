<section class="container blanc">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header>	
				<h1>Export liste taxons</h1>
			</header>					
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 col-lg-12">				
			<p>Vous pouvez exporter au format csv ou excel</p>
			<h2 class="h5">cdnom attribué (<?php echo $listeok[0];?>)</h2>
			<div class="divexport">
				<table id="listeok" class="table table-sm">		
					<thead>   
						<tr>
						   <th>Cdnom</th><th>Nom</th><th>Auteur</th><th>Nom vernaculaire</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($listeok[1] as $n)
						{
							?><tr><td><?php echo $n['cdnom'];?></td><td><?php echo $n['nom'];?></td><td><?php echo $n['auteur'];?></td><td><?php echo $n['nomvern'];?></td></tr><?php
						}
						?>
					</tbody>
				</table>
			</div>
			<h2 class="h5 mt-3">Plusieurs cdnom. A vérifier (<?php echo $listepr[0];?>)</h2>
			<div class="divexport">
				<table id="listepr" class="table table-sm">		
					<thead>   
						<tr>
						   <th>Cdnom</th><th>Nom</th><th>Auteur</th><th>Nom vernaculaire</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($listepr[1] as $n)
						{
							?><tr><td><?php echo $n['cdnom'];?></td><td><?php echo $n['nom'];?></td><td><?php echo $n['auteur'];?></td><td><?php echo $n['nomvern'];?></td></tr><?php
						}
						?>
					</tbody>
				</table>
			</div>
			<h2 class="h5 mt-3">Aucun cdnom de trouvé (<?php echo $listeno[0];?>)</h2>
			<div class="divexport">
				<table id="listeno" class="table table-sm">		
					<thead>   
						<tr>
						   <th>Nom</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($listeno[1] as $n)
						{
							?><tr><td><?php echo $n['nom'];?></td></tr><?php
						}
						?>
					</tbody>
				</table>
			</div>
			<br />
		</div>
	</div>
</section>
<script>
	$("table").tableExport({position: "top"});
</script>