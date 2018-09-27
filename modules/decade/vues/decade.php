<section class="container-fluid mb-3">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<div class="d-flex justify-content-start">
					<h1 class="h3">Liste des espèces observées durant la <?php echo $dec;?> décade du mois de <?php echo $CMois;?></h1>
					<ol class="breadcrumb ml-auto mb-0">
						<li class="breadcrumb-item active">Liste</li>
						<li class="breadcrumb-item"><a href="index.php?module=decade&amp;action=graph&amp;decade=<?php echo $decade;?>">Graph</a></li>
					</ol>
				</div>
			</div>
		</div>		
	</header>
	<div class="row mt-2">
		<div class="col-md-6 col-lg-6">
			<div class="card card-body">
				<p class="h6"><?php echo $dec1;?> <?php echo $CMois;?> <?php echo $nbsp;?></p>
				<div class="mt-3 border p-2 border-right-0 border-left-0">
					<form class="form-inline" id="rdec">
						<label class="mr-2" for="jrs">Jour</label>
						<input type="number" class="form-control form-control-sm col-2 mb-2 mr-sm-2 mb-sm-0" id="jrs" required="" min="1" max="31">
						<label class="mr-2" for="mois">Mois</label>
						<select class="form-control form-control-sm mr-3" required="" id="mois" name="mois">
							<option value="">-Choisir-</option>
							<option value="Ja">Janvier</option>
							<option value="Fe">Fevrier</option>
							<option value="Ma">Mars</option>
							<option value="Av">Avril</option>
							<option value="M">Mai</option>
							<option value="Ju">Juin</option>
							<option value="Jl">Juillet</option>
							<option value="A">Août</option>
							<option value="S">Septembre</option>
							<option value="O">Octobre</option>
							<option value="N">Novembre</option>
							<option value="D">Decembre</option>
						</select>
						<button type="submit" class="btn btn-secondary btn-sm">Rechercher</button>
					</form>
				</div>
				<?php
				if(isset($taxon))
				{
					?>
					<h2 class="h4 mt-3">Liste par observatoire</h2>
					<div class="mb-3">
						<button type="button" id="voir" class="btn btn-sm btn-outline-secondary">Tout afficher</button>
						<button type="button" id="pasvoir" class="btn btn-sm btn-outline-secondary">Tout cacher</button>
					</div>
					<?php
					foreach($observa as $f)
					{
						?>
						<div class="listefamille">
							<div>
								<h3 class="h5">
									<button id="<?php echo $f['observa'];?>" class="btn btn-sm color1_bg idfam" type="button"><span class="fa fa-plus blanc"></span></button>
									<a href="observatoire/index.php?module=decade&amp;action=decade&amp;d=<?php echo $f['observa'];?>&amp;decade=<?php echo $decade;?>"><?php echo $f['nom'];?></a>
								</h3>
							</div>
							<ul id="f<?php echo $f['observa'];?>" class="collapse mb-3">
								<?php
								foreach($taxon as $n)
								{
									if($n['observa'] == $f['observa'])
									{
										?><li><?php echo $n['taxon'];?><?php										
									}
								}
								?>
							</ul>
						</div>
						<?php
					}
				}
				?>
			</div>
		</div>
		<?php
		if(isset($taxon))
		{
			?>
			<div class="col-md-6 col-lg-6">
				<div class="card card-body">
					<h2 class="h4">Liste par nombre d'observation (50 premiers)</h2>
					<table class="table table-sm table-hover">
						<thead>
							<tr><th>Espèce</th><th>Nb obs</th></tr>
						</thead>
						<tbody>
							<?php
							foreach($taxonobs as $n)
							{
								?>
								<tr>
									<td>
										<i class="<?php echo $n['icon'];?>"></i>
										<a href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d=<?php echo $n['observa'];?>&amp;id=<?php echo $n['cdnom'];?>"><i><?php echo $n['nom'];?></i>
										<?php if($n['nomvern'] != '')
										{
											?>- <?php echo $n['nomvern'];?><?php
										}
										?>
										</a>
									</td><td><?php echo $n['nb'];?></td>
								</tr>
								<?php
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
			<?php
		}
		?>
	</div>
</section>
<script>
$('.idfam').click(function() {
	'use strict';
	var sel = $(this).attr('id');
	if ($(this).children().hasClass('fa-plus')) {
		$(this).children().removeClass('fa-plus').addClass('fa-minus');
	} else {
		$(this).children().removeClass('fa-minus').addClass('fa-plus');
	}
	$('#f'+ sel).toggle();
});
$('#voir').click(function(e) {
	'use strict';
	$('.listefamille .collapse').show(); e.preventDefault();
	$('.idfam span').removeClass('fa-plus').addClass('fa-minus');
});
$('#pasvoir').click(function(e) { 
	'use strict';
	$('.listefamille .collapse').hide(); e.preventDefault(); 
	$('.idfam span').removeClass('fa-minus').addClass('fa-plus');
});
$('#rdec').on('submit', function(e) {
	'use strict'; 
	e.preventDefault();
	var jrs = $('#jrs').val(), mois = $('#mois').val();
	document.location.href='index.php?module=decade&action=decade&jrs='+ jrs +'&mois='+ mois;	
});
</script>