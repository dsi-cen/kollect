<section class="container-fluid blanc">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header>	
				<h1>Import de données 2</h1>
			</header>				
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 col-lg-12">				
			<h2 class="h5">Information</h2>
			<p>
				Il est préférable d'importer vos observation par observatoire<br />
			
				Si vous utilisiez pas le référentiel Taxref, vous pouvez importer un fichier (csv) contenant la liste de vos taxons (<b>nom latin</b>).
				L'application va tenter d'attribué un cdnom à vos taxons et vous proposé un fichier à téléchargé contenant le cdnom, nom, auteur.
				En croisant votre fichier avec celui-ci vous pourrez préparer votre fichier d'import de données.
			</p>
		</div>
	</div>
	<div class="row">
		<div class="col-md-8 col-lg-8 mb-3">		
			<h2 class="h5">1 - Vérification taxref (par observatoire) et configuration des champs</h2>
			<?php
			if ($nbobservatoire == 0)
			{
				?><p class="text-warning">Aucun observatoire pour l'instant sur le site</p><?php
			}
			else
			{
				?>
				<form class="form-inline mt-1">
					<div class="form-group">
						<label for="choix">Vous avez <?php echo ''.$nbobservatoire.' '.$libnbobser.'';?> à gérer. </label>
						<select id="choix" class="form-control ml-2">
							<option value="NR" name="theme">--choisir--</option>
							<?php
							foreach ($menuobservatoire as $n)
							{
								?><option value="<?php echo $n['nomvar'];?>" name="theme"><?php echo $n['nom'];?></option><?php
							}
							?>
						</select>
					</div>
				</form>				
				<?php
			}
			?>			
			<form id="importliste" enctype="multipart/form-data" class="form-inline mt-2">
				<input type="file" name="file" accept=".csv"/>				
				<button type="submit" class="btn btn-success ml-2">Importer votre liste d'espèce</button>
				<input id="sel" name="sel" type="hidden"/>						
			</form>
			<div id="valajax1"><progress></progress></div><div id="mes1"></div>
			<h2 class="h5 mt-2">2 - Importer les données</h2>
			<h3 class="h6">Info sur l'import</h3>
			<form>
				<div class="form-inline">
					<label for="nomimp">Indiquer la provenance des données</label>
					<input type="text" class="form-control ml-2" id="nomimp" size="40">
				</div>
				<input id="idm" type="hidden" value="<?php echo $id;?>"/>							
			</form>
			<p>Si vous importez beaucoup de donnée, vous pouvez le temps de l'importation "mettre la saisie en maintenance", cela est une source d'erreur en moins. N'oubliez pas d'enlever le statut de maintenance à la fin de votre importation :</p>
			<form class="form-inline mb-3">
				<label for="maintenance">Statut de la fiche de saisie</label>
				<select id="maintenance" class="form-control form-control-sm ml-2">
					<option value="n">Normal</option>
					<option value="m">Maintenance</option>
				</select>
			</form>
			<p>Si votre fichier fait moins de <?php echo $maxup;?>, vous pouvez le télécharger autrement déposer le via ftp sur votre serveur dans le répertoire tmp de gestion</p>
			<p><b>Important : </b>Importer au maximum 50.000 lignes. Si votre fichiers en compte plus, "découper" le en plusieurs mais assurez vous que les observations liées à une même fiche soit dans le même fichier</p>
			<h3 class="h6">Par téléchargement</h2>
			<form id="importobs" enctype="multipart/form-data" class="form-inline mt-1">
				<input type="file" name="file" accept=".csv"/>
				<button type="submit" class="btn btn-success ml-2">Importer votre fichier</button>
			</form>
			<h3 class="h6 mt-2">Par ftp</h2>
			<form class="form-inline">
				<input type="text" placeholder="Nom de votre fichier (sans extension)" class="form-control" id="nomftp" size="40">
				<button class="btn btn-success ml-1" type="button" id="bttftp">Valider</button>
			</form>
			<div class="mt-1">
				<div id="valajax2"><progress></progress></div><div id="mes2"></div><div id="mescdref"></div>
				<progress id="BarFiche"></progress><span id="InfoFiche"></span>
			</div>
			<h2 class="h5 mt-3">3 - Importation des fichiers falcutatifs</h2>
			<a href="index.php?module=import&amp;action=import3">Importer les autres fichiers</a>
			<input id="idobsdeb" type="hidden" value="0"/><input id="idobsfin" type="hidden"/>
		</div>
		<div class="col-md-4 col-lg-4">	
			<div id="mconfig">
				<h2 class="h5">Configuration des champs pour cet observatoire</h2>
				<div id="malisteconfig"></div>
			</div>
		</div>
	</div>
</section>