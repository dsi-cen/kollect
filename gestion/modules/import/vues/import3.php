<section class="container-fluid blanc">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header>	
				<h1 class="h2">Import des compléments</h1>
				<p>Effectué les imports que vous avez besoin. Fichiers csv UTF8. Si les identifiants (idobs) de vos fichiers d'imports sont tous différents, vous pouvez faire les imports directement. Autrement indiquer sur quel imports vous voulez relier ces nouveaux imports </p>
			</header>				
		</div>
	</div>
	<div class="row">
		<div class="col-md-5 col-lg-5">
			<table class="table table-sm table-hover">
				<thead>
					<tr>
						<th></th><th>Date</th><th>Description</th><th>Idobs mini</th><th>idobs max</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach($liste as $n)
				{
					?>
					<tr id="<?php echo $n['id'];?>">
						<td><input type="checkbox" value="oui" class="sel curseurlien"></td><td><?php echo $n['datefr'];?></td><td class="descri"><?php echo $n['descri'];?></td><td class="mini"><?php echo $n['idobsdeb'];?></td><td class="max"><?php echo $n['idobsfin'];?></td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>
			<textarea rows="2" class="form-control" id="labcondi"></textarea>
			<input id="condi" type="hidden">
		</div>
		<div class="col-md-4 col-lg-4">			
			<h2 class="h5">1 - Import des plantes hôtes et/ou consommées</h2>
			<p class="mb-1">idobs origine/nombre/cdnom plante/idstade</p>
			<form id="importplte" enctype="multipart/form-data" class="form-inline mt-2">
				<input type="file" name="file" accept=".csv"/>				
				<button type="submit" class="btn btn-success ml-2">Importer votre fichier</button>
				<input name="choix" type="hidden" value="plte"/>
			</form>			
			<h2 class="h5 mt-3">2 - Import des collections, genitalia, edeage, etc...</h2>
			<p class="mb-1">idobs origine/iddetcol/iddtegen/code/sexe/idprep/typedet/idstade</p>
			<form id="importcoll" enctype="multipart/form-data" class="form-inline mt-1">
				<input type="file" name="file" accept=".csv"/>
				<button type="submit" class="btn btn-success ml-2">Importer votre fichier</button>
				<input name="choix" type="hidden" value="coll"/>
			</form>
			<h2 class="h5 mt-3">3 - Import des habitats</h2>
			<p class="mb-1">idobs origine/cdhabitat/cdref(cdnom)</p>
			<form id="importhab" enctype="multipart/form-data" class="form-inline mt-1">
				<input type="file" name="file" accept=".csv"/>
				<button type="submit" class="btn btn-success ml-2">Importer votre fichier</button>
				<input name="choix" type="hidden" value="hab"/>
			</form>
			<h2 class="h5 mt-3">4 - Import des mortalités</h2>
			<p class="mb-1">idobs origine/idmort/idstade</p>
			<form id="importmort" enctype="multipart/form-data" class="form-inline mt-1">
				<input type="file" name="file" accept=".csv"/>
				<button type="submit" class="btn btn-success ml-2">Importer votre fichier</button>
				<input name="choix" type="hidden" value="mort"/>
			</form>
			<h2 class="h5 mt-3">5 - Import des code nicheur (oiseaux)</h2>
			<p class="mb-1">idobs origine/code/idstade</p>
			<form id="importpiaf" enctype="multipart/form-data" class="form-inline mt-1">
				<input type="file" name="file" accept=".csv"/>
				<button type="submit" class="btn btn-success ml-2">Importer votre fichier</button>
				<input name="choix" type="hidden" value="piaf"/>
			</form>
		</div>
		<div class="col-md-3 col-lg-3">
			<div id="valajax1"><progress></progress></div><div id="mes1"></div><div id="meser"></div>
			<progress id="BarFiche"></progress><span id="InfoFiche"></span>
		</div>		
	</div>
</section>