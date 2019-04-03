<section class="container-fluid">
	<header class="row color4_bg barreheader blanc">
		<div class="col-md-12 col-lg-12">
			<div class="d-flex">
				<h1 class="h6 centreligne">OUTIL DE SAISIE DE DONNÉES NATURALISTES</h1>
				<span class="curseurlien ml-4">
					<i id="btnaide" class="text-primary centreligne fa fa-info fa-lg" title="Activer l'aide"></i>
					<i id="btfiche10" class="text-primary centreligne fa fa-pencil-square-o fa-lg ml-2" title="Vos dix dernières fiches"></i>
				</span>					
				<form class="form-custom-saisie ml-auto w-50">
					<div class="row">
						<div class="col-sm-4">
							<input type="text" id="observateur" name="observateur" class="form-control" value="<?php echo $_SESSION['nom'].' '.$_SESSION['prenom'].'';?>">				
						</div>
						<div class="col-sm-8">
							<input type="text" id="observateur2" name="observateur2" class="form-control" placeholder="Ajouter observateur(s)" data-toggle="tooltip" data-placement="bottom" title="Vous pouvez ajouter des observateurs (laissez la virgule entre chaque observateur) déjà enregistrés dans la base ou en ajouter en cliquant sur la croix à droite">				
						</div>					
					</div>					
				</form>	
				<span id="plusobs" class="ml-auto curseurlien" title="Créer un observateur"><i class="centreligne fa fa-user-plus fa-lg"></i></span>
			</div>					
		</div>
	</header>	
