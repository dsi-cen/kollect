<?php 
if(!isset($sansheader))
{
	?>
	<nav class="navbar navbarlight fixed-top navbar-toggleable-sm navbar-light fondblanc">
		<div class="header-link1 hide-menu centreligne"><i class="fa fa-bars fa-lg"></i></div>
		<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#cachemenu" aria-controls="cachemenu" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>		
		<div class="collapse navbar-collapse" id="cachemenu">
			<form class="form-inline ml-2 my-2 my-lg-0" id="navbar-rechercher">
				<input type="text" placeholder="Rechercher une espèce" class="form-control mr-sm-1" id="rbh">
				<input id="obser" type="hidden" value="<?php echo $obser;?>"/>
				<button class="btn color1_bg" type="button" id="bttrbhobserva"><i class="fa fa-search blanc"></i></button>			
			</form>			
			<ul class="navbar-nav ml-auto">
				<?php
				if (isset($_SESSION['virtuel']))
				{
					?><li class="nav-item text-info centreligne">Session virtuel </li><?php
				}
				if (isset($_SESSION['idmembre']))
				{
					if($nbnotif > 0)
					{
						?><li class="nav-item"><a href="index.php?module=membre&amp;action=notif" title="notification" rel="nofollow"><i class="fa fa-bell-o fa-2x color5 centreligne"></i><span class="label label-pill label-info"><?php echo $nbnotif;?></span></a></li><?php
					}
					$favatar = '../photo/avatar/'.$_SESSION['prenom'].''.$_SESSION['idmembre'].'.jpg';
					?>					
					<li class="nav-item dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<?php
							if (file_exists($favatar))
							{
								?><img class="rounded-circle" src="<?php echo $favatar;?>" width=36 height=36 alt=""/><?php
							}
							else
							{
								?><img class="rounded-circle" src="../photo/avatar/usera.jpg" width=36 height=36 alt=""/><?php
							}
							?>
							<span class="font-extra-bold centreligne"><?php echo ''.$_SESSION['prenom'].' '.$_SESSION['nom'].'';?></span>
						</a>
						<div class="dropdown-menu dropdown-menu-right">
							<a class="dropdown-item" href="../index.php?module=membre&amp;action=preference" rel="nofollow">Vos préférences</a>
							<a class="dropdown-item" href="../index.php?module=membre&amp;action=ajoutphoto" rel="nofollow">Ajouter des photos</a>
							<a class="dropdown-item" href="../index.php?module=membre&amp;action=donnee" rel="nofollow">Vos données</a>
							<a class="dropdown-item" href="../index.php?module=membre&amp;action=statmembre" rel="nofollow">Vos statistique</a>
							<a class="dropdown-item" href="../index.php?module=membre&amp;action=notif" rel="nofollow">Vos notifications</a>
							<?php
							if ($_SESSION['droits'] >= 1)
							{
								?><a class="dropdown-item" href="../index.php?module=membre&amp;action=export" rel="nofollow">Consulter/exporter</a><?php
							}
							?>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="../index.php?module=connexion&amp;action=deconnexion" rel="nofollow">Déconnexion</a>
						</div>
					</li>
					<?php
					if ($_SESSION['droits'] >= 2)
					{
						?><li class="nav-item"><a href="../gestion" rel="nofollow" title="gestion"><i class="fa fa-cog fa-2x color1 centreligne"></i></a></li><?php
					}				
				}
				?>
				<?php
				if (!isset($_SESSION['idmembre']))
				{
					?><li class="nav-item color1_bg"><a href="../index.php?module=connexion&amp;action=connexion" rel="nofollow" title="connexion"><i class="fa fa-unlock-alt fa-2x blanc centreligne"></i></a></li><?php
				}					
				?>
			</ul>
		</div>
	</nav>
	<?php
}
if(isset($sansheader))
{
	?>
	<aside id="menu" class="menu lightm">
		<div id="logolightl" class="color1_bg">
			<a href="../index.php"><i class="fe-webobs3 fa-2x blanc"></i></a>
		</div>
		<?php
}
else
{
	?>
	<div id="logomlightl" class="color1_bg centreligne">
		<a href="../index.php"><i class="fe-webobs3 fa-2x blanc"></i></a>		
	</div>
	<aside id="menu" class="menu lightmh">
	<?php
}
?>
	<div id="navigation" class="color4_bg">
		<ul class="nav side-menu flex-column text-center">
			<?php
			if(!empty($counomvar))
			{
				?><li class="nav-item"><a href="index.php?d=<?php echo $obser;?>" class="nav-link bb-light"><i class="<?php echo $rjson_obser['icon'];?> fa-3x" style="color:<?php echo $counomvar;?>"></i></a></li><?php
			}
			else
			{
				?><li class="nav-item"><a href="index.php?d=<?php echo $obser;?>" class="nav-link bb-light"><i class="<?php echo $rjson_obser['icon'];?> fa-2x"></i></a></li><?php
			}
			if ($rjson_site['actu'] == 'oui')
			{
				?><li class="nav-item"><a href="../index.php?module=actu&amp;action=actu&amp;theme=<?php echo $nomvar;?>" class="nav-link bb-light"><i class="fa fa-file-text-o fa-2x"></i><br /><span class="blanc">Actus</span></a></li><?php
			}
			?>
			<li class="nav-item"><a href="../index.php?module=observation&amp;action=observation&amp;d=<?php echo $nomvar;?>" class="nav-link bb-light"><i class="fa fa-binoculars fa-2x"></i><br /><span class="blanc">Obs</span></a></li>
			<li class="nav-item"><a href="index.php?module=liste&amp;action=liste&amp;d=<?php echo $nomvar;?>" class="nav-link bb-light"><i class="fa fa-list-ul fa-2x"></i><br /><span class="blanc">Liste</span></a></li>
			<li class="nav-item"><a href="index.php?module=carto&amp;action=carto&amp;d=<?php echo $nomvar;?>" class="nav-link bb-light"><i class="fa fa-map-o fa-2x"></i><br /><span class="blanc">Carto</span></a></li>
			<li class="nav-item"><a href="index.php?module=bilan&amp;action=bilan&amp;d=<?php echo $nomvar;?>" class="nav-link bb-light"><i class="fa fa-bar-chart fa-2x"></i><br /><span class="blanc">Bilan</span></a></li>
			<li class="nav-item"><a href="../index.php?module=saisie&amp;action=saisie" class="nav-link bb-light"><i class="fa fa-pencil-square-o fa-2x"></i><br /><span class="blanc">Saisie</span></a></li>
			<?php 
			if ($rjson_site['biblio'] == 'oui')
			{
				?><li class="nav-item"><a href="index.php?module=biblio&amp;action=biblio" class="nav-link bb-light"><i class="fa fa-book fa-2x"></i><br /><span class="blanc">Biblio</span></a></li><?php
			}
			?>
		</ul>
	</div>
</aside>