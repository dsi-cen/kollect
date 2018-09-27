<section class="container blanc">	
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header>	
				<h1>Suivi des sessions "virtuelles"</h1>				
			</header>
			<p>les 100 dernières sessions :</p>
			<table class="table table-sm table-hover">
				<thead>
					<tr>
						<th>Nom virtuel</th>						
						<th>Id</th>
						<th>TypeId</th>
						<th>Date</th>
						<th>Membre origine</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($liste as $n)
					{
						?>
						<tr>
							<td><?php echo $n['nomvirtuel'];?></td><td><?php echo $n['idsession'];?></td><td><?php echo $n['typeid'];?></td><td><?php echo $n['datevirt'];?></td><td><?php echo $n['prenom'].' '.$n['nom'];?></td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</section>