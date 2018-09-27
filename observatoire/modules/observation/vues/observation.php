<section class="container-fluid mb-3">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<header>
					<h1 class="h3 text-center">
						<?php
						if($rang == 'ES' || $rang == 'SSES')
						{
							?>
							Liste des observations de 
							<a href="index.php?module=fiche&amp;action=fiche&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $id;?>">
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
							</a>
							<?php
						}
						elseif($rang == 'GN')
						{
							?>
							Liste des observations du genre <a href="index.php?module=fiche&amp;action=ficheg&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $id;?>"><i><?php echo $nom;?></i> <span class="xsmall"><?php echo $inventeur;?></span></a>
							<?php
						}
						elseif($rang == 'COM')
						{
							?>
							Liste des observations du complexe <a href="index.php?module=fiche&amp;action=fichec&amp;d=<?php echo $nomvar;?>&amp;id=<?php echo $id;?>"><i><?php echo $nom;?></i></a>
							<?php
						}
						?>
					</h1>
				</header>
				<form class="form-inline">
					<label for="tri">tri par</label>							
					<select id="tri" class="form-control form-control-sm ml-2">
						<option value="dateobs">Date d'observation</option>
						<option value="datesaisie">Date de saisie</option>							
					</select>
					<label class="ml-3" for="regroup">Regroupement par</label>							
					<select id="regroup" class="form-control form-control-sm ml-2">
						<option value="date">Date</option>
						<?php
						if($dep == 'oui')
						{
							?><option value="departement">Département</option><?php
						}
						?>
						<option value="commune">Commune</option>
						<option value="observateur">Observateur</option>
					</select>
					<?php
					if(isset($_SESSION['idmembre']))
					{
						?>
						<div class="custom-control custom-checkbox ml-2">
							<input type="checkbox" class="custom-control-input" id="perso">
							<label class="custom-control-label" for="perso">Uniquement vos observations</label>							
						</div>
						<?php
					}
					?>
				</form>				
			</div>
			<div class="card card-body mt-2">
				<div id="listeobs">
					<!--<div class="mt-2"><p class="text-warning text-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement des données...</p></div>-->
				</div>
				<div class="row mb-1">					
					<div class="col-md-12 col-lg-12 text-center">
						<div id="pagination" class="float-right"></div>
						<button class="btn color1_bg" type="button" id="bttrhaut"><i class="fa fa-arrow-up blanc"></i></button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<input id="lat" type="hidden" value="<?php echo $rjson_emprise['lat'];?>"/><input id="lng" type="hidden" value="<?php echo $rjson_emprise['lng'];?>"/><input id="p" type="hidden" value="1"/><input id="sen" type="hidden" value="<?php echo $sensible;?>"/>
	<input id="id" name="id" type="hidden" value="<?php echo $id;?>"/><input id="sel" name="sel" type="hidden" value="<?php echo $nomvar;?>"/><input id="dep" type="hidden" value="<?php echo $dep;?>"/>
</section>
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
								<form id="postcom">
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