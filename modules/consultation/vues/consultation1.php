<section class="container-fluid mb-3">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<header>
					<ol class="breadcrumb float-right">
						<?php
						if(isset($_GET['perso']) && $_GET['perso'] == 'oui')
						{
							?><li class="breadcrumb-item"><a href="index.php?module=observation&amp;action=observation&amp;perso=oui">Observations</a></li><?php
						}
						else
						{
							?><li class="breadcrumb-item"><a href="index.php?module=observation&amp;action=observation">Observations</a></li><?php
						}
						?>					
						<li class="breadcrumb-item active">Consultation</li>
					</ol>					
					<h1 class="h2 text-xs-center">Consultation des observations</h1>
					<span id="lchoix"></span>
				</header>
				<span id="rchoix" class="text-primary curseurlien">Retour au choix</span>
			</div>
			<div class="card card-body mt-2 p-0">
				<?php
				if(!isset($pasobs))
				{
					?>
					<div id="choixconsult" class="min p-3">
						<div class="row">
							<div class="col-md-6 col-lg-6">
								<!--<button type="button" class="btn btn-info btn-sm" id="BttA"><span id="btn-aide-txt">Aide</span></button>
								<div id="infoaide" class="mt-2">
									<p>
										-> A faire : explication différents boutons etc...
									</p>
								</div>-->
								<form id="form"> 
									<div class="form-inline">
										<label class="custom-control custom-checkbox">
											<?php
											if($voir == 'non')
											{
												?><input id="perso" type="checkbox" class="custom-control-input" checked><?php										
											}
											else
											{
												?><input id="perso" type="checkbox" class="custom-control-input"><?php
											}
											?>							
											<span class="custom-control-indicator"></span>
											<span class="custom-control-description">Uniquement vos observations</span>
										</label>
										<?php
										if($voir == 'non')
										{
											?><input id="idobser" type="hidden" value="<?php echo $idobser;?>"/><?php
										}
										else
										{
											?>
											<label for="obser" class="mr-2">Observateur</label>
											<input type="text" class="form-control form-control-sm" id="obser" size=30>
											<input id="idobser" type="hidden"/>
											<?php
										}
										?>
									</div>
									<div class="form-inline">
										<label for="orga" class="mr-2">Organisme</label>
										<select id="orga" class="form-control form-control-sm">
											<option value="NR">-- choisir au besoin --</option>
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
									<fieldset>
										<legend class="legendesaisie">Espèce</legend>
										<div class="form-inline">
											<label for="observa" class="mr-2">Observatoire</label>
											<select id="observa" class="form-control form-control-sm">
												<option value="NR">-- choisir au besoin --</option>
												<?php
												foreach($menuobservatoire as $n)
												{
													?>
													<option value="<?php echo $n['var'];?>"><?php echo $n['nom'];?></option>
													<?php
												}
												?>
											</select>
											<label for="taxon" class="ml-3 mr-2">Ou une espèce</label>
											<input type="text" class="form-control form-control-sm" id="taxon">
											<i class="fa fa-plus text-success curseurlien ml-3" title="Ajouter une espèce" id="imgplustaxon"></i>
											<!--<label for="etatbio" class="ml-3 mr-2">Etat biologique</label>
											<select id="etatbio" class="form-control form-control-sm">
												<option value="NR">Tous</option>
												<option value="2">Observé vivant</option>
												<option value="3">Trouvé mort</option>
												<option value="1">Non renseigné</option>
												<option value="0">Inconu</option>
											</select>-->
										</div>
										<p class="mt-2 mb-0" id="Vchoixes">Votre choix : <output class="font-weight-bold" id="inchoixtax"></output> <i id="supchoixes" class="fa fa-trash curseurlien text-danger"></i></p>
									</fieldset>
									<fieldset class="mt-1">
										<legend class="legendesaisie">Localisation (précisez au besoin)</legend>
										<div class="form-inline">
											<label for="commune" class="">Sur une commune</label>
											<input type="text" class="ml-2 form-control form-control-sm" id="commune" size="40">
											<i class="fa fa-plus text-success curseurlien ml-3" title="Ajouter une commune" id="imgpluscom"></i>
										</div>
										<div class="form-inline mt-2">
											<label for="site" class="">Sur un site</label>
											<input type="text" class="ml-2 form-control form-control-sm" id="site" size="30">
											<i class="fa fa-plus text-success curseurlien ml-3" title="Ajouter un site" id="imgplussite"></i>
										</div>
										<div class="form-inline mt-2">
											<label for="sitee" class="">Sur les sites contenant le mot</label>
											<input type="text" class="ml-2 form-control form-control-sm" id="sitee" size=40>	
										</div>
										<p class="mt-2 mb-0" id="Vchoixloca">Votre choix : <output class="font-weight-bold" id="inchoixloca"></output> <i id="supchoixloca" class="fa fa-trash curseurlien text-danger"></i></p>
										<p class="mt-2 mb-0">En dessinant sur la carte (cliquer sur l'icone polygone se trouvant sur la carte)</p>
										<div class="form-inline mt-2">
											<label for="rayon">En indiquant une distance (en km)</label>
											<input type="number" class="form-control form-control-sm ml-2 mr-2" id="rayon" min="0" max="20" value="" pattern="^\d*">
											et cliquer sur la carte.
										</div>								
									</fieldset>
									<fieldset class="mt-2">
										<legend class="legendesaisie">Date ou intervalle de date (précisez au besoin) ou décade</legend>
										<div class="form-inline">
											<label for="date" class="mr-2">Du</label>
											<input type="text" class="form-control form-control-sm" id="date" pattern="\d{1,2}/\d{1,2}/\d{4}">
											<label for="date2" class="ml-2 mr-2">au</label>
											<input type="text" class="form-control form-control-sm" id="date2" pattern="\d{1,2}/\d{1,2}/\d{4}">
										</div>
										<div class="form-inline mt-2">
											<label for="dates" class="mr-2">Ou saisie du</label>
											<input type="text" class="form-control form-control-sm" id="dates" pattern="\d{1,2}/\d{1,2}/\d{4}">
											<label for="dates2" class="ml-2 mr-2">au</label>
											<input type="text" class="form-control form-control-sm" id="dates2" pattern="\d{1,2}/\d{1,2}/\d{4}">
										</div>
										<div class="form-inline mt-2">
											<label for="decade" class="mr-2">Décade</label>
											<select id="decade" class="form-control form-control-sm">
												<option value="NR">--Choisir--</option>
												<option value="Ja1">Janvier 1</option><option value="Ja2">Janvier 2</option><option value="Ja3">Janvier 3</option>
												<option value="Fe1">Février 1</option><option value="Fe1">Février 1</option><option value="Fe1">Février 1</option>
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
									</fieldset>
									<fieldset class="mt-2">
										<legend class="legendesaisie">Habitats (précisez au besoin)</legend>
										<div class="form-inline">
											A faire
										</div>
									</fieldset>
									<fieldset class="mt-2">
										<legend class="legendesaisie">Autre (précisez au besoin)</legend>
										<div class="form-inline">
											<label for="vali" class="mr-2">Validation</label>
											<select id="vali" class="form-control form-control-sm">
												<option value="NR">-- Toutes --</option>
												<option value="6">Non évalué</option>
												<option value="1">Certain - très probable</option>
												<option value="2">Probable</option>
												<option value="3">Douteux</option>
												<option value="4">Invalide</option>
												<option value="5">Non réalisable</option>
											</select>
											<label class="custom-control custom-checkbox ml-3">
												<input id="photo" type="checkbox" class="custom-control-input">
												<span class="custom-control-indicator"></span>
												<span class="custom-control-description">Avec photo</span>
											</label>
											<label class="custom-control custom-checkbox ml-3">
												<input id="son" type="checkbox" class="custom-control-input">
												<span class="custom-control-indicator"></span>
												<span class="custom-control-description">Avec son</span>
											</label>
											<label for="etude" class="ml-3 mr-2">Etude</label>
												<select id="etude" class="form-control form-control-sm">
													<option value="0">Aucune</option>
													<?php
													foreach($etude as $n)
													{
														?><option value="<?php echo $n['idetude'];?>"><?php echo $n['etude'];?></option><?php
													}
													?>
												</select>
										</div>
									</fieldset>
									<div class="form-inline mt-2">
										<button type="button" class="btn btn-success" id="BttV">Voir les observations</button>
										<button type="button" class="ml-3 btn btn-success" id="BttS">Liste des espèces</button>
									</div>
									<?php
									if($droit == 'oui')
									{
										?>
										<div class="form-inline mt-2">
											<button type="button" class="btn btn-success" id="BttE">Exporter</button>
											<button type="button" class="ml-3 btn btn-success" id="BttG">Exporter fichier Geojson</button>
											<a class="ml-3" id="dlink">Cliquer pour télécharger le fichier</a>
										</div>
										<div class="form-inline mt-2">
											<label class="" for="nomfichier">Nommé votre fichier d'export</label>
											<input type="text" class="form-control form-control-sm ml-2" id="nomfichier">
										</div>
										<input id="droit" type="hidden" value="oui"/>
										<?php
									}
									else
									{
										?>
										<div class="form-inline mt-2">
											<button type="button" class="btn btn-success" id="BttE">Exporter</button>
											<button type="button" class="ml-3 btn btn-success" id="BttG">Exporter fichier Geojson</button>
											<a class="ml-3" id="dlink">Cliquer pour télécharger le fichier</a>
										</div>
										<div class="form-inline mt-2">
											<label class="" for="nomfichier">Nommé votre fichier d'export</label>
											<input type="text" class="form-control form-control-sm ml-2" id="nomfichier">
										</div>
										<?php
									}
									?>
									<input id="choixtax" type="hidden"/><input id="choixloca" type="hidden"/><input id="idobseror" type="hidden" value="<?php echo $idobser;?>"/><input id="observateur" type="hidden" value="<?php echo $observateur;?>"/>
									<input id="idsite" type="hidden"/><input id="choixobserva" type="hidden"/><input id="cdnom" type="hidden"/><input id="codecom" type="hidden"/><input id="cperso" type="hidden" value="<?php echo $perso;?>"/>
									<input id="poly" type="hidden"/><input id="latc" type="hidden"/><input id="lngc" type="hidden"/>									
								</form>
								<div id="mes" class="mt-2"></div>							
							</div>
							<div class="col-md-6 col-lg-6">
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