<section>
	<div class="d-flex flex-row header p-2">
		<div class="container">
			<div class="row">
				<header class="col-md-12 col-lg-12">
					<div class="d-flex justify-content-start">
						<h1 class="h4 text-uppercase ctitre">Recherche par espèce</h1>
						<ol class="breadcrumb ml-auto mb-0 p-1 small">							
							<li class="breadcrumb-item"><a href="index.php?module=recherche&amp;action=recherche">Recherche</a></li>
							<li class="breadcrumb-item active">Par espèce</li>
						</ol>
					</div>
				</header>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row mt-5">
			<div class="col-md-8">
				<h2 class="h4 ctitre">Informations</h2>
				<p>La recherche par espèce se fait soit en cliquant sur une lettre ou bien en tapant un nom d'espèces dans le champ dessous.</p>
				<h3 class="h6 ctitre">Nom latin</h3>
				<div class="d-flex flex-wrap">
					<?php
					foreach($lettre as $n)
					{
						?>
						<button type="button" class="btn blanc colorbiblio_bg ml-3 mt-1 curseurlien" id="<?php echo $n['l'];?>"><?php echo $n['l'];?></button>
						<?php
					}						
					?>
				</div>
				<h3 class="h6 ctitre mt-3">Nom français</h3>
				<div class="d-flex flex-wrap">
					<?php
					foreach($lettrefr as $n)
					{
						if(!empty($n['l']))
						{
							//$idfr = mb_strtolower($n['l'], 'UTF-8');
							?>
							<button type="button" class="btn blanc colorbiblio_bg ml-3 mt-1 curseurlien" id="fr-<?php echo $n['l'];?>"><?php echo $n['l'];?></button>
							<?php
						}
					}						
					?>
				</div>
				<h3 class="h6 ctitre mt-3">Une espèce</h3>
				
			</div>
			<div class="col-md-4">
				<div id="listealpa"></div>
			</div>
		</div>
	</div>
</section>
<script>
$('.curseurlien').click(function(){
	'use strict';
	var id = $(this).attr('id');
	$.post('modeles/ajax/listetaxon.php', {id:id}, function(listealpha){ $('#listealpa').html(listealpha); });		
});
</script>