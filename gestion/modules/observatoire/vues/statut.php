<section class="container-fluid blanc">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header>	
				<h1>Choix des statuts</h1>
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
		</div>		
	</div>
	<div class="row">
		<div class="col-md-12 col-lg-12">				
			<form>
				<div class="form-group row">
					<label for="choix" class="col-sm-1 col-form-label">Observatoire</label>
					<div class="col-sm-2">
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
					<button type="button" class="btn btn-success mr-2" id="BttA">Ajouter un statut</button> 
					<button type="button" class="btn btn-success" id="BttT">Importer des taxons</button> 
				</div>
				<div id="valajax"><progress></progress></div>
			</form>			
		</div>		
	</div>
	<div class="row">
		<div class="col-md-8 col-lg-8">
			<h2>Liste des statuts</h2>
			<p>
				<b>Type :</b> DH : Directive Européenne, PN : Protection France, PR : Protection Régionale, PD : Ptrotection Départementale, LRE : Liste Rouge Européenne, LRF : Liste Rouge Nationale, LRR : Liste Rouge Régionale, LRD : Liste Rouge Départementale, Z : Znieff, A : Autre, I : Invasif
			</p>
			<p>
				<i class="fa fa-trash text-danger" title="Supprimer ce statut"></i> : Attention si vous supprimer un statut, celui-ci est totalement supprimé du site (disponible pour aucun observatoire).
			</p>
			<table id="liste" class="table table-hover table-sm" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th>Type</th>
						<th>Id</th>
						<th>Article</th>
						<th>Intitulé</th>						
					</tr>
				</thead>
				<tbody id="listestatut"></tbody>
			</table>
		</div>
		<div class="col-md-4 col-lg-4">
			<div id="ajoutstatut">
				<h2>Ajouter / modifier un statut</h2>
				<p>Les statuts ajoutés sont disponible ensuite dans la liste de gauche pour l'ensemble des observatoires.</p>
				<form id="Ajout">
					<div class="form-group row">
						<label for="type" class="col-sm-2 col-form-label">Type</label>
						<div class="col-sm-9">
							<select id="type" class="form-control">
								<option value="NR">--choisir--</option>
								<option value="DH">Directive Européenne</option>
								<option value="PN">Protection France</option>
								<option value="PR">Protection Région</option>
								<option value="PD">Protection Département</option>
								<option value="LRE">Liste Rouge Europe</option>
								<option value="LRF">Liste Rouge France</option>
								<option value="LRR">Liste Rouge Région</option>
								<option value="LRD">Liste Rouge Département</option>
								<option value="Z">Znieff</option>
								<option value="A">Autres</option>
								<option value="I">Invasif</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="idstatut" class="col-sm-2 col-form-label">Id</label>
						<div class="col-sm-10"><input type="text" class="form-control" id="idstatut" placeholder="Ex : LRR1 = Liste Rouge Lepido Régionale"></div>							
					</div>
					<div class="form-group row">
						<label for="article" class="col-sm-2 col-form-label">Article</label>
						<div class="col-sm-10"><input type="text" class="form-control" id="article" placeholder="Si texte de loi"></div>							
					</div>
					<div class="form-group row">
						<label for="lib" class="col-sm-2 col-form-label">Intitulé</label>
						<div class="col-sm-10"><input type="text" class="form-control" id="lib"></div>							
					</div>
					<div class="form-group">
						<label for="ref" class="">Référence (arrêté ou référence)</label>
						<textarea class="form-control" id="ref" rows="1"></textarea>
					</div>					
					<div class="form-group row">
						<label for="url" class="col-sm-2 col-form-label">Lien</label>
						<div class="col-sm-10"><input type="text" class="form-control" id="url"></div>							
					</div>
					<div class="form-group row">
						<label for="annee" class="col-sm-2 col-form-label">Année</label>
						<div class="col-sm-10"><input type="text" class="form-control" id="annee" placeholder="Année de publication"></div>							
					</div>
					<div class="form-group row">
						<div class="offset-sm-2 col-sm-8">
							<button type="button" class="btn btn-success" id="BttV">Valider</button>
						</div>							
					</div>					
					<input id="typeval" type="hidden">
				</form>
			</div>
			<div id="import">
				<h2>Importation de taxons</h2>
				<p>
					Avant d'importer des taxons, assurez vous que le(s) statut(s) est bien présent dans la liste<br />
					Préparer un csv structuré comme cela : <b>(3 colonnes, pas d'entête)</b><br />
					cdnom;id;si liste rouge catégorie (autrement vide)<br />
					Ex : 132223;LRF2;CR<br />
					EX : 132223;Z1;<br />
					<b>Attention à ne pas avoir de doublons (cdnom identique pour même id)</b>
				</p>
				<form id="importtax" enctype="multipart/form-data">
					<div class="form-group row">
						<div class="col-sm-5"><input type="file" name="file" accept=".csv"/></div>
					</div>
					<div class="form-group row">
						<div class="col-sm-5"><button type="submit" class="btn btn-success">Importation</button></div>
					</div>
				</form>
			</div>
			<div id="valajax1"><progress></progress></div>
			<div id="mes"></div>
			<div id="listetaxon"></div>
		</div>
	</div>
</section>