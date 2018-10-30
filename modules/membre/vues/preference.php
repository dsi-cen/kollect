<section class="container-fluid mb-3">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<h1 class="h2">Vos préférences</h1>
			</div>
		</div>
	</header>
	<div class="row mt-2">
		<div class="col-md-5 col-lg-5">
			<article class="card card-body">				
				<h2 class="">Votre compte</h2>
                <div class="alert alert-primary mt-2 mb-2">
				<h3 class="h4">Votre avatar</h3>
				<p>Votre avatar doit faire au minimum 36 x 36, 500 ko max et être au format jpg.</p>
				<form class="row" id="favatar" enctype="multipart/form-data" method="post">
					<div class="col-md-8 col-lg-8"><input type="file" name="file" required="" accept="image/jpeg"/></div>
					<div class="col-md-4 col-lg-4">
						<div id="avatar">
							<?php
							if(file_exists($favatar))
							{
								?>
								<img src="<?php echo $favatar;?>" width=36 height=36 alt=""/>
								<i class="text-danger fa fa-trash fa-lg curseurlien supavatar" title="supprimer votre avatar"></i>
								<?php
							}
							else
							{
								?><img src="photo/avatar/usera.jpg" width=36 height=36 alt=""/><?php
							}
							?>
						</div>
					</div>
					<div class="col-md-4 col-lg-4"><button type="submit" class="btn btn-success" id="BttA">Valider</button></div>
					<input id="idm" name="idm" type="hidden" value="<?php echo $idm;?>"/><input id="prenom" name="prenom" type="hidden" value="<?php echo $_SESSION['prenom'];?>"/>		
				</form>
                </div>
				<div id="mesa" class="mt-1"></div>
                <div class="alert alert-primary mt-2 mb-2">
				<h3 class="h4 mt-2">Votre mail</h3>
				<p>Vous pouvez changer votre adresse de messagerie</p>
				<form class="form-inline">
					<input type="email" class="form-control form-control-sm mr-1" id="mail" value="<?php echo $mail;?>">
					<button type="button" id="BttMail" class="btn btn-success btn-sm">Valider si modification</button>
				</form>
                </div>
                <div class="alert alert-primary mt-2 mb-2">
				<h3 class="h4 mt-3">Votre mot de passe</h3>
				<p>Vous pouvez changer votre mot de passe</p>
				<form>
					<div class="form-group row">
						<label for="mdp" class="col-sm-4 col-form-label">Mot de passe actuel</label>
						<div class="col-sm-8"><input type="password" class="form-control" id="mdp"></div>
					</div>
					<div class="form-group row">
						<label for="mdp1" class="col-sm-4 col-form-label">Nouveau mot de passe</label>
						<div class="col-sm-8"><input type="password" class="form-control" id="mdp1"></div>
					</div>
					<div class="form-group row">
						<div class="col-sm-8"><button type="button" class="btn btn-success" id="Bttmdp">Valider</button></div>
					</div>
				</form>
                </div>
				<div id="mes1"></div>
			</article>
		</div>
		<div class="col-md-7 col-lg-7">
			<article class="card card-body">
				<h2 class="">Vos préférences</h2>
                <div class="alert alert-primary mt-2 mb-2">
				<p>Il vous est possible de choisir un observatoire. Sur la fiche de saisie et la page observation cet observatoire vous sera proposé par défaut.</p>
				<?php
				if (isset ($rjson_site['observatoire']))
				{
					?>
					<ul class="list-inline">
						<?php
						foreach ($menuobservatoire as $n)
						{
							if($n['var'] == $obser)
							{
								?><li class="list-inline-item idvar color1 curseurlien" id="<?php echo $n['var'];?>"><i class="cercleicone <?php echo $n['icon'];?> fa-2x" title="<?php echo $n['nom'];?>"></i></li><?php
							}
							else
							{
								?><li class="list-inline-item idvar curseurlien" id="<?php echo $n['var'];?>"><i class="cercleicone <?php echo $n['icon'];?> fa-2x" title="<?php echo $n['nom'];?>"></i></li><?php
							}								
						}
						?>
						<li class="list-inline-item idvar curseurlien" id="aucun"><i class="cercleicone fe-webobs3 fa-2x" title="Aucun"></i></li>
					</ul>							
					<?php
				}
				?>
                </div>
                <div class="alert alert-primary mt-2 mb-2">
				<p>
					Vous pouvez choisir par défaut l'organisme auquel vous êtes rattaché (il est toujours possible de le modifier lors de la saisie)
				</p>
				<form class="form-inline">
					<select id="org" name="org" class="form-control form-control-sm">														
						<?php
						foreach($orga as $n)
						{
							if($n['idorg'] == $idorg)
							{
								?><option value="<?php echo $n['idorg'];?>" selected><?php echo $n['organisme'];?></option><?php
							}
							elseif($n['idorg'] != 1)
							{
								?><option value="<?php echo $n['idorg'];?>"><?php echo $n['organisme'];?></option><?php
							}										
						}
						?>								
					</select>
				</form>
                </div>
