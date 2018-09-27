<section class="container-fluid blanc">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header>	
				<h1>Gestion de la bibliographie</h1>
			</header>			
		</div>
	</div>
	<div class="row" id="infoaide">
		<div class="col-md-12 col-lg-12">
			<p>
				Les champs "chercher une référence par auteur", "par id", "auteurs", "mots clés" et "taxon" sont autocomplétants.<br />
				Dans les champs "Titre" et "Résumé" vous pouvez sélectionner du texte pour le mettre en <b>gras</b> et/ou <i>italique</i>.<br />
				La liste des mots clés est automatiquement construite suivant les nouveau mots clés saisie.<br />
				Vous pouvez ajouter un auteur si il n'est pas présent en cliquant sur la <i class="fa fa-plus text-success"></i>
			</p>
		</div>
	</div>
	<div class="row">
		<div class="col-md-8 col-lg-8 mt-2">
			<div class="row">
				<div class="col-sm-1">
					<button type="button" class="btn btn-info" id="aide"><span id="btn-aide-txt">Aide</span></button>
				</div>
				<div class="col-sm-2">
					<button type="button" class="btn btn-success" id="BttN"><b>Ajouter une référence</b></button>
				</div>				
				<div class="col-sm-6">
					<input type="text" id="rauteur" class="form-control" placeholder="Ou chercher une référence (par auteur)">				
				</div>
				<div class="col-sm-2">
					<input type="text" id="ridbiblio" class="form-control" placeholder="par id">
				</div>
			</div>
			<hr />
			<form id="biblio">
				<fieldset id="gestionb">
					<div class="form-group row">
						<label for="auteur" class="col-sm-1 col-form-label">Auteur(s)</label>
						<div class="col-sm-10"><input type="text" class="form-control" id="auteur"></div>
						<div class="col-sm-1"><i class="fa fa-plus text-success curseurlien" id="bttplus" title="Ajouter un auteur"></i></div>
					</div>
					<div class="form-group">
						<label for="titre" class="col-form-label">Titre</label>
						<textarea class="form-control" id="titre" name="titre" rows="2" contenteditable="true"></textarea>						
					</div>				
					<div class="form-group row">
						<label for="publication" class="col-sm-1 col-form-label">Publication</label>
						<div class="col-sm-11"><input type="text" class="form-control" id="publication" name="publi"></div>
					</div>
					<div class="form-group row">
						<label for="annee" class="col-sm-1 col-form-label">Année</label>
						<div class="col-sm-1"><input type="text" class="form-control input-sm" id="annee" name="annee"></div>
						<label for="tome" class="col-sm-1 col-form-label text-right">Tome</label>
						<div class="col-sm-1"><input type="text" class="form-control input-sm" id="tome" name="tome"></div>	
						<label for="fas" class="col-sm-1 col-form-label text-right">Fascicule</label>
						<div class="col-sm-1"><input type="text" class="form-control input-sm" id="fas" name="fas"></div>
						<label for="page" class="col-sm-1 col-form-label text-right">Page(s)</label>
						<div class="col-sm-2"><input type="text" class="form-control input-sm" id="page" name="page"></div>
						<label for="type" class="col-sm-1 col-form-label">Type publi</label>
						<div class="col-sm-2">
							<select id="type" class="form-control" name="type">
								<option value="NR" name="type">Non rens.</option>
								<option value="Revue" name="type">Revue</option>
								<option value="Rapport" name="type">Rapport</option>
								<option value="Livre" name="type">Livre</option>
								<option value="Internet" name="type">Internet</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="isbn" class="col-sm-1 col-form-label">N° ISBN</label>
						<div class="col-sm-4"><input type="text" class="form-control" id="isbn" name="isbn"></div>
					</div>
					<div class="form-group">
						<label for="resum" class="col-form-label">Résumé</label>
						<textarea class="form-control" id="resum" name="resum" rows="2" contenteditable="true"></textarea>
					</div>
					<?php
					if(isset($discipline))
					{
						?>
						<div class="form-group row">
							<label for="observa" class="col-sm-2 col-form-label">Observatoire</label>
							<div class="col-sm-4">
								<select id="observa" class="form-control" name="observa">
									<option value="NR">Non renseigné.</option>
									<?php
									foreach($discipline as $n)
									{
										?>
										<option value="<?php echo $n['var'];?>"><?php echo $n['disc'];?></option>
										<?php
									}
									?>
								</select>
							</div>
						</div>					
						<?php
					}
					?>				
					<div class="form-group row">
						<label for="lienw" class="col-sm-2 col-form-label">Lien sur internet</label>
						<div class="col-sm-9"><input type="text" class="form-control" id="lienw" name="lienw"></div>
					</div>
					<div class="form-group row">
						<label for="taxon" class="col-sm-2 col-form-label">Ajout de taxon</label>
						<div class="col-sm-4"><input type="text" class="form-control" id="taxon"></div>
						<label for="motcle" class="col-sm-2 col-form-label text-right">Ajout Mots clés</label>
						<div class="col-sm-3"><input type="text" class="form-control" id="motcle"></div>
						<div class="col-sm-1"><i class="fa fa-plus text-success curseurlien" id="bttplusMc" title="Ajouter un mot clé"></i></div>
					</div>
					<div class="form-group row">
						<label for="com" class="col-sm-2 col-form-label">Commune</label>
						<div class="col-sm-4"><input type="text" class="form-control" id="com"></div>
						<?php 
						if($dep == 'oui')
						{
							?>
							<label for="dep" class="col-sm-2 col-form-label">Département</label>
							<div class="col-sm-9"><input type="text" class="form-control" id="dep"></div>
							<?php			
						}
						?>
					</div>
				</fieldset>
				<div class="form-group row">
					<div class="col-sm-10">
						<button type="submit" class="btn btn-success" id="BttV">Valider</button>
						<button type="submit" class="btn btn-success" id="BttM">Valider les modifications</button>
						<button type="button" class="btn btn-info" id="BttP">Ajouter Ref même publication</button>
						<button type="button" class="btn btn-info" id="BttA">Ajouter Ref même auteur</button>
						<div id="valajax"><progress></progress></div><div id="mes"></div>
					</div>
				</div>
				<input id="idbiblio" name="idbiblio" type="hidden" value="<?php echo $idbiblio;?>"><input id="idauteur" name="idauteur" type="hidden"><input id="cdnom" name="cdnom" type="hidden"><input id="mc" name="mc" type="hidden"><input id="codecom" name="codecom" type="hidden">
			</form>
		</div>
		<div class="col-md-4 col-lg-4">
			<p class="mb-0"><b>Taxons</b></p>
			<ul id="ltaxon" class="list-unstyled"></ul>
			<p class="mb-0"><b>Mots clés</b></p>
			<ul id="lmotcle" class="list-unstyled"></ul>
			<p class="mb-0"><b>Communes</b></p>
			<ul id="lcommune" class="list-unstyled"></ul>
		</div>
	</div>	
