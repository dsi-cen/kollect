<section class="container-fluid blanc">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header>	
				<h1>Gestion des indices de rareté</h1>
			</header>		
			<form class="form-inline">
				<div class="form-group">
					<?php
					if ($nbobservatoire == 0)
					{
						?><p class="form-control-plaintext text-warning">Aucun observatoire pour l'instant sur le site</p><?php
					}
					else
					{
						?><p class="form-control-plaintext">Vous avez <?php echo ''.$nbobservatoire.' '.$libnbobser.'';?> à gérer.</p><?php
					}
					?>				
					<label for="choix" class="sr-only">Observatoire</label>					
					<select id="choix" class="form-control ml-2">
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
					<button type="button" class="btn btn-info ml-2" id="aide"><span id="btn-aide-txt">Aide</span></button>
				</div>			
			</form>			
		</div>
	</div>
	<div class="row" id="infoaide">
		<div class="col-md-12 col-lg-12">
			<p class="mb-0 mt-2">
				- Indiquez votre choix de maillage, puis choisissez, soit un nombre minimum d'observation par maille, ou bien un nombre minimum d'epèces par maille. Vous pouvez aussi indiquer une année, le calcul des indices se faisant alors qu'à partir de celle-ci.<br />
				- Utilisez ensuite le bouton "Tester" afin de voir (à droite) les seuils des classes de rareté.<br />
				- Cliquez sur le bouton "Valider" pour valider vos choix de bornes.<br />
				- Si vous avez définit les indices et ne souhaitez ne plus les utilisés, cliquez sur "Ne plus utiliser".<br />
				- Le calcul des indices peut être assez long. Les indices d'un observatoire sont automatiquement mis à jour (de manière asynchrone) dès qu'un utilisateur se trouve sur la page d'accueil de l'observatoire.<br />
				Cependant afin de pas "chargé" le serveur et eviter de faire des mises à jour qui s'enchaines, celle-ci s'effectuera que si la dernière a été faite depuis au moins 30 minutes.<br />
				Afin que vous puissiez voir (en cas de modification des bornes) le résultat, vous pouvez utiliser le bouton "Mise à jour".<br />
				Nb : Si vous utilisez la borne année et que pour une espèce il y a aucune observation depuis, celle-ci aura comme indice : D? (présumé disparu).
			</p>
		</div>
	</div>
	<hr />
	<div class="row" id="aff">
		<div class="col-md-4 col-lg-4">
			<form>
				<?php
				if(isset($emprise['nbmaille5']))
				{
					?>
					<div class="form-check form-check-inline">
						<label class="form-check-label">
							<input class="form-check-input" type="radio" name="maillage" id="maille10" value="l93"> Maillage 10 km
						</label>
					</div>
					<div class="form-check form-check-inline">
						<label class="form-check-label">
							<input class="form-check-input" type="radio" name="maillage" id="maille5" value="l935"> Maillage 5 km
						</label>
					</div>					
					<?php					
				}
				else
				{
					?>
					<div class="form-check form-check-inline">
						<label class="form-check-label">
							<input class="form-check-input" type="radio" name="maillage" id="maille10" value="l93"> Maillage 10 km
						</label>
					</div>
					<?php
				}
				?>
				<div class="form-group row">
					<label for="mt" class="col-sm-5 col-form-label">Nb maille total</label>
					<div class="col-sm-3"><input type="text" class="form-control" id="mt" disabled></div>
				</div>
				<div class="form-group row">
					<label for="m" class="col-sm-5 col-form-label">m</label>
					<div class="col-sm-3"><input type="number" class="form-control" id="m" value="<?php //echo $m;?>" disabled></div>
				</div>
				<div class="form-group row">
					<label for="Mm" class="col-sm-5 col-form-label">M</label>
					<div class="col-sm-3"><input type="text" class="form-control" id="Mm" disabled></div>
				</div>
				
			</form>
			<p>
				Vous pouvez définir "m" par un nombre d'espèces ou d'observations<br />
				Ex : "m" = nombre de mailles avec - de 200 observations.<br />
				<b>Calcul de m :</b>
			</p>
			<form>
				<div class="form-check form-check-inline">
					<label class="form-check-label">
						<input class="form-check-input" type="radio" name="choixm" id="choixobs" value="obs"> Observation
					</label>
				</div>
				<div class="form-check form-check-inline">
					<label class="form-check-label">
						<input class="form-check-input" type="radio" name="choixm" id="choixes" value="es"> Espèce
					</label>
				</div>
				<div class="form-group row">
					<label for="obs" class="col-sm-5 col-form-label">Nb observations minimum</label>
					<div class="col-sm-3"><input type="number" class="form-control" id="obs"></div>
				</div>
				<div class="form-group row">
					<label for="es" class="col-sm-5 col-form-label">Nb espèces minimum</label>
					<div class="col-sm-3"><input type="number" class="form-control" id="es"></div>
				</div>
				<p>
					Vous pouvez mettre une "borne" année. Si renseigné, le calcul se fera seulement sur les observations effectuées depuis
				</p>
				<div class="form-group row">
					<label for="date" class="col-sm-5 col-form-label">Année >=</label>
					<div class="col-sm-3"><input type="number" class="form-control" id="date"></div>
				</div>
				<div class="form-group row">
					<div class="col-sm-10">
						<button id="BttT" type="button" class="btn btn-success">Tester</button>
						<button id="BttV" type="button" class="btn btn-success">Valider</button>
						<button id="BttS" type="button" class="btn btn-warning">Ne plus utiliser</button>
						<button id="BttM" type="button" class="btn btn-warning">Mise à jour</button>
					</div>
				</div>
			</form>
			<div id="mes"></div>
		</div>
		<div class="col-md-8 col-lg-8">
			<table class="table table-sm table-hover">
				<thead class="thead-light border-top-0">
					<tr>
						<th class="border-bottom-0"></th><th class="border-bottom-0"></th>
						<th colspan="4" class="text-center bg-warning">Ir</th>
						<th colspan="4" class="text-center bg-danger">Ird</th>
					</tr>
					<tr>
						<th class="border-bottom-0 border-top-0"></th><th class="border-bottom-0 border-top-0"></th>
						<th colspan="2" class="text-center">Coeff rareté</th>
						<th colspan="2" class="text-center">Nb mailles</th>
						<th colspan="2" class="text-center">Coeff rareté p</th>
						<th colspan="2" class="text-center">Nb mailles</th>
					</tr>
					<tr>
						<th class="border-top-0"></th><th class="border-top-0"></th>
						<th class="text-center"><</th>
						<th class="text-center">>=</th>
						<th></th><th></th>
						<th class="text-center"><</th>
						<th class="text-center">>=</th>
						<th></th><th></th>
					</tr>
				</thead>
				<tbody class="text-center" id="tab"></tbody>
			</table>
			<div id="ex"></div>
		</div>		
	</div>	
</section>
<input type="hidden" value="<?php echo $emprise['nbmaille'];?>" id="nbl93">
<input type="hidden" value="<?php echo $l935;?>" id="nbl935">
<input type="hidden" value="<?php //echo $m;?>" id="choixm">