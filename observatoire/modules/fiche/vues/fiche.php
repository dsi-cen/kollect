<section class="container-fluid fiche mb-3">
	<div class="row">		
		<div class="col-lg-12 col-md-12 mt-2">			
			<div class="d-flex justify-content-start">
				<?php
				if(isset($nomprecedent) && $nomprecedent != '')
				{
					?><div class="p-2"><a data-toggle="tooltip" data-placement="right" title="Fiche de <?php echo $nomprecedent;?>" href="index.php?module=fiche&amp;action=fiche&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $esprecedente;?>"><i class="fa fa-angle-left fa-2x color1"></i></a></div><?php
				}
				?>
				<div class="p-2 align-self-center">
					<p class="color1 mb-0">							
						<?php
						if($rang == 'ES')
						{
							?>
							<a class="color1" href="index.php?module=famille&amp;action=famille&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $taxon['cdnomf'];?>"><?php echo $famille;?></a>
							<?php
							if(isset($sfamille) && $sfamille['cdnom'] != '')
							{
								?>> <a class="color1" href="index.php?module=famille&amp;action=sfamille&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $sfamille['cdnom'];?>"><?php echo $sfamille['sousfamille'];?></a> 
								<?php
								if($nbgenre > 1) { ?>><a class="color1" href="index.php?module=fiche&amp;action=ficheg&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $cdnomg;?>"> <i><?php echo $genre;?></i></a><?php }
								else { ?>> <i><?php echo $genre;?></i><?php }						
							}
							else
							{
								if($nbgenre > 1) { ?>><a class="color1" href="index.php?module=fiche&amp;action=ficheg&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $cdnomg;?>"> <i><?php echo $genre;?></i></a><?php }
								else { ?>> <i><?php echo $genre;?></i><?php }
							}					
						}
						elseif($rang == 'SSES')
						{
							?>
							<a class="color1" href="index.php?module=famille&amp;action=famille&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $taxon['cdnomf'];?>"><?php echo $famille;?></a>
							<?php
							if(isset($sfamille))
							{
								?>> <a class="color1" href="index.php?module=famille&amp;action=sfamille&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $sfamille['cdnom'];?>"><?php echo $sfamille['sousfamille'];?></a>
								<?php
								if($nbgenre > 1) { ?>><a class="color1" href="index.php?module=fiche&amp;action=ficheg&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $cdnomg;?>"> <i><?php echo $genre;?></i></a><?php }
								else { ?>> <i><?php echo $genre;?></i><?php }
								?>
								><a class="font-bold color1" href="index.php?module=fiche&amp;action=fiche&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $cdnomes;?>"> <?php echo $nomes;?></a><?php
							}
							else
							{
								if($nbgenre > 1) { ?>><a class="color1" href="index.php?module=fiche&amp;action=ficheg&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $cdnomg;?>"> <i><?php echo $genre;?></i></a><?php }
								else { ?>> <i><?php echo $genre;?></i><?php }
								?>><a class="font-bold color1" href="index.php?module=fiche&amp;action=fiche&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $cdnomes;?>"> <?php echo $nomes;?></a><?php
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
				<?php
				if(isset($nomsuivant) && $nomsuivant != '')
				{
					?><div class="ml-auto p-2"><a data-toggle="tooltip" data-placement="left" title="Fiche de <?php echo $nomsuivant;?>" class="" href="index.php?module=fiche&amp;action=fiche&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $essuivante;?>"><i class="fa fa-angle-right fa-2x color1"></i></a></div><?php
				}
				?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4 col-xs-12 col-lg-4 col-xl-3">
			<div class="card border-0">
				<div class="card-header color1_bg blanc p-2">
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
										<a class="mx-auto" href="../photo/P800/<?php echo $n['observatoire'];?>/<?php echo $n['nomphoto'];?>.jpg" title="<?php echo $n['observateur'];?> - <?php echo $n['datefr'];?>">
											<img alt="<?php echo $nom;?>" src="../photo/P400/<?php echo $n['observatoire'];?>/<?php echo $n['nomphoto'];?>.jpg" class="d-block img-fluid" style="max-height:235px;margin:0px auto;">
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
				<div class="card-footer color1_bg blanc p-0 font13">
					<div class="row text-center">
						<div class="col-md-3 border-right p-1">							
							<?php echo $nbobs;?>				
						</div>
						<div class="col-md-4 border-right p-1">
							<?php echo $nbcom;?>
						</div>
						<div class="col-md-5 p-1">
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
					<div class="row m-0 border-top text-center">						
						<div class="col-md-6 border-right p-1">
							<?php echo $nbmaille10;?>				
						</div>
						<div class="col-md-6 p-1">
							<span class="h5"><?php echo $couverture10;?> %</span><br />
							<span>territoire maillé</span>														
						</div>
					</div>
					<?php
					if(isset($nbmaille5))
					{
						?>
						<div class="row m-0 border-top text-center">						
							<div class="col-md-6 border-right p-1">
								<?php echo $nbmaille5;?>				
							</div>
							<div class="col-md-6 p-1">
								<span class="h5"><?php echo $couverture5;?> %</span><br />
								<span>territoire maillé</span>														
							</div>
						</div>
						<?php
					}
					?>
				</div>
			</div>
			<div class="card card-body font13">				
				<?php
				if($afflatin == 'oui')
				{
					if(!empty($nomfr))
					{
						?>
						<h2 class="h5">Nom français : </h2>
						<p class="font-weight-bold mb-0"><?php echo $nomfr;?><p>
						<?php
					}
				}
				if(isset($nbsses) && $nbsses >= 1)
				{
					?>
					<h2 class="h5">Sous espèce(s) : </h2>
					<p>
						<?php 
						foreach($soussp as $n) 
						{ 
							if($afflatin == 'oui')
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
				if($nbsyno >= 1)
				{
					?>
					<h2 class="h5">Synonyme(s) : </h2>
					<p>
						<?php 
						foreach($synonyme as $n) 
						{ 
							?><i><?php echo $n['nom'];?></i> <?php echo $n['auteur'];?><br /><?php 
						} 
						?>
					</p>					
					<?php 
				}
				if($simi != false)
				{
					?>
					<h2 class="h5">Espèce(s) similaire(s) : </h2>
					<p>
						<?php 
						foreach($simi as $n) 
						{ 
							if($afflatin == 'oui')
							{
								?><a href="index.php?module=fiche&amp;action=fiche&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $n['simi'];?>"><i><?php echo $n['nom'];?></i></a><br /><?php 
							}
							else
							{
								?><a href="index.php?module=fiche&amp;action=fiche&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $n['simi'];?>"><?php echo $n['nomvern'];?> <i><?php echo $n['nom'];?></i></a><br /><?php
							}								
						} 
						?>							
					</p>
					<?php 
				}
				if($nomvar == 'lepido')
				{
					?>
					<h2 class="h5">Lien(s) : </h2>
					<a href="http://www.lepiforum.de/lepiwiki.pl?<?php echo $genre.'_'.$espece;?>">Lepiforum</a>
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
						<li class="nav-item"><a class="nav-link active" href="#general" data-toggle="tab" data-id="general" title="Synthèse"><i class="fa fa-eye fa-lg"></i></a></li>
						<?php
						if($sensible < 3 || $emprise['emprise'] == 'fr')
						{
							?><li class="nav-item"><a class="nav-link" href="#carto" data-toggle="tab" data-id="carto" title="Atlas"><i class="fa fa-clone fa-lg"></i></a></li><?php
						}
						if($sensible == '' || $sensible == 0 || $droit == 'oui')
						{
							?><li class="nav-item"><a class="nav-link" href="#cartoleaflet" data-toggle="tab" data-id="cartoleaflet" title="Cartographie"><i class="fa fa-map-o fa-lg"></i></a></li><?php
						}
						if($emprise['biogeo'] == 'oui' && $sensible <= 2)
						{
							?><li class="nav-item"><a class="nav-link" href="#biogeo" data-toggle="tab" data-id="biogeo" title="Biogéographie"><i class="fa fa-pie-chart fa-lg"></i></a></li><?php
						}
						if($emprise['emprise'] != 'fr' && $aves == 'oui' && $sensible <= 2)
						{
							?><li class="nav-item"><a class="nav-link" href="#nicheur" data-toggle="tab" data-id="nicheur" title="Nicheur"><i class="<?php echo $rjson_obser['icon'];?> fa-lg"></i></a></li><?php
						}
						if(!empty($statut))
						{
							?><li class="nav-item"><a class="nav-link" href="#statuts" data-toggle="tab" data-id="statuts" title="Statuts"><i class="fa fa-file-text-o fa-lg"></i></a></li><?php
						}
						?>
						<li class="nav-item"><a class="nav-link" href="#phenologie" data-toggle="tab" data-id="phenologie" title="Phénologie"><i class="fa fa-bar-chart fa-lg"></i></a></li>
						<li class="nav-item"><a class="nav-link" href="#observateur" data-toggle="tab" data-id="observateur" title="Observateurs"><i class="fa fa-users fa-lg"></i></a></li>
						<li class="nav-item"><a class="nav-link" href="#infosp" data-toggle="tab" data-id="infosp" title="informations diverses"><i class="fa fa-info fa-lg"></i></a></li>
						<?php
						if($ouiphoto > 0)
						{
							?><li class="nav-item"><a class="nav-link" href="#photo" data-toggle="tab" data-id="photo"><i class="fa fa-camera fa-lg"></i></a></li><?php
						}
						if($habitat > 0)
						{
							?><li class="nav-item"><a class="nav-link" href="#habitat" data-toggle="tab" data-id="habitat" title="Habitats"><i class="fe-arb2 fa-lg"></i></a></li><?php
						}
						if(!empty($taxon['info']))
						{
							?><li class="nav-item"><a class="nav-link" href="#blocinfo" data-toggle="tab" data-id="blocinfo"><i class="fa fa-newspaper-o fa-lg"></i></a></li><?php
						}
						if(isset($biblio) && $biblio > 0)
						{
							?><li class="nav-item"><a class="nav-link" href="#biblio" data-toggle="tab" data-id="biblio" title="Bibliographie"><i class="fa fa-book fa-lg"></i></a></li><?php
						}

						/*if($indice != 'NC')
						{
							?><li class="nav-item"><a class="nav-link" href="#indice" data-toggle="tab" data-id="indice">Ind</a></li><?php
						}*/
						?>
                        <li class="nav-item"><a class="nav-link" href="#commentaires" data-toggle="tab" data-id="commentaires" title="Commentaires"><i class="fa fa-pencil-square-o fa-lg"></i></a></li>
					</ul>
				</div>
				<div class="col-sm-11 pl-0">
					<div class="tab-content hauteurfiche">
						<div class="tab-pane fade show active" id="general" role="tabpanel">
							<h2 class="h5">Synthèse</h2>
							<hr />
							<div class="row">
								<div class="col-sm-6">									
									<div class="cartefiche curseurlien border" id="categen">
										<div class="mt-1"><p class="text-warning text-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement des informations...</p></div>
									</div>
								</div>
								<div class="col-sm-6">
									<?php
									if(isset($infosensible))
									{
										?>
										<h3 class="h5"><span class="fa fa-exclamation-triangle fa-lg text-warning"></span> Espèce sensible</h3>
										<?php
										if($droit == 'non')
										{
											?><p><?php echo $infosensible;?></p><?php
										}
										?>
										<hr />
										<?php
									}
									?>	
									<div id="statutgen" class="curseurlien">
										<?php
										if(!empty($statut))
										{
											?><h3 class="h5">Statuts</h3>
											<p>
												<?php
												if(isset($dh))
												{
													?><span class="px-2 border-right-claire"><img src="../dist/img/dh.png" alt="PN" width="29" height="20"/></span><?php
												}
												if(isset($pn))
												{
													?><span class="px-2 border-right-claire"><img src="../dist/img/protect.png" alt="PN" width="20" height="20" title="France" /></span><?php
												}
												if(isset($pr))
												{
													?><span class="px-2 border-right-claire"><img src="../dist/img/protect.png" alt="PN" width="20" height="20" title="Régionale" /></span><?php
												}
												if(isset($pd))
												{
													?><span class="px-2 border-right-claire"><img src="../dist/img/protect.png" alt="PN" width="20" height="20" title="Départementale" /></span><?php
												}
												if(isset($znieff))
												{
													?><span class="px-2 border-right-claire"><img src="../dist/img/znieff.png" alt="Znieff" width="38" height="20" title="Espèce déterminantes Znieff" /></span><?php
												}
                                            if(isset($lrm))
                                            {
                                                ?>
                                                <span class="fa-stack">
														<i class="fa fa-circle fa-stack-2x <?php echo $lrep;?>"></i>
														<i class="fa fa-stack-1x font13 <?php echo $lrep;?>t"><?php echo $lrm;?></i>
													</span>(M)
                                                <?php
                                            }
												if(isset($lre))
												{
													?>
													<span class="fa-stack">
														<i class="fa fa-circle fa-stack-2x <?php echo $lrep;?>"></i>
														<i class="fa fa-stack-1x font13 <?php echo $lrep;?>t"><?php echo $lre;?></i>
													</span>(E)												
													<?php
												}
												if(isset($lrf))
												{
													if($nlrf > 1)
													{
														foreach($tablrfp as $n)
														{
															?>
															<span class="fa-stack">
																<i class="fa fa-circle fa-stack-2x <?php echo $n;?>"></i>
																<i class="fa fa-stack-1x font13 <?php echo $n;?>t"><?php echo $n;?></i>
															</span>(F)
															<?php
														}
													}
													else
													{
														?>
														<span class="fa-stack">
															<i class="fa fa-circle fa-stack-2x <?php echo $lrfp;?>"></i>
															<i class="fa fa-stack-1x font13 <?php echo $lrfp;?>t"><?php echo $lrf;?></i>
														</span>(F)
														<?php
													}
												}
												if(isset($lrr))
												{
													?>
													<span class="fa-stack">
														<i class="fa fa-circle fa-stack-2x <?php echo $lrrp;?>"></i>
														<i class="fa fa-stack-1x font13 <?php echo $lrrp;?>t"><?php echo $lrr;?></i>
													</span>(R)
													<?php													
												}
												if(isset($lrd))
												{
													?>
													<span class="fa-stack">
														<i class="fa fa-circle fa-stack-2x <?php echo $lrdp;?>"></i>
														<i class="fa fa-stack-1x font13 <?php echo $lrdp;?>t"><?php echo $lrd;?></i>
													</span>(D)
													<?php
												}
												if(isset($typea))
												{
													?>
													<span class="px-1 border-right-claire"></span><span class="px-1" title="<?php echo $intypea;?>"><?php echo $typea;?></span>
													<?php
												}
												if(isset($typei))
												{
													?>
													<span class="px-1 border-right-claire"></span><span class="px-1" title="<?php echo $intypei;?>"><?php echo $typei;?></span>
													<?php
												}
												?>
											</p><?php
										}
										else
										{
											?><p>Aucun statut de protection et/ou de patrimonialité pour cette espèce.</p><?php
										}
										?>
									</div>
									<hr />
									<div class="minigraph curseurlien border" id="minigraphpheno">
										<div class="mt-1"><p class="text-warning text-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement des informations...</p></div>
									</div>
									<div>
										<figure>
											<object data="https://inpn.mnhn.fr/cartosvg/couchegeo/repartition/atlas/<?php echo $id;?>/fr_light_l93,fr_light_mer_l93,fr_lit_l93"
												type="image/svg+xml" class="mx-auto d-block" width="100%" height="100%">
											</object>
											<figcaption>Répartition en France métropolitaine - <a class="" href="http://inpn.mnhn.fr/espece/cd_nom/<?php echo $id; ?>" title="Accéder à la fiche de l'INPN"><img src="../dist/img/inpn.png" width="50" height="18" alt="logo INPN"/></a></figcaption>
										</figure>
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
										<h2 class="h5 tleg"><span id="titrecarte"><?php echo $titrecarte;?> </span><?php echo $nomstitre;?> <?php echo $rjson_site['ad2'];?><?php echo $rjson_site['lieu'];?> <small class="font12 text-muted">Données saisies au <?php echo $datejour;?></small></h2>
										<hr />
										<div id="container" class="cartefiche border">
											<div class="mt-2"><p class="text-warning text-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement de la carte...</p></div>
										</div>							
									</div>
									<div class="col-md-3">
										<h3 class="h6">Choix de la carte</h3>
										<hr />
										<?php
										if($cartecom != 'non')
										{
											?>											
											<div class="custom-control custom-radio font13">
												<input type="radio" name="choixcarte" id="commune" value="commune" class="custom-control-input" checked> 
												<label class="custom-control-label" for="commune"><?php echo $cartecom;?></label>
											</div>											
											<?php
										}
										if($cartemaille != 'non')
										{
											?>
											<div class="custom-control custom-radio font13">
												<input type="radio" name="choixcarte" id="maille" value="maille" class="custom-control-input"> 
												<label class="custom-control-label" for="maille"><?php echo $cartemaille;?></label>
											</div>
											<?php
										}
										if($cartemaille5 != 'non')
										{
											?>
											<div class="custom-control custom-radio font13">
												<input type="radio" name="choixcarte" id="maille5" value="maille5" class="custom-control-input"> 
												<label class="custom-control-label" for="maille5"><?php echo $cartemaille5;?></label>
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
										<?php
										if(isset($lepinet) && !empty($lepinet))
										{
											?>
											<h3 class="h6">Répartition française</h3>
											<hr />
											<a href="https://www.lepinet.fr/especes/nation/lep/index.php?id=<?php echo $lepinet;?>"><img src="https://www.lepinet.fr/especes/cartes/carte_<?php echo $lepinet;?>.gif" title="Site Lepinet" class="img-fluid"></a>
											<?php
										}
										?>
									</div>
								</figure>								
							</div>
							<?php
						}
						if($sensible == '' || $sensible == 0 || $droit == 'oui')
						{
							?>
							<div class="tab-pane fade" id="cartoleaflet">
								<h2 class="h5">
									Cartographie - <?php echo $nomstitre;?>
									<?php
									if($droit == 'non')
									{
										?><small class="text-muted"> (uniquement pour les observations à diffusion localisée)</small><?php
									}
									?>
								</h2>
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
								<span class="float-right"><a href="https://inpn.mnhn.fr/informations/biodiversite/france"><i class="fa fa-info-circle fa-lg text-info"></i></a>&nbsp;&nbsp;&nbsp;<a class="image-popup-no-margins" href="../dist/img/biogeo.png"><i class="fa fa-map-o fa-lg text-info"></i></a></span>
								<h2 class="h5">Répartition des données par secteur biogéographique</h2>
								<hr />					
								<div class="row">
									<div class="col-md-6 col-xs-12">
										<div id="cartebiogeo" class="cartefiche">
											<div class="mt-1"><p class="text-warning text-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement de la carte...</p></div>
										</div>							
									</div>
									<div class="col-md-6 col-xs-12">
										<h3 class="h6">Nombre d'observations par secteur</h3>
										<div id="graphebiogeo" class="minigraph border">
											<div class="mt-1"><p class="text-warning text-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement du graph...</p></div>
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
								<figure class="row">
									<div class="col-md-8">
										<h2 class="h5">Statut nicheur</h2>										
										<hr />
										<div id="cartenicheur" class="cartefiche">
											<div class="mt-1"><p class="text-warning text-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement du graph...</p></div>
										</div>
									</div>
									<div class="col-md-4">
										<figcaption>
											<h3 class="h6">Légende</h3>
											<hr />
											<p>
												<i class="fa fa-circle fa-lg" style="color:#FF0000"></i> Certain : <span id="nc"></span><br />
												<i class="fa fa-circle fa-lg" style="color:#FFAA00"></i> Probable : <span id="npr"></span><br />
												<i class="fa fa-circle fa-lg" style="color:#FFFF00"></i> Possible : <span id="np"></span><br />
											</p>
										</figcaption>
										<div class="" id="slideraves">
											<p class="curseurlien">
												<span id="anminaves"></span><input id="sliderControlaves" type="text" data-slider-tooltip="hide"/><span id="anmaxaves"></span><br />
												<span id="annicheur"></span>
											</p>											
										</div>										
									</div>
								</figure>
							</div>
							<?php
						}
						if(!empty($statut))
						{
							?>
							<div class="tab-pane fade" id="statuts" role="tabpanel">
								<h2 class="h5">Statuts - <?php echo $nomstitre;?></h2>
								<hr />
								<div id="listestatut"></div>
							</div>							
							<?php
						}
						?>						
						<div class="tab-pane fade" id="phenologie" >
							<h2 class="h5 tlegpheno"><?php echo $nomstitre;?> - Nombre d'observations par décade <small class="font12 text-muted">Données saisies au <?php echo $datejour;?> (Observé vivant)</small></h2>
							<hr />
							<figure>
								<div id="graphpheno" class="cartefiche">
									<div class="mt-1"><p class="text-warning text-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement du graph...</p></div>
								</div>
							</figure>							
						</div>
						<div class="tab-pane fade" id="observateur">
							<h2 class="h5">Liste des observateurs <span id="nbobser"></span></h2>
							<div id="listeobser">
								<div class="mt-1"><p class="text-warning text-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement de la liste...</p></div>
							</div>
						</div>
						<div class="tab-pane fade" id="infosp" role="tabpanel">
							<h2 class="h4">Informations diverses</h2>
							<hr />
							<div class="row">
								<div class="col-md-6">
									<h3 class="h5">Liste des observations <a href="index.php?module=observation&amp;action=observation&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $id;?>"><i class="fa fa-eye color1"></i></a></h3>
									<h3 class="h6">Observation "la plus importante en nombre"</h3>
									<p id="infomax"></p>
									<h3 class="h6">Date de la dernière observation</h3>
									<p id="derniere"></p>
									<h3 class="h6">Dates extrêmes</h3>
									<p><span id="extrememin"></span> - <span id="extrememax"></span></p>
									<div id="afalt"></div>
									<h3 class="h6">Etat biologique</h3>
									<div id="grapheetatbio" class=""></div>
									<h3 class="h6">Type de contact</h3>
									<div id="graphemethode" class=""></div>
								</div>
								<div class="col-md-6">
									<div id="graphenbobs" class="minigraph"></div>
									<h3 class="h6">Prospection</h3>
									<div id="grapheprospect" class="minigraph"></div>
								</div>
							</div>						
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
								<div class="row mt-1 photo-grid" id="listephoto"></div>
							</div>
							<?php
						}
						if($habitat > 0)
						{
							?>
							<div class="tab-pane fade" id="habitat">
								<h2 class="h5">Habitats</h2>
								<hr />
								<h3 class="h6" id="nbhab"></h3>
								<div id="tblhabitat" class="mt-3"></div>
								<input type="hidden" id="cdhab">
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
						if(isset($biblio) && $biblio > 0)
						{
							?>
							<div class="tab-pane fade" id="biblio">
								<h2 class="h5">Bibliographie</h2>
								<hr />
								<div id="rbiblio"></div>
							</div>
							<?php
						}
						if($indice != 'NC')
						{
							?>
							<div class="tab-pane fade" id="indice">
								<h2 class="h5">Indices</h2>
								<hr />
								<div id="listeindice"></div>
							</div>
							<?php
						}
						?>
                            <div class="tab-pane fade" id="commentaires">
                                <h2 class="h5">Commentaire général sur l'espèce (spécificité géographique...)
                                <?php
                                    if ($_SESSION['droits'] > 1) {
                                        ?>
                                        <i class="ml-2 fa fa-pencil curseurlien text-warning" title="Modifier/corriger le commentaire" onclick="edit_comment()"></i>
                                        <i class="ml-2 text-danger fa fa-trash fa-lg curseurlien" onclick="supp_comment()" title="Supprimer le commentaire"></i>
                                        <i id="vali_comment" class="fa fa-check fa-lg curseurlien ml-2 text-success" title="Valider le commentaire" style="display:none"></i>
                                    <?php
                                    }
                                ?>
                                </h2>
                                <hr />
                                <textarea disabled="true" class="form-control" rows="10" id="affichercommentaires" name="rq" placeholder="Pas de commentaire pour le moment"></textarea>
                            </div>
					</div>
				</div>
			</div>
		</div>		
	</div>
	<input id="choixcarte" type="hidden" value="<?php echo $choixcarte;?>"/><input id="emprise" type="hidden" value="<?php echo $emprise['emprise'];?>"/><input id="cdnom" type="hidden" value="<?php echo $id;?>"/><input id="utm" type="hidden" value="<?php echo $emprise['utm'];?>"/><input id="contour2" type="hidden" value="<?php echo $emprise['contour2'];?>"/>
	<input id="nomvar" type="hidden" value="<?php echo $nomvar;?>"/><input id="rangsp" type="hidden" value="<?php echo $rangssses;?>"/><input id="nom" type="hidden" value="<?php echo $nom;?>"/><input id="ign" type="hidden" value="<?php echo $ign;?>"/>
	<input id="idc" type="hidden"/><input id="typec" type="hidden"/><input id="nomc" type="hidden"/><input id="sensible" type="hidden" value="<?php echo $sensible;?>"/><input id="nomsite" type="hidden" value="<?php echo $rjson_site['titre'];?>"/><input id="adresse" type="hidden" value="<?php echo $rjson_site['adresse'];?>"/>
</section>
<div class="modal fade" id="infos">
	<div class="modal-dialog modal-lg">	
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title" id="titleinfos"></h1>
				<button type="button" class="close" data-dismiss="modal">&times;</button>				
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-6">
							<h2 class="h3">Nombre d'observations</h2>
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
				<h4 class="modal-title">Information liste rouge</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>				
			</div>
			<div class="modal-body">
				<h4 class="h5">Espèces éteintes</h4>
				<dl class="row mt-1">
					<dt class="col-sm-1"><span class="fa-stack"><i class="fa fa-circle fa-stack-2x EW"></i><i class="fa fa-stack-1x font13 EWt"><b>EW</b></i></span></dt><dd class="col-sm-11">Espèce éteinte à l'état sauvage</dd>
					<dt class="col-sm-1 mt-1"><span class="fa-stack"><i class="fa fa-circle fa-stack-2x RE"></i><i class="fa fa-stack-1x REt"><b>RE</b></i></span></dt><dd class="col-sm-11 mt-1">Espèce disparue de la région considérée</dd>
					
				</dl>
				<h4 class="h5">Espèces menacées de disparition</h4>
				<dl class="row mt-1">
					<dt class="col-sm-1"><span class="fa-stack"><i class="fa fa-circle fa-stack-2x CR"></i><i class="fa fa-stack-1x CRt"><b>CR</b></i></span></dt><dd class="col-sm-11">En danger critique (CR* Espèce probablement éteinte)</dd>
					<dt class="col-sm-1 mt-1"><span class="fa-stack"><i class="fa fa-circle fa-stack-2x EN"></i><i class="fa fa-stack-1x ENt"><b>EN</b></i></span></dt><dd class="col-sm-11 mt-1">En danger</dd>
					<dt class="col-sm-1 mt-1"><span class="fa-stack"><i class="fa fa-circle fa-stack-2x VU"></i><i class="fa fa-stack-1x VUt"><b>VU</b></i></span></dt><dd class="col-sm-11 mt-1">Vulnérable</dd>
				</dl>
				<h4 class="h5">Autres catégories</h4>
				<dl class="row mt-1">
					<dt class="col-sm-1"><span class="fa-stack"><i class="fa fa-circle fa-stack-2x NT"></i><i class="fa fa-stack-1x NTt"><b>NT</b></i></span></dt><dd class="col-sm-11">Quasi menacée (espèce proche du seuil des espèces menacées ou qui pourrait être menacée si des mesures de conservation spécifiques n'étaient pas prises)</dd>
					<dt class="col-sm-1"><span class="fa-stack"><i class="fa fa-circle fa-stack-2x LC"></i><i class="fa fa-stack-1x LCt"><b>LC</b></i></span></dt><dd class="col-sm-11">Préoccupation mineure (espèce pour laquelle le risque de disparition est faible)</dd>
					<dt class="col-sm-1"><span class="fa-stack"><i class="fa fa-circle fa-stack-2x DD"></i><i class="fa fa-stack-1x DDt"><b>DD</b></i></span></dt><dd class="col-sm-11">Données insuffisantes (espèce pour laquelle l'évaluation n'a pas pu être réalisée faute de données suffisantes)</dd>
					<dt class="col-sm-1"><span class="fa-stack"><i class="fa fa-circle fa-stack-2x NA"></i><i class="fa fa-stack-1x NAt"><b>NA</b></i></span></dt><dd class="col-sm-11">Non applicable (espèce non soumise à évaluation car (a) introduite dans la période récente ou (b) présente de manière occasionnelle)</dd>
				</dl>				
			</div>
		</div>
	</div>
</div>
<?php
if($habitat > 0)
{
	?>
	<div class="modal fade" id="infohab">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Description</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>				
				</div>
				<div class="modal-body">
					<p id="descrihab"></p>			
				</div>
			</div>
		</div>
	</div>	
	<?php
}
?>	