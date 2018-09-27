<section class="container-fluid mb-3">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<div class="d-flex justify-content-start">
					<h1 class="h2">Détermination d'espèce</h1>
					<ol class="breadcrumb ml-auto mb-0">
						<li class="breadcrumb-item active">Détermination</li>
						<li class="breadcrumb-item"><a href="index.php?module=det&amp;action=bilan">Bilan</a></li>
					</ol>
				</div>
			</div>
		</div>		
	</header>
	<div class="row mt-2">
		<div class="col-md-12 col-lg-12">
			<div class="card card-body">					
				<p>
					Vous avez fait une photo ou enregistré un son d'une espèce <b><?php echo $rjson_site['ad2'];?> <?php echo $rjson_site['lieu'];?></b> mais vous ne savez pas l'identifier ou vous avez un doute. Vous pouvez déposer la photo ou un fichier son. Il est possible que parmi les membres du site quelqu'un puisse l'identifier.
				</p>
				<div class="row">
					<div class="col-md-6 col-lg-6">
						<h2 class="h5">Télécharger une photo ou un fichier son</h2>
						<form id="form_redim" action="#" method="post">
							<fieldset>
								<legend class="legendesaisie">Auteur</legend>
								<div class="form-inline">
									<input type="text" class="form-control form-control-sm" id="nom" value="<?php echo $_SESSION['prenom'];?> <?php echo $_SESSION['nom'];?>" disabled>
									<label for="observa" class="ml-3 mr-2">Observatoire</label>
									<select id="observa" name="observa" class="form-control form-control-sm">
										<option value="NR">-- Choisir --</option>
										<option value="NR">Ne sait pas</option>
										<?php
										foreach($rjson_site['observatoire'] as $n)
										{
											?><option value="<?php echo $n['nomvar'];?>"><?php echo $n['nom'];?></option><?php
										}
										?>
									</select>
								</div>
							</fieldset>
							<fieldset class="mt-2">
								<legend class="legendesaisie">Renseignements</legend>
								<div class="form-inline">
									<label for="commune" class="">Commune</label>
									<input type="text" class="ml-2 form-control form-control-sm" id="commune" required="">
									<label for="date" class="ml-3">Date</label>
									<input type="text" max="<?php echo $datej;?>" class="ml-2 form-control form-control-sm" id="date" name="dateph" required="" pattern="\d{1,2}/\d{1,2}/\d{4}">
								</div>
								<div class="form-group row mt-2">
									<div class="col-10"><textarea class="form-control" id="rq" name="rq" rows="2" placeholder="Indiquez ici, tous renseignements pouvant aider à la détermination"></textarea></div>
								</div>
							</fieldset>
							<fieldset>
								<legend class="legendesaisie">Téléchargement photo</legend>
								<p>
									Fichiers autorisés "jpg" - 8 M maximum. <br />
									Paysage - Mettre au minimum des photos de 800 de largeur x 600 de hauteur.<br />
									Portrait - Mettre au minimum des photos de 400 de largeur x 600 de hauteur.
								</p>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" class="custom-control-input" id="paysage" name="orien" value="paysage" checked>
									<label class="custom-control-label" for="paysage">Paysage</label>
								</div>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" class="custom-control-input" id="portrait" name="orien" value="portrait">
									<label class="custom-control-label" for="portrait">Portrait</label>
								</div>												
							</fieldset>
							<div id="crop" class="mt-2">
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
							<div id="tele" class="mt-2"></div>
							<div class="mt-3 mb-2" id="BttV">
								<input type="submit" value="Télécharger la photo" class="btn btn-success"/>
								<button type="button" class="export btn btn-warning" id="BttP">Prévisualisation</button>
							</div>
							<div id="valajax" class="mt-2"><progress></progress></div>							
							<input id="codecom" type="hidden" name="codecom"><input id="idm" type="hidden" name="idm" value="<?php echo $_SESSION['idmembre'];?>"><input id="nomphoto" type="hidden" name="nomphoto">
						</form>
						<form id="ason" enctype="multipart/form-data" method="post">
							<fieldset>
								<legend class="legendesaisie">Téléchargement fichier son</legend>
								<p>
									- Format accepté : mp3<br />
									- Taille maximum du fichier : 2 Mo					
								</p>
								<div class="form-group" id="cachemp">	
									<input type="file" id="mp" name="mp" accept=".mp3"/>						
								</div>
								<div id="ok"></div>
								<div class="form-group" id="voirbt">
									<button type="submit" class="btn btn-success ml-3">Télécharger</button>
								</div>
							</fieldset>
							<input id="dates" type="hidden" name="dates"><input id="codecoms" type="hidden" name="codecoms"><input id="observas" type="hidden" name="observas"><input id="rqs" type="hidden" name="rqs"><input id="idms" type="hidden" name="idms" value="<?php echo $_SESSION['idmembre'];?>">
						</form>
					</div>
					<div class="col-md-6 col-lg-6">
						<h2 class="h5">Les dix dernières demandes</h2>		
						<div id="liste"></div>
						<div id="perso"></div>
					</div>			
				</div>			
			</div>
		</div>
	</div>		
</section>
<div id="dia1" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Prévisualisation de votre photo</h4>
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