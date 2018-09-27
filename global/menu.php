<?php 
if(!isset($sansheader))
{
	?>
	<nav class="navbar navbar-expand-md navbar-light static-top">
		<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#cachemenu" aria-controls="cachemenu" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<?php
		if($rjson_site['logo'] != 'non')
		{
			if($rjson_site['lien'] != 'non')
			{
				?><a class="" href="<?php echo $rjson_site['lien'];?>"><img class="mh-100" src="dist/img/<?php echo $rjson_site['logo'];?>" height=56 alt=""/></a><?php
			}
			else
			{
				?><img class="mh-100 img-thumbnail p-0" src="dist/img/<?php echo $rjson_site['logo'];?>" alt=""/><?php
			}
		}
		?>
		<div class="header-link hide-menu centreligne"><i class="fa fa-bars fa-lg"></i></div>		
		<div class="collapse navbar-collapse" id="cachemenu">			
			<form class="form-inline ml-2 my-2 my-lg-0" id="navbar-rechercher">
				<input type="text" placeholder="Rechercher une espèce" class="form-control mr-sm-1" id="rbh">
				<button class="btn color1_bg" type="submit"><i class="fa fa-search blanc"></i></button>			
			</form>
			<ul class="navbar-nav ml-auto">
				<?php
				if(isset($_SESSION['virtuel']))
				{
					?><li class="nav-item text-info centreligne">Session virtuel </li><?php
				}
				if(isset($_SESSION['idmembre']))
				{
					if($nbnotif > 0)
					{
						?><li class="nav-item centreligne"><a href="index.php?module=membre&amp;action=notif" title="notification" rel="nofollow"><i class="fa fa-bell-o fa-2x text-danger centreligne"></i><span class="tag tag-pill tag-info"><?php echo $nbnotif;?></span></a></li><?php
					}
					$favatar = 'photo/avatar/'.$_SESSION['prenom'].''.$_SESSION['idmembre'].'.jpg';
					?>
					<li class="nav-item dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<?php
							if(file_exists($favatar))
							{
								?><img class="rounded-circle" src="<?php echo $favatar;?>" width=36 height=36 alt=""/><?php
							}
							else
							{
								?><img class="rounded-circle" src="photo/avatar/usera.jpg" width=36 height=36 alt=""/><?php
							}
							?>
							<span class="font-extra-bold centreligne blanc"> <?php echo ''.$_SESSION['prenom'].' '.$_SESSION['nom'].'';?></span>
						</a>
						<div class="dropdown-menu dropdown-menu-right">
							<?php
							if(!isset($_SESSION['virtobs']))
							{
								?>
								<a class="dropdown-item" href="index.php?module=membre&amp;action=preference" rel="nofollow">Vos préférences</a>
								<a class="dropdown-item" href="index.php?module=membre&amp;action=typedon" rel="nofollow">Vos types de données</a>
								<a class="dropdown-item" href="index.php?module=consultation&amp;action=consultation&amp;perso=oui" rel="nofollow">Vos données</a>
								<a class="dropdown-item" href="index.php?module=infoobser&amp;action=info&amp;idobser=na" rel="nofollow">Vos statistiques</a>
								<a class="dropdown-item" href="index.php?module=membre&amp;action=notif" rel="nofollow">Vos notifications</a>
								<a class="dropdown-item" href="index.php?module=membre&amp;action=site" rel="nofollow">Vos sites</a>
								<a class="dropdown-item" href="index.php?module=membre&amp;action=espece" rel="nofollow">Vos espèces</a>
								<div class="dropdown-divider"></div>
								<?php
							}
							?>
							<a class="dropdown-item" href="index.php?module=connexion&amp;action=deconnexion" rel="nofollow">Déconnexion</a>
						</div>						
					</li>
					<?php					
					if($_SESSION['droits'] >= 2 || isset($_SESSION['virtuel']))
					{
						?><li class="nav-item"><a href="gestion" rel="nofollow" title="gestion"><i class="fa fa-cog fa-2x blanc centreligne"></i></a></li><?php
					}				
				}
				if(!isset($_SESSION['idmembre']))
				{
					?><li class="nav-item"><a href="index.php?module=connexion&amp;action=connexion" rel="nofollow" title="connexion"><i class="material-icons centreligne blanc md-48">account_circle</i></a></li><?php
				}
				?>
			</ul>    
		</div>
	</nav>
	<?php
}
?>
<div class="accueil color4_bg text-center pt-0 mr-0" id="couleur1">
	<a href="index.php"><span class="font-weight-bold blanc centreligne h3">Kollect</span></a>		
