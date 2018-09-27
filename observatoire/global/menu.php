<?php 
if(!isset($sansheader))
{
	?>
	<nav class="navbar navbar-expand-md navbar-light fixed-top color4_bg">
		<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#cachemenu" aria-controls="cachemenu" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<?php
		if($rjson_site['logo'] != 'non')
		{
			if($rjson_site['lien'] != 'non')
			{
				?><a class="" href="<?php echo $rjson_site['lien'];?>"><img class="mh-100" src="../dist/img/<?php echo $rjson_site['logo'];?>" height=56 alt=""/></a><?php
			}
			else
			{
				?><img class="mh-100 img-thumbnail p-0" src="../dist/img/<?php echo $rjson_site['logo'];?>" alt=""/><?php
			}
		}
		?>
		<div class="header-link hide-menu centreligne"><i class="fa fa-bars fa-lg"></i></div>	
		<div class="collapse navbar-collapse" id="cachemenu">
			<form class="form-inline ml-2 my-2 my-lg-0" id="navbar-rechercher">
				<input type="text" placeholder="Rechercher une espèce" class="form-control mr-sm-1" id="rbh">
				<input id="obser" type="hidden" value="<?php echo $obser;?>"/>
				<button class="btn color1_bg" type="submit"><i class="fa fa-search blanc"></i></button>			
			</form>	
			<ul class="navbar-nav">
				<?php
				if(!empty($counomvar))
				{
					?><li class="nav-item"><a href="index.php?d=<?php echo $obser;?>" class="nav-link bb-light"><i class="<?php echo $rjson_obser['icon'];?> fa-4x centreligne" style="color:<?php echo $counomvar;?>"></i></a></li><?php
				}
				else
				{
					?><li class="nav-item"><a href="index.php?d=<?php echo $obser;?>" class="nav-link bb-light"><i class="<?php echo $rjson_obser['icon'];?> fa-3x centreligne"></i></a></li><?php
				}
				?>
			</ul>
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
						?><li class="nav-item"><a href="../index.php?module=membre&amp;action=notif" title="notification" rel="nofollow"><i class="fa fa-bell-o fa-2x text-danger centreligne"></i><span class="tag tag-pill tag-info"><?php echo $nbnotif;?></span></a></li><?php
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
							<span class="font-extra-bold centreligne blanc"><?php echo ''.$_SESSION['prenom'].' '.$_SESSION['nom'].'';?></span>
						</a>
						<div class="dropdown-menu dropdown-menu-right">
							<?php
							if(!isset($_SESSION['virtobs']))
							{
								?>
								<a class="dropdown-item" href="../index.php?module=membre&amp;action=preference" rel="nofollow">Vos préférences</a>
								<a class="dropdown-item" href="../index.php?module=consultation&amp;action=consultation&amp;perso=oui" rel="nofollow">Vos données</a>
								<a class="dropdown-item" href="../index.php?module=infoobser&amp;action=info&amp;idobser=na" rel="nofollow">Vos statistiques</a>
								<a class="dropdown-item" href="../index.php?module=membre&amp;action=notif" rel="nofollow">Vos notifications</a>
								<div class="dropdown-divider"></div>
								<?php
							}
							?>
							<a class="dropdown-item" href="../index.php?module=connexion&amp;action=deconnexion" rel="nofollow">Déconnexion</a>
						</div>
					</li>
					<?php
					if($_SESSION['droits'] >= 2 || isset($_SESSION['virtuel']))
					{
						?><li class="nav-item"><a href="../gestion" rel="nofollow" title="gestion"><i class="fa fa-cog fa-2x blanc centreligne"></i></a></li><?php
					}				
				}
				?>
				<li class="nav-item"><a href="#" id="sidebar" class="right-sidebar-toggle"><i class="material-icons md-36 toppad blanc centreligne">speaker_notes</i></a></li>
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
					$nbmots = explode(' ', $n['commentaire'], 5+1); unset($nbmots[5]);
					?>
					<a href="../index.php?module=observation&amp;action=detail&amp;idobs=<?php echo $n['idobs'];?>">- <?php echo $n['datefr'];?>, <?php echo implode(' ',$nbmots).'...';?><br />
						par <?php echo $n['prenom']. ' '.$n['nom'];?><br />
					</a>
					<?php				
				}	
				?></p><?php
			}
			?>
		</div>
	</div>
	<?php
}
?>
<div class="accueil color4_bg text-center" id="couleur1">
	<a href="../index.php"><span class="font-weight-bold blanc centreligne h3">Kollect</span></a>		
