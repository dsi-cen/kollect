<section class="container blanc">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header>	
				<h1>Gestion des articles</h1>
			</header>
		</div>
	</div>	
	<div class="row">
		<div class="col-md-12 col-lg-12">
			<p>
				<b>- Aller à la ligne :</b> Maj+entrer <br />				
			</p>
			<form>
				<div class="form-group row">					
					<label for="choix" class="col-sm-1 col-form-label">Article</label>
					<div class="col-sm-3">
						<select id="choix" class="form-control">
							<option value="NR" name="type">--Choisir--</option>
							<option value="acsite" name="type">Accueil - Site</option>
							<?php
							foreach ($choix as $n)
							{
								?><option value="<?php echo $n['val'];?>"><?php echo $n['nom'];?></option><?php
							}
							?>
						</select>
					</div>
					<div class="col-sm-3">
						Ou <button type="button" class="btn btn-success ml-2">Nouvel article</button>
					</div>
				</div>
				<div class="form-group row">
					<label for="titre" class="col-sm-1 col-form-label">Titre</label>
					<div class="col-sm-10"><input type="text" class="form-control input-sm" id="titre"></div>
				</div>
				<div class="form-group row">
					<label for="stitre" class="col-sm-1 col-form-label">Sous-titre</label>
					<div class="col-sm-10"><input type="text" class="form-control input-sm" id="stitre"></div>
				</div>
				<div class="form-group">
					<label for="article" class="">Article</label>
					<textarea class="form-control" id="article" rows="5"></textarea>					
				</div>
				<div id="mes"></div>
				<div class="form-group">
					<div class="col-sm-5">
						<button type="button" class="btn btn-success" id="BttV">Valider</button>						
					</div>
				</div>
				<div id="valajax"></div><div id="mes"></div>				
				<input id="idarticle" type="hidden">
			</form>
		</div>
	</div>
</section>