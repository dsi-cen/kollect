<section class="container blanc">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header>	
				<h1>Choix de la liste des taxons</h1>
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
			<button type="button" class="btn btn-info" id="aide"><span id="btn-aide-txt">Aide</span></button>			
		</div>
	</div>
	<div class="row" id="infoaide">
		<div class="col-md-12 col-lg-12">
			<p>
				Vous pouvez choisir la liste des espèces de votre observatoire suivant plusieurs niveaux.<br />
				Ex : Pour un observatoire sur tous les reptiles, il suffit de sélectionner Reptiles dans Groupe puis de valider.<br />
				Ex : Pour un observatoire sur toutes les Zygènes, il faut sélectionner Insectes dans Groupe, Lepidoptera dans Ordre, Zygaenidae dans Famille.<br />
				A la validation les diiférentes tables correspondant à votre sélection seront crées dans le shema de votre observatoire.<br />
				La table liste contiendra l'ensemble des cdnom correspondant à votre sélection.<br />
				Les chiffres indiqués dans les sélections correspondent aux nombre de taxons ou : cdref = cdnom.<br />
				Il est possible de faire un observatoire incluant des groupes/ordres/familles différents<br />
				Ex : Pour un observatoire herpeto (au sens large), selectionner Reptiles dans groupe, valider, puis re-selectionner Amphibiens, valider.<br /> 
				<b>Le choix des espèces pour un observatoire ce fait qu'une fois - le traitement peut-être assez long</b>
			</p>
		</div>
	</div>
	<br />
	<div class="row">
		<div class="col-md-6 col-lg-6">				
			<form class="form">
				<div class="form-group row">
					<label for="theme" class="col-sm-2 col-form-label">Observatoire</label>
					<div class="col-sm-6">
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
				<hr />
				<p><b>Choix des espèces</b></p>
				<div class="form-group row">
					<label for="groupe" class="col-sm-2 col-form-label">Groupe</label>
					<div class="col-sm-7">
						<select id="groupe" class="form-control"></select>
					</div>
				</div>
				<div class="form-group row">
					<label for="ordre" class="col-sm-2 col-form-label">Ordre</label>
					<div class="col-sm-7">
						<select id="ordre" class="form-control"></select>
					</div>
				</div>
				<div class="form-group row">
					<label for="famille" class="col-sm-2 col-form-label">Famille</label>
					<div class="col-sm-7">
						<select id="famille" class="form-control"></select>
					</div>
				</div>
			</form>
			<input id="disc" type="hidden"/><input id="sup" type="hidden" value="non"/>			
		</div>
		<div class="col-md-6 col-lg-6">
			<div id="valajax"><progress></progress></div>
			<form class="form" id="choixespece">
				<p><b>Votre choix</b></p>
				<div class="form-group row">
					<label for="groupec" class="col-sm-2 col-form-label">Groupe</label>
					<div class="col-sm-6"><input type="text" class="form-control" id="groupec"></div>
				</div>
				<div class="form-group row">
					<label for="ordrec" class="col-sm-2 col-form-label">Ordre</label>
					<div class="col-sm-6"><input type="text" class="form-control" id="ordrec"></div>
				</div>
				<div class="form-group row">
					<label for="famillec" class="col-sm-2 col-form-label">Famille</label>
					<div class="col-sm-6"><input type="text" class="form-control" id="famillec"></div>
				</div>
				<div class="form-group row">
					<div class="col-sm-offset-2 col-sm-8">
						<button type="button" class="btn btn-success" id="BttV">Valider</button>
					</div>							
				</div>			
			</form>
			<div id="suite" class="alert alert-success">
				<p>
					L'installation des tables de votre observatoire a été réalisé.<br />
					Vous pouvez rajouter des taxons à cet observatoire en re-selectionnant des espèces ou 
					Poursuivre <a href="index.php?module=observatoire&amp;action=liste">l'installation</a>.
				</p>
			</div>
			<div id="suite1" class="alert alert-warning">
				<p>
					L'installation des tables de votre observatoire a été réalisé.<br />
					Vous pouvez rajouter des taxons à cet observatoire en re-selectionnant des espèces ou
					Poursuivre <a href="index.php?module=observatoire&amp;action=liste">l'installation</a>.<br />
					Attention votre observatoire comprends des taxons se trouvant déjà dans un observatoire. Vous devrez supprimer ceux-ci en					
					Poursuivant <a href="index.php?module=observatoire&amp;action=liste">l'installation</a>
				</p>
			</div>
			<div id="mes"></div>
		</div>
	</div>
</section>