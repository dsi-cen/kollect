<section class="container blanc">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header">		
				<h1>Gestion des observatoires</h1>
			</header>		
			<?php
			if ($nbobservatoire == 0)
			{
				?><p class="text-warning">Aucun observatoire pour l'instant sur le site</p><?php
			}
			else
			{
				?><p><?php echo ''.$nbobservatoire.' '.$libnbobser.'';?> à gérer.</p><?php
			}
			?>
			<button type="button" class="btn btn-info" id="aide"><span id="btn-aide-txt">Aide</span></button>
			<?php
			if($_SESSION['droits'] == 4)
			{
				?><button type="button" class="btn btn-success mr-2" id="BttA">Ajouter un observatoire</button><?php
			}
			if($nbobservatoire >= 1)
			{
				?><button type="button" class="btn btn-success" id="BttM">Modifier un observatoire</button><?php
			}
			?>
		</div>
	</div>
	<br />
	<div class="row" id="infoaide">
		<div class="col-md-12 col-lg-12">
			<p>
				<b>Discipline :</b> Utiliser par le module bibliographie. Permet de regrouper des observatoires sur une même discipline (ex : Entomologie, regroupe
				les papillons, odonates, ect...).<br />
				<b>Nom :</b> Nom qui sera utilisé dans les différents menus.<br />
				<b>Identifiant :</b> Attention à <b>mettre en minuscule sans caractères spéciaux.</b> Il s'agit de l'identifiant de l'observatoire sur le site. N'apparaît pas à l'affichage, uniquement
				dans le code.<br />
				<b>Nom 1 :</b> Nom de l'observatoire en minuscule (utiliser dans des phrases)<br />
				<b>Nom 2 :</b> Vous pouvez rajouter un second nom (en minuscule). Ce n'est pas obligatoire.<br />
				<b>Case à cocher :</b> Par défaut dans les listes et sur les fiches pour un taxon s'affiche d'abord son nom latin puis son nom français (<i>Lycaena dispar</i> - Le Cuivré des marais). Décocher pour le contraire (Le Cuivré des marais - <i>Lycaena dispar</i>).
			</p>
		</div>
	</div>	
	<div class="row" id="ajout">
		<div class="col-md-6 col-lg-6">
			<div id="mes"></div>
			<form class="form" id="obser">
				<div class="form-group row">
					<label for="disc" class="col-sm-3 col-form-label">Discipline</label>
					<div class="col-sm-9"><input type="text" class="form-control" id="disc" placeholder="Ex : Ornithologie, Malacologie"></div>							
				</div>
				<div class="form-group row">
					<label for="nom" class="col-sm-3 col-form-label">Nom</label>
					<div class="col-sm-9"><input type="text" class="form-control" id="nom" placeholder="Ex : Mammifères, Papillons, Coléoptères"></div>
				</div>
				<div class="form-group row has-danger">
					<label for="nomvar" class="col-sm-3 col-form-label">Identifiant</label>
					<div class="col-sm-9">
						<input type="text" class="form-control form-control-danger text-lowercase" id="nomvar" placeholder="Ex : mam, lepido (en minucule, 10 caractères max)">
						<span class="text-muted">Attention pas d'accents, chiffres et caractères spéciaux'.</span>
					</div>
				</div>
				<div class="form-group row">
					<label for="icon" class="col-sm-3 col-form-label">Icone</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="icon">
						<span class="text-muted">Cliquer sur un icon à droite.</span>
					</div>
				</div>
				<div class="form-group row">
					<label for="couleuricon" class="col-sm-3 col-form-label">Couleur</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="couleuricon" placeholder="Ex : #1D82AA" data-format="hex">
						<span class="text-muted">Vous pouvez choisir une couleur pour l' observatoire.</span>
					</div>
				</div>
				<div class="form-group row">
					<label for="titre" class="col-sm-3 col-form-label">Titre </label>
					<div class="col-sm-9"><input type="text" class="form-control" id="titre" placeholder="Titre de l'observatoire"></div>							
				</div>
				<div class="form-group">
					<label for="descri">Description</label>
					<textarea class="form-control" id="descri" rows="1"></textarea>
				</div>
				<div class="form-group row">
					<label for="metakey" class="col-sm-3 col-form-label">Mots-clés</label>
					<div class="col-sm-9"><input type="text" class="form-control" name="metakey" id="metakey" placeholder="spécifique à cet observatoire (séparé par une ,)"></div>
				</div>
				<div class="form-group row">
					<label for="nomc" class="col-sm-3 col-form-label">Nom 1</label>
					<div class="col-sm-9"><input type="text" class="form-control" id="nomc" placeholder="En minuscule (ex : papillons, libellules, oiseaux)"></div>							
				</div>
				<div class="form-group row">
					<label for="nomdeux" class="col-sm-3 col-form-label">Nom 2</label>
					<div class="col-sm-9"><input type="text" class="form-control" id="nomdeux" placeholder="En minuscule (ex : lépidoptères, odonates, oiseaux)"></div>							
				</div>
				<div class="form-group row">
					<div class="offset-sm-2 col-sm-8">
						<div class="checkbox">
							<label><input type="checkbox" id="latin" value="oui" checked> Décocher pour afficher d'abord le nom vernaculaire</label><br />
						</div>
					</div>
				</div>
				<div class="form-group row">
					<div class="offset-sm-2 col-sm-8">
						<button type="button" class="btn btn-success" id="BttV">Valider</button>
					</div>							
				</div>
			</form>
			<form class="form" id="mod">
				<div class="form-group row">
					<label for="theme" class="col-sm-2 col-form-label">Observatoire</label>
					<div class="col-sm-6">
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
				</div>
				<div class="form-group row">
					<label for="discm" class="col-sm-2 col-form-label">Discipline</label>
					<div class="col-sm-8"><input type="text" class="form-control" id="discm"></div>							
				</div>
				<div class="form-group row">
					<label for="nomm" class="col-sm-2 col-form-label">Nom</label>
					<div class="col-sm-8"><input type="text" class="form-control" id="nomm"></div>
				</div>
				<div class="form-group row">
					<label for="nomvarm" class="col-sm-2 col-form-label">Identifiant</label>
					<div class="col-sm-8"><input type="text" class="form-control" id="nomvarm" disabled></div>
				</div>
				<div class="form-group row">
					<label for="iconm" class="col-sm-2 col-form-label">Icone</label>
					<div class="col-sm-7"><input type="text" class="form-control" id="iconm"></div>
				</div>
				<div class="form-group row">
					<label for="couleuriconm" class="col-sm-2 col-form-label">Couleur</label>
					<div class="col-sm-7"><input type="text" class="form-control" id="couleuriconm" data-format="hex"></div>
				</div>
				<div class="form-group row">
					<label for="titrem" class="col-sm-2 col-form-label">Titre </label>
					<div class="col-sm-10"><input type="text" class="form-control" id="titrem"></div>							
				</div>
				<div class="form-group">
					<label for="descrim">Description</label>
					<textarea class="form-control" id="descrim" rows="1"></textarea>
				</div>
				<div class="form-group row">
					<label for="metakeym" class="col-sm-2 col-form-label">Mots-clés</label>
					<div class="col-sm-10"><input type="text" class="form-control" id="metakeym"></div>
				</div>
				<div class="form-group row">
					<label for="nomcm" class="col-sm-2 col-form-label">Nom 1</label>
					<div class="col-sm-10"><input type="text" class="form-control" id="nomcm"></div>							
				</div>
				<div class="form-group row">
					<label for="nomdeuxm" class="col-sm-2 col-form-label">Nom 2</label>
					<div class="col-sm-10"><input type="text" class="form-control" id="nomdeuxm"></div>							
				</div>
				<div class="form-group row">
					<div class="offset-sm-2 col-sm-8">
						<div class="checkbox">
							<label><input type="checkbox" id="latinm"> Décocher pour afficher d'abord le nom vernaculaire</label><br />
						</div>
					</div>
				</div>
				<div class="form-group row">
					<div class="offset-sm-2 col-sm-8">
						<button type="button" class="btn btn-success" id="BttVm">Valider les modifications</button>
					</div>							
				</div>			
			</form>
			<div id="valajax"><progress></progress></div>
		</div>
		<div class="col-md-6 col-lg-6">
			<div class="row">
				<div class="col-sm-3" style="background-color:#<?php echo $color4bg;?>">
					<div id="choixicon" class="p-2 text-center" style="color:#<?php echo $color1;?>"></div>
				</div>
				<div class="col-sm-9">
					<div id="listeicon">
						<ul class="list-inline">
							<li class="list-inline-item curseurlien idicon" id="fe-amph1"><i class="fe-amph1 fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-amph2"><i class="fe-amph2 fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-araig"><i class="fe-araig fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-araig2"><i class="fe-araig2 fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-arb"><i class="fe-arb fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-arb2"><i class="fe-arb2 fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-bota"><i class="fe-bota fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-chiro"><i class="fe-chiro fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-coleo"><i class="fe-coleo fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-coleo2"><i class="fe-coleo2 fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-coleo3"><i class="fe-coleo3 fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-crus"><i class="fe-crus fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-derma"><i class="fe-derma fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-dipt"><i class="fe-dipt fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-dipt2"><i class="fe-dipt2 fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-foss"><i class="fe-foss fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-foss2"><i class="fe-foss2 fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-gram"><i class="fe-gram fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-gram2"><i class="fe-gram2 fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-hemip"><i class="fe-hemip fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-hemip2"><i class="fe-hemip2 fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-heteroc"><i class="fe-heteroc fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-heteroc3"><i class="fe-heteroc3 fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-hymeno"><i class="fe-hymeno fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-hymeno2"><i class="fe-hymeno2 fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-hymeno3"><i class="fe-hymeno3 fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-heterop"><i class="fe-heterop fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-libell"><i class="fe-libell fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-libell2"><i class="fe-libell2 fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-longi"><i class="fe-longi fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-mamm"><i class="fe-mamm fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-mamm2"><i class="fe-mamm2 fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-mamm3"><i class="fe-mamm3 fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-moll"><i class="fe-moll fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-moll2"><i class="fe-moll2 fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-moll3"><i class="fe-moll3 fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-nevrop"><i class="fe-nevrop fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-rep"><i class="fe-rep fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-rep2"><i class="fe-rep2 fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-rep3"><i class="fe-rep3 fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-ois1"><i class="fe-ois1 fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-ois2"><i class="fe-ois2 fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-ois3"><i class="fe-ois3 fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-ois4"><i class="fe-ois4 fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-ois5"><i class="fe-ois5 fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-ois6"><i class="fe-ois6 fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-ois7"><i class="fe-ois7 fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-ortho"><i class="fe-ortho fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-poiss"><i class="fe-poiss fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-rhopal1"><i class="fe-rhopal1 fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-rhopal2"><i class="fe-rhopal2 fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-rhopal3"><i class="fe-rhopal3 fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-scorp"><i class="fe-scorp fa-3x"></i></li>
							<li class="list-inline-item curseurlien idicon" id="fe-zygn"><i class="fe-zygn fa-3x"></i></li>					
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<input id="idm" type="hidden" value="<?php echo $_SESSION['idmembre'];?>"/><input id="pascouleur" type="hidden" value="<?php echo $color1;?>"/>