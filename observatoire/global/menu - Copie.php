<nav class="navbar fixed-top navbar-toggleable-sm navbar-light fondblanc">
	<div class="header-link hide-menu centreligne"><i class="fa fa-bars fa-lg"></i></div>
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
				?><li class="nav-item text-info">Session virtuel </li><?php
			}
			if (isset($_SESSION['idmembre']))
			{
				if($nbnotif > 0)
				{
					?><li class="nav-item"><a href="../index.php?module=membre&amp;action=notif" title="notification" rel="nofollow"><i class="fa fa-bell-o fa-2x color5 centreligne"></i><span class="tag tag-pill tag-info"><?php echo $nbnotif;?></span></a></li><?php
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
				if($_SESSION['droits'] >= 2)
				{
					?><li class="nav-item"><a href="../gestion" rel="nofollow" title="gestion"><i class="fa fa-cog fa-2x color1 centreligne"></i></a></li><?php
				}				
			}
			?>
			<li class="nav-item"><a href="#" id="sidebar" class="right-sidebar-toggle"><i class="fa fa-file-text-o fa-lg centreligne color_body"></i></a></li>
			<?php
			if(!isset($_SESSION['idmembre']))
			{
				?><li class="nav-item color1_bg"><a href="../index.php?module=connexion&amp;action=connexion" rel="nofollow" title="connexion"><i class="fa fa-unlock-alt fa-2x blanc centreligne"></i></a></li><?php
			}			
			?>
		</ul>		
	</div>
</nav>
<div id="right-sidebar" class="animated fadeInRight">
	<div class="p-2">
		<button id="sidebar-close" type="button" class="right-sidebar-toggle close float-left" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>		
	</div>
	<div class="mt-5 p-2 bg-light border-bottom border-top">
		<p class="font-weight-bold">Les derniers commentaires</p>
		<?php
		if(count($listecomsocial > 0))
		{
			?><p><?php
			foreach($listecomsocial as $n)
			{
				$nbmots = explode(' ', $n['commentaire'], 5+1);unset($nbmots[5]);
				?>
				<a href="../index.php?module=observation&amp;action=detail&amp;idobs=<?php echo $n['idobs'];?>">
				- <?php echo $n['datefr'];?>, <?php echo implode(' ',$nbmots).'...';?><br />
				par <?php echo $n['prenom']. ' '.$n['nom'];?><br /></a>
				<?php				
			}	
			?></p><?php
		}
		?>
	</div>
</div>
<div class="accueil color1_bg text-center" id="couleur1">
	<a href="../index.php"><i class="fe-webobs3 fa-2x blanc centreligne"></i></a>		
</div>
<aside id="menu" class="menu color4_bg">	
	<ul class="nav flex-column side-menu">
		<?php
		if(isset($rjson_site['observatoire']))
		{
			?>
			<li class="nav-item">
				<a class="nav-link" href="#menuobser" data-toggle="collapse"><?php echo $libnbobser;?><span class="badge color1_bg float-right"><?php echo $nbobservatoire;?></span></a>
				<ul class="nav-second-level collapse list-unstyled" id="menuobser">					
					<?php
					foreach($menuobservatoire as $n)
					{
						$couleurnomvar = (!empty($n['couleur'])) ? $n['couleur'] : '';
						?><li class="nav-item"><a class="nav-link" href="index.php?d=<?php echo $n['var'];?>"><i class="<?php echo $n['icon'];?> fa-2x" style="color:<?php echo $couleurnomvar;?>"></i>&nbsp;&nbsp;<?php echo $n['nom'];?></a></li><?php
					}
					?>
				</ul>
			</li>
			<?php
		}
		?>
	</ul>
	<div class="obsnat">
		<?php
		if(!empty($counomvar))
		{
			?><a href="index.php?d=<?php echo $obser;?>" style="color:<?php echo $counomvar;?>"><i class="<?php echo $rjson_obser['icon'];?> fa-4x"></i></a><?php
		}
		else
		{
			?><a href="index.php?d=<?php echo $obser;?>" class="color1"><i class="<?php echo $rjson_obser['icon'];?> fa-4x"></i></a><?php
		}
		?>		
	</div>	
	<ul class="nav flex-column side-menu">
		<?php
		if ($rjson_site['actu'] == 'oui')
		{
			?><li class="nav-item"><a class="nav-link" href="../index.php?module=actu&amp;action=actu&amp;theme=<?php echo $nomvar;?>">Actualités</a></li><?php
		}
		?>
		<li class="nav-item"><a class="nav-link" href="../index.php?module=observation&amp;action=observation&amp;d=<?php echo $nomvar;?>">Observations</a></li>
		<li class="nav-item"><a class="nav-link" href="index.php?module=recherche&amp;action=recherche&amp;d=<?php echo $nomvar;?>">Recherche</a></li>
		<li class="nav-item"><a class="nav-link" href="index.php?module=decade&amp;action=decade&amp;d=<?php echo $nomvar;?>">En ce moment</a></li>
		<li class="nav-item"><a class="nav-link" href="index.php?module=liste&amp;action=liste&amp;d=<?php echo $nomvar;?>">Liste</a></li>
		<li class="nav-item"><a class="nav-link" href="index.php?module=carto&amp;action=carto&amp;d=<?php echo $nomvar;?>">Cartographie</a></li>
		<li class="nav-item">
			<a class="nav-link" href="#menubilan" data-toggle="collapse">Bilans</a>
			<ul class="nav-second-level collapse list-unstyled" id="menubilan">
				<li class="nav-item"><a class="nav-link" href="index.php?module=bilan&amp;action=bilan&amp;d=<?php echo $nomvar;?>">Bilan</a></li>
				<li class="nav-item"><a class="nav-link" href="index.php?module=bilan&amp;action=prospection&amp;d=<?php echo $nomvar;?>">Prospection</a></li>
				<li class="nav-item"><a class="nav-link" href="index.php?module=bilan&amp;action=evolution&amp;d=<?php echo $nomvar;?>">Evolution</a></li>
			</ul>
		</li>
		<li class="nav-item"><a class="nav-link" href="../index.php?module=saisie&amp;action=saisie">Saisie</a></li>
		<?php 
		if ($rjson_site['biblio'] == 'oui')
		{
			?><li class="nav-item"><a class="nav-link" href="../index.php?module=biblio&amp;action=biblio">Bibliographie</a></li><?php
		}
		?>
	</ul>	
</aside>
