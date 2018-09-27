<section class="container blanc">	
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header>	
				<h1>les 100 dernières modifications</h1>				
			</header>
			<p>Prévoir peut-être de faire mini formulaire de recherche modif par intervalle de date ?</p>
			<table class="table table-sm table-hover">
				<thead>
					<tr>
						<th>TypeId</th>
						<th>Id</th>
						<th>Type Modif</th>
						<th>Modif</th>
						<th>Date</th>
						<th>Membre</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($liste as $n)
					{
						?>
						<tr>
							<td><?php echo $n['typeid'];?></td><td><?php echo $n['numid'];?></td><td><?php echo $n['typemodif'];?></td><td><?php echo $n['modif'];?></td><td><?php echo $n['datemodif'];?></td><td><?php echo ''.$n['prenom'].' '.$n['nom'].'';?></td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</section>