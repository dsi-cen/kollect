<section class="fiche">
	<div class="row">		
		<div class="col-lg-12 col-md-12 mt-3">
			<p class="color1">
				<?php
				if($rang == 'ES')
				{
					?>
					<a class="color1" href="index.php?module=famille&amp;action=famille&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $taxon['cdnomf'];?>"><?php echo $famille;?></a>
					<?php
					if(isset($sfamille) && $sfamille['cdnom'] != '')
					{
						?>> <a class="color1" href="index.php?module=famille&amp;action=sfamille&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $sfamille['cdnom'];?>"><?php echo $sfamille['sousfamille'];?></a> ><a class="font-bold color1" href="index.php?module=fiche&amp;action=ficheg&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $cdnomg;?>"> <i><?php echo $genre;?></i></a><?php
					}
					else
					{
						?>><a class="font-bold color1" href="index.php?module=fiche&amp;action=ficheg&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $cdnomg;?>"> <i><?php echo $genre;?></i></a><?php
					}					
				}
				elseif($rang == 'SSES')
				{
					?>
					<a class="color1" href="index.php?module=famille&amp;action=famille&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $taxon['cdnomf'];?>"><?php echo $famille;?></a>
					<?php
					if(isset($sfamille))
					{
						?>> <a class="color1" href="index.php?module=famille&amp;action=sfamille&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $sfamille['cdnom'];?>"><?php echo $sfamille['sousfamille'];?></a> ><a class="color1" href="index.php?module=fiche&amp;action=ficheg&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $cdnomg;?>"> <i><?php echo $genre;?></i></a> ><a class="font-bold color1" href="index.php?module=fiche&amp;action=fiche&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $cdnomes;?>"> <?php echo $nomes;?></a><?php
					}
					else
					{
						?>><a class="color1" href="index.php?module=fiche&amp;action=ficheg&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $cdnomg;?>"> <i><?php echo $genre;?></i></a> ><a class="font-bold color1" href="index.php?module=fiche&amp;action=fiche&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $cdnomes;?>"> <?php echo $nomes;?></a><?php
					}
				}
				if (isset($gen1) && !empty($gen1))
				{
					?> -  <?php echo $gen1;?><?php
				}
				if (isset($gen2) && !empty($gen2))
				{
					?> -  <?php echo $gen2;?><?php
				}
				?>
			</p>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4 col-xs-12 col-lg-4 col-xl-3">
			<div class="card border-0">
				<div class="card-header color1_bg blanc p-2">
					<?php
					if(isset($nomprecedent) && $nomprecedent != '')
					{
						?><a title="Fiche de <?php echo $nomprecedent;?>" class="float-left mr-1" href="index.php?module=fiche&amp;action=fiche&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $esprecedente;?>"><i class="fa fa-angle-left fa-2x color3"></i></a><?php
					}
					if(isset($nomsuivant) && $nomsuivant != '')
					{
						?><a title="Fiche de <?php echo $nomsuivant;?>" class="float-right ml-1" href="index.php?module=fiche&amp;action=fiche&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $essuivante;?>"><i class="fa fa-angle-right fa-2x color3"></i></a><?php
					}
					?>					
					<h1 class="h4">
						<?php
						if($afflatin == 'oui')
						{
							?><i><?php echo $nom;?></i> <span class="xsmall"><?php echo $inventeur;?></span><?php
						}
						else
						{
							if(!empty($nomfr))
							{
								?><?php echo $nomfr;?><?php
							}
							?><small><i> <?php echo $nom;?></i></small> <span class="xsmall"><?php echo $inventeur;?></span><?php
						}
						?>
					</h1>					
				</div>
				<div class="card-body">
					<?php
					if($ouiphoto > 0)
					{
						?>
						<div id="carousel" class="carousel slide text-center" data-ride="carousel">
							<ol class="carousel-indicators">
								<?php
								$i = -1;
								foreach($photo as $n)
								{
									$class = (++$i == 0) ? 'class="active"' : '';
									?><li data-target="#carousel" data-slide-to="<?php echo $i;?>" <?php echo $class;?>></li><?php
								}
								?>
							</ol>
							<div class="carousel-inner popup-gallery">
								<?php									
								$i = -1;
								foreach($photo as $n)
								{
									$class = (++$i == 0) ? 'carousel-item active' : 'carousel-item';
									?>
									<div class="<?php echo $class;?>">
										<a href="../photo/P800/<?php echo $n['observatoire'];?>/<?php echo $n['nomphoto'];?>.jpg" title="<?php echo $n['observateur'];?> - <?php echo $n['datefr'];?>">
											<img alt="<?php echo $nom;?>" src="../photo/P400/<?php echo $n['observatoire'];?>/<?php echo $n['nomphoto'];?>.jpg" class="img-fluid" style="max-height:235px;margin:0px auto;">
										</a>
									</div>
									<?php
								}
								?>											
							</div>
						</div>
						<br />
						<?php
					}
					else
					{
						?><div class="text-center"><img src="../dist/img/pasimage.png" class="img-fluid" width="200" height="150"></div><?php
					}
					?>
					<div class="">
						<a target="_blank" title="Twitter" href="https://twitter.com/share?url=<?php echo urlencode($url);?>&text=<?php echo $titre;?>" rel="nofollow" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=400,width=700');return false;"><img src="../dist/img/twitter_icon.png" alt="Twitter" width="20" height="20" /></a>
						<a target="_blank" title="Facebook" href="https://www.facebook.com/sharer.php?u=<?php echo urlencode($url);?>&t=<?php echo $titre;?>" rel="nofollow" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=500,width=700');return false;"><img src="../dist/img/facebook_icon.png" alt="Facebook" width="20" height="20" /></a>
						<a target="_blank" title="Google +" href="https://plus.google.com/share?url=<?php echo $url;?>&hl=fr" rel="nofollow" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=450,width=650');return false;"><img src="../dist/img/gplus_icon.png" alt="Google Plus" width="20" height="20" /></a>
						<a target="_blank" title="Linkedin" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode($url);?>&title=<?php echo $titre;?>" rel="nofollow" onclick="javascript:window.open(this.href, '','menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=450,width=650');return false;"><img src="../dist/img/linkedin_icon.png" alt="Linkedin" width="20" height="20" /></a>
						<a title="Envoyer par mail" href="mailto:?subject=<?php echo $titre;?>&body=<?php echo urlencode($url);?>" rel="nofollow"><img src="../dist/img/email_icon.png" alt="email" width="20" height="20"/></a>
						<a class="float-right" href="http://inpn.mnhn.fr/espece/cd_nom/<?php echo $id; ?>" title="Accéder à la fiche de l'INPN"><img src="../dist/img/inpn.png" width="50" height="18" alt="logo INPN"/></a>
					</div>
				</div>
				<div class="card-footer color1_bg blanc p-0">
					<div class="row text-center">
						<div class="col-md-4 border-right p-1">							
							<span class="h5"><?php echo $nbobs;?></span><br />
							<span>donnée(s)</span>							
						</div>
						<div class="col-md-4 border-right p-1">
							<span class="h5"><?php echo $nbcom;?></span><br />
							<span>commune(s)</span>
						</div>
						<div class="col-md-4 p-1">
							<span class="h5"><?php echo $nbmaille;?></span><br />
							<?php
							if($emprise['utm'] == 'non')
							{
								?><span>maille(s) (Lamb93)</span><?php
							}
							else
							{
								?><span>maille(s) (UTM)</span><?php
							}
							?>
						</div>
					</div>
					<div class="row m-0 border-top text-center">
						<div class="col-md-6 border-right p-1">
							<span class="h5"><?php echo $couverture;?> %</span><br />
							<span>territoire maillé</span>
						</div>
						<div class="col-md-6 p-1">
							<span class="h5"><?php echo $indice;?></span><br />
							<span><?php echo $libindice;?></span>
							<?php
							if($indice != 'NC')
							{
								?><a class="blanc" href="index.php?module=indice&amp;action=indice&amp;d=<?php echo $nomvar;?>"><i class="fa fa-info-circle" title="info sur le calcul des indices"></i></a><?php							
							}
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="card card-body">				
				<?php
				if ($afflatin == 'oui')
				{
					if (!empty($nomfr))
					{
						?>
						<h2 class="h5">Nom français : </h2>
						<p class="font-weight-bold mb-0"><?php echo $nomfr;?><p>
						<?php
					}
				}
				if (isset($nbsses) && $nbsses >= 1)
				{
					?>
					<h2 class="h5">Sous espèce(s) : </h2>
					<p>
						<?php 
						foreach ($soussp as $n) 
						{ 
							if ($afflatin == 'oui')
							{
								?><a href="index.php?module=fiche&amp;action=fiche&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $n['cdnom'];?>"><i><?php echo $n['nom'];?></i> <?php echo $n['auteur'];?></a><br /><?php 
							}
							else
							{
								?><a href="index.php?module=fiche&amp;action=fiche&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $n['cdnom'];?>"><?php echo $n['nomvern'];?> <i><?php echo $n['nom'];?></i></a><br /><?php
							}								
						} 
						?>							
					</p>
					<?php 
				}
				if ($nbsyno >= 1)
				{
					?>
					<h2 class="h5">Synonyme(s) : </h2>
					<p>
						<?php 
						foreach ($synonyme as $n) 
						{ 
							?><i><?php echo $n['nom'];?></i> <?php echo $n['auteur'];?><br /><?php 
						} 
						?>
					</p>					
					<?php 
				}				
				?>				
			</div>
		</div>
		<!-- Onglets de la fiche -->
		<div class="col-md-8 col-lg-8 col-xs-12 col-xl-9">
			<div class="row">	
				<div class="col-sm-1 p-0">			
					<ul class="nav nav-tabs tabs-left flex-column color4_bg" id="onglet">
						<li class="nav-item"><a class="nav-link active" href="#general" data-toggle="tab" data-id="general" title="Synthèse"><i class="pe-7s-look fa-2x"></i></a></li>
						<?php
						if($sensible < 3 || $emprise['emprise'] == 'fr')
						{
							?><li class="nav-item"><a class="nav-link" href="#carto" data-toggle="tab" data-id="carto" title="Atlas"><i class="pe-7s-map-marker fa-2x"></i></a></li><?php
						}
						if($sensible == '' || $sensible == 0)
						{
							?><li class="nav-item"><a class="nav-link" href="#cartoleaflet" data-toggle="tab" data-id="cartoleaflet" title="Cartographie"><i class="pe-7s-map fa-2x"></i></a></li><?php
						}
						if($emprise['biogeo'] == 'oui' && $sensible <= 2)
						{
							?><li class="nav-item"><a class="nav-link" href="#biogeo" data-toggle="tab" data-id="biogeo" title="Biogéographie"><i class="pe-7s-graph fa-2x"></i></a></li><?php
						}
						if($emprise['emprise'] != 'fr' && $aves == 'oui' && $sensible <= 2)
						{
							?><li class="nav-item"><a class="nav-link" href="#nicheur" data-toggle="tab" data-id="nicheur" title="Nicheur"><i class="pe-7s-keypad fa-2x"></i></a></li><?php
						}
						if(!empty($statut))
						{
							?><li class="nav-item"><a class="nav-link" href="#statuts" data-toggle="tab" data-id="statuts" title="Statuts"><i class="pe-7s-note2 fa-2x"></i></a></li><?php
						}
						?>
						<li class="nav-item"><a class="nav-link" href="#phenologie" data-toggle="tab" data-id="phenologie" title="Phénologie"><i class="pe-7s-graph3 fa-2x"></i></a></li>
						<li class="nav-item"><a class="nav-link" href="#observateur" data-toggle="tab" data-id="observateur" title="Observateurs"><i class="pe-7s-users fa-2x"></i></a></li>
						<li class="nav-item"><a class="nav-link" href="#infosp" data-toggle="tab" data-id="infosp"><i class="pe-7s-info fa-2x"></i></a></li>
						<?php
						if($ouiphoto > 0)
						{
							?><li class="nav-item"><a class="nav-link" href="#photo" data-toggle="tab" data-id="photo"><i class="pe-7s-camera fa-2x"></i></a></li><?php
						}
						if(!empty($infosp))
						{
							?><li class="nav-item"><a class="nav-link" href="#blocinfo" data-toggle="tab" data-id="blocinfo"><i class="pe-7s-info fa-2x"></i></a></li><?php
						}
						?>
					</ul>
				</div>
				<div class="col-sm-11 p-0">
					<div class="tab-content hauteurfiche">
						<div class="tab-pane fade show active" id="general" role="tabpanel">
							<h2 class="h5">Synthèse</h2>
							<hr />
							<div class="row">
								<div class="col-sm-6">									
									<div class="cartefiche curseurlien border" id="categen">
										<div class="mt-1"><p class="text-warning text-xs-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement des informations...</p></div>
									</div>
								</div>
								<div class="col-sm-6">
									<?php
									if(isset($infosensible))
									{
										?>
										<h3 class="h5"><span class="pe-7s-attention fa-2x text-danger"></span> Espèce sensible</h3>
										<p><?php echo $infosensible;?></p>
										<hr />
										<?php
									}
									?>	
									<div id="statutgen" class="curseurlien">
										<?php
										if(!empty($statut))
										{
											?><h3 class="h5">Statuts</h3><?php
											if(isset($dh))
											{
												?><span class="px-1 border-right-claire"><img src="../dist/img/dh.png" alt="PN" width="29" height="20"/></span><?php
											}
											if(isset($pn))
											{
												?><span class="px-1 border-right-claire"><img src="../dist/img/protect.png" alt="PN" width="20" height="20" title="France" /></span><?php
											}
											if(isset($pr))
											{
												?><span class="px-1 border-right-claire"><img src="../dist/img/protect.png" alt="PN" width="20" height="20" title="Régionale" /></span><?php
											}
											if(isset($pd))
											{
												?><span class="px-1 border-right-claire"><img src="../img/protect.png" alt="PN" width="20" height="20" title="Départementale" /></span><?php
											}
											if(isset($lre))
											{
												?><span class="<?php echo $lrep;?> mx-1"><?php echo $lre;?></span>(E)<?php
											}
											if(isset($lrf))
											{
												?><span class="<?php echo $lrfp;?> mx-1 border-right-claire"><?php echo $lrf;?></span>(F)<?php
											}
											if(isset($lrr))
											{
												?><span class="<?php echo $lrrp;?> mx-1 border-right-claire"><?php echo $lrr;?></span>(R)<?php
											}
											if(isset($lrd))
											{
												?><span class="<?php echo $lrdp;?> mx-1 border-right-claire"><?php echo $lrd;?></span>(D)<?php
											}									
										}
										else
										{
											?><p>Aucun statut de protection et/ou de patrimonialité pour cette espèce.</p><?php
										}
										?>
									</div>
									<hr />
									<div class="minigraph curseurlien border" id="minigraphpheno">
										<div class="mt-1"><p class="text-warning text-xs-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement des informations...</p></div>
									</div>
								</div>
							</div>
						</div>
						<?php
						if($sensible < 3 || $emprise['emprise'] == 'fr')
						{
							?>
							<div class="tab-pane fade" id="carto">
								<figure class="row">
									<div class="col-md-9">
										<h2 class="h5"><span id="titrecarte"><?php echo $titrecarte;?> </span><i><?php echo $nom;?></i> <?php echo $rjson_site['ad2'];?><?php echo $rjson_site['lieu'];?></h2>
										<hr />
										<div id="container" class="cartefiche border">
											<div class="mt-2"><p class="text-warning text-xs-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement de la carte...</p></div>
										</div>							
									</div>
									<div class="col-md-3">
										<h3 class="h6">Choix de la carte</h3>
										<hr />
										<?php
										if($cartecom != 'non')
										{
											?>
											<div class="form-check">
												<label class="custom-control custom-radio">
													<input type="radio" name="choixcarte" id="commune" value="commune" class="custom-control-input" checked> 
													<span class="custom-control-indicator"></span>
													<span class="custom-control-description"><?php echo $cartecom;?></span>
												</label>
											</div>
											<?php
										}
										if($cartemaille != 'non')
										{
											?>
											<div class="form-check">
												<label class="custom-control custom-radio">
													<input type="radio" name="choixcarte" id="maille" value="maille" class="custom-control-input"> 
													<span class="custom-control-indicator"></span>
													<span class="custom-control-description"><?php echo $cartemaille;?></span>
												</label>
											</div>
											<?php
										}
										if($cartemaille5 != 'non')
										{
											?>
											<div class="form-check">
												<label class="custom-control custom-radio">
													<input type="radio" name="choixcarte" id="maille5" value="maille5" class="custom-control-input"> 
													<span class="custom-control-indicator"></span>
													<span class="custom-control-description"><?php echo $cartemaille5;?></span>
												</label>
											</div>									
											<?php
										}
										?>
										<br />
										<figcaption>
											<h3 class="h6">Légende</h3>
											<hr />
											<?php
											if(isset($legende))
											{
												?>
												<p>
													<span id="nouvemp"></span>
													<?php									
													foreach($legende as $n)
													{
														?>
														<i class="fa fa-square fa-lg" style="color:<?php echo $n['couleur'];?>;"></i> <?php echo $n['label'];?><br />
														<?php
													}
													?>
												</p>
												<?php
											}
											?>
										</figcaption>								
									</div>
								</figure>
							</div>
							<?php
						}
						if($sensible == '' || $sensible == 0)
						{
							?>
							<div class="tab-pane fade" id="cartoleaflet">
								<h2 class="h5">Cartographie de <i><?php echo $nom;?></i><small class="text-muted"> (uniquement pour les observations à diffusion localisée)</small></h2>
								<hr />
								<div id="mapleaflet" class="cartefiche">
									<div class="mt-1"><p class="text-warning text-xs-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement des données...</p></div>
								</div>
							</div>
							<?php
						}
						if($emprise['biogeo'] == 'oui' && $sensible <= 2)
						{
							?>
							<div class="tab-pane fade" id="biogeo">
								<span class="float-xs-right"><a href="https://inpn.mnhn.fr/informations/biodiversite/france"><i class="fa fa-info-circle fa-lg text-info"></i></a>&nbsp;&nbsp;&nbsp;<a class="image-popup-no-margins" href="../dist/img/biogeo.png"><i class="pe-7s-map fa-lg text-info"></i></a></span>
								<h2 class="h5">Répartition des données par secteur biogéographique</h2>
								<hr />					
								<div class="row">
									<div class="col-md-6 col-xs-12">
										<div id="cartebiogeo" class="cartefiche">
											<div class="mt-1"><p class="text-warning text-xs-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement de la carte...</p></div>
										</div>							
									</div>
									<div class="col-md-6 col-xs-12">
										<h3 class="h6">Nombre d'observations par secteur</h3>
										<div id="graphebiogeo" class="minigraph border">
											<div class="mt-1"><p class="text-warning text-xs-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement du graph...</p></div>
										</div>
										<br />
										<h3 class="h6"><span id="titregraphbio">Phénologie par secteur (cliquer sur une zone de la carte)</span></h3>
										<div id="graphephenobio" class="minigraph border"></div>								
									</div>						
								</div>
							</div>
							<?php					
						}
						if($emprise['emprise'] != 'fr' && $aves == 'oui' && $sensible <= 2)
						{
							?>
							<div class="tab-pane fade" id="nicheur">
								<h2 class="h5">Statut nicheur - Test</h2>
								<hr />
								<p><b>A revoir couleur / taille </b>A voir : proposer sur année en cours, année précédente, les classes d'années de la carto...</p> 
								<figure>
									<div id="cartenicheur" class="cartefiche">
										<div class="mt-1"><p class="text-warning text-xs-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement du graph...</p></div>
									</div>							
								</figure>
							</div>
							<?php
						}
						if(!empty($statut))
						{
							?>
							<div class="tab-pane fade" id="statuts" role="tabpanel">
								<h2 class="h5">Statuts de <i><?php echo $nom;?></i></h2>
								<hr />
								<div id="listestatut"></div>
							</div>							
							<?php
						}
						?>						
						<div class="tab-pane fade" id="phenologie" >
							<h2 class="h5">Nombre d'observations par décade</h2>
							<hr />
							<figure>
								<div id="graphpheno" class="cartefiche">
									<div class="mt-1"><p class="text-warning text-xs-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement du graph...</p></div>
								</div>
							</figure>					
						</div>
						<div class="tab-pane fade" id="observateur">
							<h2 class="h5">Liste des observateurs <span id="nbobser"></span> de <i><?php echo $nom;?></i></h2>
							<div id="listeobser">
								<div class="mt-1"><p class="text-warning text-xs-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement de la liste...</p></div>
							</div>
						</div>
						<div class="tab-pane fade" id="infosp" role="tabpanel">
							<h2 class="h4">Informations diverses</h2>
							<hr />
							<div class="row">
								<div class="col-md-6">
									<h3 class="h5">Observation "la plus importante en nombre"</h3>
									<p id="infomax"></p>
									<h3 class="h5">Date de la dernière observation</h3>
									<p id="derniere"></p>
								</div>
								<div class="col-md-6">
									<h3 class="h5">Dates extrêmes</h3>
									<p><span id="extrememin"></span> - <span id="extrememax"></span></p>
									<h3 class="h5">Altitude</h3>
									<p><span id="altimin"></span><br /><span id="altimax"></span></p>
								</div>
							</div>					
							<div id="graphenbobs" class=""></div>
						</div>
						<?php
						if($ouiphoto > 0)
						{
							?>
							<div class="tab-pane fade" id="photo">
								<h2 class="h5">Photos de <i><?php echo $nom;?></i></h2>
								<hr />
								<div class="row">
									<div class="col-md-12" id="listebut"></div>
								</div>
								<div class="row mt-1 photo-grid popup-gallery" id="listephoto"></div>
							</div>
							<?php
						}
						if(!empty($infosp))
						{
							?>
							<div class="tab-pane fade" id="blocinfo">
								<h2 class="h5">Répartition</h2>
								<p>Test avec glossaire</p> 
								<div id="repartition"></div>
							</div>
							<?php					
						}	
						?>
					</div>
				</div>
			</div>
		</div>		
	</div>
	<input id="choixcarte" type="hidden" value="<?php echo $choixcarte;?>"/><input id="emprise" type="hidden" value="<?php echo $emprise['emprise'];?>"/><input id="cdnom" type="hidden" value="<?php echo $id;?>"/><input id="utm" type="hidden" value="<?php echo $emprise['utm'];?>"/><input id="contour2" type="hidden" value="<?php echo $emprise['contour2'];?>"/>
	<input id="nomvar" type="hidden" value="<?php echo $nomvar;?>"/><input id="rangsp" type="hidden" value="<?php echo $rangssses;?>"/><input id="nom" type="hidden" value="<?php echo $nom;?>"/>
	<input id="idc" type="hidden"/><input id="typec" type="hidden"/><input id="nomc" type="hidden"/><input id="sensible" type="hidden" value="<?php echo $sensible;?>"/>
