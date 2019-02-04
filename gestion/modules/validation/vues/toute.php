<section class="container-fluid blanc">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header>	
				<h1>Validation des données</h1>
			</header>			
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<form>
				<div class="form-inline">
					<label for="choix">Observatoire</label>
					<select id="choix" class="form-control ml-2">
						<option value="NR" name="theme">--choisir--</option>
						<?php
						foreach($menuobservatoire as $n)
						{
							?>
							<option value="<?php echo $n['nomvar'];?>" name="theme"><?php echo $n['nom'];?></option>
							<?php
						}
						?>
					</select>
					<button type="button" class="btn btn-info ml-2" id="aide"><span id="btn-aide-txt">Aide</span></button>
					<div id="btchoixcheck">
                        <button type="button" id="Btvalitscheck" class="ml-2 btn btn-warning">Valider la sélection</button>
                    </div>
                    <div id="btchoix">
                        <button type="button" id="Btvalits" class="ml-2 btn btn-danger">Valider tout</button>
                    </div>
					<div id="valajax" class="ml-3"><progress></progress></div>
				</div>
			</form>
			<div class="row" id="infoaide">
				<div class="col-md-12 col-lg-12 mt-2">
					<p>
						<i class="fa fa-pencil text-danger"></i> : Espèce à valider manuellement - <i class="fa fa-pencil text-warning"></i> : Espèce n'ayant pas passée le filtre automatique - <i class="fa fa-eye text-primary"></i> : Voir l'observation - <i class="fa fa-file-text-o text-primary"></i> : Voir la fiche du taxon - <i class="fa fa-plus text-success"></i> : Détail du filtre automatique<br />
						Pour procéder à la validation cliquer sur le <i class="fa fa-pencil text-danger"></i> ou <i class="fa fa-pencil text-warning"></i> de l'observation. Dès qu'une observation est validée, celle-ci est retirée de la liste.<br />
						Vous pouvez valider l'ensemble des observations d'un coup en cliquant sur le bouton "Valider tout". Cela validera toutes les observations sauf celles pour lesquelles vous avez fait un commentaire et laissés en "Non évalué, en cours"
					</p>
					
				</div>
			</div>
			<div id="liste" class="mt-3"></div>
		</div>
	</div>
	<input id="observa" type="hidden" value="<?php echo $choix;?>"/><input id="new" type="hidden" value="<?php echo $new;?>"/>
</section>
<div id="dia1" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Validation observation <span id="tdia1"></span></h4>
			</div>
			<div class="modal-body">
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
						<div class="col-sm-12"><textarea class="form-control" rows="3" id="rq" placeholder="Indiquez ici vos commentaires"></textarea></div>
					</div>
					<div class="form-inline mt-3">
						<button type="button" class="btn btn-success" id="BttV">Valider</button>
					</div>
				</form>
				<input id="idobs" type="hidden"/>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Fermer</button>
			</div>
		</div>
	</div>
</div>
<div id="dia2" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-body" id="mes"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>				
			</div>
		</div>
	</div>
</div>