</div>
<aside id="menu" class="menu color4_bg">	
	<ul class="nav side-menu flex-column" role="navigation">
		<?php
		if(!empty($counomvar))
		{
			?><li class="nav-item"><a href="index.php?d=<?php echo $obser;?>" class="nav-link bb-light"><i class="<?php echo $rjson_obser['icon'];?> fa-4x" style="color:<?php echo $counomvar;?>"></i></a></li><?php
		}
		else
		{
			?><li class="nav-item"><a href="index.php?d=<?php echo $obser;?>" class="nav-link bb-light"><i class="<?php echo $rjson_obser['icon'];?> fa-4x blanc"></i></a></li><?php
		}
		?>		
		<li class="nav-item">
			<a title="Liste des observatoires" class="nav-link bb-light" href="#menuobser" data-toggle="collapse"><i class="fe-webobs3 fa-2x blanc"></i><br /><span class="badge color1_bg blanc"><?php echo $nbobservatoire;?></span></a>
			<ul class="menu menu-droit nav-second-level collapse list-unstyled" id="menuobser">
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
		if($rjson_site['actu'] == 'oui')
		{
			?><li class="nav-item"><a class="nav-link bb-light" href="../index.php?module=actu&amp;action=actu&amp;theme=<?php echo $nomvar;?>"><i class="fa fa-file-text-o fa-2x blanc"></i><br /><span class="blanc font-weight-normal">Actualités</span></a></li><?php
		}
		?>
		<li class="nav-item"><a class="nav-link bb-light" href="../index.php?module=observation&amp;action=observation&amp;d=<?php echo $nomvar;?>"><i class="fa fa-list-alt fa-2x blanc"></i><br /><span class="blanc font-weight-normal">Observations</span></a></li>
		<li class="nav-item"><a class="nav-link bb-light" href="index.php?module=recherche&amp;action=recherche&amp;d=<?php echo $nomvar;?>"><i class="fa fa-search fa-2x blanc"></i><br /><span class="blanc font-weight-normal">Recherche</span></a></li>
		<li class="nav-item" title="Consultation"><a class="nav-link bb-light" href="index.php?module=consultation&amp;action=consultation&amp;d=<?php echo $nomvar;?>"><i class="fa fa-eye fa-2x blanc"></i><br /><span class="blanc font-weight-normal">Consultation</span></a>
		<li class="nav-item"><a class="nav-link bb-light" href="index.php?module=decade&amp;action=decade&amp;d=<?php echo $nomvar;?>" title="Espèces du moment"><i class="fa fa-calendar fa-2x blanc"></i><br /><span class="blanc font-weight-normal">Decade</span></a></li>
		<li class="nav-item">
			<a class="nav-link bb-light" href="#menuliste" data-toggle="collapse"><i class="fa fa-list-ul fa-2x blanc"></i><br /><span class="blanc font-weight-normal">Liste</span></a>
			<div class="fixemenu">
				<ul class="menu-droitp nav-second-level collapse list-unstyled" id="menuliste">
					<li class="nav-item"><a class="nav-link" href="index.php?module=liste&amp;action=liste&amp;d=<?php echo $nomvar;?>">Liste</a></li>
					<li class="nav-item"><a class="nav-link" href="index.php?module=liste&amp;action=tele&amp;d=<?php echo $nomvar;?>">Téléchargement</a></li>
				</ul>
			</div>
		</li>
		<li class="nav-item">
			<a class="nav-link bb-light" href="#menubilan" data-toggle="collapse"><i class="fa fa-bar-chart fa-2x blanc"></i><br /><span class="blanc font-weight-normal">Bilan</span></a>
			<div class="fixemenu">
				<ul class="menu-droitp nav-second-level collapse list-unstyled" id="menubilan">
					<li class="nav-item"><a class="nav-link" href="index.php?module=bilan&amp;action=bilan&amp;d=<?php echo $nomvar;?>">Bilan</a></li>
					<li class="nav-item"><a class="nav-link" href="index.php?module=bilan&amp;action=prospection&amp;d=<?php echo $nomvar;?>">Prospection</a></li>
					<li class="nav-item"><a class="nav-link" href="index.php?module=bilan&amp;action=evolution&amp;d=<?php echo $nomvar;?>">Evolution</a></li>
					<li class="nav-item"><a class="nav-link" href="index.php?module=atrouver&amp;action=atrouver&amp;d=<?php echo $nomvar;?>">Espèces à retrouver</a></li>
					<li class="nav-item"><a class="nav-link" href="index.php?module=liste&amp;action=nouveau&amp;d=<?php echo $nomvar;?>">Nouvelles espèces</a></li>
					<li class="nav-item"><a class="nav-link" href="index.php?module=photo&amp;action=bilan&amp;d=<?php echo $nomvar;?>">Photos</a></li>
				</ul>
			</div>
		</li>
		<li class="nav-item"><a class="nav-link bb-light" href="../index.php?module=saisie&amp;action=saisie"><i class="fa fa-pencil-square-o fa-2x blanc"></i><br /><span class="blanc font-weight-normal">Saisie</span></a></li>
		<li class="nav-item"><a class="nav-link bb-light" href="index.php?module=galerie&amp;action=galerie&amp;d=<?php echo $nomvar;?>"><i class="fa fa-camera fa-2x blanc"></i><br /><span class="blanc font-weight-normal">Galerie</span></a></li>
		<?php 
		if ($rjson_site['biblio'] == 'oui')
		{
			?><li class="nav-item"><a class="nav-link bb-light" href="../biblio/index.php?module=liste&amp;action=liste&amp;choix=observa&amp;id=<?php echo $nomvar;?>"><i class="fa fa-book fa-2x blanc"></i><br /><span class="blanc font-weight-normal">Biblio</span></a></li><?php
		}
		if($rjson_site['email'] != '')
		{
			?><li class="nav-item"><a class="nav-link bb-light" href="../index.php?module=contact&amp;action=contact&amp;d=<?php echo $nomvar;?>"><i class="fa fa-envelope-o fa-2x blanc"></i><br /><span class="blanc font-weight-normal">Contact</span></a></li><?php
		}
		?>		
	</ul>
</aside>
