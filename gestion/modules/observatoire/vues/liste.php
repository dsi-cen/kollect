<section class="container blanc">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header>	
				<h1>Configuration de la liste des taxons</h1>
			</header>			
			<?php
			if ($nbobservatoire == 0)
			{
				?><p class="text-warning">Aucun observatoire pour l'instant sur le site</p><?php
			}
			else
			{
				?><p>Vous avez <?php echo ''.$nbobservatoire.' '.$libnbobser.'';?> à gérer.</p><?php
			}
			?>
			<button type="button" class="btn btn-info" id="aide"><span id="btn-aide-txt">Aide</span></button><a class="ml-2" href="index.php?module=observatoire&amp;action=listea">Méthode 2 - tableau</a>			
		</div>
	</div>
	<br />
	<div class="row" id="infoaide">
		<div class="col-md-12 col-lg-12">
			<p>
				Par défaut lors de la création de la liste des taxons de l'observatoire, tous les taxons de France peuvent-être sélectionnés sur le site. 
				Vous pouvez choisir de filtrer que sur ceux présent sur votre emprise.<br />
				Lors de la saisie sur le site seul les taxons cochés apparaîtront dans les listes de choix. Néanmoins vous pouvez permettre (à voir page "Choix des champs" à "Permettre la saisie de taxons non local")
				la saisie de taxons non cochés.<br />
				Vous pouvez cocher/décocher à n'importe quel niveau systématique. Par exemple, en décochant/cochant au niveau d'une famille, vous décocher/cocher automatiquement tous les taxons s'y référant.<br />
				La liste ci-dessous reprends uniquement les taxons valides (cdref = cdnom dans Taxref). En cochant/décochant un taxon, l'ensemble des synonymes ayant comme cdref le cdnom du taxons seront également cocher/décocher.<br />
				De la même façon, vous pouvez faire des suppressions (<i class="fa fa-trash curseurlien text-danger"></i>).<b> Attention dans ce cas ils sont réellement supprimé de votre liste.</b><br />
				Après avoir fait des modifications vous devez cliquer sur le bouton "Valider avant de quitter la page".
			</p>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 col-lg-12">				
			<form class="form">
				<div class="form-group row">
					<label for="theme" class="col-sm-1 col-form-label">Observatoire</label>
					<div class="col-sm-3">
						<select id="choix" class="form-control">
							<option value="NR" name="theme">--choisir--</option>
							<?php
							foreach ($menuobservatoire as $n)
							{
								?>
								<option value="<?php echo $n['nomvar'];?>" name="theme"><?php echo $n['nom'];?></option>
								<?php
							}
							?>
						</select>						
					</div>
					<div class="col-sm-offset-1 col-sm-5">
						<button type="button" class="btn btn-success" id="BttV">Valider avant de quitter la page (si modification)</button>
					</div>
				</div>
			</form>
			<div id="valajax"><progress></progress></div><div id="mes"></div>
			<div id="tab"></div>			
		</div>		
	</div>
</section>
<input id="souvenir1" type="hidden" value="non"/><input id="sisfm" type="hidden"/>
<div id="dia1" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Confirmation</h4>
			</div>
			<div class="modal-body">
				<p>Voulez-vous vraiment supprimer la(les) ligne(s) en rouges ?.</p>
				<input type="checkbox" id="souvenir"> Ne plus afficher ce message.
				<input id="rang" type="hidden"/><input id="idsup" type="hidden"/>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Annuler</button>
				<button type="button" class="btn btn-success" data-dismiss="modal" id="bttdia1">Oui</button>
			</div>
		</div>
	</div>
</div>
<div id="dia2" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Confirmation</h4>
			</div>
			<div class="modal-body">
				<p>Voulez-vous vraiment mettre l'ensemble des taxons de cet observatoire comme non présent ?.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Annuler</button>
				<button type="button" class="btn btn-success" data-dismiss="modal" id="bttdia2">Oui</button>
			</div>
		</div>
	</div>
</div>
<script>
function cacher(bouton, id) {
	var div = document.getElementById(id);
	if (div.style.display == "none") { 
		div.style.display = "block"; 
		bouton.innerHTML = "-"; 
	} else { 
		div.style.display = "none"; 
		bouton.innerHTML = "+";
	}
}
</script>