<section class="container-fluid blanc">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<form class="form-inline">
				<label for="rtax">Recherche d'une espèce </label>
				<input type="text" placeholder="Rechercher une espèce" class="form-control ml-2" id="rtax" size="40">
			</form>				
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header>	
				<h1 class="h2">Gestion de <?php echo $nom;?></h1>
			</header>					
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 col-lg-12">	
			<ul class="nav nav-tabs mt-2" role="tablist" id="onglet">
				<li class="nav-item"><a class="nav-link active" href="#info" role="tab" data-id="info" data-toggle="tab">Info espèce</a></li>
				<li class="nav-item"><a class="nav-link" href="#taxonomie" role="tab" data-id="taxonomie" data-toggle="tab">Taxonomie</a></li>
				<li class="nav-item"><a class="nav-link" href="#synthese" role="tab" data-id="synthese" data-toggle="tab">Synthèse</a></li>				
				<li class="nav-item"><a class="nav-link" href="#repartition" role="tab" data-id="repartition" data-toggle="tab">Répartition</a></li>
				<li class="nav-item"><a class="nav-link" href="#ecologie" role="tab" data-id="ecologie" data-toggle="tab">Ecologie</a></li>
				<li class="nav-item"><a class="nav-link" href="#biologie" role="tab" data-id="biologie" data-toggle="tab">Biologie</a></li>
				<li class="nav-item"><a class="nav-link" href="#plante" role="tab" data-id="plante" data-toggle="tab">Plante hôte</a></li>
				<li class="nav-item"><a class="nav-link" href="#prospection" role="tab" data-id="prospection" data-toggle="tab">Prospection</a></li>
				<li class="nav-item"><a class="nav-link" href="#similaire" role="tab" data-id="similaire" data-toggle="tab">Similaire</a></li>
				<li class="nav-item"><a class="nav-link" href="#critere" role="tab" data-id="critere" data-toggle="tab">Critères</a></li>					
			</ul>
			<div class="tab-content">
				<div class="tab-pane fade show active" id="info" role="tabpanel">					
					<h2 class="h6 mt-2">Locale</h2>
					<form class="form-inline">
						<input id="locale" type="text" class="form-control form-control-sm" value="<?php echo $locale;?>" size="5">
						<label for="locale" class="ml-2">(Présente sur l'emprise ou pas) -> Modifier</label>
						<select id="localec" class="form-control form-control-sm ml-2">
							<option value="" >Choisir</option>
							<option value="oui" >Oui</option>
							<option value="non" >Non</option>
						</select>
					</form>
					<h2 class="h6 mt-3">Info sur le taxon</h2>
					<form>	
						<div class="form-group row">
							<label class="col-sm-1 col-form-label" for="nom">Nom</label>
							<div class="col-sm-3"><input class="form-control" id="nom" type="text" value="<?php echo $nom;?>"></div>
						</div>
						<div class="form-group row">
							<label class="col-sm-1 col-form-label" for="nomf">Nom Fr</label>
							<div class="col-sm-3"><input class="form-control" id="nomf" type="text" value="<?php echo $nomfr;?>"></div>
						</div>
						<div class="form-group row">
							<label class="col-sm-1 col-form-label" for="auteur">Auteur</label>
							<div class="col-sm-3"><input class="form-control" id="auteur" type="text" value="<?php echo $inventeur;?>"></div>
						</div>
						<div class="form-group row">
							<label class="col-sm-1 col-form-label" for="taxref">cdnom</label>
							<div class="col-sm-1"><input class="form-control" id="taxref" type="text" value="<?php echo $id;?>"></div>
							<label class="col-sm-1 col-form-label text-right" for="rang">Rang</label>
							<div class="col-sm-2"><input class="form-control" id="rang" type="text" value="<?php echo $rang;?>"></div>
						</div>
					</form>
					<h2 class="h6 mt-3">Taxonomie</h2>
					<?php
					if(isset($taxo))
					{
						if(isset($taxo['sousgenre']))
						{
							?>-> (Sous genre) <?php echo $taxo['sousgenre'];?> <?php
						}
						if(isset($taxo['genre']))
						{
							?>-> (Genre) <?php echo $taxo['genre'];?> <?php
						}
						if(isset($taxo['soustribu']))
						{
							?>-> (Sous tribu) <?php echo $taxo['soustribu'];?> <?php
						}
						if(isset($taxo['tribu']))
						{
							?>-> (Tribu) <?php echo $taxo['tribu'];?> <?php
						}
						if(isset($taxo['sousfamille']))
						{
							?>-> (Sous famille) <?php echo $taxo['sousfamille'];?> <?php
						}
						?>-> (Famille) <?php echo $famille;?> <?php
					}
					else
					{
						?>-> (Famille) <?php echo $famille;?> <?php
					}
					?>
				</div>
				<div class="tab-pane fade" id="taxonomie">
					<h2 class="h6 mt-2">Taxonomie</h2>
					Voir pour modif taxo ?
				</div>
				<div class="tab-pane fade" id="synthese">
					
				</div>
				
			</div>
		</div>
	</div>	
</section>