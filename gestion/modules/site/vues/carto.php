<section class="container-fluid blanc">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header>		
				<h1 class="h2">Gestion de la cartographie</h1>
			</header>
			<hr />
			<div id="mes"></div>
			<form id="site" method="post">
				<div class="form-group row">
					<label for="ign" class="col-sm-1 col-form-label">Clé IGN</label>
					<div class="col-sm-3"><input type="text" class="form-control" id="ign" placeholder="Mettre ici votre clé IGN" value="<?php echo $cleign;?>"></div>
					<label for="couche" class="col-sm-2 col-form-label">Couche par défaut</label>
					<div class="col-sm-2">
						<select id="couche" class="form-control">							
							<option <?php if($couche == 'osm') {echo 'selected="selected"';}?>  value="osm" name="type">OSM</option>
							<option <?php if($couche == 'osmfr') {echo 'selected="selected"';}?> value="osmfr" name="type">OSMfr</option>
							<option <?php if($couche == 'topo') {echo 'selected="selected"';}?> value="topo" name="type">OSM Topo</option>
							<option <?php if($couche == 'ign') {echo 'selected="selected"';}?> value="ign" name="type">IGN</option>
							<option <?php if($couche == 'photo') {echo 'selected="selected"';}?> value="photo " name="type">Photo IGN</option>							
						</select>
					</div>
				</div>
				<?php				
				if($rjson_emprise['emprise'] != 'fr')
				{
					?>
					<div class="form-group row">
						<?php
						if($rjson_emprise['contour2'] == 'oui')
						{
							?><div class="col-sm-2"><p class="form-control-plaintext mb-0"><b>Couche départements</b></p></div><?php
						}
						else
						{
							?><div class="col-sm-2"><p class="form-control-plaintext mb-0"><b>Emprise</b></p></div><?php
						}
						?>
						<label for="color2" class="col-sm-1 col-form-label">Couleur</label>
						<div class="col-sm-1"><input type="text" class="form-control" id="color2" value="<?php echo $color2;?>"></div>
						<label for="weight2" class="col-sm-1 col-form-label">Epaisseur</label>
						<div class="col-sm-1"><input type="number" min="1" max="9" class="form-control" id="weight2" value="<?php echo $weight2;?>"></div>
						<div class="col-sm-4"><p class="form-control-plaintext mb-0">(Défaut = #B27335, 3)</p></div>
					</div>
					<?php						
				}				
				?>				
				<div class="form-group row">
					<?php
					if($rjson_emprise['emprise'] != 'fr')
					{
						?><div class="col-sm-2"><p class="form-control-plaintext mb-0"><b>Couche communes</b></p></div><?php
					}
					else
					{
						?><div class="col-sm-1"><p class="form-control-plaintext mb-0"><b>Couche départements</b></p></div><?php
					}
					?>
					<label for="color" class="col-sm-1 col-form-label">Couleur</label>
					<div class="col-sm-1"><input type="text" class="form-control" id="color" value="<?php echo $color;?>"></div>
					<label for="weight" class="col-sm-1 col-form-label">Epaisseur</label>
					<div class="col-sm-1"><input type="number" min="1" max="9" class="form-control" id="weight" value="<?php echo $weight;?>"></div>					
					<label for="opacity" class="col-sm-1 col-form-label">Opacité</label>
					<div class="col-sm-1"><input type="text" class="form-control" id="opacity" value="<?php echo $opacity;?>"></div>
					<div class="col-sm-4"><p class="form-control-plaintext mb-0">(Défaut = #03f, 5, 0.2)</p></div>
				</div>
				<div class="form-group row">
					<div class="col-sm-2"><p class="form-control-plaintext mb-0"><b>Couche mailles</b></p></div>
					<label for="colorm" class="col-sm-1 col-form-label">Couleur</label>
					<div class="col-sm-1"><input type="text" class="form-control" id="colorm" value="<?php echo $colorm;?>"></div>
					<label for="weightm" class="col-sm-1 col-form-label">Epaisseur</label>
					<div class="col-sm-1"><input type="number" min="1" max="9" class="form-control" id="weightm" value="<?php echo $weightm;?>"></div>
					<label for="opacitym" class="col-sm-1 col-form-label">Opacité</label>
					<div class="col-sm-1"><input type="text" class="form-control" id="opacitym" value="<?php echo $opacitym;?>"></div>
					<div class="col-sm-4"><p class="form-control-plaintext mb-0">(Défaut = #ff7800, 5, 0.2)</p></div>
				</div>
				<div class="form-inline mb-3">
					<label for="proche" class="mr-2">Distance site proches (0.3 par exemple pour 300 mètres)</label>
					<input type="text" class="form-control" id="proche" value="<?php echo $proche;?>">
					<?php
					if($lambert5 == 'oui')
					{
						?>
						<label for="l935" class="ml-3 mr-2">Nombre de mailles 5 sur l'emprise</label>
						<input type="text" class="form-control" id="l935" value="<?php echo $l935;?>">
						<?php
					}
					else
					{
						?><input id="l935" type="hidden" value="non"/><?php
					}
					?>
				</div>
				<div class="form-group row">
					<div class="col-sm-8">
						<button type="button" class="btn btn-success" id="BttV">Valider les modifications</button>
					</div>							
				</div>	
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-6 col-md-6">
			<div class="card">
				<div class="card-body" id="map"></div>
			</div>
		</div>
		<div class="col-lg-6 col-md-6">
			<?php echo $infocontour;?>
			<p>
				Moins le fichier est lourd plus vite se fera l'affichage<br />
				Il est possible de réduire la taille en récupérant le fichier avec votre ftp dans le répertoire emprise. (faite une sauvegarde avant les modifications)<br />
				- Vous pouvez l'ouvrir avec votre logiciel SIG et simplifier sa géométrie. Attention de bien l'enregistrer en Lambert 93 (EPSG:2154)<br />
				- Vous pouvez utiliser l'outil en ligne <a href="http://www.mapshaper.org/">mapshaper</a> en utilisant "simplify". Attention lors de l'export l'extension de votre fichier
				est "json". Renommer le en "geojson" puis remplacer le via ftp.
			</p>
			<h2 class="h4">Ajout de couche</h2>
			<button type="button" class="btn" id="BttA">Ajout</button>
			<div id="ajout">
				<form>
					<div class="form-group row">
						<label for="titre" class="col-sm-3 col-form-label">Titre</label>
						<div class="col-sm-9"><input type="text" class="form-control" id="titre" placeholder="titre de la couche"></div>
					</div>
					<div class="form-group row">
						<label for="stitre" class="col-sm-3 col-form-label">Sous titre</label>
						<div class="col-sm-9"><input type="text" class="form-control" id="stitre" placeholder="Info supplémentaire si besoin (si Choroplète)"></div>
					</div>
					<div class="form-group row">
						<label for="type" class="col-sm-2 col-form-label">Type</label>
						<div class="col-sm-3">
							<select id="type" class="form-control">							
								<option value="choro">Choroplète</option>
								<option value="gen" name="type">Général</option>							
							</select>
						</div>
						<label for="uni" class="col-sm-2 col-form-label">Unité</label>
						<div class="col-sm-4"><input type="text" class="form-control" id="uni" placeholder="Unité pour la légende"></div>
					</div>
					<div class="form-group row">
						<div class="col-sm-8">
							<button type="button" class="btn btn-success" id="BttC">Valider</button>
						</div>							
					</div>
					<div id="mescouche"></div>
				</form>
			</div>
			<p>		
				Il est possible de rajouter des couches (pour la page cartographie). Ces couches doivent-être aux format geojson et les geometries en lambert 93. Vos fichiers doivent-être nommé comme ceci : couche1.geojson, couche2.geojon, etc...<br />
				<b>-> couche général</b> (ex: régions naturelles) : ces couches seront rajoutées dans le gestionnaire de couche de leaflet (comme les mailles).<br />
				<b>Ex: de fichiers :</b> (des = description)
			</p>
<pre>
{ "type": "FeatureCollection",
	"crs":{"type":"name","properties":{"name":"urn:ogc:def:crs:EPSG::2154"}},
	"features":[
		{"type":"Feature",
			"geometry":{"type":"Polygon","coordinates":[[[.....]]]},
			"properties":{"id":2,"couleur":"#CEE77B","des":"Continental"},
		}
	]	
}
</pre>
			<p>	
				<b>-> couche choroplèthe</b> (ex : température moyenne) : ces couches sont rajoutées en dehors du gestionnaire de couche de leaflet <br />
				<b>Ex: de fichiers :</b> 
			</p>
<pre>
{ "type": "FeatureCollection",
	"crs":{"type":"name","properties":{"name":"urn:ogc:def:crs:EPSG::2154"}},
	"nbchoro": 3, (votre nombre de classe)
	"choro": {
		"class1": {"co":"#eff3ff","val":700}, (co = couleur, val = valeur)
		"class2": {"co":"#bdd7e7","val":750},
		"class3": {"co":"#6baed6","val":800}
	}
	"features":[
		{"type":"Feature",
			"geometry":{"type":"Polygon","coordinates":[[[.....]]]},
			"properties":{"val":710},
		}
	]	
}
</pre>
			<p>
				Pour les couleurs des classes vous pouvez vous inspirez de <a href="http://colorbrewer2.org/">ColorBrewer</a>. Vous pouvez mettre au maximum 8 classes<br />
				L'analyse des class est faite de cette façon (ex : 3 class du dessus) :
			</p>
<pre>
valeur > valeur de la class1.val et valeur < valeur de la class2 = couleur class1
valeur >= valeur de la class2 et valeur < valeur de la class3 = couleur class2
valeur >= valeur de la class3 = couleur class3
</pre>
		</div>
	</div>
</section>
<input id="emp" type="hidden" value="<?php echo $rjson_emprise['emprise'];?>"/>