<section class="container-fluid mb-3">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<div class="d-flex justify-content-start">
					<h1 class="h2">Relevé n° <?php echo $idfiche;?></h1>
					<?php
					if(isset($_SESSION['idmembre']) && $ficheexist == 'oui')
					{
						if($idm == $_SESSION['idmembre'] || (isset($_SESSION['virtobs']) && $info['idobser'] == $_SESSION['idmembre']))
						{
							?>
							<span class="ml-auto">
								<i class="text-warning fa fa-pencil fa-lg curseurlien mr-2" onclick="modfiche(<?php echo $idfiche;?>)" title="Modifier votre relevé"></i>							
								<i class="text-danger fa fa-trash fa-lg curseurlien" onclick="supfiche(<?php echo $idfiche;?>)" title="Supprimer votre relevé"></i>
							</span>
							<?php
						}
					}
					?>					
				</div>
			</div>
		</div>
	</header>
	<?php 
	if($ficheexist == 'oui')
	{
		?>
		<div class="row mt-2">
			<div class="col-md-6 col-lg-6">
				<div class="card">
					<div class="card-body">
						<h2 class="h3">Informations</h2>
						<h3 class="h5">Date : <small><?php echo $datefiche;?></small></h3>
						<h3 class="h5">Localisation</h3>
						<p><?php echo $localisation;?></p>
						<h3 class="h5">Observateur(s)</h3>
						<p><?php echo $observateur;?></p>						
						<?php
						if(isset($info['idbiblio']) && !empty($info['idbiblio']))
						{
							?><h3 class="h5">Référence bibliographique : [<a class="lienbiblio" href="biblio/index.php?module=biblio&amp;action=biblio&amp;id=<?php echo $info['idbiblio'];?>"><?php echo $info['idbiblio'];?></a>]</h3><?php
						}	
						if(isset($sel))
						{
							?>
							<div class="row">
								<div class="col-md-7 col-lg-7">
									<h2 class="h3">Liste des espèces <small class="text-muted">(<?php echo $nbt;?>)</small></h2>
									<?php							
									foreach($sel as $s)
									{
										?>
										<h3 class="h5"><?php echo $s['nom'];?> <small class="text-muted">(<?php echo $s['nb'];?>)</small></h3>
										<ul class="list-unstyled">
											<?php
											foreach ($tabobs as $n)
											{
												if($n['nomvar'] == $s['nomvar'])
												{
													if($n['latin'] == 'oui')
													{	
														if($n['rang'] != 'GN')
														{
															?><li><i class="fa fa-check-circle <?php echo $n['vali'];?>"></i>&nbsp;<?php echo $n['nb'];?>&nbsp;<a href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d=<?php echo $n['nomvar'];?>&amp;id=<?php echo $n['cdnom'];?>"><i><?php echo $n['nomlat'];?> <?php echo $n['auteur'];?></i></a> <a href="index.php?module=observation&amp;action=detail&amp;idobs=<?php echo $n['idobs'];?>" title="Détail de l'observation"><i class="fa fa-eye"></i></a></li><?php
														}
														else
														{
															?><li><i class="fa fa-check-circle <?php echo $n['vali'];?>"></i>&nbsp;<?php echo $n['nb'];?>&nbsp;<a href="observatoire/index.php?module=fiche&amp;action=ficheg&amp;d=<?php echo $n['nomvar'];?>&amp;id=<?php echo $n['cdnom'];?>"><i><?php echo $n['nomlat'];?>.sp <?php echo $n['auteur'];?></i></a> <a href="index.php?module=observation&amp;action=detail&amp;idobs=<?php echo $n['idobs'];?>" title="Détail de l'observation"><i class="fa fa-eye"></i></a></li><?php
														}											
													}
													else
													{
														if($n['nomfr'] != '')
														{
															?><li><i class="fa fa-check-circle <?php echo $n['vali'];?>"></i>&nbsp;<?php echo $n['nb'];?>&nbsp;<a href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d=<?php echo $n['nomvar'];?>&amp;id=<?php echo $n['cdnom'];?>"><?php echo $n['nomfr'];?> (<i><?php echo $n['nomlat'];?></i>)</a> <a href="index.php?module=observation&amp;action=detail&amp;idobs=<?php echo $n['idobs'];?>" title="Détail de l'observation"><i class="fa fa-eye"></i></a></li><?php
														}
														else
														{
															if($n['rang'] != 'GN')
															{
																?><li><i class="fa fa-check-circle <?php echo $n['vali'];?>"></i>&nbsp;<?php echo $n['nb'];?>&nbsp;<a href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d=<?php echo $n['nomvar'];?>&amp;id=<?php echo $n['cdnom'];?>"><i><?php echo $n['nomlat'];?> <?php echo $n['auteur'];?></i></a> <a href="index.php?module=observation&amp;action=detail&amp;idobs=<?php echo $n['idobs'];?>" title="Détail de l'observation"><i class="fa fa-eye"></i></a></li><?php
															}
															else
															{
																?><li><i class="fa fa-check-circle <?php echo $n['vali'];?>"></i>&nbsp;<?php echo $n['nb'];?>&nbsp;<a href="observatoire/index.php?module=fiche&amp;action=ficheg&amp;d=<?php echo $n['nomvar'];?>&amp;id=<?php echo $n['cdnom'];?>"><i><?php echo $n['nomlat'];?>.sp <?php echo $n['auteur'];?></i></a> <a href="index.php?module=observation&amp;action=detail&amp;idobs=<?php echo $n['idobs'];?>" title="Détail de l'observation"><i class="fa fa-eye"></i></a></li><?php
															}												
														}
													}
												}									
											}
											?>
										</ul>
										<?php
									}
									?>
								</div>
								<div class="col-md-5 col-lg-5">
									<?php
									if(isset($libphoto))
									{
										?>
										<h2 class="h5"><?php echo $libphoto;?> sur le relevé</h2>
										<div class="popup-gallery">
											<?php
											foreach($photo as $n)
											{
												?>
												<a href="photo/P800/<?php echo $n['observatoire'];?>/<?php echo $n['nomphoto'];?>.jpg" title="<?php echo $n['auteur'];?> - <?php echo $n['nom'];?>">
													<img src="photo/P200/<?php echo $n['observatoire'];?>/<?php echo $n['nomphoto'];?>.jpg" class="mt-1 img-thumbnail" alt="<?php echo $n['nom'];?>">
												</a>
												<?php
											}
											?>
										</div>
										<?php
									}
									?>
								</div>
							</div>
							<?php
						}
						else
						{
							?><p>Aucune espèce associée à ce relevé !</p><?php
						}
						?>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-lg-6">
				<div class="card">
					<div class="card-body">
						<div id="mapdetail"></div>					
					</div>
				</div>
				<input id="idfiche" type="hidden" value="<?php echo $idfiche;?>"/><input id="pre" type="hidden" value="<?php echo $pre;?>"/><input id="sel" type="hidden" value="<?php echo $type;?>"/><input id="flou" type="hidden" value="<?php echo $flou;?>"/>
				<input id="color" type="hidden" value="<?php echo $color;?>"/><input id="weight" type="hidden" value="<?php echo $weight;?>"/><input id="opacity" type="hidden" value="<?php echo $opacity;?>"/>
			</div>
		</div>
		<?php
	}
	else
	{
		?>
		<div class="row mt-2">
			<div class="col-md-6 col-lg-6">
				<div class="card">
					<div class="card-body">
						<p>Ce relevé n'existe pas ou plus.</p>
					</div>
				</div>
			</div>
		</div>
		<input id="flou" type="hidden" value="aucun"/>
		<?php
	}
	?>	
</section>
<div id="dia2" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Suppression</h4>
			</div>
			<div class="modal-body">
				<p>Voulez vous vraiment supprimer ce relevé ?<br />Toutes les observations de ce relevé seront supprimées.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Annuler</button>
				<button type="button" class="btn btn-success" data-dismiss="modal" id="bttdia2">Oui</button>
			</div>
		</div>
	</div>
</div>