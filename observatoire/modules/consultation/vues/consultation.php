<section class="container-fluid mb-3">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<div class="d-flex justify-content-start">
					<h1 class="h2">Consultation - <?php echo $nomd;?></h1>					
					<ol class="breadcrumb ml-auto mb-0">
						<?php
						if(isset($_GET['perso']) && $_GET['perso'] == 'oui')
						{
							?><li class="breadcrumb-item"><a href="../index.php?module=observation&amp;action=observation&amp;d=<?php echo $nomvar;?>&amp;perso=oui">Observations</a></li><?php
						}
						else
						{
							?><li class="breadcrumb-item"><a href="../index.php?module=observation&amp;action=observation&amp;d=<?php echo $nomvar;?>">Observations</a></li><?php
						}
						?>					
						<li class="breadcrumb-item active">Consultation</li>
					</ol>					
				</div>
				<div><span id="rchoix" class="text-primary curseurlien">Retour au choix</span> <span id="lchoix"></span></div>
			</div>
		</div>
	</header>
	<div class="row mt-2">
		<div class="col-md-12 col-lg-12">
			<div class="card card-body p-0">
				<?php
				if(!isset($pasobs))
				{
					?>
					<div id="choixconsult" class="min p-3">
						<div class="row">
							<div class="col-md-7 col-lg-7">
								<form id="form">
									<div class="form-row">
										<div class="col">
											<div class="custom-control custom-checkbox">
												<?php
												if($voir == 'non')
												{
													?>
													<input id="perso" type="checkbox" class="custom-control-input" checked>
													<input id="idobser" name="idobser" type="hidden" value="<?php echo $idobser;?>"/>
													<?php										
												}
												else
												{
													?>
													<input id="perso" type="checkbox" class="custom-control-input">
													<input id="idobser" name="idobser" type="hidden"/>
													<?php
												}
												?>							
												<label class="custom-control-label" for="perso">Uniquement vos observations</label>
											</div>
										</div>
										<?php
										if($voir == 'oui')
										{
											?>
											<div class="col">
												<input type="text" class="form-control form-control-sm" id="nobser" placeholder="Observateur">
											</div>	
											<?php
										}
										?>										
										<div class="col">
											<select id="orga" name="orga" class="form-control form-control-sm">
												<option value="NR">-- Organisme - choisir au besoin --</option>
												<?php
												foreach($org as $n)
												{
													?>
													<option value="<?php echo $n['idorg'];?>"><?php echo $n['organisme'];?></option>
													<?php
												}
												?>
											</select>
										</div>
									</div>
									<div class="form-row mt-1">
										<div class="col">
											<select id="typedon" name="typedon" class="form-control form-control-sm">
												<option value="NR">-- Type données - choisir au besoin --</option>
												<option value="Pr">privée</option>
												<option value="Pu">publique</option>														
												<option value="Ac">acquise sur fonds publics</option>
											</select>
										</div>
										<div class="col">
											<select name="flou" class="form-control form-control-sm">
												<option value="NR">-- Floutage - choisir au besoin --</option>
												<option value="0">Tel que (x/y)</option>
												<option value="1">Commune/maille 10x10</option>
												<option value="2">Maille 10x10</option>
												<option value="3">Département</option>
											</select>
										</div>
										<div class="col">
											<select name="pr" class="form-control form-control-sm">
												<option value="NR">-- Précision - choisir au besoin --</option>
												<option value="1">A la coordonnée</option>
												<option value="2">A la commune</option>
												<option value="3">Au département</option>
											</select>
										</div>
									</div>
									<fieldset class="mt-2">
										<legend class="legendesaisie">Taxons</legend>
										<div class="form-row">
											<div class="col">
												<input type="text" class="form-control form-control-sm" id="espece" placeholder="Espèce">
											</div>
											<div class="col">
												<input type="text" class="form-control form-control-sm" id="genre" placeholder="Ou genre">
											</div>
											<div class="col">
												<input type="text" class="form-control form-control-sm mt-lg-1 mt-xl-0" id="famille" placeholder="Ou famille">
											</div>
										</div>
										<div class="form-row mt-2">
											<div class="col">
												<select name="stade" class="form-control form-control-sm">
													<option value="0">Stade</option>
													<?php
													foreach($stade as $key => $n)
													{
														?><option value="<?php echo $n;?>"><?php echo $key;?></option><?php
													}
													?>
												</select>
											</div>
											<div class="col">
												<select name="etatbio" id="etatbio" class="form-control form-control-sm">
													<option value="NR">Etat biologique</option>
													<option value="2">Observé vivant</option>
													<option value="3">Trouvé mort</option>
													<option value="1">Non renseigné</option>
													<option value="0">Inconu</option>
												</select>
											</div>
											<div class="col">
												<select name="methode" class="form-control form-control-sm">
													<option value="0">Type contact</option>
													<?php
													foreach($methode as $key => $n)
													{
														?><option value="<?php echo $n;?>"><?php echo $key;?></option><?php
													}
													?>
												</select>
											</div>
											<div class="col">
												<select name="prospect" class="form-control form-control-sm">
													<option value="0">Prospection</option>
													<?php
													foreach($collecte as $key => $n)
													{
														?><option value="<?php echo $n;?>"><?php echo $key;?></option><?php
													}
													?>
												</select>
											</div>
											<div class="col">
												<select name="statbio" class="form-control form-control-sm">
													<option value="0">Statut bio</option>
													<?php
													foreach($statbio as $key => $n)
													{
														?><option value="<?php echo $n;?>"><?php echo $key;?></option><?php
													}
													?>
												</select>
											</div>
										</div>
										<?php
										if($choixmort == 'oui')
										{
											?>
											<div class="form-inline mt-2" id="afmort">
												<select id="mort" name="mort" class="form-control form-control-sm">
													<option value="0">Cause de la mort</option>
													<?php
													foreach($rjson_obser['saisie']['mort'] as $key => $n)
													{
														?><option value="<?php echo $n;?>"><?php echo $key;?></option><?php
													}
													?>
												</select>
											</div>
											<?php
										}
										?>
										<ul id="ltaxon" class="list-unstyled font12 mt-1"></ul>										
									</fieldset>
									<?php
									if(isset($rjson_obser['saisie']['aves']) && $rjson_obser['saisie']['aves'] == 'oui')
									{
										?>
										<fieldset class="mb-2">
											<legend class="legendesaisie">Indices de nidification</legend>
											<div class="form-row">
												<div class="col">
													<select name="cataves" id="cataves" class="form-control form-control-sm">
														<option value="NR">-- Choisir au besoin --</option>
														<option value="tous">Toutes nidification</option>
														<option value="possible">Nidification possible</option>
														<option value="probable">Nidification probable</option>
														<option value="certain">Nidification certaine</option>
													</select>
												</div>
												<div class="col">
													<select name="aves" id="aves" class="form-control form-control-sm">
														<option value="0">-- Code nicheur --</option>
														<optgroup label="Possible" id="spossible">
															<option value="2">2 - Présence dans son habitat durant sa période de reproduction</option>
															<option value="3" title="Mâle chanteur présent en période de nidification, cris nuptiaux ou tambourinages entendus, mâle vu en parade. Si ce comportement est observé de manière répétée, voir code 5">3 - Mâle chanteur présent en période de nidification</option>
														</optgroup> 
															<optgroup label="Probable" id="sprobable">
															<option value="4" title="Observation d'un couple sans comportement particulier, sinon, voir indices 5 et 6">4 - Couple présent dans son habitat durant sa période de nidifcation</option>
															<option value="5" title="(chant, chants simultanés de plusieurs individus, querelles avec des voisins,...)">5 - Comportement territorial observé sur un même territoire, 2 journées différentes à 7 jours ou plus d'intervalle</option>
															<option value="6">6 - Comportement nuptial : parades, vols nuptiaux, copulation ou échange de nourriture entre adultes.</option>
															<option value="7" title="Visite de nichoir, cavité, falaise,... (Voir également code 10)">7 - Visite d'un site de nidification probable, distinct d'un site de repos</option>
															<option value="8">8 - Cri d'alarme ou tout autre comportement agité indiquant la présence d'un nid ou de jeunes aux alentours</option>
															<option value="9" title="La capture d'espèces protégées est interdite. Uniquement pour les personnes autorisées.">9 - Preuve physiologique : plaque incubatrice très vascularisée ou oeuf présent dans l'oviducte (observation sur un oiseau en main)</option>
														</optgroup> 
														<optgroup label="Certain" id="scertain">														
															<option value="10" title="Oiseau transportant des brindilles, herbes, mousses, boue,... ou forant une cavité dans un tronc (pics) ou dans le sol (guépiers,...)">10 - Transport de matériel ou construction d'un nid, forage d'une cavité</option>
															<option value="11" title="Oiseau simulant une aile brisée ou ayant un comportement agressif lors de l'approche du nid">11 - Oiseau simulant une blessure ou détournant l'attention, tels les canards, gallinacés, oiseaux de rivage,...</option>
															<option value="12">12 - Nid vide ayant été utilisé la présente saison</option>
															<option value="13">13 - Jeunes en duvet ou jeunes venant de quitter le nid et incapables de soutenir le vol sur de longues distances</option>
															<option value="14">14 - Adulte gagnant, occupant ou quittant le site d'un nid, comportement révélateur d'un nid occupé dont le contenu ne peut être vérifié (trop haut, trop loin, dans une cavité...)</option>
															<option value="15">15 - Adulte transportant un sac fécal.</option>
															<option value="16">16 - Adulte transportant de la nourriture pour les jeunes durant sa période de nidifcation</option>
															<option value="17">17 - Coquilles d'oeufs éclos</option>
															<option value="18">18 - Nid vu avec un adulte couvant</option>
															<option value="19">19 - Nid contenant des oeufs ou des jeunes (vus ou entendus)</option>
														</optgroup>
													</select>
													
												</div>
											</div>
											<ul id="laves" class="list-unstyled font12 mt-1"></ul>
										</fieldset>
										<?php
									}
									?>
									<fieldset>
										<legend class="legendesaisie">Localisation (précisez au besoin)</legend>
										<div class="form-row">
											<div class="col">
												<input type="text" class="form-control form-control-sm" id="commune" placeholder="Commune">
											</div>
											<div class="col">
												<input type="text" class="ml-2 form-control form-control-sm" id="site" placeholder="Site">
											</div>
											<div class="col">
												<input type="text" class="ml-2 form-control form-control-sm" id="sitee" name="sitee" placeholder="Sur les sites contenant le mot">
											</div>
										</div>
										<ul id="lloca" class="list-unstyled font12 mt-1"></ul>	
										<p class="mt-2 mb-0">
											En sélectionnant la couche des mailles et en cliquant sur les mailles désirées<br />
											En dessinant sur la carte (cliquez sur l'icone polygone <img src="../dist/img/poly.png" width="20" height="19"> se trouvant sur la carte)
										</p>
										<div class="form-inline mt-2">
											<label for="rayon">En indiquant une distance (en km)</label>
											<input type="number" class="form-control form-control-sm ml-2 mr-2" id="rayon" name="rayon" min="0" max="20" value="" pattern="^\d*">
											et cliquez sur la carte.
										</div>								
									</fieldset>
									<fieldset class="mt-2">
										<legend class="legendesaisie">Date ou intervalle de date ou une décade (précisez au besoin)</legend>
										<div class="form-row">
											<div class="col">
												<input type="text" class="form-control form-control-sm" id="date" name="date" pattern="\d{1,2}/\d{1,2}/\d{4}" placeholder="Du">
											</div>
											<div class="col">
												<input type="text" class="form-control form-control-sm" id="date2" name="date2" pattern="\d{1,2}/\d{1,2}/\d{4}" placeholder="au">
											</div>
											<div class="col">
												<input type="text" class="form-control form-control-sm" id="dates" name="dates" pattern="\d{1,2}/\d{1,2}/\d{4}" placeholder="Ou saisie du">
											</div>
											<div class="col">
												<input type="text" class="form-control form-control-sm" id="dates2" name="dates2" pattern="\d{1,2}/\d{1,2}/\d{4}" placeholder="au">
											</div>
											<div class="form-group col">
												<select id="decade" name="decade" class="form-control form-control-sm">
													<option value="NR">--Ou décade - Choisir--</option>
													<option value="Ja1">Janvier 1</option><option value="Ja2">Janvier 2</option><option value="Ja3">Janvier 3</option>
													<option value="Fe1">Février 1</option><option value="Fe2">Février 2</option><option value="Fe3">Février 3</option>
													<option value="Ma1">Mars 1</option><option value="Ma2">Mars 2</option><option value="Ma3">Mars 3</option>
													<option value="Av1">Avril 1</option><<option value="Av2">Avril 2</option><option value="Av3">Avril 3</option>
													<option value="M1">Mai 1</option><option value="M2">Mai 2</option><option value="M3">Mai 3</option>
													<option value="Ju1">Juin 1</option><option value="Ju2">Juin 2</option><option value="Ju3">Juin 3</option>
													<option value="Jl1">Juillet 1</option><option value="Jl2">Juillet 2</option><option value="Jl3">Juillet 3</option>
													<option value="A1">Août 1</option><option value="A2">Août 2</option><option value="A3">Août 3</option>
													<option value="S1">Septembre 1</option><option value="S2">Septembre 2</option><option value="S3">Septembre 3</option>
													<option value="O1">Octobre 1</option><option value="O2">Octobre 2</option><option value="O3">Octobre 3</option>
													<option value="N1">Novembre 1</option><option value="N2">Novembre 2</option><option value="N3">Novembre 3</option>
													<option value="D1">Décembre 1</option><option value="D2">Décembre 2</option><option value="D3">Décembre 3</option>
												</select>
											</div>
										</div>
									</fieldset>
									<fieldset>
										<legend class="legendesaisie">Habitats (précisez au besoin)</legend>
										<div class="form-group row">
											<div class="col-sm-12 col-lg-6">
												<select id="habitat" name="habitat" class="form-control form-control-sm">
													<option value="NR">-- choisir un habitat au besoin --</option>
													<?php
													foreach($habitat as $n)
													{
														?><option value="<?php echo $n['lbcode'];?>" ><?php echo $n['lbcode'];?> - <?php echo $n['lbhabitat'];?></option><?php
													}
													?>
												</select>
											</div>
										</div>
									</fieldset>
									<?php
									if(isset($tabstat) || isset($rjson_obser['indice'])) 
									{
										?>
										<fieldset>
											<legend class="legendesaisie"><?php echo $legstat;?></legend>
											<div class="form-row">
												<?php
												if(isset($tabstat)) 
												{
													?>
													<div class="col">
														<select id="statut" name="statut" class="form-control form-control-sm">
															<option value="NR">-- Statuts - choisir au besoin --</option>
															<?php
															foreach($tabstat as $n)
															{
																?><option data-type="<?php echo $n['type'];?>" value="<?php echo $n['id'];?>"><?php echo $n['lib'];?></option><?php
															}
															?>
														</select>
														<ul id="lstatut" class="list-unstyled font12 mt-1"></ul>
													</div>
													<div class="col" id="collr">
														<select name="lr" id="lr" class="form-control form-control-sm">
															<option value="NR">-- choisir au besoin --</option>
															<option value="CR">CR - En danger critique</option>
															<option value="EN">EN - En danger</option>
															<option value="VU">VU - Vulnérable</option>
															<option value="NT">NT - Quasi menacé</option>
														</select>
													</div>
													<?php
												}
												if(isset($rjson_obser['indice'])) 
												{
													?>
													<div class="col">
														<select id="indice" name="indice" class="form-control form-control-sm">
															<option value="NR">-- Indices - choisir au besoin --</option>
															<option value="E">Exceptionnel</option>
															<option value="TR">Très rare</option>
															<option value="R">Rare</option>
															<option value="AR">Assez rare</option>
															<option value="AC">Assez commun</option>
															<option value="C">Commun</option>
															<option value="CC">Très commun</option>
														</select>
														<ul id="lindice" class="list-unstyled font12 mt-1"></ul>
													</div>
													<?php
												}
												?>
											</div>
										</fieldset>
										<?php
									}
									?>
									<fieldset class="mt-2">
										<legend class="legendesaisie">Autre (précisez au besoin)</legend>
										<div class="form-row">
											<div class="col">
												<select name="vali" class="form-control form-control-sm">
													<option value="NR">-- Validation - Choisir au besoin -</option>													
													<option value="1">Certain - très probable</option>
													<option value="2">Probable</option>
													<option value="3">Douteux</option>
													<option value="4">Invalide</option>
													<option value="5">Non réalisable</option>
													<option value="6">Non évalué</option>
													<optgroup label="Plus (regroupement)">
													<option value="8">Certain - très probable et probable</option>
													<option value="9">Douteux et Invalide</option>
												</select>
											</div>
											<div class="col">
												<div class="custom-control custom-checkbox custom-control-inline ml-2">
													<input name="photo" id="photo" type="checkbox" class="custom-control-input">
													<label class="custom-control-label" for="photo">Avec photo</label>
												</div>
												<div class="custom-control custom-checkbox custom-control-inline ml-2">
													<input name="son" id="son" type="checkbox" class="custom-control-input">
													<label class="custom-control-label" for="son">Avec son</label>
												</div>
											</div>
											<div class="col">
												<select id="etude" name="etude" class="form-control form-control-sm">
													<option value="0">Etude - Choisir au besoin</option>
													<?php
													foreach($etude as $n)
													{
														?><option value="<?php echo $n['idetude'];?>"><?php echo $n['etude'];?></option><?php
													}
													?>
												</select>
											</div>
											<div class="col">
												<select id="proto" name="proto" class="form-control form-control-sm">
													<option value="0">Protocole - Choisir au besoin</option>
													<?php
													foreach($protocole as $key => $n)
													{
														?><option value="<?php echo $n;?>"><?php echo $key;?></option><?php
													}
													?>
												</select>
											</div>
										</div>
									</fieldset>
									<div class="form-inline mt-3">
										<button type="submit" class="btn btn-sm btn-success" id="BttV">Voir les observations</button>
										<button type="submit" class="ml-3 btn btn-sm btn-success" id="BttS">Liste des espèces</button>
									</div>
									<?php
									if($droit == 'oui')
									{
										?>
										<div class="form-inline mt-2">
											<button type="button" class="btn btn-sm btn-success" id="BttE">Exporter</button>
											<button type="button" class="ml-3 btn btn-sm btn-success" id="BttG">Exporter fichier Geojson</button>
											<a class="ml-3" id="dlink">Cliquer pour télécharger le fichier</a>
										</div>
										<input id="droit" type="hidden" value="oui"/>
										<?php
									}
									else
									{
										?>
										<div class="form-inline mt-2">
											<button type="button" class="btn btn-sm btn-success" id="BttE">Exporter</button>
											<button type="button" class="ml-3 btn btn-sm btn-success" id="BttG">Exporter fichier Geojson</button>
											<a class="ml-3" id="dlink">Cliquer pour télécharger le fichier</a>
										</div>
										<?php										
									}
									?>
									<input id="choixtax" name="choixtax" type="hidden"/><input id="choixloca" name="choixloca" type="hidden"/><input id="rchoixtax" name="rchoixtax" type="hidden"/><input id="rchoixloca" name="rchoixloca" type="hidden"/><input id="idobseror" type="hidden" value="<?php echo $idobser;?>"/><input id="observateur" type="hidden" value="<?php echo $observateur;?>"/>
									<input id="rindice" name="rindice" type="hidden"/><input id="raves" name="raves" type="hidden"/><input id="rstatut" name="rstatut" type="hidden"/><input id="rlr" name="rlr" type="hidden"/><input id="d" name="d" type="hidden"/>
									<input id="cperso" type="hidden" value="<?php echo $perso;?>"/><input id="voir" type="hidden" value="<?php echo $voir;?>"/><input id="poly" name="poly" type="hidden"/><input id="latc" name="lat" type="hidden"/><input id="lngc" name="lng" type="hidden"/><input id="page" name="page" type="hidden" value="1"/><input id="observa" name="observa" type="hidden" value="<?php echo $nomvar;?>"/>								
								</form>
								<input id="Bt" type="hidden"><input id="choixmort" type="hidden" value="<?php echo $choixmort;?>"><input id="couche" type="hidden" value="<?php echo $couche;?>">
								<div id="mes" class="mt-2"></div>							
							</div>
							<div class="col-md-5 col-lg-5">
								<div id="carte" class="cartefiche"></div>
							</div>
						</div>
					</div>
					<div id="listeobs" class="p-3">
						<div class="mt-2"><p class="text-warning text-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement des données...</p></div>
					</div>
					<div class="row mb-1 p-3" id="afpage">					
						<div class="col-md-12 col-lg-12 text-center">
							<div id="pagination" class="float-right"></div>
							<button class="btn color1_bg" type="button" id="bttrhaut"><i class="fa fa-arrow-up blanc"></i></button>
						</div>
					</div>
					<?php
				}
				else
				{
					?><p class="p-2">Vous avez aucune observation dans la base</p><?php					
				}
				?>				
			</div>
		</div>
	</div>
	<input id="lat" type="hidden" value="<?php echo $rjson_emprise['lat'];?>"/><input id="lng" type="hidden" value="<?php echo $rjson_emprise['lng'];?>"/><input id="dep" type="hidden" value="<?php echo $dep;?>"/>
</section>
<div class="modal fade" id="fiche">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">				
				<h4 class="modal-title"></h4>
				<span class="lienidobs ml-auto mr-3"></span>							
				<button type="button" class="close" data-dismiss="modal">&times;</button>				
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
							<h5>Liste des espèces</h5>
							<div id="listefiche"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="obs">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"></h4>
				<?php
				if(isset($_SESSION['idmembre']))
				{
					?><span class="modobs ml-auto mr-3"></span><?php
				}
				?>
				<button type="button" class="close" data-dismiss="modal">&times;</button>				
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-7">
							<span class="lienidobs float-right"></span>
							<h5>Informations sur l'observation n° <span class="obsidobs"></span></h5>
							<p>
								<span class="diffcdref"></span><br />
								<span class="obsdatefr"></span> - <span class="obsfloutage"></span><br /><br />
								<span class="obsobservateur"></span>
							</p>
							<h6>Détail de l'observation</h6>
							<p>
								<span class="obsligne"></span><br />
								<span class="obsdeterminateur"></span>								
							</p>
							<div class="row obsphoto popup-gallery"></div>
							<div class="obscommentaire"></div>
							<?php 
							if(isset($_SESSION['idmembre']))
							{
								?>
								<form>
									<div class="form-group">
										<label for="commentaire">Ajouter un commentaire</label>
										<textarea class="form-control" id="commentaire"></textarea>
									</div>
									<div class="form-group">
										<button type="button" id="BttVcom" class="btn btn-success" data-dismiss="modal">Envoyer</button>
									</div>
									<input id="idobscom" type="hidden"/><input id="idmcom" type="hidden" value="<?php echo $_SESSION['idmembre'];?>"/><input id="idmor" type="hidden"/>
								</form>								
								<?php
							}
							?>
						</div>
						<div class="col-md-5">
							<div id="mapobser"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>