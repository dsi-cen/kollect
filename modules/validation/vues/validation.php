<section class="container-fluid mb-3">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<header>
					<ol class="breadcrumb float-right mb-0">
						<li class="breadcrumb-item active">Information</li>
						<li class="breadcrumb-item"><a href="index.php?module=validation&amp;action=liste">Type de validation</a></li>
					</ol>
					<h1 class="h2">Validation des données</h1>
				</header>
			</div>
		</div>		
	</div>
	<div class="row mt-2">
		<div class="col-md-6 col-lg-6">
			<div class="card card-body">
				<h2 class="h4">Niveaux de validité (SINP)</h2>
				<ul class="list-unstyled ml-3">
					<li><i class="fa fa-check-circle val1"></i><span class="font-weight-bold"> 1 - Certain - très probable.</span><br />La donnée est exacte. Il n’y a pas de doute notable et significatif quant à l’exactitude de l’observation ou de la détermination du taxon.
						La validation a été réalisée notamment à partir d’une preuve de l’observation qui confirme la détermination du producteur ou après vérification auprès de l’observateur et/ou du déterminateur.
					</li>
					<li><i class="fa fa-check-circle val2"></i><span class="font-weight-bold"> 2 - Probable</span><br />
						La donnée présente un bon niveau de fiabilité. Elle est vraisemblable et crédible. Il n’y a, a priori, aucune raison de douter de l’exactitude de la donnée mais il n’y a pas d’éléments complémentaires suffisants disponibles ou évalués (notamment la présence d’une preuve ou la possibilité de revenir à la donnée source) permettant d’attribuer un plus haut niveau de certitude.
					</li>
					<li><i class="fa fa-check-circle val3"></i><span class="font-weight-bold"> 3 - Douteux</span><br />
						La donnée est peu vraisemblable ou surprenante mais on ne dispose pas d’éléments suffisants pour attester d’une erreur manifeste. La donnée est considérée comme douteuse.
					</li>
					<li><i class="fa fa-check-circle val4"></i><span class="font-weight-bold"> 4 - Invalide</span><br />
						La donnée a été infirmée (erreur manifeste/avérée) ou présente un trop bas niveau de fiabilité. Elle est considérée comme trop improbable (aberrante notamment au regard de l’aire de répartition connue, des paramètres biotiques et abiotiques de la niche écologique du taxon, la preuve révèle une erreur de détermination). Elle est considérée comme invalide.
					</li>
					<li><i class="fa fa-check-circle val5"></i><span class="font-weight-bold"> 5 - Non réalisable</span><br />
						La donnée a été soumise à l’ensemble du processus de validation mais l’opérateur (humain ou machine) n’a pas pu statuer sur le niveau de fiabilité. Notamment:<br />
						-Etat des connaissances du taxon insuffisante<br />
						-Informations insuffisantes sur l’observation<br />
					</li>
					<li><i class="fa fa-check-circle"></i><span class="font-weight-bold"> 6 - Non évalué</span><br />
						Niveau initial ou temporaire. La donnée n’a pas été soumise à l’opération de validation ou l’opération n’est pas encore terminée (validation en cours). Elle n’est donc pas évaluée à un temps précis défini par la date de validation.
					</li>
				</ul>
				<h2 class="h4">Validation manuelle/automatique</h2>
				<p>
					Chaque taxon est atribué d'un code :<br />
					- 0 : Espèce validée dès la saisie (non soumise à validation)<br />
					- 1 : Validation automatique par des algorithmes informatiques<br /> 
					- 2 : Validation manuelle.<br />
					Pour les espèces à validation manuelle, une liste d'exigence peut-être définie (photo, genitalia, etc.).<br >
					<a href="index.php?module=validation&amp;action=liste">Voir les listes</a>
				</p>
			</div>
		</div>
		<div class="col-md-6 col-lg-6">
			<div class="card card-body">
				<h2 class="h4">Processus de validation sur le site</h2>
				<p>
					Pour les espèces à validation manuelle ou automatique par filtre : <br /> 
					Chaque donnée saisie (ou modifiée) passe par un "filtre" qui la compare à l'ensemble des données validées contenues dans la base pour l'espèce (dates d'observation, maille, experience observateur, etc.).<br />
					A l'issue de ce filtre, soit la donnée est validée, soit elle passe en validation manuelle avec le code 6 <i class="fa fa-check-circle"></i>.<br />
					Note : Les espèces pour lesquelles une validation manuelle est exigée passent également par le filtre, ceci permettant aux validateurs de mieux appréhender la donnée par la suite.<br />
					Un validateur peut à tout moment revenir sur une validation.								
				</p>
				<h2 class="h4">Visibilité des espèces sur le site</h2>
				<p>
					- Sur les fiches : uniquement les espèces validées probable à certain<br />
					- Sur la page listant les observations : toutes les données avec distinction de couleur sur le picto.
				</p>
				<h2 class="h4">Validateurs</h2>
				<?php
				if(isset($validateur))
				{
					?>
					<table class="table table-sm table-hover">
						<tbody>
							<?php
							foreach($validateur as $n)
							{
								?>
								<tr>
									<td><i class="<?php echo $n['icon'];?> fa-lg"></i> <?php echo $n['observa'];?></td><td><?php echo $n['nom'];?></td>
								</tr>
								<?php
							}
							?>
						</tbody>
					</table>
					<?php
				}				
				?>
			</div>
		</div>
	</div>
</section>