</section>
<section class="container-fluid p-0 font13">
	<div class="row no-gutters">
		<div class="w-50" id="blocmap">
			<div id="map"></div>
		</div>
		<div class="w-50" id="change">
			<form id="formulaire">
				<div class="card card-body" id="blocfiche">
					<div id="R"></div>
                    <div class="min p-2">
                    <fieldset>
                        <legend class="legendesaisie">Centrer la carte sur une localisation <i id="infolieu" class="fa fa-info-circle curseurlien text-info info" title="Aide à la saisie"></i></legend>
                            <div class="form-group row pt-2">
							<?php 
							if ($dep == 'oui')
							{
								?><div class="col-sm-5"><input type="text" class="form-control" id="choixdep" placeholder="Chercher un département"></div><?php
							}
							?>
							<div class="col-sm-6"><input type="text" class="form-control" id="choixcom" placeholder="Centrer sur une commune"></div>
							<div class="col-sm-1"><i class="fa fa-eye fa-lg curseurlien text-primary" id="vsite" title="Affiche les localisations de la commune"></i></div>
						</div>
						<div class="form-group row">
							<div class="col-sm-5"><input type="text" class="form-control" id="choixsite" placeholder="Chercher dans vos localisations existantes (2 lettres)" data-toggle="tooltip" data-placement="bottom" data-title="Si vous connaissez le nom du site, vous pouvez le rechercher ici. Autrement cliquer sur la carte"></div>
							<div class="col-sm-5"><input type="text" class="form-control" id="choixsite1" placeholder="Chercher une localisation existante (toutes)" data-toggle="tooltip" data-placement="bottom" data-title="Si vous connaissez le nom du site, vous pouvez le rechercher ici. Autrement cliquer sur la carte"></div>
							<div class="col-sm-1"><i class="fa fa-plus text-success curseurlien" id="imgpluscoord" data-toggle="tooltip" data-placement="top" data-title="A partir de coordonnées (GPS,..)"></i></div>
						</div>
						<div id="pluscoord" class="form-group row">
							<div class="col-sm-2"><input type="text" class="form-control" id="xcoord" placeholder="x / lng"></div>
							<div class="col-sm-2"><input type="text" class="form-control" id="ycoord" placeholder="y / lat"></div>
							<label for="proj" class="col-sm-2 col-form-label">Projection</label>
							<div class="col-sm-2">
								<select id="proj" class="form-control">
									<option value="nr">Choisir</option>
									<option value="w84">WGS84</option>
									<option value="l93">Lambert 93</option>														
									<option value="l2">Lambert 2</option>
								</select>
							</div>
						</div>						
					</fieldset>
                    </div>
                    <div class="min p-2 mt-3">
					<fieldset class="mt-2">
						<legend class="legendesaisie">Informations sur la localisation</legend>
						<?php 
						if ($dep == 'oui')
						{
							?><div class="form-group row"><div class="col-sm-6"><input type="text" class="form-control" id="dep" placeholder="Département"></div></div><?php
						}
						?>
						<div class="form-group row">
							<div class="col-sm-6"><input type="text" class="form-control" id="communeb" placeholder="Commune"></div>
						</div>
						<div class="form-group row">
							<div class="col-sm-6"><input type="text" class="form-control" id="lieub" name="lieub" placeholder="Créer une nouvelle localisation (facultatif)"></div>
							<div class="col-sm-2"><input type="text" class="form-control" id="altitude" name="altitude" placeholder="Alt."></div>
						</div>
						<div class="form-group row">
							<div class="col-sm-2"><input type="text" class="form-control" id="xlambert" name="xlambert" placeholder="X"></div>
							<div class="col-sm-3"><input type="text" class="form-control" id="ylambert" name="ylamber" placeholder="Y"></div>
							<div class="col-sm-3"><input type="text" class="form-control" id="lat" name="lat" placeholder="lat"></div>
							<div class="col-sm-3"><input type="text" class="form-control" id="lng" name="lng" placeholder="lng"></div>
						</div>
						<div class="form-group row">
							<div class="col-sm-4"><input type="text" class="form-control" id="l93" name="l93" placeholder="Maille 10 km"></div>		
							<div class="col-sm-4"><input type="text" class="form-control" id="l935" name="l935" placeholder="Maille 5 km"></div>
						</div>
						<?php
						if($utm == 'oui')
						{
							?>
							<div class="form-group row">
							<div class="col-sm-3"><input type="text" class="form-control" id="utm" name="utm" placeholder="UTM"></div>
							<div class="col-sm-3"><input type="text" class="form-control" id="utm1" name="utm1" placeholder="UTM 1"></div>
							</div>
							<?php
						}
						?>	
					</fieldset>
                    <fieldset>
                        <legend class="legendesaisie">Précision de la géométrie (à titre d'information, non-utilisée dans les traitements)</legend>
                        <div class="form-group row">
                            <label for="prec" class="col-sm-3 col-form-label">Echelle de précision</label>
                            <div class="col-sm-4">
                                <select id="precision" name="precision" class="form-control">
                                    <?php
                                    foreach($precision as $n)
                                    {
                                        if($n['idpreci'] == $idpreci)
                                        {
                                            ?><option value="<?php echo $n['idpreci'];?>" selected><?php echo $n['lbpreci'];?></option><?php
                                        }
                                        else
                                        {
                                            ?><option value="<?php echo $n['idpreci'];?>"><?php echo $n['lbpreci'];?></option><?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </fieldset>
                    </div>
                    <div class="min p-2 mt-3">
					<fieldset>
						<legend class="legendesaisie">Dates (heures, météo, etc..)</legend>
						<div class="form-group row">
							<label for="date" class="col-sm-1 col-form-label">Du</label>
							<div class="col-sm-3"><input type="text" class="form-control" id="date" name="date" pattern="\d{1,2}/\d{1,2}/\d{4}"></div>
							<label for="date2" class="col-sm-1 col-form-label">au</label>
							<div class="col-sm-3"><input type="text" class="form-control" id="date2" name="date2" pattern="\d{1,2}/\d{1,2}/\d{4}"></div>
							<div class="col-sm-1"><i class="fa fa-plus text-success curseurlien" id="imgplusfiche"></i></div>
						</div>
						<div id="plusfiche">
							<div class="form-group row">
								<label for="heure" class="col-sm-1 col-form-label">De</label>
								<div class="col-sm-2"><input type="text" class="form-control" id="heure" name="heure"></div>
								<label for="heure2" class="col-form-label">a</label>
								<div class="col-sm-2"><input type="text" class="form-control" id="heure2" name="heure2"></div>
								<label for="tempdeb" class="ml-2 col-form-label">°C debut</label>
								<div class="col-sm-2"><input type="number" min="-50" max="50" class="form-control" id="tempdeb" name="tempdeb"></div>
								<label for="tempfin" class="col-form-label">°C fin</label>
								<div class="col-sm-2"><input type="number" min="-50" max="50" class="form-control" id="tempfin" name="tempfin"></div>
							</div>
						</div>
					</fieldset>
                    </div>
                    <div class="min p-2 mt-3 mb-3">
					<fieldset>
						<legend class="legendesaisie">Diffusion, type et source de(ou des) données</legend>
                        <div class="form-group row">
                            <label for="org" class="col-sm-5 col-form-label">Organisme</label>
                            <div class="col-sm-6">
                                <select id="org" name="org" class="form-control">
                                    <?php
                                    foreach($org as $n)
                                    {
                                        if($n['idorg'] == $idorg)
                                        {
                                            ?><option value="<?php echo $n['idorg'];?>" selected><?php echo $n['organisme'];?></option><?php
                                        }
                                        else
                                        {
                                            ?><option value="<?php echo $n['idorg'];?>"><?php echo $n['organisme'];?></option><?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" id="etudecache">
                        <label for="etude"class="col-sm-5 col-form-label">Etude - dossier</label>
                            <div class="col-sm-6">
                            <select id="etude" required="" name="etude" class="form-control"></select>
                            </div>
                        </div>
                        <div class="form-group row" id="typedoncache">
							<label for="typedon" class="col-sm-5 col-form-label">Origine de la donnée</label>
							<div class="col-sm-6">
								<select id="typedon" required="" name="typedon" class="form-control">
                                    <option value="Pu">Publique</option>
                                    <option value="Pr">Privée</option>
									<option value="Ac" style="display:none;">Selon étude</option>
								</select>
							</div>
						</div>
						<div class="form-group row" id="foutagecache">
							<label for="floutage" class="col-sm-5 col-form-label">Restitution dans Kollect</label>
							<div class="col-sm-6">
								<select id="floutage" required="" name="floutage" class="form-control">
									<option value="0">Pas de dégradation</option>
									<option value="1">Commune/maille 10x10</option>
									<option value="2">Maille 10x10</option>
									<option value="3">Département</option>
								</select>
							</div>
						</div>										
						<div class="form-group row">
							<label for="source" class="col-sm-5 col-form-label">Source des données</label>
							<div class="col-sm-5">
								<select id="source" required="" name="source" class="form-control">
									<option value="Te">Terrain</option>
									<option value="NSP">Ne Sait Pas</option>
									<option value="Co">Collection</option>
									<option value="Li">Littérature</option>
								</select>
							</div>
						</div>
					</fieldset>
                    </div>
					<div class="row ml-1">
						<button type="button" class="afcarte btn btn-success curseurlien" data-toggle="tooltip" data-placement="bottom" data-title="Masque/affiche la carte">Saisir les espèces observées <i class="fa fa-eye fa-1x"></i></button>
						<div class="" id="valf">
							<button type="button" class="btn btn-success" id="BttF" data-toggle="tooltip" data-placement="top" data-title="Valide les modifications de la fiche">Valider les modifications</button>
							<button type="button" class="btn btn-warning" id="vobsfiche" data-toggle="tooltip" data-placement="top" data-title="Liste des observations du relevé"><i class="fa fa-eye fa-lg"></i> Voir les espèces</button>
							<button type="button" class="BttSA btn btn-warning" data-toggle="tooltip" data-placement="top" data-title="Réinitialise la fiche de saisie">Réinitialiser</button>
						</div>
					</div>
					<div id="alert1" class="mt-2"></div>					
				</div>		
				<div class="row no-gutters" id="blocobs">
					<div class="col-md-9 col-lg-9">
						<?php
						if(isset($rjson_site['observatoire']))
						{
							?>
							<div class="color1_bg">										
								<ul class="ulsaisie list-inline blanc stadecache ndecache">
									<li class="list-inline-item"><span class="font-bold blanc">CHOISIR :  </span></li>
									<?php
									foreach ($menuobservatoire as $n)
									{
										if($n['var'] == $obser)
										{
											?><li id="<?php echo $n['var'];?>" class="idvar list-inline-item text-primary curseurlien"><i class="cercleicone <?php echo $n['icon'];?> fa-lg" title="<?php echo $n['nom'];?>"></i></li><?php
										}
										else
										{
											?><li id="<?php echo $n['var'];?>" class="idvar list-inline-item curseurlien"><i class="cercleicone <?php echo $n['icon'];?> fa-lg" title="<?php echo $n['nom'];?>"></i></li><?php
										}
									}
									?>
									<li class="list-inline-item float-right font-weight-bold" id="cachefleche">Revenir à la carte : <i class="afcarte fa fa-map-o fa-2x curseurlien" data-toggle="tooltip" data-placement="bottom" data-title="Masque/affiche la carte"></i></li>
								</ul>
							</div>
							<div id="mes"></div>
							<?php
						}
						?>
						<div class="card card-body" id="blocsaisie">
							<div id="R1"></div>
                            <div class="min p-2">
							<fieldset class="stadecache ndecache">
								<legend class="legendesaisie">Choix de l'espèce <i class="fa fa-plus text-success curseurlien ml-3" id="imgpluslocale" data-toggle="tooltip" data-placement="bottom" data-title="Chercher dans les espèces non inclusent (espèces nouvelles)"></i></legend>
								<div class="form-group row" id="pluslatin1">
									<div class="col-sm-5"><input type="text" class="form-control" id="latin1" placeholder="nom latin (liste entière)"></div>
								</div>
								<div class="form-group row">
									<div class="col-sm-4"><input type="text" class="form-control" id="latin" name="latin" placeholder="nom latin"></div>
									<div class="col-sm-4"><input type="text" class="form-control" id="nomf" name="nomf" placeholder="où nom français"></div>
									<div class="col-sm-4"><input type="text" class="form-control" id="nomb" required=""></div>								
								</div>
                                <div class="form-group row form-inline">
                                    <label for="nom_cite" class="ml-3 mr-1">Nom cité</label><i id="infocite" class="fa fa-info-circle curseurlien text-info info"></i>
                                    <div class="col-sm-4"><input type="text" class="form-control" id="nom_cite" name="nom_cite" placeholder="Indiquez le nom cité"></div>
                                </div>
								<p id="mesvali" class="font-weight-bold text-danger"></p>
							</fieldset>
                            </div>
                            <div class="min p-2 mt-3">
                            <fieldset>
                                <legend class="legendesaisie">Type d'acquisition</legend>
                                <div id="plusproto">
                                    <div class="form-inline">
                                        <!-- <label for="protocol" class="ml-3 mr-4">Protocole</label> -->
                                        <select id="protocol" required="" name="protocol" class="form-control form-control-sm"></select>
                                    </div>
                                </div>
                            </fieldset>
                            </div>
                            <div class="min p-2 mt-3">
							<fieldset>
								<legend class="legendesaisie">Renseignements sur l'observation</legend>
								<div class="form-inline">
									<label for="stade" class="mr-1">Stade</label><i id="info4" class="fa fa-info-circle curseurlien text-info info"></i>
									<select id="stade" required="" name="stade" class="ml-2 form-control form-control-sm"></select>
									<label for="etatbio" class="ml-3 mr-2 mb-2">Etat biologique</label>
									<select id="etatbio" required="" name="etatbio" class="form-control form-control-sm">
										<option value="2">Observé vivant</option>
										<option value="3">Trouvé mort</option>
										<option value="1">Non renseigné</option>
										<option value="0">Inconu</option>
									</select>
									<label for="statutobs" class="ml-3 mr-2">Statut</label>
									<div class="stadecache ndecache">
										<select id="statutobs" required="" name="statutobs" class="form-control form-control-sm" data-toggle="tooltip" data-placement="top" data-title="Non Observé : L'observateur n'a pas détecté un taxon particulier, recherché suivant un protocole">
											<option value="Pr">Présent</option>
											<option value="No">Non Observé</option>
										</select>
									</div>
									<label for="denom" class="ml-3 mr-2">Denombre.</label>
									<select id="denom" name="denom" required="" class="form-control form-control-sm">
										<option value="Co">Compté</option>
										<option value="Es">Estimé</option>
										<option value="NSP">Non Rens.</option>
									</select>
									<label for="tdenom" class="ml-3 mr-2">Type</label>
									<select id="tdenom" name="tdenom" class="form-control form-control-sm"></select>									
								</div>
								<div class="form-inline mt-2" id="cmort">
									<label for="mort" class="mr-1">Cause mort</label><i id="info5" class="fa fa-info-circle curseurlien text-info info"></i>
									<select id="mort" name="mort" class="ml-2 form-control form-control-sm"></select>
								</div>
							</fieldset>
                            <div class="row mt-3 ">
								<fieldset class="col-md-6">
									<div class="form-group row mb-0">
										<label for="ndiff" class="col-sm-2 col-form-label">Indéterminé</label>
										<div class="col-sm-4"><input type="number" class="form-control form-control-sm nbexact" min="0" name="ndiff" id="ndiff" pattern="^\d*" data-toggle="tooltip" data-placement="top" data-title="(Non différencié mâle/femelle)"></div>
										<label for="male" class="col-sm-2 col-form-label">Mâle</label>
										<div class="col-sm-4"><input type="number" class="form-control form-control-sm nbexact" min="0" name="male" id="male" pattern="^\d*"></div>
									</div>
									<div class="form-group row" >
										<label for="femelle" class="col-sm-2 col-form-label">Femelle</label>
										<div class="col-sm-4"><input type="number" class="form-control form-control-sm nbexact" min="0" name="femelle" id="femelle" pattern="^\d*"></div>
									</div>
								</fieldset>
								<div class="col-md-6">
									<div id="estim">
										<div class="form-check form-check-inline">
											<label class="form-check-label"><input class="form-check-input" type="radio" name="clabon" id="cl1" value="cl1"> 1 à 10</label>
										</div>
										<div class="form-check form-check-inline">
											<label class="form-check-label"><input class="form-check-input" type="radio" name="clabon" id="cl2" value="cl2"> 11 à 100</label>
										</div>
										<div class="form-check form-check-inline">
											<label class="form-check-label"><input class="form-check-input" type="radio" name="clabon" id="cl3" value="cl3"> 101 à 1 000</label>
										</div>
										<div class="form-check form-check-inline">
											<label class="form-check-label"><input class="form-check-input" type="radio" name="clabon" id="cl4" value="cl4"> 1 001 à 10 000</label>
										</div>
										<div class="form-check form-check-inline">
											<label class="form-check-label"><input class="form-check-input" type="radio" name="clabon" id="cl5" value="cl5"> > 10 000</label>
										</div>
									</div>
									<div class="form-inline mb-3" id="nbtmp">
										<label for="nbtmp1" class="mr-2">Nombre</label>
										<input type="number" class="form-control form-control-sm mr-3" id="nbtmp1" min="0">
									</div>
									<div class="form-inline">										
										<input type="number" class="form-control form-control-sm mr-3" name="nbmin" id="nbmin" placeholder="mini" disabled>
										<input type="number" class="form-control form-control-sm" name="nbmax" id="nbmax" placeholder="max" disabled>
									</div>									
								</div>								
							</div>
                            </div>
                            <div class="min p-2 mt-3">
							<fieldset>
								<legend class="legendesaisie">Contact, méthode de prospection, statut biologique, comportement</legend>
								<div class="form-inline">
									<label for="obsmethode" class="mr-1">Type de contact </label><i id="info1" class="fa fa-info-circle curseurlien text-info info"></i>
									<select id="obsmethode" required="" name="obsmethode" class="ml-2 form-control form-control-sm"></select>
									<label for="obscoll" class="ml-3 mr-1">Prospection</label><i id="info2" class="fa fa-info-circle curseurlien text-info info"></i>
									<select id="obscoll" required="" name="obscoll" class="ml-2 form-control form-control-sm"></select>
									<label for="bio" class="ml-3 mr-1">Statut biologique</label><i id="info3" class="fa fa-info-circle curseurlien text-info info"></i>
                                    <select id="bio" required="" name="bio" class="ml-2 form-control form-control-sm"></select>
                                    <label for="comportement" class="ml-3 mr-1">Comportement</label><i id="infocomp" class="fa fa-info-circle curseurlien text-info info"></i>
                                    <select id="comportement" required="" name="comportement" class="ml-2 form-control form-control-sm"></select>
                                </div>
							</fieldset>
                            </div>
                            <div class="min p-2 mt-3">
							<fieldset class="stadecache ndecache">
								<legend class="legendesaisie">Détermination</legend>
								<div class="form-inline">
									<label for="det" class="">Déterminateur</label>
									<input type="text" class="ml-2 form-control form-control-sm" id="det" required="" value="<?php echo $_SESSION['nom'].' '.$_SESSION['prenom'].'';?>">
									<i class="fa fa-plus text-success curseurlien ml-2" id="imgpluscol"></i>									
								</div>
							</fieldset>
							<fieldset id="pluscol" class="mt-2">
								<legend class="legendesaisie">Détermination à partir d'un examen sous loupe, étude édéage/genitalia</legend>
								<div class="form-inline">
									<div class="form-check">
										<label class="form-check-label"><input class="form-check-input" type="checkbox" id="collect"> Spécimen conservé</label>
									</div>
									<label for="detcol" class="ml-3">-> Détenteur</label>
									<input type="text" class="ml-2 form-control form-control-sm" id="detcol">
								</div>	
								<div class="form-inline mt-2">
									<div class="form-check">
										<label class="form-check-label"><input class="form-check-input" type="checkbox" id="gen"> Methode</label>
									</div>
									<select id="typegen" name="typegen" class="ml-3 form-control form-control-sm">
										<option value="NR">--Choisir--</option>
										<option value="edeage">Edéage</option>
										<option value="genitalia">Genitalia</option>
										<option value="loupe">Loupe, en main</option>
										<option value="bino">Loupe binoculaire</option>
									</select>
									<label for="prepgen" class="ml-3">Examinateur/préparateur</label>
									<input type="text" class="ml-2 form-control form-control-sm" id="prepgen">
									<label for="detgen" class="ml-3">Détenteur</label>
									<input type="text" class="ml-2 form-control form-control-sm" id="detgen">
									<label for="codegen" class="ml-3">Code</label>
									<input type="text" class="ml-2 form-control form-control-sm" id="codegen" name="codegen" placeholder="max 20 cararctères">
									<label for="sexegen" class="ml-3">Sexe</label>
									<select id="sexegen" name="sexegen" class="ml-2 form-control form-control-sm">
										<option value="">--Choisir--</option>
										<option value="M">Mâle</option>
										<option value="F">Femelle</option>										
									</select>
								</div>								
							</fieldset>
                            </div>
							<fieldset id="plteh" class="mt-2">
								<legend class="legendesaisie">Plante hôte, support de ponte, butiné, consommé <i class="fa fa-plus text-success curseurlien ml-3" id="imgplusplte"></i></legend>
								<div id="pltehote">
									<p>Si vous connaissez la\les plante(s) sur laquelle vous avez fait votre observation (vous pouvez préciser un nombre d'individu, dans ce cas le mettre avant de choisir la plante)</p>
									<div class="form-inline mb-2">
										<label for="nbpltel" class="">Nombre d'individu sur la plante</label>
										<input type="number" min="0" class="ml-2 form-control form-control-sm" id="nbpltel">
										<label for="choixplte" class="ml-3 mr-2">Plante</label>
										<input type="text" class="form-control form-control-sm" id="choixplte">
										<button type="button" class="ml-3 btn btn-success btn-sm" id="bttAplte">Ajouter une plante</button>
									</div>
									<ul id="ulplante"></ul>
								</div>
							</fieldset>
                            <div class="min p-2 mt-3">
							<fieldset>
								<legend class="legendesaisie">Habitats (typologie eunis) <i class="fa fa-plus text-success curseurlien ml-3" id="imgplushab"></i></legend>
								<div id="plushab">	
									<?php
									if(count($habitat) > 0)
									{
										?>
										<div class="form-group row">
											<div class="col-sm-4">
												<select id="habitat" class="form-control form-control-sm">
													<option value="NR">-- choisir un habitat au besoin --</option>
													<?php
													foreach($habitat as $n)
													{
														?><option value="<?php echo $n['cdhab'];?>" ><?php echo $n['lbcode'];?> - <?php echo $n['lbhabitat'];?></option><?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-4">
												<select id="habitat2" class="form-control form-control-sm"></select>
											</div>
											<div class="col-sm-4">
												<select id="habitat3" class="form-control form-control-sm"></select>
											</div>
										</div>
										<?php
									}
									else
									{
										?><p>Aucun habitat paramétré</p><?php
									}
									?>
								</div>
							</fieldset>
                            </div>
                            <div class="min p-2 mt-3" id="aves">
							<fieldset>
								<legend class="legendesaisie">Indices de nidification pour les oiseaux <span id="nicheur" class="text-primary"></span></legend>
								<div class="form-group row">
									<div class="col-sm-12">
										<select id="indnid" name="indnid" class="form-control form-control-sm"></select>
									</div>
								</div>										
							</fieldset>
                            </div>
                            <div class="min p-2 mt-3">
							<fieldset>
								<legend class="legendesaisie">Remarques sur l'observation
									<div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" id="mrq"> <label class="form-check-label">(Cocher pour conserver les remarques)</label></div>
								</legend>
								<div class="form-group row">
									<div class="col-sm-12"><textarea class="form-control" rows="2" id="rq" name="rq" placeholder="Remarques sur l'observation"></textarea></div>
								</div>
							</fieldset>
                            </div>
                            <div class="min p-2 mt-3 mb-3">
							<fieldset>
								<legend class="legendesaisie">Ajouter une photo <i class="fa fa-camera text-success curseurlien ml-3" id="adphoto" data-toggle="tooltip" data-placement="bottom" data-title="Chercher dans les espèces non inclusent (espèces nouvelles)"></i></legend>
								<div class="row mb-3" id="photo">
									<div class="col-md-5 col-lg-5">
										<p>
											<b>La photo doit être prise lors de cette observation</b><br />											
											Fichiers autorisés "jpg". <br />
											Paysage - Mettre au minimum des photos de 800 de largeur x 600 de hauteur.<br />
											Portrait - Mettre au minimum des photos de 400 de largeur x 600 de hauteur.<br />
											Il est également possible de rajouter des photos par la suite.
										</p>
										<div class="custom-control custom-radio custom-control-inline">
											<input type="radio" class="custom-control-input" id="paysage" name="orien" value="paysage" checked>
											<label class="custom-control-label" for="paysage">Paysage</label>
										</div>
										<div class="custom-control custom-radio custom-control-inline">
											<input type="radio" class="custom-control-input" id="portrait" name="orien" value="portrait">
											<label class="custom-control-label" for="portrait">Portrait</label>
										</div>
										<div class="form-inline mb-2">
											<label for="sexe">Sexe</label>
											<select id="sexe" name="sexe" class="ml-2 form-control form-control-sm">
												<option value="">Indéterminé</option>
												<option value="M">Mâle</option>
												<option value="F">Femelle</option>
												<option value="C">Couple</option>
											</select>
										</div>
										<div id="obserphoto">
											<p><b>Si la photo n'est pas de vous mais d'un co-observateur, cocher son nom</b></p>
											<div id="opph"></div>
										</div>
									</div>
									<div class="col-md-7 col-lg-7">
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
										<div class="mt-3 mb-2">
											<button type="button" class="export btn btn-warning" id="BttP">Prévisualisation</button>
										</div>
									</div>
								</div>
							</fieldset>
                            </div>
                            <div class="" id="val">
								<div class="form-inline">
									<button type="submit" class="btn btn-info" id="BttS" data-toggle="tooltip" data-placement="top" data-title="Permet de rajouter un stade à cette espèce">Ajouter un stade/Etat bio</button>
									<button type="submit" class="ml-3 btn btn-info" id="BttN" data-toggle="tooltip" data-placement="top" data-title="Rajoute une espèce à cette fiche (relevé)">Ajouter une espèce</button>
								</div>
								<div class="form-inline mt-3">
									<button type="submit" class="btn btn-success" id="BttV" data-toggle="tooltip" data-placement="top" data-title="Termine l'enregistrement pour cette fiche">Valider</button>
									<button type="button" class="ml-3 BttSA btn btn-warning" data-toggle="tooltip" data-placement="top" data-title="Réinitialise la fiche de saisie">Réinitialiser</button>
								</div>
								<!--<div class="p-2"><button type="submit" class="btn btn-success" id="BttV" data-toggle="tooltip" data-placement="top" data-title="Termine l'enregistrement pour cette fiche">Valider</button></div>
								<div class="p-2"><button type="submit" class="btn btn-info" id="BttS" data-toggle="tooltip" data-placement="top" data-title="Permet de rajouter un stade à cette espèce">Ajouter un stade/Etat bio</button></div>
								<div class="p-2"><button type="submit" class="btn btn-info" id="BttN" data-toggle="tooltip" data-placement="top" data-title="Rajoute une espèce à cette fiche (relevé)">Ajouter une espèce</button></div>
								<div class="p-2"><button type="button" class="BttSA btn btn-warning" data-toggle="tooltip" data-placement="top" data-title="Réinitialise la fiche de saisie">Réinitialiser</button></div>-->
							</div>
							<div class="d-flex" id="valm">
								<div class="p-2"><button type="submit" class="btn btn-success" id="BttM" data-toggle="tooltip" data-placement="top" data-title="Valide les modifications">Valider les modification</button></div>
								<div class="p-2"><button type="button" class="btn btn-info" id="BttSM" data-toggle="tooltip" data-placement="top" data-title="Rajoute un stade à cette espèce">Ajouter un stade</button></div>
								<div class="p-2"><button type="button" class="btn btn-info" id="aobsfiche" data-toggle="tooltip" data-placement="top" data-title="Rajoute une espèce à cette fiche (relevé)"><i class="fa fa-plus-circle fa-lg"></i> ajouter une espèce</button></div>
								<div class="p-2"><button type="button" class="btn btn-warning" id="vofiche" data-toggle="tooltip" data-placement="top" data-title="Modifié le relevé"><i class="fa fa-file-text-o fa-lg"></i> Voir le relevé</button></div>
								<div class="p-2"><button type="button" class="BttSA btn btn-warning" data-toggle="tooltip" data-placement="top" data-title="Réinitialise la fiche de saisie">Réinitialiser</button></div>
							</div>
							<div id="valajaxs"><progress></progress></div>
						</div>
					</div>
					<div class="col-md-3 col-lg-3">
						<div class="card card-body">
							<ul id="listeobs" class="list-unstyled font13"></ul>
						</div>
					</div>
				</div>
				<!--input hidden -->
				<!--localisation, fiche, obs -->
				<input id="codecom" name="codecom" type="hidden"/><input id="codedep" type="hidden" name="codedep"/><input id="codesite" name="codesite" type="hidden"/><input id="idcoord" type="hidden" name="idcoord"/>
				<input id="idobser" name="idobser" type="hidden" value="<?php echo $idobser;?>"/><input id="iddet" name="iddet" type="hidden" value="<?php echo $idobser;?>"/><input id="cdnom" name="cdnom" type="hidden"/><input id="cdref" name="cdref" type="hidden"/>
				<input id="idfiche" name="idfiche" type="hidden" value="Nouv"/><input id="idobs" name="idobs" type="hidden" value="Nouv"/><input id="cdhab" name="cdhab" type="hidden"/>
				<input id="pr" name="pr" type="hidden"/><input id="nb" name="nb" type="hidden"/><input id="newsp" name="newsp" type="hidden"/><input id="biogeo" name="biogeo" type="hidden" value="<?php echo $biogeo;?>"/>
				<input id="typepoly" name="typepoly" type="hidden" size="200"/><input id="idm" type="hidden" value="<?php echo $_SESSION['idmembre'];?>"/>
				<!-- specificite aux observatoires -->
				<input id="sel" name="sel" type="hidden" value="<?php echo $obser;?>"/><input id="selm" type="hidden"/><input id="tvali" name="tvali" type="hidden"/><input id="validateur" name="validateur" type="hidden"/>
				<input id="iddetcol" name="iddetcol" type="hidden"/><input id="iddetgen" name="iddetgen" type="hidden"/><input id="idprep" name="idprep" type="hidden"/><input id="cdnombota" name="cdnombota" type="hidden"/><input id="nbplte" name="nbplte" type="hidden"/>
			</form>
		</div>		
	</div>
	<div class="row no-gutters" id="liste10">
		<div class="col-md-9 col-lg-9">
			<div class="card card-body min">
				<p id="titrer"><b>Vos dix dernières fiches enregistrées</b></p>
				<div id="listefiche"></div>
			</div>
		</div>				
	</div>
	<!--input hidden pour traitement js -->
	<input id="idobseror" type="hidden" value="<?php echo $idobser;?>"/><input id="iddetor" type="hidden" value="<?php echo $idobser;?>"/><input id="getidfiche" type="hidden" value="<?php echo $getidfiche;?>"/><input id="couche" type="hidden" value="<?php echo $couche;?>"/><input id="proche" type="hidden" value="<?php echo $dist;?>"/>
	<input id="flou" type="hidden" value="<?php echo $flou;?>"/><input id="tdon" type="hidden" value="<?php echo $typedon;?>"/><input id="idligneobs" type="hidden"/><input id="valsel" type="hidden"/><input id="Bt" type="hidden" name="Bt"><input id="aphoto" type="hidden"><input id="afiche" type="hidden"><input id="choixauto" type="hidden">
	<input id="coordpr" type="hidden"/>
</section>
<!-- Boite dialogue -->
<div id="dia1" class="modal" tabindex="-1" role="dialog" aria-labelledby="Modalajoutobs" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Ajouter un observateur</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<form class="form">
							<div class="form-group row">
								<label for="nomobs" class="col-sm-2 col-form-label">Nom</label>
								<div class="col-sm-8"><input type="text" class="form-control" id="nomobs"></div>
							</div>
							<div class="form-group row">
								<label for="prenomobs" class="col-sm-2 col-form-label">Prénom</label>
								<div class="col-sm-8"><input type="text" class="form-control" id="prenomobs"></div>
							</div>							
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
<div id="dia2" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<p>Le nom doit-être présent dans la liste proposée (La recherche se fait sur le nom de famille).<br />Vous pouvez créer un observateur en cliquant sur le <i class="fa fa-plus text-success"></i> à droite du champ.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Fermer</button>				
			</div>
		</div>
	</div>
</div>
<div id="dia3" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<p>Le point cliqué ne fait pas parti de l'emprise du site.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Fermer</button>				
			</div>
		</div>
	</div>
</div>
<div id="dia4" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<p>L'espèce doit-être présente dans la liste proposée.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Fermer</button>				
			</div>
		</div>
	</div>
</div>
<div id="dia6" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Choisir le stade</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<p>  
							<b><span id="stademod"></span></b> <span id="libmod"></span>
						</p>
						<ul id="modligne"></ul>
						<p id="suptous"></p>
					</div>
				</div>
			</div>			
		</div>
	</div>
</div>
<div id="dia7" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Suppression</h4>
			</div>
			<div class="modal-body">
				<p>Voulez vraiment supprimer cette observation ?</p>
			</div>
			<input id="encours" type="hidden"/>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Annuler</button>
				<button type="button" class="btn btn-success" data-dismiss="modal" id="bttdia7">Oui</button>
			</div>
		</div>
	</div>
</div>
<div id="dia8" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Site proche</h4>
			</div>
			<div class="modal-body">
				<p>
					Il existe un ou plusieurs sites d'observations à moins de <?php echo $dist;?> km<br>
					Cliquez sur un des <img class="" src="dist/css/images/marker-vert.png" alt="" height="20" width="12"/> si vous voulez enregistrer votre observation dessus.
				</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal" id="bttdia8">Fermer</button>
			</div>
		</div>
	</div>
</div>
<div id="dia9" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><span id="dia9titre"></span></h4>
			</div>
			<div class="modal-body">
				<p>Enregistrer sur ce point d'observation ?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Annuler</button>
				<button type="button" class="btn btn-success" data-dismiss="modal" id="bttdia9">Oui</button>
			</div>
		</div>
	</div>
</div>
<div id="dia10" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<p>Vous devez indiquer un nom de site.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Fermer</button>				
			</div>
		</div>
	</div>
</div>
<div id="dia11" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<p>Il faut d'abord selectionner un site pour créer une station..</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Fermer</button>				
			</div>
		</div>
	</div>
</div>
<div id="dia12" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Prévisualisation</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div id="imgdia12"></div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
			</div>
		</div>
	</div>
</div>
<div id="dia13" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
                <h4 class="modal-title">Vous avez modifié une géométrie d'un station existante</h4>
            </div>
            <div class="modal-body">
                <p>Si vous réalisez une modification pour le site : <b><span id="spandia13"></span></b>, cliquer sur Oui. Cela aura pour incidence de déplacer l'ensemble des observations réalisées
                    sur l'ancienne géométrie vers la nouvelle.</p>
                <p>Si vous souhaitez concerver les anciennes observations sur l'ancienne géométrie, cliquez sur Nouveau pour créer une stations 'fille'. </p>
            </div>
			<div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal" id="bttdiaN13no">Sortir de la station</button> <br/>
                <button type="button" class="btn btn-warning" data-dismiss="modal" id="bttdiaN13">Nouvelle station 'fille'</button> <br/>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Oui, modifier la station et TOUTES les observations de Kollect associées </button>
			</div>
		</div>
	</div>
</div>
<div id="dia14" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Information sur les attributs</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div id="rinfo"></div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
			</div>
		</div>
	</div>
</div>
<div id="dia15" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Aide à la saisie</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div id="rinfoaide"></div>
						<p>
							Une aide vous est également proposée pour certains champs lorsque vous passez la souris dessus. Pour activer l'aide, cliquez sur le <i class="text-primary fa fa-info"></i> en haut au dessus de la carte.
						</p>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
			</div>
		</div>
	</div>
</div>
<div id="dia16" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Précision</h4>
			</div>
			<div class="modal-body">
				<p><b>Attention !</b> Votre observation est saisie à l'échelle communale.<br />
				Cliquez sur modifier si besoin, puis cliquez sur la carte pour désigner votre point exact d'observation</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Modifier</button>
				<button type="button" class="btn btn-success" data-dismiss="modal" id="bttdia16">Oui</button>
			</div>
		</div>
	</div>
</div>