<section class="container mb-3">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<header>
					<h1 class="h2">Liste des demandes de détermination</h1>
				</header>
				<form class="form-inline">				
					<label class="custom-control custom-checkbox ml-2">
						<?php
						if(isset($_GET['perso']) && $_GET['perso'] == 'oui')
						{
							?><input id="perso" type="checkbox" class="custom-control-input" checked><?php
						}
						else
						{
							?><input id="perso" type="checkbox" class="custom-control-input"><?php
						}
						?>
						<span class="custom-control-indicator"></span>
						<span class="custom-control-description">Uniquement vos demandes</span>
					</label>
					<label for="choix">Filtre par</label>							
					<select id="choix" class="form-control form-control-sm ml-2">
						<option value="0">Tous</option>
						<option value="1">Déterminé</option>
						<option value="2" <?php if(isset($f) && $f == 2) { echo 'selected'; } ?>>Non déterminé</option>
						<option value="3">Non déterminable</option>
					</select>
				</form>
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
</section>
<input type="hidden" value="<?php echo $observa;?>" id="observa">