</section>
<div id="dia1" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Ajouter un auteur</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<form class="form">
							<div class="form-group row">
								<label for="nom" class="col-sm-2 col-form-label">Nom</label>
								<div class="col-sm-8"><input type="text" class="form-control" id="nom"></div>
							</div>
							<div class="form-group row">
								<label for="prenom" class="col-sm-2 col-form-label">Prénom</label>
								<div class="col-sm-8"><input type="text" class="form-control" id="prenom"></div>
							</div>
							<div class="form-group row">
								<label for="ab" class="col-sm-3 col-form-label">Prénom ab.</label>
								<div class="col-sm-5"><input type="text" class="form-control" id="ab"></div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Annuler</button>
				<button type="button" class="btn btn-success" id="bttdia1">Valider</button>
			</div>
		</div>
	</div>
</div>
<div id="dia2" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Information doublon</h4>
			</div>
			<div class="modal-body">
				<p>Attention ! Il existe déjà un auteur <b><output id="doublon"></output></b><br />Cliqué sur "insérer" si il s'agit pas d'un doublon</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Annuler</button>
				<button type="button" class="btn btn-success" data-dismiss="modal" id="bttdia2">Insérer</button>
			</div>
		</div>
	</div>
</div>
<div id="dia3" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<p>Le nom doit-être présent dans la liste proposée.<br />Vous pouvez créer un auteur en cliquant sur le <i class="fa fa-plus text-success"></i> à droite du champ.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Fermer</button>				
			</div>
		</div>
	</div>
</div>
<div id="dia4" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Ajouter un mot clé</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<form class="form">
							<div class="form-group row">
								<label for="mca" class="col-sm-2 col-form-label">Mot</label>
								<div class="col-sm-8"><input type="text" class="form-control" id="mca"></div>
							</div>							
						</form>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Annuler</button>
				<button type="button" class="btn btn-success" id="bttdia4">Valider</button>
			</div>
		</div>
	</div>
</div>
