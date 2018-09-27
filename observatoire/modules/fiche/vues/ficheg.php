<section class="container-fluid fiche">
	<div class="row">		
		<div class="col-lg-12 col-md-12 mt-3">
			<p class="color1">
				<?php echo $famille;?> > Sous famille
			</p>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4 col-xs-12 col-lg-4 col-xl-3">
			<div class="card border-0">
				<div class="card-header color1_bg blanc p-2">
					<h1 class="h4"><i><?php echo $nom;?></i> <span class="xsmall"><?php echo $inventeur;?></span></h1>
				</div>
				<div class="card-body">
					<?php
					if($ouiphoto > 0)
					{
						?>
						<div id="carousel" class="carousel slide">
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
									$class = (++$i == 0) ? 'item active' : 'item';
									?>
									<div class="<?php echo $class;?>">
										<a href="../photo/P800/<?php echo $n['observatoire'];?>/<?php echo $n['nomphoto'];?>.jpg" title="<?php echo $n['auteur'];?> - <?php echo $n['datefr'];?>">
											<img alt="" src="../photo/P400/<?php echo $n['observatoire'];?>/<?php echo $n['nomphoto'];?>.jpg" class="img-responsive" style="height:266px;margin:0px auto;">
											<div class="carousel-caption">
												<p>&copy; <?php echo $n['observateur'];?>&nbsp;&nbsp;&nbsp;&nbsp;<i class="pe-7s-clock"></i> <?php echo $n['datefr'];?></p>
											</div>
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
					?>					
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
						<div class="col-md-6">
							<span class="h5">NC</span><br />
							<span>Indice de rareté</span>
						</div>
					</div>
				</div>				
			</div>
			<div class="card card-body">				
				<?php
				if(isset($nbes) && $nbes >= 1)
				{
					if($nbes == 1)
					{
						?><h2 class="h5">Espèce : </h2><?php
					}
					elseif($nbes > 1)
					{
						?><h2 class="h5">Espèces (<?php echo $nbes;?>) : </h2><?php
					}
					?>
					<p>
						<?php 
						foreach($espece as $n) 
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
				if(isset($complexe) && $complexe != false)
				{
					?>
					<h2 class="h5">Complexe d'espèces : </h2>
					<p>
						<?php 
						foreach($complexe as $n) 
						{ 
							?><i><?php echo $n['nom'];?></i> <a href="index.php?module=observation&amp;action=observation&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $n['com'];?>" title="Voir les observations"><i class="fa fa-eye"></i></a><br /><?php 								
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
						<li class="nav-item"><a class="nav-link active" href="#carto" data-toggle="tab" data-id="carto" title="Atlas"><i class="fa fa-clone fa-lg"></i></a></li>
						<li class="nav-item"><a class="nav-link" href="#phenologie" data-toggle="tab" data-id="phenologie" title="Phénologie"><i class="fa fa-bar-chart fa-lg"></i></a></li>
						<li class="nav-item"><a class="nav-link" href="#observateur" data-toggle="tab" data-id="observateur" title="Observateurs"><i class="fa fa-users fa-lg"></i></a></li>
						<?php
						if($nbgenresp > 0)
						{
							?><li class="nav-item" title="Voir les observations"><a class="nav-link" href="index.php?module=observation&amp;action=observation&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $id;?>"><i class="fa fa-eye fa-2x"></i></a></li><?php
						}
						if($ouiphoto > 0)
						{
							?><li class="nav-item"><a class="nav-link" href="#photo" data-toggle="tab" data-id="photo"><i class="fa fa-camera fa-2x"></i></a></li><?php
						}
						?>
					</ul>
				</div>
				<div class="col-sm-11 pl-0">
					<div class="tab-content hauteurfiche">
						<div class="tab-pane fade show active" id="carto">
							<figure class="row">
								<div class="col-md-9">
									<h2 class="h5"><span id="titrecarte"><?php echo $titrecarte;?> </span><span id="titrecarte2"><i><?php echo $nom;?></i></span> <?php echo $rjson_site['ad2'];?> <?php echo $rjson_site['lieu'];?></h2>
									<hr />
									<div id="container" class="cartefiche border">
										<div class="mt-2"><p class="text-warning text-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement de la carte...</p></div>
									</div>							
								</div>
								<div class="col-md-3">
									<h3 class="h6">Choix de la carte</h3>
									<hr />
									<?php
									if(isset($infosensible))
									{
										?>
										<h3 class="h5"><span class="fa fa-exclamation-triangle fa-lg text-warning"></span> Espèce sensible</h3>
										<p><?php echo $infosensible;?></p>
										<hr />
										<?php
									}
									?>
									<?php
									if($cartecom != 'non')
									{
										?>
										<div class="form-check font13 mb-0">
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
										<div class="form-check font13 mb-0">
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
										<div class="form-check font13 mb-0">
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
									<?php
									if($nbgenresp > 0 || (isset($complexe) && $complexe != false))
									{
										?>
										<div class="form-check font13 mb-0" id="tousc">
											<label class="custom-control custom-radio">
												<input type="radio" name="setdata" id="tous" value="tous" class="custom-control-input" checked> 
												<span class="custom-control-indicator"></span>
												<span class="custom-control-description"><?php echo $nom;?> (toutes)</span>
											</label>
										</div>
										<div class="form-check font13 mb-0" id="spc">
											<label class="custom-control custom-radio">
												<input type="radio" name="setdata" value="sp" class="custom-control-input"> 
												<span class="custom-control-indicator"></span>
												<span class="custom-control-description"><?php echo $nom;?> sp.</span>
											</label>
										</div>
										<div class="form-check font13 mb-0" id="dc">
											<label class="custom-control custom-radio">
												<input type="radio" name="setdata" value="d" class="custom-control-input"> 
												<span class="custom-control-indicator"></span>
												<span class="custom-control-description"><?php echo $nom;?> (dét.)</span>
											</label>
										</div>
										<?php
										if(isset($complexe) && $complexe != false)
										{
											foreach($complexe as $n) 
											{ 
												?>
												<div class="form-check font13 mb-0" id="c<?php echo $n['com'];?>">
													<label class="custom-control custom-radio">
														<input type="radio" name="setdata" value="c<?php echo $n['com'];?>" class="custom-control-input"> 
														<span class="custom-control-indicator"></span>
														<span class="custom-control-description" id="labc<?php echo $n['com'];?>"><?php echo $n['nom'];?></span>
													</label>
												</div>
												<?php 								
											}											
										}									
									}
									?>
									<figcaption class="mt-2">
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
						<div class="tab-pane fade" id="phenologie" >
							<h2 class="h5">Nombre d'observations par décade</h2>
							<figure>
								<div id="graphpheno" class="cartefiche">
									<div class="m-t-1"><p class="text-warning text-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement du graph...</p></div>
								</div>
							</figure>					
						</div>
						<div class="tab-pane fade" id="observateur">
							<h2 class="h5">Liste des observateurs <span id="nbobser"></span> de <i><?php echo $nom;?></i></h2>
							<div id="listeobser">
								<div class="mt-1"><p class="text-warning text-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement de la liste...</p></div>
							</div>
						</div>
						<?php
						if($ouiphoto > 0)
						{
							?>
							<div class="tab-pane fade" id="photo">
								<h2 class="small">Photos</h2>
								<p>A faire : chargement via ajax au clic sur onglet<br /> 
								Faire tri par stade adulte etc...
							</div>
							<?php
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>	
	<input id="choixcarte" type="hidden" value="<?php echo $choixcarte;?>"/><input id="emprise" type="hidden" value="<?php echo $emprise['emprise'];?>"/><input id="cdnom" type="hidden" value="<?php echo $id;?>"/><input id="utm" type="hidden" value="<?php echo $emprise['utm'];?>"/><input id="contour2" type="hidden" value="<?php echo $emprise['contour2'];?>"/><input id="nomvar" type="hidden" value="<?php echo $nomvar;?>"/>
	<input id="nbgenresp" type="hidden" value="<?php echo $nbgenresp;?>"/><input id="nom" type="hidden" value="<?php echo $nom;?>"/>
	<input id="idc" type="hidden"/><input id="typec" type="hidden"/><input id="nomc" type="hidden"/>
</section>
<div class="modal fade" id="infos">
	<div class="modal-dialog modal-lg">	
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title"></h1>
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