<!--				<div class="alert alert-primary mt-2 mb-2">
                    <p>
					Pour vos données d'origine privées (réalisées en temps qu'indépendant), vous pouvez choisir la façon dont elles apparaitront sur le site (il est toujours possible de le modifier lors de la saisie)
                    </p>
				<form class="form-inline">
					<select id="floutage" class="form-control form-control-sm">
						<option value="0">Tel que (x/y)</option>
						<option value="1">Commune/maille 10x10</option>
						<option value="2">Maille 10x10</option>
						<option value="3">Département</option>
					</select>
				</form>
                </div>       -->
                <div class="alert alert-primary mt-2 mb-2">
				<p>
					En fonction des paramétrages des observatoires par les administrateurs du site, l'affichage pour les espèces peut changer (nom latin / nom vernaculaire). Vous pouvez si vous le souhaitez choisir votre préférence :<br />
				</p>
				<form class="form-inline">
					<div class="custom-control custom-radio">
						<input type="radio" class="custom-control-input" id="def" name="radionom" value="defaut" <?php if($latin == '' || $latin == 'defaut') {echo 'checked';}?>>
						<label class="custom-control-label" for="def">Défaut</label>						
					</div>
					<div class="custom-control custom-radio ml-2">
						<input type="radio" class="custom-control-input" id="latin" name="radionom" value="oui" <?php if($latin == 'oui') {echo 'checked';}?>>
						<label class="custom-control-label" for="latin">Nom latin</label>
					</div>
					<div class="custom-control custom-radio ml-2">
						<input type="radio" class="custom-control-input" id="vern" name="radionom" value="non" <?php if($latin == 'non') {echo 'checked';}?>>
						<label class="custom-control-label" for="vern">Nom vernaculaire</label>
					</div>
				</form>
                </div>
                <div class="alert alert-primary mt-2 mb-2">
                    <p>Vous pouvez choisir le fond de carte que vous souhaitez par défaut (fiche de saisie et cartographie)</p>
				<form class="form-inline">
					<select id="choixcouche" class="form-control form-control-sm">
						<option value="osm" name="type">Carte Open Street (OSM)</option>
						<option value="osmfr" name="type">Carte Open Street FR (OSMfr)</option>
						<option value="topo" name="type">Carte Open Topo (OSM Topo)</option>
						<option value="ign" name="type">Carte IGN</option>
						<option value="photo " name="type">Photo aériennes IGN</option>	
					</select>
				</form>
                </div>
				<button type="button" class="btn btn-success mt-3 col-3" id="BttV">Valider</button>
				<div id="valajax"><progress></progress></div><div id="mes"></div>				
			</article>
		</div>
	</div>
	<input id="sel" type="hidden" value="<?php echo $obser;?>"/><input id="flou" type="hidden" value="<?php echo $flou;?>"/><input id="couche" type="hidden" value="<?php echo $couche;?>"/><input id="tdon" type="hidden" value="<?php echo $typedon;?>"/>
</section>
<div class="modal fade" id="adonoui">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Information donnée</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>				
			</div>
			<div class="modal-body">
				<ul>
					<li><b>Privée :</b> La donnée source a été produite par un organisme privé ou un individu à titre personnel. Aucun organisme ayant autorité publique n'a acquis les droits patrimoniaux, la donnée source reste la propriété de l'organisme ou de l'individu privé. Seul ce cas autorise un floutage géographique.</li>
					<li><b>Publique :</b> La donnée source est publique qu'elle soit produite en "régie" ou "acquise".</li>
					<li><b>Acquise sur fonds publics :</b> La donnée source a été produite par un organisme privé (association, bureau d'étude...) ou une personne physique à titre personnel. Les droits patrimoniaux exclusifs, de copie, traitement et diffusion sans limitation ont été acquis à titre gracieux ou payant, sur le marché ou par convention, par un organisme ayant autorité publique. La donnée source est devenue publique.</li>
				</ul>			
			</div>
		</div>
	</div>
</div>