</div>
<aside id="menu" class="menu color4_bg">
	<ul class="nav side-menu flex-column" role="navigation">
		<li class="nav-item">
			<a title="Liste des observatoires" class="nav-link bb-light" href="#menuobser" data-toggle="collapse"><i class="fe-webobs3 fa-2x blanc"></i><br /><span class="badge color1_bg blanc"><?php echo $nbobservatoire;?></span></a>
			<ul class="menu menu-droit nav-second-level collapse list-unstyled" id="menuobser">
				<?php
				foreach($menuobservatoire as $n)
				{
					$couleurnomvar = (!empty($n['couleur'])) ? $n['couleur'] : '';
					?><li class="nav-item"><a class="nav-link" href="observatoire/index.php?d=<?php echo $n['var'];?>"><i class="<?php echo $n['icon'];?> fa-2x" style="color:<?php echo $couleurnomvar;?>"></i>&nbsp;&nbsp;<?php echo $n['nom'];?></a></li><?php
				}
				?>
			</ul>
		</li>
		<?php
		if($rjson_site['actu'] == 'oui')
		{
			?><li class="nav-item"><a class="nav-link bb-light" href="index.php?module=actu&amp;action=actu"><i class="fa fa-file-text-o fa-2x blanc"></i><br /><span class="blanc font-weight-normal">Actualités</span></a></li><?php
		}
		?>
		<li class="nav-item" title="Les dernières observations"><a class="nav-link bb-light" href="index.php?module=observation&amp;action=observation"><i class="fa fa-list-alt fa-2x blanc"></i><br /><span class="blanc font-weight-normal">Observations</span></a></li>
		<?php
		if(isset($_SESSION['idmembre']))
		{
			?><li class="nav-item" title="Consultation"><a class="nav-link bb-light" href="index.php?module=consultation&amp;action=consultation"><i class="fa fa-eye fa-2x blanc"></i><br /><span class="blanc font-weight-normal">Consultation</span></a></li><?php
		}
		?>
		<li class="nav-item"><a class="nav-link bb-light" href="index.php?module=recherche&amp;action=recherche"><i class="fa fa-search fa-2x blanc"></i><br /><span class="blanc font-weight-normal">Recherche</span></a></li>
		<li class="nav-item"><a class="nav-link bb-light" href="index.php?module=carto&amp;action=carto"><i class="fa fa-map-o fa-2x blanc"></i><br /><span class="blanc font-weight-normal">Cartographie</span></a></li>
		<li class="nav-item"><a class="nav-link bb-light" href="index.php?module=bilan&amp;action=bilan"><i class="fa fa-bar-chart fa-2x blanc"></i><br /><span class="blanc font-weight-normal">Bilan</span></a></li>
		<li class="nav-item"><a class="nav-link bb-light" href="index.php?module=saisie&amp;action=saisie"><i class="fa fa-pencil-square-o fa-2x blanc"></i><br /><span class="blanc font-weight-normal">Saisie</span></a></li>
		<li class="nav-item"><a class="nav-link bb-light" href="index.php?module=det&amp;action=det"><i class="fa fa-question-circle-o fa-2x blanc"></i><br /><span class="blanc font-weight-normal">Détermination</span></a></li>
		<?php 
		if($rjson_site['biblio'] == 'oui')
		{
			?><li class="nav-item"><a class="nav-link bb-light" href="biblio/"><i class="fa fa-book fa-2x blanc"></i><br /><span class="blanc font-weight-normal">Bibliographie</span></a></li><?php
		}
		if($rjson_site['email'] != '')
		{
			?><li class="nav-item"><a class="nav-link bb-light" href="index.php?module=contact&amp;action=contact"><i class="fa fa-envelope-o fa-2x blanc"></i><br /><span class="blanc font-weight-normal">Contact</span></a></li><?php
		}		
		?>
		<li class="nav-item" title="Information"><a class="nav-link bb-light" href="index.php?module=info&amp;action=info"><i class="fa fa-info fa-2x blanc"></i><br /><span class="blanc font-weight-normal">Aide</span></a></li>
	</ul>
</aside>