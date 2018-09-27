<section class="container-fluid blanc">
	<div class="row">
		<div class="col-md-12 col-lg-12">
			<header class="mt-3">	
				<h1>Utilisateurs actuel</h1>
			</header>		
			<p>Utilisateurs actifs durant les 5 dernières minutes</p>
			<?php
			if(count($utilisateur) > 0)
			{
				?>
				<table class="table table-hover table-sm">
					<thead>
						<tr>
							<th>Ip</th><th>Membre</th><th>Agent</th><th>Viens de</th><th>Se trouve</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach($utilisateur as $n)
						{
							$membre = ($n['nom'] != '') ? $n['prenom'].' '.$n['nom'] : '';
							?>
							<tr>
								<td><?php echo $n['ip'];?></td><td><?php echo $membre;?></td><td><?php echo $n['agent'];?></td><td><?php echo $n['referer'];?></td><td><?php echo $n['uri'];?></td>							
							</tr>
							<?php
						}
						?>
					</tbody>
				</table>
				<?php
			}
			else
			{
				?><p>Aucun utilisateur actuellement sur le site</p><?php
			}
			?>
			<p>A voir par la suite : pouvoir permettre de bloquer une Ip, un robot ? </p>
		</div>
	</div>	
</section>
