<section class="container-fluid mb-3">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<div class="d-flex justify-content-start">
					<h1 class="h2">Observation n° <?php echo $idobs;?>
					<?php
					if($obsexist == 'oui')
					{
						?><small><?php echo $titrepage;?></small></h1><?php
					}
					else
					{
						?></h1><?php
					}
					if(isset($_SESSION['idmembre']) && $obsexist == 'oui')
					{
						?>
						<span class="ml-auto">
							<?php
							if($validateur !== false)
							{
								?><i class="fa fa-check fa-lg curseurlien mr-2" onclick="valid('non')" title="Valider/Modifier la validation"></i><?php
							}
							if($idm == $_SESSION['idmembre'] || (isset($_SESSION['virtobs']) && $obs['idobser'] == $_SESSION['idmembre']))
							{
								?>
								<i class="text-warning fa fa-pencil fa-lg curseurlien mr-2" onclick="modfiche(<?php echo $obs['idfiche'];?>)" title="Modifier votre observation"></i>
								<i class="text-success fa fa-camera fa-lg curseurlien mr-2" onclick="adphoto(idobs=<?php echo $idobs;?>)" title="Ajouter une photo"></i>
								<i class="text-success fa fa-volume-off fa-lg curseurlien mr-2" onclick="adson(idobs=<?php echo $idobs;?>)" title="Ajouter un son"></i>
								<i class="text-danger fa fa-trash fa-lg curseurlien" onclick="supobs(idobs=<?php echo $idobs;?>)" title="Supprimer votre observations"></i>
								<?php
							}
							elseif(isset($adphoto))
							{
								?><i class="text-success fa fa-camera fa-lg curseurlien mr-2" onclick="adphoto(idobs=<?php echo $idobs;?>)" title="Ajouter une photo"></i><i class="text-success fa fa-volume-off fa-lg curseurlien mr-2" onclick="adson(idobs=<?php echo $idobs;?>)" title="Ajouter un son"></i><?php
							}
							?>
						</span>
						<?php
					}
					?>					
				</div>	
			</div>
		</div>
	</header>
	<?php
	if($obsexist == 'oui')
	{
		?>
		<div class="row mt-2">
			<div class="col-md-6 col-lg-6">
				<div class="card">
					<div class="card-body">
						<h2 class="h3">Informations</h2>						
						<?php
						if(isset($diffcdref))
						{
							?><p>Saisie sous le nom de : <i><?php echo $diffcdref;?></i></p><?php
						}
						?>
						<p><span id="retourvali"><?php echo $vali;?></span><a href="index.php?module=validation&amp;action=validation"><i class="ml-2 fa fa-info-circle text-info"></i></a></p>
						<p><?php
						if(!empty($obs['idobs_sinp']))
							{
							echo '<h3 class="h6">Identifiant SINP : ' . $obs['idobs_sinp'] . '</h3>' ;
							} 	
						?></p>
						<div class="row">
							<div class="col-md-5 col-lg-5">
								<h3 class="h5">Date : <small><?php echo $dateobs;?></small></h3>
								<p>Date de saisie : <?php echo $obs['dates'];?></p>
								<h3 class="h5">Localisation</h3>
								<p><?php echo $localisation;?></p>							
								<h3 class="h5">
									Observateur(s)
									<?php
									if(!empty($obs['organisme']))
									{
										?><small class="text-muted">(<?php echo $obs['organisme'];?>)</small><?php
									}
									?>
								</h3>
								<p><?php echo $observateur;?></p>
								<?php 
								if(isset($idmor))
								{
									?><p>(Saisie par : <?php echo $idmor['prenom'];?> <?php echo $idmor['nom'];?>)</p><?php
								}
								if(!empty($obs['etude']))
									{
										echo '<h3 class="h5">Etude : <small>' . $obs['etude'] . '</small></h3>' ;
									}
								if(!empty($obs['protocole']))
									{
										echo '<h3 class="h5">Type d\'acquisition : <small>' . $obs['protocole'] . '</small></h3>' ;
									}
								if(!empty($obs['ca']))
									{
										echo '<h3 class="h5">Cadre d\'acquisition : <small>' . $obs['ca'] . '</small></h3>' ;
									}
								if(!empty($obs['jdd']))
									{
										echo '<h3 class="h5">Jeu de données : <small>' . $obs['jdd'] . '</small></h3>' ;
									} 	
									?>
							</div>

							<div class="col-md-7 col-lg-7">
								<h3 class="h5">Détail de l'observation</h3>
								<p>
									<?php echo $ligne;?><br />
									Déterminateur : <?php echo $determinateur;?>
									<?php
									if(!empty($obs['iddetcol']))
									{
										?><br />Conservé en collection de référence.<?php
									}
									if(!empty($obs['typedet']))
									{
										?><br />Type détermination : <?php echo $obs['typedet'];?>.<?php
									}
									if(isset($pltebota))
									{
										?><br />Plante hôte et/ou nourricière : <br />
										<?php echo $pltebota;
									}
									if(isset($piaf))
									{
										?><br /><?php echo $piaf;
									}
									if(!empty($obs['rqobs']) && $flousen == 0)
									{
										?><br />Remarque : <?php echo $obs['rqobs'];
									}
									if(isset($obs['idbiblio']) && !empty($obs['idbiblio']))
									{
										?><br />Référence bibliographique : [<a class="font-weight-bold lienbiblio" href="biblio/index.php?module=biblio&amp;action=biblio&amp;id=<?php echo $obs['idbiblio'];?>"><?php echo $obs['idbiblio'];?></a>]<?php
									}
									?>
								</p>
								<?php
								if(isset($son))
								{
									?>
									<h3 class="h5">Son(s)</h3>
									<div><?php echo $son;?></div>
									<?php
								}
								?>
							</div>
						</div>
						<?php
						if(isset($photo))
						{
							?>
							<h3 class="h5">Photo(s)</h3>
							<div class="row popup-gallery"><?php echo $photo;?></div>
							<?php
						}					
						if(isset($mediacom))
						{
							?>
							<h3 class="h5 mt-1">Commentaire(s)</h3>
							<?php echo $mediacom;
						}
						if(isset($_SESSION['idmembre']) && !empty($idm))
						{
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
								<input id="idobscom" type="hidden" value="<?php echo $idobs;?>"/><input id="idmcom" type="hidden" value="<?php echo $_SESSION['idmembre'];?>"/><input id="idmor" type="hidden" value="<?php echo $idm;?>"/>
							</form>								
							<?php
						}
						?>
						<hr />
						<p>
							<?php
							if($obs['rang'] == 'ES' || $obs['rang'] == 'SSES')
							{
								?><a class="color1" href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d=<?php echo $obs['observa'];?>&amp;id=<?php echo $obs['cdref'];?>">Fiche de <?php echo $titrepage;?></a><?php
							}
							elseif($obs['rang'] == 'GN')
							{
								?><a class="color1" href="observatoire/index.php?module=fiche&amp;action=ficheg&amp;d=<?php echo $obs['observa'];?>&amp;id=<?php echo $obs['cdref'];?>">Fiche de <?php echo $titrepage;?></a><?php
							}
							elseif($obs['rang'] == 'COM')
							{
								?><a class="color1" href="observatoire/index.php?module=fiche&amp;action=fichec&amp;d=<?php echo $obs['observa'];?>&amp;id=<?php echo $obs['cdref'];?>">Fiche de <?php echo $titrepage;?></a><?php
							}
							?>
							<a class="float-right color1" href="index.php?module=observation&amp;action=fiche&amp;idfiche=<?php echo $obs['idfiche'];?>">Relevé n° <?php echo $obs['idfiche'];?></a>
						</p>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-lg-6">
				<div class="card">
					<div class="card-body">
						<div id="mapdetail"></div>					
					</div>
				</div>
				<div class="card card-body mt-2">
					<div class="">
						<a target="_blank" title="Twitter" href="https://twitter.com/share?url=<?php echo urlencode($url);?>&text=<?php echo $titre;?>" rel="nofollow" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=400,width=700');return false;"><img src="dist/img/twitter_icon.png" alt="Twitter" width="20" height="20" /></a>
						<a target="_blank" title="Facebook" href="https://www.facebook.com/sharer.php?u=<?php echo urlencode($url);?>&t=<?php echo $titre;?>" rel="nofollow" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=500,width=700');return false;"><img src="dist/img/facebook_icon.png" alt="Facebook" width="20" height="20" /></a>
						<a target="_blank" title="Google +" href="https://plus.google.com/share?url=<?php echo $url;?>&hl=fr" rel="nofollow" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=450,width=650');return false;"><img src="dist/img/gplus_icon.png" alt="Google Plus" width="20" height="20" /></a>
						<a target="_blank" title="Linkedin" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode($url);?>&title=<?php echo $titre;?>" rel="nofollow" onclick="javascript:window.open(this.href, '','menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=450,width=650');return false;"><img src="dist/img/linkedin_icon.png" alt="Linkedin" width="20" height="20" /></a>
						<a title="Envoyer par mail" href="mailto:?subject=<?php echo $titre;?>&body=<?php echo urlencode($url);?>" rel="nofollow"><img src="dist/img/email_icon.png" alt="email" width="20" height="20"/></a>						
					</div>					
				</div>				
				<input id="pre" type="hidden" value="<?php echo $pre;?>"/><input id="idobs" type="hidden" value="<?php echo $idobs;?>"/><input id="sel" type="hidden" value="<?php echo $sel;?>"/><input id="flou" type="hidden" value="<?php echo $flou;?>"/>
				<input id="color" type="hidden" value="<?php echo $color;?>"/><input id="weight" type="hidden" value="<?php echo $weight;?>"/><input id="opacity" type="hidden" value="<?php echo $opacity;?>"/><input id="observa" type="hidden" value="<?php echo $obs['observa'];?>"/><input id="new" type="hidden" value="<?php echo $nouv;?>"/><input id="rvali" type="hidden" value="<?php echo $rvali;?>"/>
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
						<p>Cette observation n'existe pas ou plus.</p>
					</div>
				</div>
			</div>
		</div>
		<input id="flou" type="hidden" value="aucun"/>
		<?php
	}
	?>	
