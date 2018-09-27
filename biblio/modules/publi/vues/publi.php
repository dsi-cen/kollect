<section>
	<div class="d-flex flex-row header p-2">
		<div class="container">
			<div class="row">
				<header class="col-md-12 col-lg-12">
					<div class="d-flex justify-content-start">
						<h1 class="h4 text-uppercase ctitre">Recherche par publication</h1>
						<ol class="breadcrumb ml-auto mb-0 p-1 small">							
							<li class="breadcrumb-item"><a href="index.php?module=recherche&amp;action=recherche">Recherche</a></li>
							<li class="breadcrumb-item active">Par publication</li>
						</ol>
					</div>
				</header>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row mt-5">
			<div class="col-md-8">
				<div class="row">
					<div class="col">
						<img src="dist/img/motcle.jpg" class="img-fluid" alt="mot">
					</div>
					<div class="col-9">
						<h2 class="h4 ctitre">Informations</h2>
						<p>Recherche par titre de publication<br />Cliquez sur l'une des lettres pour afficher les publications associées.</p>
					</div>
				</div>
				<div class="row mt-3">
					<div class="col">
						<div class="d-flex flex-wrap">
						<?php
						foreach($lettre as $n)
						{
							?>
							<button type="button" class="btn blanc colorbiblio_bg ml-3 mt-2 curseurlien" id="<?php echo $n['l'];?>"><?php echo $n['l'];?></button>
							<?php
						}						
						?>
						</div>
					</div>
				</div>
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
	$.post('modeles/ajax/listepubli.php', {id:id}, function(listealpha){ $('#listealpa').html(listealpha); });		
});
</script>