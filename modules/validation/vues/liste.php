<section class="container-fluid mb-3">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<header>
					<ol class="breadcrumb float-right">
						<li class="breadcrumb-item"><a href="index.php?module=validation&amp;action=validation">Information</a></li>
						<li class="breadcrumb-item active">Type de validation</li>
					</ol>
					<h1 class="h2">Type de validation des différentes espèces</h1>
				</header>
				<?php
				if(isset($rjson_site['observatoire']))
				{
					?>
					<ul class="list-inline">						
						<?php
						foreach ($menuobservatoire as $n)
						{
							if($n['var'] == $obser)
							{
								?><li id="<?php echo $n['var'];?>" class="list-inline-item idvar color1"><i class="cercleicone <?php echo $n['icon'];?> fa-2x curseurlien" title="<?php echo $n['nom'];?>"></i></li><?php
							}
							else
							{
								?><li id="<?php echo $n['var'];?>" class="list-inline-item idvar"><i class="cercleicone <?php echo $n['icon'];?> fa-2x curseurlien" title="<?php echo $n['nom'];?>"></i></li><?php
							}								
						}
						?>
					</ul>
					<form class="form-inline">
						<label for="choix">filtre par</label>							
						<select id="choix" class="form-control form-control-sm ml-2">
							<option value="NR">Tous</option>
							<option value="0">Espèces non soumises à validation</option>
							<option value="1">Validation par filtre informatique</option>
							<option value="2">Validation manuelle</option>
						</select>
					</form>
					<?php
				}
				?>
			</div>
		</div>		
	</div>
	<div class="row mt-2">
		<div class="col-md-12 col-lg-12">
			<div class="card card-body">
				<div id="liste"></div>				
			</div>
		</div>
	</div>
	<input id="sel" type="hidden" value="<?php echo $obser;?>"/>
</section>