<section class="container blanc">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header>	
				<h1>Gestion de la systématique</h1>
			</header>
			<?php
			if ($nbobservatoire == 0)
			{
				?><p class="text-warning">Aucun observatoire pour l'instant sur le site</p><?php
			}
			else
			{
				?><p>Vous avez <?php echo ''.$nbobservatoire.' '.$libnbobser.'';?> à gérer.</p><?php
			}
			?>			
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 col-lg-12">				
			<form class="form">
				<div class="form-group row">
					<label for="theme" class="col-sm-1 col-form-label">Observatoire</label>
					<div class="col-sm-3">
						<select id="choix" class="form-control">
							<option value="NR" name="theme">--choisir--</option>
							<?php
							foreach ($menuobservatoire as $n)
							{
								?>
								<option value="<?php echo $n['nomvar'];?>" name="theme"><?php echo $n['nom'];?></option>
								<?php
							}
							?>
						</select>
					</div>
				</div>
			</form>			
			<div id="valajax"><progress></progress></div>
		</div>
	</div>
	<div class="row">
		<div id="ok" class="col-md-6 col-lg-6">
			<div id="mes"></div>
			<div class="col-md-12 col-lg-12">
				<p>Vous pouvez soit, exporter la liste (modification ou création)</p>
				<button type="button" class="btn btn-success" id="BttV">Exporter (csv)</button>
				<button type="button" class="btn btn-success" id="BttVfr">Exporter (csv) avec nom fr</button>
			</div>
			<div class="col-md-12 col-lg-12">
				<p><br />Soit importer la liste après y avoir apporté vos modifications. Attention le nom du fichier à importer doit-être <b><span class="nimpo"></span></b></p>
				<form id="import" enctype="multipart/form-data" class="form">
					<div class="form-group row">
						<div class="col-sm-10"><input type="file" name="file"/></div>
					</div>
					<p>Si vous avez renseigné la colonne gen1 et gen2 : </p>
					<div class="form-group row">
						<label for="titre" class="col-sm-1 col-form-label">gen1</label>
						<div class="col-sm-8"><input type="text" class="form-control" name="gen1" id="gen1" placeholder="Nom si utilisé" ></div>
					</div>
					<div class="form-group row">
						<label for="titre" class="col-sm-1 col-form-label">gen2</label>
						<div class="col-sm-8"><input type="text" class="form-control" name="gen2" id="gen2" placeholder="Nom si utilisé"></div>
					</div>						
					<div class="form-group row">
						<div class="col-sm-12"><button type="submit" class="btn btn-success">Importer <span class="nimpo"></span></button></div>
					</div>
					<input id="sel" name="sel" type="hidden"/>						
				</form>
			</div>
		</div>
		<div id="expli" class="col-md-6 col-lg-6">
			<p>
				Après avoir sélectionné un observatoire, vous pouvez : <br />
				- Exporter la liste au format csv pour attribué des numéros d'ordre. Il est possible revenir sur votre classification en réexportant le fichier (avec vos précédentes modifications).<br />
				- Importer le fichier sur le serveur.<br /><br />
				<b>Fichier csv</b><br />
				Le fichier "identifiant de votre observatoire.csv" ("identifiant de votre observatoirefr.csv" - si vous avez choisit de mettre les nom vernaculaires), est composé de 6 colonnes (7 avec les nom vernaculaires).<br />
				- cdnom : cdnom de taxref<br />
				- ordre : numéroté la colonne de 1 à ... afin d'établir la classification. <u>Attention</u> ne pas mettre de caractères alphanumériques et de virgules<br />
				- gen1 : permet d'attribué si besoin le taxon à un identifiant de référence<br />
				- gen2 : un deuxième identifiant de référence<br />
				- rang : ES = espèce, SBFM = sous famille, FM = famille. <u>Attention</u> ne modifier pas cette colonne.<br />
				Si vous renseigné gen1 (et gen2), dans les listes d'espèces l'identifiant sera indiqué entre []. Il sera également indiqué sur les fiches espèces<br />
				Ex pour lépidoptères N° Leraut 1997 : 4756 - N° Robineau : 1176.<br />
				Pour cela vous devez indiqué à quoi correspond gen1 et gen2 si vous les utilisé.<br /><br />
				<b>Attention</b><br />
				Ne modifié pas la structure du fichier.csv.<br />
				Lors de l'enregistrement de vos modifications dans votre logiciel, conservé le format csv sinon a partir de votre logiciel (excel, calc, etc..) faite enregistré sous csv.<br />
				Les colonnes dans le fichier csv doivent-être séparées par un <b>;</b>
			</p>
		</div>			
	</div>
</section>