</section>
<div id="dia1" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Suppression</h4>
			</div>
			<div class="modal-body">
				<p>Voulez vous vraiment supprimer cette observation ?</p>
			</div>
			<input id="encours" type="hidden"/>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Annuler</button>
				<button type="button" class="btn btn-success" data-dismiss="modal" id="bttdia1">Oui</button>
			</div>
		</div>
	</div>
</div>
<div id="dia3" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Suppression</h4>
			</div>
			<div class="modal-body">
				<p>Voulez vous vraiment supprimer cette photo ?</p>
			</div>
			<input id="idphoto" type="hidden"/>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Annuler</button>
				<button type="button" class="btn btn-success" data-dismiss="modal" id="bttdia3">Oui</button>
			</div>
		</div>
	</div>
</div>
<div id="dia4" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Validation de l'observation</h4>
			</div>
			<div class="modal-body">
				<div id="histo"></div><div id="com"></div>
				<?php 
				if($rvali == 'non')
				{
					?>
					<p>
						Si vous validez l'observation en "Douteux", "Invalide" ou "Non réalisable", indiquez dans le champ la raison.<br />
						Vous pouvez aussi vous servir de ce champ pour demander des informations complémentaires à l'observateur sans forcement modifier la validation (laisser alors "choisir")
					</p>
					<form>
						<div class="form-inline">
							<label for="vali" class="">Modifier la validation</label>
							<select id="vali" class="ml-2 form-control form-control-sm">
								<option value="NR">--Choisir--</option>
								<option value="1">Certain, très probable</option>
								<option value="2">Probable</option>
								<option value="3">Douteux</option>
								<option value="4">Invalide</option>
								<option value="5">Non réalisable</option>
								<option value="6">Non évalué, en cours</option>
							</select>
						</div>
						<div class="form-group row mt-3">
							<div class="col-sm-12"><textarea class="form-control" rows="3" id="rq" placeholder="Indiquez ici votre commentaire"></textarea></div>
						</div>
						<div class="form-inline mt-3">
							<button type="button" class="btn btn-success" id="BttV">Valider et fermer</button>
						</div>
						<?php
				}
				elseif($rvali == 'oui')
				{
					?>
					<p>Répondez au(x) validateur(s) en dessous. Si vous devez apporter des modifications à l'observation, faite le en cliquant sur le <i class="fa fa-pencil text-warning"></i> à droite du n° de l'observation. (ne pas supprimer cette observation pour la re-saisir avec les modifications)</p>
					<form>
						<div class="form-group row mt-3">
							<div class="col-sm-12"><textarea class="form-control" rows="3" id="rq" placeholder="Indiquez ici votre réponse"></textarea></div>
						</div>
						<div class="form-inline mt-3">
							<button type="button" class="btn btn-success" id="BttVr">Valider et fermer</button>
						</div>
					</form>
					<?php
				}
				?>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Fermer (sans valider)</button>
			</div>
		</div>
	</div>
</div>
<div id="dia5" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-body" id="mes"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>				
			</div>
		</div>
	</div>
</div>
<div id="dia6" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Suppression d'un son</h4>
            </div>
            <div class="modal-body">
                <p>Voulez vous vraiment supprimer cet extrait sonore ?</p>
            </div>
            <input id="idson" type="hidden"/>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-success" data-dismiss="modal" id="bttdia6">Oui</button>
            </div>
        </div>
    </div>
</div>
