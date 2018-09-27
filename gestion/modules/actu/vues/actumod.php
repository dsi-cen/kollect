<section class="container blanc">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<h1 class="text-center">Gestion des actualités</h1>
			<h2 class="text-center">Modifié une actualité</h2>
		</div>
	</header>
	<form id="mod_actu" enctype="multipart/form-data" method="post">
		<div class="row m-t-1">
			<div class="col-md-8 col-lg-8">
				<a class="float-right" href="index.php?module=actu&amp;action=liste">Retour à la liste</a>
				<h3 class="h5">Article <small>(Auteur : <?php echo $auteur['prenom'];?> <?php echo $auteur['nom'];?>)</small></h3>
				<div class="form-group">
					<label for="titre" class="">Titre</label>
					<input type="text" class="form-control" id="titre" name="titre" value="<?php echo $actu['titre'];?>">
				</div>
				<div class="form-group">
					<label class="">Sous-titre</label>
					<textarea class="form-control" id="stitre" name="stitre" rows="1"><?php echo $actu['soustitre'];?></textarea>
				</div>
				<div class="form-group row">
					<label for="lienw" class="col-sm-1 col-form-label">Lien</label>
					<div class="col-sm-11"><input type="text" class="form-control" id="lienw" name="lienw" value="<?php echo $actu['url'];?>"></div>
				</div>
				<div class="form-group row">
					<label for="tag" class="col-sm-1 col-form-label">Tag</label>
					<div class="col-sm-10"><input type="text" class="form-control" id="tag" name="tag" value="<?php echo $actu['tag'];?>"></div>
					<div class="col-sm-1"><p class="form-control-plaintext"><i class="fa fa-plus text-success curseurlien" id="bttplus" title="Ajouter un tag"></i></p></div>
				</div>
				<?php
				if (isset ($discipline))
				{
					?>
					<div class="form-group row">
						<label for="theme" class="col-sm-2 col-form-label">Observatoire</label>
						<div class="col-sm-3">
							<select id="theme" name="theme" class="form-control">
								<option value="NR" >Non renseigné.</option>
								<?php
								foreach ($discipline as $n)
								{
									?><option value="<?php echo $n['var'];?>"><?php echo $n['disc'];?></option><?php
								}
								?>
							</select>
						</div>
					</div>
					<?php
				}
				else
				{
					?>
					<div class="form-group row">
						<label for="theme" name="theme" class="col-sm-2 col-form-label">Observatoire</label>
						<div class="col-sm-2">
							<select id="theme" class="form-control">
								<option value="NR" >Non renseigné.</option>
							</select>
						</div>
					</div>
					<?php
				}
				?>
				<div class="form-group row">
					<div class="col-sm-12"><textarea class="form-control" id="actu" name="actu" rows="3"><?php echo $actu['actu'];?></textarea></div>
				</div>
				<input id="idauteur" name="idauteur" type="hidden" value="<?php echo $actu['idauteur'];?>"/>
				<input id="idm" name="idm" type="hidden" value="<?php echo $_SESSION['idmembre'];?>"/>
				<input name="idactu" type="hidden" value="<?php echo $actu['idactu'];?>"/>
			</div>
			<div class="col-md-4 col-lg-4">
				<h3 class="h5">Photo</h3>
				<?php
				if($actu['nom'] != '')
				{
					?>
					<div id="infophotoor">
						<div class="form-group row">
							<div class="col-sm-12 text-xs-center">
								<span class="imgPreview"><img src="../photo/article/P200/<?php echo $actu['nom'];?>.jpg" alt=""></span><br /><span class="sizeimg"></span>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-12"><input id="auteurph" name="auteurph" type="text" class="form-control" value="<?php echo $actu['auteur'];?>" placeholder="Auteur de la photo"></div>
						</div>
						<div class="form-group row">
							<div class="col-sm-12"><input id="infoph" name="infoph" type="text" class="form-control" value="<?php echo $actu['info'];?>" placeholder="Info photo (100 caractères max.)"></div>
						</div>
						<div class="form-group row">
							<div class="col-sm-12">
								<button type="button" id="BttSph" class="btn btn-warning btn-sm">Supprimer la photo</button>
								<button type="button" id="BttMph" class="btn btn-warning btn-sm">Changer la photo</button>
							</div>						
						</div>
					</div>
					<div id="changeph">
						<div class="form-group row">
							<div class="col-sm-12"><input type="file" name="image" accept="image/jpeg"/></div>
						</div>
					</div>
					<input id="supphoto" name="supphoto" type="hidden" value="photo"/>
					<?php
				}
				else
				{
					?>
					<div class="form-group row">
						<div class="col-sm-12"><input type="file" name="image" accept="image/jpeg"/></div>
					</div>
					<div id="infophoto">
						<div class="form-group row">
							<div class="col-sm-12 text-xs-center"><span class="imgPreview"></span><br /><span class="nomimg"></span> <span class="sizeimg"></span></div>
						</div>				
						<div class="form-group row">
							<div class="col-sm-12"><input id="auteurph" name="auteurph" type="text" class="form-control" placeholder="Auteur de la photo"></div>
						</div>
						<div class="form-group row">
							<div class="col-sm-12"><input id="infoph" name="infoph" type="text" class="form-control" placeholder="Info photo (100 caractères max.)"></div>
						</div>
						<div class="form-group row">
							<div class="col-sm-12"><button type="button" id="BttAphmod" class="btn btn-warning btn-sm">Annuler</button></div>
						</div>
					</div>
					<input id="supphoto" name="supphoto" type="hidden"/>
					<?php
				}
				?>				
				<h3 class="h5">Fichier pdf (ou zip)</h3>
				<?php
				if($actu['nomdoc'] != '')
				{
					?>
					<div id="infopdfor">
						<p class="text-xs-center">
							<i class="fa fa-file-pdf-o fa-2x text-success"></i>
							<?php echo $actu['nomdoc'];?>						
							<button type="button" id="BttSpdf" class="btn btn-warning btn-sm float-xs-left">Supprimer</button>
						</p>					
						<div class="form-group row">
							<div class="col-sm-12"><span class="text-muted">Changer le fichier</span><input type="file" name="pdf" accept=".pdf,.zip"/></div>
						</div>
					</div>
					<input id="suppdf" name="suppdf" type="hidden" value="change"/>
					<?php
				}
				else
				{
					?>
					<div class="form-group row">
						<div class="col-sm-12"><input type="file" name="pdf" accept=".pdf,.zip"/></div>
					</div>
					<div class="form-group row" id="infopdf">
						<div class="col-sm-12"><button type="button" id="BttApdf" class="btn btn-warning btn-sm">Annuler</button></div>
					</div>
					<input id="suppdf" name="suppdf" type="hidden" value="nouveau"/>
					<?php
				}
				?>
				<h3 class="h5">Validation</h3>
				<div class="form-group row">
					<label class="col-sm-5">Visible sur le site</label>
					<div class="col-sm-7">
						<label class="custom-control custom-radio">
							<input type="radio" name="visible" value="1" class="custom-control-input">
							<span class="custom-control-indicator"></span> 
							<span class="custom-control-description">Oui</span>
						</label>
						<label class="custom-control custom-radio">
							<input type="radio" name="visible" value="0" class="custom-control-input">
							<span class="custom-control-indicator"></span> 
							<span class="custom-control-description">Non</span>
						</label>										
					</div>						
				</div>
				<div id="valajax"><progress></progress></div><div id="mes"></div>
				<div class="form-group row">
					<div class="col-sm-12">
						<a class="btn btn-warning" href="index.php?module=actu&amp;action=liste">Annuler</a>
						<button type="submit" class="btn btn-success">Valider</button>
					</div>
				</div>
				<p>Pour le téléchargement, vous pouvez pas téléchargé plus de <?php echo $maxup;?> (limite de votre serveur)</p>
			</div>
		</div>		
		<input id="themeor" type="hidden" value="<?php echo $actu['theme'];?>"/><input id="visibleor" type="hidden" value="<?php echo $actu['visible'];?>"/>
	</form>
</section>
<div class="modal" id="dia3" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Ajouter un tag</h4>
			</div>
			<div class="modal-body">
				<form class="form-inline">
					<div class="form-group">
						<label for="taga">Tag</label>
						<input type="text" class="form-control ml-2" id="taga">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Annuler</button>
				<button type="button" class="btn btn-success" id="bttplusV" data-dismiss="modal">Valider</button>
			</div>
		</div>
	</div>
</div>