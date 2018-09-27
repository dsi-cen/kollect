<section class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12 m-t-1">
            <div class="card card-body">
                <div class="row">
                    <div class="col-md-1 col-lg-1 m-t-1">
						<?php echo $favatar;?>
                    </div>
                    <div class="col-md-4 col-lg-4 m-t-1">
                        <h1 class="h2"><?php echo $titre;?></h1>
                    </div>
                    <div class="col-md-2 col-lg-2 m-t-2">
						<span class="pe-7s-users fa-2x"></span><span class="tag tag-success tag-pill pull-xs-left"><output id="abo"><?php echo $abo;?></output> Abonnées</span>
					</div>
					<div class="col-md-2 col-lg-2 m-t-2">
						<span class="pe-7s-user fa-2x" id="abonnement"></span><span class="tag tag-info tag-pill pull-xs-left"><?php echo $folo;?>  Abonnements</span>
					</div>
					<?php 
					if($idm == $idcompare)
					{ 
						?>
						<div class="col-md-2 col-lg-2 m-t-2">
							<a href="index.php?module=infoobser&amp;action=pref&amp;idobser=<?php echo $idobser;?>"" rel="nofollow" title="Préférences" >
								<span class="pe-7s-config fa-2x"></span>
								<span class="tag tag-warning tag-pill pull-xs-left"> Préférences</span>
							</a>
						</div>
						<?php 
					}	
					?>
				</div>
            </div>
        </div>
    </div>
	<div class="row">
		<div class="col-md-6 col-lg-6">
			<div class="card card-body">
				<h2 class="h4"><span class="tag tag-info"><?php echo $folo;?></span> ABONNEMNENTS</h4>
				<?php
				if(isset($tababonnement))
				{
					?>
					<table class="table table-hover">
						<tbody>
							<?php
							foreach($tababonnement as $n)
							{								
								?>
								<tr>
									<td><img class="img-circle" src="<?php echo $n['avatar'];?>" width=30 height=30 alt="avatar"></td>
									<td><a href="index.php?module=infoobser&amp;action=info&amp;idobser=<?php echo $n['idobser'];?>"><?php echo $n['prenom'];?> <?php echo $n['nom'];?></a></td>
									<?php
									if($idm == $idcompare) // peut supprimer à abonnement
									{
										//La class sup permet de récupérer l'id à supprimer voir dans le js
										?><td class="curseurlien sup" id="<?php echo $n['id'];?>"><i class="fa fa-trash fa-fw text-danger"></i></td><?php
									}
									?>
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
		<div class="col-md-6 col-lg-6">
			<div class="card card-body">
				<h2 class="h4"><span class="tag tag-info"><?php echo $abo;?></span> ABONNES</h4>
				<?php
				if(isset($tababonne))
				{
					?>
					<table class="table table-hover">
						<tbody>
							<?php
							foreach($tababonne as $n)
							{
								?>
								<tr>
									<td><img class="img-circle" src="<?php echo $n['avatar'];?>" width=30 height=30 alt="avatar"></td>
									<td><a href="index.php?module=infoobser&amp;action=info&amp;idobser=<?php echo $n['idobser'];?>"><?php echo $n['prenom'];?> <?php echo $n['nom'];?></a></td>									
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