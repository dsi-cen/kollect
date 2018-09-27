<section class="container blanc">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header>	
				<h1>Gestion des espèces</h1>
			</header>					
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 col-lg-12">
			<hr />
			<h2 class="h5">Rechercher une espèce</h2>
			<p>Recherche d'une espèce grâce à la liste de saisie semi-automatique (autocomplétion)</p>
			<form class="form-inline">
				<input type="text" placeholder="Rechercher une espèce" class="form-control" id="rtax" size="40">
			</form>
			<h2 class="h5 mt-3">Rajouter une espèce ou créer un complexe d'espèces</h2>
				<p>
					Il est possible de rajouter des espèces qui ne seraient pas dans taxref (en attendant qu'elles soient inclusent dans la prochaine version)<br />
					Il est possible de créer des "complexes d'espèces" (espèces proches souvent indicernable à l'habitus ex : <i>Acronicta psi/tridens</i>)<br />
					<a href="index.php?module=taxon&amp;action=ajout">c'est ici</a>
				</p>
			<h2 class="h5">Glossaire</h2>
			<input id="glo" type="hidden" value="oui"/>	
			<div id="blocinfo">
				<p>
					Si vous apportez des informations pour les fiches espèces (répartition, écologie, biologie, etc...), vous pouvez à partir de <a href="index.php?module=taxon&amp;action=glossaire">cette page</a>
					renseigné un glossaire. Ce glossaire sera ensuite affiché par une infobulle dans le texte<br />
					Ex : ... les genitalia d'Oligia strigilis....
				</p>
			</div>			
		</div>
	</div>
	
</section>