<section class="container blanc mb-3">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header">	
				<h1>Import de données - 1</h1>
			</header>					
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 col-lg-12">	
			<h2 class="h5">Information</h2>
			<p><a href="index.php?module=import&amp;action=histo">Historique des imports.</a></p>
			<p>
				L'importation des données se fait en 3 étapes : les 2 premières concernent l'ensemble du site, la dernière (observations) est spécifique à chaque observatoire :
			</p>
			<ul>
				<li>1 - Importation de la liste des observateurs -> <a href="tmp/modele/modobservateur.xls">Téléchargement du fichier modele (modobservateur)</a></li>
				<li>2 - Importation des fiches (stations, relevés) -> <a href="tmp/modele/modfiche.xls">Téléchargement du fichier modele (modfiche)</a></li>
				<li>3 - Importation des observations -> <a href="tmp/modele/modobs.xls">Téléchargement du fichier modele (modobs)</a></li>
			</ul>
			<p>Si vous avez déjà effectué l'import des observateurs et fiches, passer directement à <a href="index.php?module=import&amp;action=import2">l'importation des données.</a></p>
			<p><b>Important :</b> Vos fichiers .csv d'importation doivent-être en UTF-8 (Avec Notepad++ par exemple : Encodage -> Convertir en UTF-8 (sans BOM)<br />Les colonnes dans les fichiers .csv doivent-être séparées par un point virgule ( ; )</p>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 col-lg-12 m-b-2">				
			<h2 class="h5">1 - Importation des observateurs</h2>
			<p>Pour les observateurs déjà présents dans la base, vous pouvez trouver leurs identifiants ("Id") ici <a href="index.php?module=observateur&amp;action=observateur">Contributeur -> Observateurs</a></p>
			<form id="importobser" enctype="multipart/form-data" class="form">
				<div class="form-group row">
					<div class="col-sm-10"><input type="file" name="file" accept=".csv"/></div>
				</div>
				<div class="form-group row">
					<div class="col-sm-12"><button type="submit" class="btn btn-success">Importer votre fichier</button></div>
				</div>								
			</form>
			<div id="valajax1"><progress></progress></div><div id="mes1"></div>
			<h2 class="h5">2 - Importation des fiches (stations, relevés)</h2>
			<p>Si votre fichier fait moins de <?php echo $maxup;?>, vous pouvez le télécharger ici. Sinon, déposez-le par ftp sur votre serveur dans le répertoire suivant : gestion/tmp.</p>
			<form id="importfiche" enctype="multipart/form-data" class="form">
				<fieldset class="form-group row">
					<legend class="col-form-legend col-sm-3">Sélectionnez votre format de date</legend>
					<div class="col-sm-9">
						<div class="form-check">
							<label class="form-check-label">
								<input class="form-check-input" type="radio" name="cdate" value="fr">
								format de date français (fr) (02/09/2016)
							</label>
						</div>
						<div class="form-check">
							<label class="form-check-label">
								<input class="form-check-input" type="radio" name="cdate" value="us">
								format de date us (2016-09-02)
							</label>
						</div>
					</div>
				</fieldset>	
				<h3 class="h6">Téléchargement (fichier < <?php echo $maxup;?>)</h2>
				<div class="form-group row">
					<div class="col-sm-10"><input type="file" name="file" accept=".csv"/></div>
				</div>
				<div class="form-group row">
					<div class="col-sm-12"><button type="submit" class="btn btn-success">Importation</button></div>
				</div>								
			</form>
			<h3 class="h6">Ftp (fichier > <?php echo $maxup;?>)</h2>
			<form class="form-inline">
				<input type="text" placeholder="Nom de votre fichier (sans extension)" class="form-control" id="nomftpfiche" size="40">
				<button class="btn btn-success" type="button" id="bttftpfiche">Valider</button>
				<input id="biogeo" type="hidden" value="<?php echo $rjson_emprise['biogeo'];?>"/>
			</form>
			<div class="mt-1">
				<div id="valajax2"><progress></progress></div><div id="mes2"></div>
				<progress id="BarFiche"></progress>
			</div>
			<h2 class="h5 mt-1">3 - Importation des observations</h2>
			<a href="index.php?module=import&amp;action=import2">Importer les observations</a>
		</div>		
	</div>
</section>