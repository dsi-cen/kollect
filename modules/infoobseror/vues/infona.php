<section class="container">
	<header class="row">
		<div class="col-md-12 col-lg-12 m-t-1">
			<div class="card card-body">
				<h1 class="h2"><?php echo $titre;?> <?php echo $favatar;?></h1>
			</div>
		</div>
	</header>
	<div class="row">
		<div class="col-md-12 col-lg-12">
			<div class="card card-body">
				<p>
					Page qui affichera les info sur cet observateur. Possibilté d'envoyer un mail si autorisation etc...	<br />
					ex : 									
				</p>
				<table class="table table-sm table-hover">
					<thead>
						<tr>
							<th></th>
							<th>Nb observation</th>
							<th>Nb espèces</th>
						</tr>
					</thead>
					<tbody>
					<?php 
					foreach($tab as $n)
					{
						?>
						<tr>
							<td><?php echo $n['nom'];?></td><td><?php echo $n['nb'];?></td><td><?php echo $n['nbsp'];?></td>
						</tr>
						<?php
					}
					if(count($tab) > 1)
					{
						?>
						<tr class="table-active">
							<td>Total</td><td><?php echo $nbobs1;?></td><td><?php echo $nbsp;?></td>
						</tr>
						<?php
					}
					?>
					</tbody>
				</table>				
				<p>
					En liaison avec le module social si activé (-> A faire par Yohan)
				</p>
			</div>
		</div>
    </div>	
</section>