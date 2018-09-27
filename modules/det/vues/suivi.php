<section class="container-fluid mb-3">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<div class="d-flex justify-content-start">
					<h1 class="h3">
						<?php echo $titrep;?>
						<span class="ml-2"><?php echo $check;?></span>
					</h1>
					<ol class="breadcrumb ml-auto mb-0">
						<li class="breadcrumb-item active"><a href="index.php?module=det&amp;action=det">Détermination</a></li>
						<li class="breadcrumb-item active">Demande <?php echo $id;?></li>
						<li class="breadcrumb-item"><a href="index.php?module=det&amp;action=bilan">Bilan</a></li>
					</ol>					
				</div>
			</div>
		</div>		
	</header>
	<div class="row mt-2">
		<div class="col-md-12 col-lg-12">
			<div class="card card-body">					
				<h2 class="h4">Information</h2>
				<p>
					<?php echo $libtype;?> le <?php echo $info['dateph'];?> sur la commune de <?php echo $info['commune'];?> par <?php echo $auteur;?>. <?php echo $nomini;?>
				</p>
				<div class="d-flex flex-row">
					<div class="p-2">
						<?php
						if($typef == 'photo')
						{
							?><img src="photo/det/p800/<?php echo $info['nomphoto'];?>.jpg" height="<?php echo $size[1];?>" width="<?php echo $size[0];?>" class="" alt=""><?php
						}
						else
						{
							?><audio controls="controls" preload="none"><source src="son/det/<?php echo $info['nomphoto'];?>.mp3" type="audio/mp3"/>Votre navigateur n\'est pas compatible</audio><?php
						}
						?>
					</div>
					<div class="p-2">
						<?php
						if(isset($tabvali))
						{
							if($typef == 'photo')
							{
								?><p>Photo déterminée : (Si vous avez une autre proposition cliquer sur le <i id="plus" class="curseurlien fa fa-plus text-info"></i>)</p><?php
							}
							else
							{
								?><p>Son déterminé : (Si vous avez une autre proposition cliquer sur le <i id="plus" class="curseurlien fa fa-plus text-info"></i>)</p><?php
							}
							?>
							<ul>
								<?php
								foreach($tabvali as $n)
								{
									?><li><?php echo $n['taxon'];?> - le <?php echo $n['date'];?> par <?php echo $n['obser'];?></li><?php
								}
								?>
							</ul>
							<form id="det2">
								<div class="form-inline">
									<input type="text" class="form-control form-control-sm" id="nomp">
									<button type="button" class="ml-2 btn btn-success btn-sm" id="Bttvali">Valider</button>
								</div>
							</form>
							<?php
							/*if(!empty($info['idobs']))
							{
								?><a href="index.php?module=observation&amp;action=detail&amp;idobs=<?php echo $info['idobs'];?>">Voir l'observation</a><?php	
							}*/							
							if($info['idm'] == $_SESSION['idmembre'] && !isset($ndet))
							{
								?><p>Votre <?php echo $typef;?> est déterminée. Vous pouvez <a href="index.php?module=saisie&amp;action=saisie>">enregistrer la donnée.</a></p><?php
							}
						}
						else
						{
							?>
							<p>
								- Si vous avez identifié l'espèce, indiquez dans le champs ci-dessous le nom de l'espèce. Si l'espèce n'est pas présente dans la liste, indiquez son nom dans un commentaire (sous la photo) et validez.<br />
								- Si la photo n'est pas déterminable cochez la case "Non déterminable" et ajoutez un commentaire précisant les raisons<br />
								- Si l'espèce ne fait pas partie d'un observatoire cochez la case "Hors site".
							</p>
							<form id="det">
								<div class="form-inline">
									<input type="text" class="form-control form-control-sm" id="nomp">
								</div>
								<div class="form-inline mt-2">
									<label class="form-check-label">
										<input class="form-check-input" type="checkbox" id="ndet"> Non déterminable
									</label>
									<label class="form-check-label ml-3">
										<input class="form-check-input" type="checkbox" id="hsite"> Hors site
									</label>
								</div>
								<div class="form-inline mt-2">
									<button type="button" class="btn btn-success" id="Bttvali">Valider</button>
								</div>
							</form>							
							<?php
						}
						?>						
					</div>
				</div>
				<div class="row mt-2">
					<div class="col-md-12 col-lg-12">
						<?php
						if(isset($mediacom))
						{
							?>
							<h3 class="h5 mt-1">Commentaire(s)</h4>
							<?php echo $mediacom;
						}
						?>
						<hr />
						<form>
							<div class="form-group">
								<label for="commentaire" class="control-label">Ajouter un commentaire</label>
								<textarea class="form-control" id="commentaire"></textarea>
							</div>
							<div class="form-group">
								<button type="button" id="BttVcom" class="btn btn-success">Envoyer</button>
							</div>
						</form>	
					</div>					
				</div>
			</div>
		</div>
	</div>		
</section>
<input type="hidden" id="membre" value="<?php echo $membre;?>"><input type="hidden" id="idpdet" value="<?php echo $id;?>"><input type="hidden" id="cdnom"><input type="hidden" id="idmor" value="<?php echo $info['idm'];?>"><input type="hidden" id="observa" value="<?php echo $info['observa'];?>">