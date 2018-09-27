<section class="container blanc">
	<div class="row">
		<div class="col-md-12 col-lg-12">
			<header class="mt-3">
				<h1 class="">Administration du site</h1>
			</header>
			<hr />
			<?php
			if(!isset($_SESSION['virtobs']))
			{
				if(isset($rjson_site['observatoire']))
				{
					if($vali !== false)
					{
						?>
						<blockquote class="blockquote">
							<p>
							<?php
							foreach($tabvali as $n)
							{
								if($n['nb'] == 0)
								{
									?><i class="<?php echo $n['icon'];?>"></i>&nbsp;&nbsp;Aucune donnée actuellement à valider pour l'observatoire <?php echo $n['obser'];?><br /><?php
								}
								else
								{
									?><span class="text-primary"><i class="<?php echo $n['icon'];?>"></i>&nbsp;&nbsp;<?php echo $n['nb'];?></span> donnée(s) à valider pour l'observatoire <?php echo $n['obser'];?> <a href="index.php?module=validation&amp;action=toute&amp;d=<?php echo $n['nomvar'];?>"><i class="fa fa-eye text-primary"></i></a><br /><?php
								}					
							}
							?>
							</p>
						</blockquote>
						<?php
						if(isset($tabvali7))
						{
							?>
							<blockquote class="blockquote">
								<p>
								<?php
								foreach($tabvali7 as $n)
								{
									?><span class="text-danger"><i class="<?php echo $n['icon'];?>"></i>&nbsp;&nbsp;<?php echo $n['nb'];?></span> donnée(s) à valider dont l'espèce a été ajoutée à la liste pour l'observatoire <?php echo $n['obser'];?> <a href="index.php?module=validation&amp;action=toute&amp;d=<?php echo $n['nomvar'];?>&amp;new=oui"><i class="fa fa-eye text-danger"></i></a><br /><?php
								}
								?>
								</p>
							</blockquote>
							<?php
						}
						?>
						<blockquote class="blockquote">
							<p>
							<?php
							foreach($tabdet as $n)
							{
								if($n['nb'] > 0)
								{
									if($n['nbv'] > 0)
									{
										?><span class="text-success"><i class="<?php echo $n['icon'];?>"></i>&nbsp;&nbsp;<?php echo $n['nbv'];?></span> demande(s) de détermination validée(s) pour l'observatoire <?php echo $n['obser'];?> <a href="../index.php?module=det&amp;action=liste&amp;d=<?php echo $n['nomvar'];?>"><i class="fa fa-eye text-success"></i></a><br /><?php
									}
									if($n['nbn'] >= 1)
									{
										?><span class="text-warning"><i class="<?php echo $n['icon'];?>"></i>&nbsp;&nbsp;<?php echo $n['nbn'];?></span> demande(s) de détermination en cours pour l'observatoire <?php echo $n['obser'];?> <a href="../index.php?module=det&amp;action=liste&amp;d=<?php echo $n['nomvar'];?>&amp;f=2"><i class="fa fa-eye text-warning"></i></a><br /><?php
									}							
								}											
							}
							?>
							</p>
						</blockquote>
						<?php					
					}
				}
			}
			if(isset($_SESSION['virtobs']))
			{
				?>
				<p>
					Vous êtes connecté sous l'id membre virtuel : <?php echo $_SESSION['idmembre'];?>
					<br />Votre id membre réel : <?php echo $_SESSION['idmorigin'];?>
				</p>
				<?php
			}				
			?>			
		</div>
	</div>	
</section>