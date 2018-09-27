<section class="container-fluid blanc">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3 mb-2">
			<header>	
				<h1 class="h2">Gestion des photos</h1>
			</header>		
			<hr />
			<form class="form-inline">
				<input type="text" placeholder="Rechercher une espèce" class="form-control" id="rtax" size="40">
				<button type="button" class="btn btn-info ml-2" id="aide"><span id="btn-aide-txt">Aide</span></button>
			</form>			
		</div>
	</div>
	<div class="row" id="infoaide">
		<div class="col-md-12 col-lg-12">
			<p>Les miniatures des photos sont classées suivant l'ordre du tableau.</p>
			<ul>
				<li><i class="fa fa-trash curseurlien text-danger"></i> Permets de supprimer une photo.</li>
				<li><i class="fa fa-pencil curseurlien text-warning"></i> Permets de modifier les informations de la photo</li>
				<li><b>Ordre</b> Permets de classer les photos (les 3 premières seront les photos qui apparaitrons sur les fiches dans le bloc déroulant).</li>
			</ul>
		</div>
	</div>
	<div class="row" id="liste">
		<div class="col-md-12 col-lg-12 mt-2">
			<h2 class="h4" id="titresp"></h2>
			<div id="tblliste"></div>
			<div id="afphoto" class="pop"></div>
		</div>
	</div>
	<input type="hidden" id="cdnom" value="<?php echo $cdnom;?>"><input type="hidden" id="ini"><input type="hidden" id="get" value="<?php echo $getcdnom;?>">
	<?php 
	if(isset($_GET['cdnom']))
	{
		?><input type="hidden" id="nom" value="<?php echo $r['nom'].' ('.$r['nomvern'].')';?>"><input type="hidden" id="observa" value="<?php echo $r['observatoire'];?>"><?php
		
	}
	?>
</section>
<div id="dia1" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Modifier les informations</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<form>
							<fieldset>
								<legend class="legendesaisie">Modifier le stade</legend>
								<div class="form-inline">
									<input type="text" class="form-control" id="mstade" disabled>
									<select class="ml-2 form-control" id="cstade"></select>
								</div>
							</fieldset>
							<fieldset class="mt-3">
								<legend class="legendesaisie">Modifier le sexe</legend>
								<div class="form-inline">
									<select class="form-control" id="msexe">
										<option value="">Non définit</option>
										<option value="M">Mâle</option>
										<option value="F">Femelle</option>
										<option value="C">Couple</option>
									</select>
								</div>
							</fieldset>
							<fieldset class="mt-3">
								<legend class="legendesaisie">Modifier l'observatoire</legend>
								<div class="form-inline">
									<input type="text" class="form-control" id="mobserva" disabled>
									<select class="ml-2 form-control" id="cobserva">
									<?php
									foreach($rjson_site['observatoire'] as $n)
									{
										?>
										<option value="<?php echo $n['nomvar'];?>" name="theme"><?php echo $n['nom'];?></option>
										<?php											
									}
									?>
									</select>
								</div>
							</fieldset>
							<fieldset class="mt-3">
								<legend class="legendesaisie">Modifier l'auteur</legend>
								<div class="form-inline ui-front">
									<input type="text" class="form-control" id="auteur">									
								</div>
							</fieldset>
							<input type="hidden" id="idobser"><input type="hidden" id="idphoto">
						</form>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Annuler</button>
				<button type="button" class="btn btn-success" data-dismiss="modal" id="bttdia1">Valider</button>
			</div>
		</div>
	</div>
</div>