</section>
<div class="modal fade" id="infos">
	<div class="modal-dialog modal-lg">	
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h1 class="modal-title" id="titleinfos"></h1>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-6">
							<h2>Nombre d'observations</h2>
							<br />
							<div id="listenbobs"></div>
						</div>
						<div class="col-md-6">
							<div id="mapdetail"></div>
						</div>
					</div>
				</div>
			</div>
		</div>  
	</div> 
</div>
<div class="modal fade" id="infolr">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Information liste rouge</h4>
			</div>
			<div class="modal-body">
				<h4 class="h5">Espèces éteintes</h4>
				<dl class="row mt-1">
					<dt class="col-sm-1"><span class="EW">EW</span></dt><dd class="col-sm-11">Espèce éteinte à l'état sauvage</dd>
					<dt class="col-sm-1 mt-1"><span class="RE">RE</span></dt><dd class="col-sm-11 mt-1">Espèce disparue de la région considérée</dd>
					
				</dl>
				<h4 class="h5">Espèces menacées de disparition</h4>
				<dl class="row mt-1">
					<dt class="col-sm-1"><span class="CR">CR</span></dt><dd class="col-sm-11">En danger critique (CR* Espèce probablement éteinte)</dd>
					<dt class="col-sm-1 mt-1"><span class="EN">EN</span></dt><dd class="col-sm-11 mt-1">En danger</dd>
					<dt class="col-sm-1 mt-1"><span class="VU">VU</span></dt><dd class="col-sm-11 mt-1">Vulnérable</dd>
				</dl>
				<h4 class="h5">Autres catégories</h4>
				<dl class="row mt-1">
					<dt class="col-sm-1"><span class="NT">NT</span></dt><dd class="col-sm-11">Quasi menacée (espèce proche du seuil des espèces menacées ou qui pourrait être menacée si des mesures de conservation spécifiques n'étaient pas prises)</dd>
					<dt class="col-sm-1"><span class="LC">LC</span></dt><dd class="col-sm-11">Préoccupation mineure (espèce pour laquelle le risque de disparition est faible)</dd>
					<dt class="col-sm-1"><span class="DD">DD</span></dt><dd class="col-sm-11">Données insuffisantes (espèce pour laquelle l'évaluation n'a pas pu être réalisée faute de données suffisantes)</dd>
					<dt class="col-sm-1"><span class="NA">NA</span></dt><dd class="col-sm-11">Non applicable (espèce non soumise à évaluation car (a) introduite dans la période récente ou (b) présente de manière occasionnelle)</dd>
				</dl>				
			</div>
		</div>
	</div>
</div>	