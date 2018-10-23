<section class="container-fluid blanc">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header>	
				<h1>Configuration des champs de saisie</h1>
			</header>		
			<form class="form-inline">
				<div class="form-group">
					<?php
					if ($nbobservatoire == 0)
					{
						?><p class="form-control-plaintext text-warning">Aucun observatoire pour l'instant sur le site</p><?php
					}
					else
					{
						?><p class="form-control-plaintext">Vous avez <?php echo ''.$nbobservatoire.' '.$libnbobser.'';?> à gérer.</p><?php
					}
					?>				
					<label for="choix" class="sr-only">Observatoire</label>					
					<select id="choix" class="form-control ml-2">
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
					<button type="button" class="btn btn-info ml-2" id="aide"><span id="btn-aide-txt">Aide</span></button>
				</div>			
			</form>			
		</div>
	</div>
	<div class="row mt-2" id="infoaide">
		<div class="col-md-12 col-lg-12">
			<ul>
				<li>Vous pouvez permettre la saisie de taxons indiqué comme non présent (local = non) à la page "gestion des taxons"</li>
				<li>En cliquant sur les <i class="fa fa-info-circle text-info"></i> la liste avec la description s'affichera sur la droite (cliquer ensuite sur <i class="fa fa-times"></i> en haut à droite pour la masquer)</li>
				<li>Les stades, méthodes d'observations etc... que vous choisissez apparaitront sur la fiche de saisie suivant l'ordre définit ici</li>
				<li>Si vous avez besoin de rajouter des méthodes d'observation et/ou des types de collectes informer l'admin du site. Lui seul peut le faire</li>
			</ul>
		</div>
	</div>
	<div id="mes"></div><hr />
	<div class="row" id="affiche">
		<div class="col-md-5 col-lg-5">
			<form>
				<b>1 - Permettre la saisie de taxons non notés comme étant local</b>
				<div class="form-inline">
					<div class="form-check">
						<label class="form-check-label"><input class="form-check-input" type="checkbox" id="locale"> coché = oui</label>
					</div>
				</div>
			</form>
			<p class="mt-2 mb-0"><b>2 - Choix des stades</b> <i class="fa fa-info-circle curseurlien text-info info" id="info1"></i></p>
			<select id="stade" multiple="multiple" class="multit" size="3"></select>
			<p class="mt-2 mb-0"><b>3 - Méthode d'observation</b> <i class="fa fa-info-circle curseurlien text-info info" id="info2"></i></p>
			<select id="meth"  multiple="multiple" class="multit"></select>
			<p class="mt-2 mb-0"><b>4 - Méthode de collecte</b> <i class="fa fa-info-circle curseurlien text-info info" id="info3"></i></p>
			<select id="col" multiple="multiple" class="multit"></select>
			<p class="mt-2 mb-0"><b>5 - Statut biologique</b> <i class="fa fa-info-circle curseurlien text-info info" id="info4"></i></p>
			<select id="bio" multiple="multiple" class="multit"></select>
            <p class="mt-2 mb-0"><b>5b - Comportement</b> <i class="fa fa-info-circle curseurlien text-info info" id="infocomp"></i></p>
            <select id="comport" multiple="multiple" class="multit"></select>
			<p class="mt-2 mb-0"><b>6 - Protocole</b> (Mettre toujours "Aucun" en premier)</p>
			<select id="proto" multiple="multiple" class="multit"></select>
			<p class="mt-2 mb-0"><b>7 - Denombrement</b> (Mettre toujours "Nombre d'individus observés" en premier)</p>
			<select id="denom" multiple="multiple" class="form-control multit"></select>
			<form>
				<b>8 - Etat biologique</b>
				<div>
					<div class="form-check form-check-inline">
						<label class="form-check-label"><input class="form-check-input" type="radio" name="stbio" id="vivant" value="vivant"> "Observé vivant" en premier</label>
					</div>
					<div class="form-check form-check-inline">
						<label class="form-check-label"><input class="form-check-input" type="radio" name="stbio" id="mort" value="mort"> "Trouvé mort" en premier</label>
					</div>
				</div>
			</form>
			<p class="mt-2 mb-0"><b>8a - Cause de la mort</b> <i class="fa fa-info-circle curseurlien text-info info" id="info5"></i></p>
			<select id="cmort" multiple="multiple" class="multit"></select>
			<form>
				<b>9 - Mâle et femelle</b>
				<div class="form-check">
					<label class="form-check-label"><input class="form-check-input" type="checkbox" id="mf"> coché = caché les champs mâle et femelle</label>
				</div>
				<b>10 - Collection - edeage/genitalia - Examen sous loupe</b>
				<div class="form-check">
					<label class="form-check-label"><input class="form-check-input" type="checkbox" id="collection"> Inclure les champs</label>
				</div>
				<div id="aves">
					<b>11 - Autres - Oiseaux</b>
					<div class="form-check">
						<label class="form-check-label"><input class="form-check-input" type="checkbox" id="inicheur"> Utiliser les indices nicheurs (possible, probable, certain)</label>
					</div>									
				</div>
				<div id="insecta">
					<b>11 - Autres</b>
					<div class="form-check">
						<label class="form-check-label"><input class="form-check-input" type="checkbox" id="plteh"> Saisie de plante hôte, support de ponte, plante butinée, mangée</label>
					</div>
					<div id="obsbota">
						<div class="form-inline">
							<label for="idbota" class="text-primary">Si vous avez un observatoire sur les plantes, indiqué ici son identifiant (Voir avec Admin général si besoin) :</label>
							<input type="text" id="idbota" class="form-control ml-3">
						</div>
					</div>
					<div class="form-check">
						<label class="form-check-label"><input class="form-check-input" type="checkbox" id="proie"> Saisie de la proie  (chasse / alimentation)</label>
					</div>
					<!--<input id="collection" type="hidden"/>-->
				</div>
				<div class="form-group row mt-3">
					<div class="col-sm-8">
						<button type="button" class="btn btn-success" id="BttV">Valider les changements</button>
					</div>							
				</div>
				<input id="clbota" type="hidden"/>
			</form>			
		</div>
		<div class="col-md-7 col-lg-7">
			<div id="valajax"><progress></progress></div>
			<div id="inforef"></div>			
		</div>
	</div>
</section>