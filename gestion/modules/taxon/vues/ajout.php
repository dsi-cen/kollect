<section class="container blanc">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header class="mt-1">	
				<h1 class="h2">Ajout d'espèces et/ou complexe d'espèces</h1>
			</header>					
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">	
			<h2 class="h4">Espèce</h2>
			<p>
				Il est possible de rajouter des espèces qui ne seraient pas dans Taxref (en attendant qu'elles soient inclusent dans la prochaine version).<br />
				Pour rajouter une espèce, contacter l'INPN en indiquant l'espèce manquante ainsi que les références bibliographiques qui attestent la présence en France de cette espèce.<br />
				Demander à avoir le cdnom de cette nouvelle espèce une fois celle-ci ajouté à Taxref. Une fois le cdnom récupéré vous pouvez rajouter l'espèce.
			</p>
			<form id="ajoutsp" novalidate>
				<div class="form-inline">
					<input type="text" class="form-control" name="cdnom" placeholder="cdnom" required>
					<input type="text" class="form-control ml-2" name="genre" placeholder="Genre" required>
					<input type="text" class="form-control ml-2" name="espece" placeholder="Espèce" required>
					<input type="text" class="form-control ml-2" name="auteur" placeholder="Descripteur" required>
					<select class="form-control ml-2" name="observa" required>
						<option value="">Observatoire</option>
						<?php
						foreach($menuobservatoire as $n)
						{
							?>
							<option value="<?php echo $n['nomvar'];?>"><?php echo $n['nom'];?></option>
							<?php
						}
						?>
					</select>
				</div>
				<div class="form-inline mt-2">
					<button type="submit" class="btn btn-success">Valider</button> -> A finir
				</div>
			</form>
			<h2 class="h4 mt-3">Complexe</h2>
			<p>
				Vous pouvez créer un "complexe" d'espèces proches. Le complexe crée apparaîtra dans la liste de choix lors de la saisie.<br />
				Les espèce du complexe seront automatiquement mise comme similaire.
			</p>
			<p>Le nom du "complexe" doit-être en latin ex : <i>Acronicta sp(psi/tridens)</i>. Vous devez indiquer au moins deux espèces au complexe. Celles-ci doivent avoir le même genre.</p>
			<form id="com" novalidate>
				<div class="form-inline">
					<input type="text" class="form-control" name="nom" id="nomcom" placeholder="Nom du complexe" required>
					<select class="form-control ml-2" name="observa" id="observacom" required>
						<option value="">Observatoire</option>
						<?php
						foreach($menuobservatoire as $n)
						{
							?>
							<option value="<?php echo $n['nomvar'];?>"><?php echo $n['nom'];?></option>
							<?php
						}
						?>
					</select>
					<input type="text" class="form-control ml-2" id="rtax" placeholder="Chercher une espèce">
					<input type="text" class="form-control ml-2" name="cdnom" id="cdnom" placeholder="cdnom" required readonly>
					<input type="hidden" id="nbsp" value="0">
				</div>
				<div class="form-inline mt-2">
					<button type="submit" class="btn btn-success">Valider</button>
					<button id="efface" type="button" class="btn btn-warning ml-2">Effacer pour en créer un nouveau</button>
					<button id="voircom" type="button" class="btn btn-info ml-2">Voir la liste des complexes déjà créer</button>
				</div>
			</form>
			<p id="choixcom" class="mt-2"></p>
			<div id="mes"></div>			
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div id="tblcom"></div>
		</div>
	</div>
</section>