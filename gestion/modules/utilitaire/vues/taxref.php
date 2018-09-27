<section class="container blanc">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header>	
				<h1>Mise à jour de Taxref</h1>
			</header>					
		</div>
	</div>
	<div class="row mt-2">
		<div class="col-md-12 col-lg-12">
			<p>La version utilisée sur le site actuellement est la <b>version <?php echo $rjson['version'];?></b>.
			<h2 class="h3">Mise à jour</h2>
			<p>
				En cas de sortie d'une nouvelle version de taxref, vous pouvez mettre le site à jour par rapport à cette nouvelle version. Prévoyer un peu de temps<br />
				Il est recommandé de mettre le site en maintenance durant cette opération : <a href="index.php?module=utilitaire&amp;action=utile">Maintenance</a><br />
				Pour faire une mise à jour à la version <?php echo $suiv;?>, téléchargé sur ObsNat les fichiers (si cette version est disponible) :<br/>
				- change<?php echo $suiv;?>.csv<br />
				- taxref<?php echo $suiv;?>.csv<br />
				Ces fichiers sont volumineux, aussi déposer les par ftp dans le répertoire /gestion/taxref. La mise à jour ce fait ensuite en 3 temps :<br />
				- Importation de change<?php echo $suiv;?>.csv dans une table. A la fin de ce traitement, les changements de nom (cdref) sont stockés dans une autre table, ceci premettant par la suite d'afficher une page informant les utilisateurs des taxons ayant changés de nom<br />
				- Remplacement de la table taxref par les données se trouvant dans taxref<?php echo $suiv;?>.csv.<br />
				- Mise à jour des différentes tables du site par rapport aux changements.
			</p>
			<h3 class="h4">1 - Récupération des changements entre les deux versions</h3>
			<button class="btn btn-success" type="button" id="change">Lancer le traitement</button>
			<div id="mes" class="mt-2"></div>
			<progress id="BarFiche"></progress>
			<h3 class="h4 mt-3">2 - Re-construction de la table taxref à partir du csv</h3>
			<button class="btn btn-success" type="button" id="taxref">Lancer le traitement</button>
			<div id="mes1" class="mt-2"></div>
			<progress id="BarFiche1"></progress>
			<h3 class="h4 mt-3">3 - Mise à jour des tables du site</h3>
			<button class="btn btn-success" type="button" id="maj">Lancer le traitement</button>
			<div id="valajax" class="mt-2"></div>
			<div id="verif">
				<h3 class="h4 mt-3">4 - Vérification</h3>
				<p>
					Vérifiez les différent observatoires - (gestion des taxons)<br />
					Mettez la grille de validation à jour <a href="index.php?module=validation&amp;action=ajour">Validation -> grille</a><br />
					Remettre le site en production : <a href="index.php?module=utilitaire&amp;action=utile">Maintenance</a>
				</p>
			</div>
			<input type="hidden" id="version" value="<?php echo $suiv;?>">
		</div>
	</div>	
</section>