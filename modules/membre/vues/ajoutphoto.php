<section class="container-fluid">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<h1 class="h2">
					Ajouter des photos
					<small class="text-muted"> (La donnée correspondante à la photo doit être présente dans la base)</small>
				</h1>
			</div>
		</div>		
	</header>
	<form id="form_redim" action="#" method="post">
		<div class="row mt-2">		
			<div class="col-md-6 col-lg-6">
				<div class="card card-body">
					<fieldset>
						<legend class="legendesaisie">Auteur de la photo </legend>
						<div class="form-group row">
							<label for="nom" class="col-sm-1 col-form-label">Nom</label>
							<div class="col-sm-4"><input type="text" class="form-control" id="nom" value="<?php echo $_SESSION['nom'];?>"></div>
							<label for="prenom" class="col-sm-2 col-form-label">Prénom</label>
							<div class="col-sm-4"><input type="text" class="form-control" id="prenom" value="<?php echo $_SESSION['prenom'];?>"></div>							
						</div>							
					</fieldset>
					<fieldset>
						<legend class="legendesaisie">Renseignements sur la photo </legend>							
						<div class="form-group row">
							<label for="idobs" class="col-sm-3 col-form-label">Idobs (si connu)</label>
							<div class="col-sm-4"><input type="text" class="form-control" id="idobs" name="idobsph" value="<?php echo $getidobs;?>" required=""></div>
						</div>
						<div class="form-group row">
							<label for="commune" class="col-sm-3 col-form-label">Commune</label>
							<div class="col-sm-9"><input type="text" class="form-control" id="commune" required=""></div>
						</div>
						<div class="form-group row">
							<label for="espece" class="col-sm-3 col-form-label">Espèce</label>
							<div class="col-sm-9"><input type="text" class="form-control" id="espece" required=""></div>
						</div>
						<div class="form-group row">
							<label for="date" class="col-sm-3 col-form-label">Date de photo</label>
							<div class="col-sm-5"><input type="text" max="<?php echo $datej;?>" class="form-control" id="date" name="dateph" required="" pattern="\d{1,2}/\d{1,2}/\d{4}"></div>
						</div>
						<div class="form-group row">
							<label for="stade" class="col-sm-3 col-form-label">Stade</label>
							<div class="col-sm-4">
								<select id="stade" required="" name="stadeph" class="form-control"></select>
							</div>
							<label for="sexe" class="col-sm-2 col-form-label">Sexe</label>
							<div class="col-sm-3">
								<select id="sexe" name="sexe" class="form-control">
									<option value="">Indéterminé</option>
									<option value="M">Mâle</option>
									<option value="F">Femelle</option>
									<option value="C">Couple</option>
								</select>
							</div>
						</div>							
					</fieldset>
					<div id="nbphoto"></div><div id="messtade"></div><div id="mesverif"></div>
					<select id="site" class="mb-1"></select>
					<fieldset>
						<legend class="legendesaisie">Téléchargement </legend>
						<p>
							Fichiers autorisés "jpg" - 8 M maximum. <br />
							Paysage - Mettre au minimum des photos de 800 de largeur x 600 de hauteur.<br />
							Portrait - Mettre au minimum des photos de 400 de largeur x 600 de hauteur.
						</p>
						<label class="custom-control custom-radio">
							<input type="radio" class="custom-control-input" name="orien" value="paysage" checked>
							<span class="custom-control-indicator"></span>
							<span class="custom-control-description">Paysage</span>
						</label>
						<label class="custom-control custom-radio">
							<input type="radio" class="custom-control-input" name="orien" value="portrait">
							<span class="custom-control-indicator"></span>
							<span class="custom-control-description">Portrait</span>
						</label>												
					</fieldset>
					<div id="prev"></div>
				</div>
			</div>
			<div class="col-md-6 col-lg-6">
				<div class="card card-body">					
					<div id="crop">
						<div class="error-msg"></div>
						<input type="file" class="cropit-image-input mb-3" id="file">
						<div class="cropit-preview ml-3"></div>					
						<div class="ml-3 mt-3">							
							<i class="fa fa-picture-o fa-lg"></i>
							<input type="range" class="cropit-image-zoom-input">
							<i class="fa fa-picture-o fa-2x"></i>
						</div>
						<input type="hidden" name="image-data" class="hidden-image-data" />						
					</div>
					<p class="ml-3 mt-3">
						<span class="rotate-ccw curseurlien" title="rotation gauche"><i class="fa fa-undo fa-lg"></i></span>
						<span class="rotate-cw curseurlien ml-3" title="rotation droite"><i class="fa fa-repeat fa-lg"></i></span>
					</p>
					<div class="mt-3 mb-2" id="BttV">
						<input type="submit" value="Télécharger la photo" class="btn btn-success"/>
						<button type="button" class="export btn btn-warning" id="BttP">Prévisualisation</button>
					</div>
					<div id="valajax" class="mt-2"><progress></progress></div>
					<div id="tele" class="mt-2"></div>
				</div>			
			</div>		
		</div>
		<input id="nouv" type="hidden"><input id="cdnom" type="hidden" name="cdnomph"><input id="codecom" type="hidden" name="codecomph"><input id="obser" type="hidden" name="obserph"><input id="idobser" type="hidden" name="idobserph"><input id="ordreph" name="ordreph" type="hidden"><input id="nomphoto" type="hidden" name="nomphoto"><input id="idm" type="hidden" value="<?php echo $_SESSION['idmembre'];?>">
	</form>
</section>
<div id="dia1" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="prevdia1"></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div id="imgdia1"></div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
			</div>
		</div>
	</div>
</div>