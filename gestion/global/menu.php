<nav class="navbar navbar-expand-md navbar-light fixed-top bg-faded">
	<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#cachemenu" aria-controls="cachemenu" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<a class="navbar-brand" href="index.php">Accueil</a>	
	<div class="collapse navbar-collapse font13" id="cachemenu">				
		<ul class="navbar-nav mr-auto">
			<li class="nav-item"><a class="nav-link" href="../">Retour Site</a></li>
		</ul>		
		<ul class="navbar-nav ml-auto">
			<?php
			if($_SESSION['droits'] >= 2)
			{
				?>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Validation</a>
					<div class="dropdown-menu">
						<a class="dropdown-item" href="index.php?module=validation&amp;action=liste">1 - Type validation</a>
						<a class="dropdown-item" href="index.php?module=validation&amp;action=critere">2 - Critères</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="index.php?module=validation&amp;action=verif">3 - Vérification</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="index.php?module=validation&amp;action=toute">4 - Validation</a>	
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="index.php?module=validation&amp;action=info">Information</a>
						<?php
						if($_SESSION['droits'] == 4)
						{
							?>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="index.php?module=validation&amp;action=ajour">Grille</a>
							<a class="dropdown-item" href="index.php?module=validation&amp;action=com">Commentaires</a>
							<?php
						}
						?>
					</div>
				</li>
				<?php
			}	
			if($_SESSION['droits'] >= 3) 
			{
				if($menubiblio == 'oui')
				{
					?>
					<li class="nav-item dropdown"> 
						<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Bibliographie</a>
						<div class="dropdown-menu dropdown-menu-right">
							<a class="dropdown-item" href="index.php?module=biblio&amp;action=general">Général</a>
							<a class="dropdown-item" href="index.php?module=biblio&amp;action=biblio">Gestion biblio</a>
							<a class="dropdown-item" href="index.php?module=biblio&amp;action=auteur">Gestion auteurs</a>
						</div>
					</li>
					<?php
				}
				if($menuactu == 'oui')
				{
					?><li class="nav-item"><a class="nav-link" href="index.php?module=actu&amp;action=liste">Actualités</a></li><?php
				}
				?>
				<li class="nav-item"><a class="nav-link" href="index.php?module=taxon&amp;action=taxon">Espèces</a></li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Photos</a>
					<div class="dropdown-menu">
						<a class="dropdown-item" href="index.php?module=photo&amp;action=verif">1 - Vérification</a>
						<a class="dropdown-item" href="index.php?module=photo&amp;action=photo">2 - Gestion</a>
					</div>
				</li>				
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Observatoires</a>
					<div class="dropdown-menu">
						<a class="dropdown-item" href="index.php?module=observatoire&amp;action=espece">1 - Choix des taxons</a>
						<a class="dropdown-item" href="index.php?module=observatoire&amp;action=liste">2 - Gestion des taxons</a>
						<a class="dropdown-item" href="index.php?module=observatoire&amp;action=categorie">3 - Gestion des categories</a>							
						<a class="dropdown-item" href="index.php?module=observatoire&amp;action=observatoire">4 - Choix des champs</a>
						<a class="dropdown-item" href="index.php?module=observatoire&amp;action=systematique">5 - Systématique</a>
						<a class="dropdown-item" href="index.php?module=observatoire&amp;action=statut">6 - Statuts</a>
						<a class="dropdown-item" href="index.php?module=observatoire&amp;action=indice">7 - Indices</a>
						<a class="dropdown-item" href="index.php?module=observatoire&amp;action=verif">8 - Verif liste</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="index.php?module=etuproto&amp;action=protocole">- Protocoles</a>
						<a class="dropdown-item" href="index.php?module=etuproto&amp;action=etude">- Etudes</a>
					</div>
				</li>
				<li class="nav-item"><a class="nav-link" href="index.php?module=import&amp;action=import">Import</a></li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Gestion site</a>
					<div class="dropdown-menu">
						<?php
						if($_SESSION['droits'] == 4)
						{
							?>
							<a class="dropdown-item" href="index.php?module=site&amp;action=site">Général</a>
							<a class="dropdown-item" href="index.php?module=site&amp;action=carto">Cartographie</a>
							<?php
						}
						?>
						<a class="dropdown-item" href="index.php?module=site&amp;action=obser">Observatoires</a>
						<a class="dropdown-item" href="index.php?module=habitat&amp;action=habitat">Habitats</a>
						<?php
						if($_SESSION['droits'] == 4)
						{
							?><a class="dropdown-item" href="index.php?module=site&amp;action=fiche">Affichage fiche</a><?php
						}
						?>
						<a class="dropdown-item" href="index.php?module=site&amp;action=article">Articles</a>
						<?php
						if($_SESSION['droits'] == 4)
						{
							?>
							<a class="dropdown-item" href="index.php?module=site&amp;action=photo">Photos</a>
							<a class="dropdown-item" href="index.php?module=site&amp;action=style">Style</a>
							<a class="dropdown-item" href="index.php?module=site&amp;action=sauve">Sauvegardes</a>
							<a class="dropdown-item" href="index.php?module=dblefiche&amp;action=dblefiche">Doublon relevé</a>
							<a class="dropdown-item" href="index.php?module=site&amp;action=utilisateur">Utilisateur</a>
							<a class="dropdown-item" href="index.php?module=utilitaire&amp;action=taxref">Taxref</a>
							<a class="dropdown-item" href="index.php?module=utilitaire&amp;action=utile">Divers</a>
							<?php
						}
						?>	
					</div>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Contributeurs</a>
					<div class="dropdown-menu">
						<?php
						if($_SESSION['droits'] == 4)
						{
							?><a class="dropdown-item" href="index.php?module=membre&amp;action=membre">Membres</a><?php
						}
						?>
						<a class="dropdown-item" href="index.php?module=observateur&amp;action=observateur">Observateurs</a>
						<a class="dropdown-item" href="index.php?module=organisme&amp;action=organisme">Organismes</a>
						<?php
						if($_SESSION['droits'] == 4)
						{
							?><a class="dropdown-item" href="index.php?module=modif&amp;action=virtuel">Suivi Virtuel</a><?php
						}
						?>
					</div>
				</li>
				<?php
				if($_SESSION['droits'] == 4)
				{
					?><li class="nav-item"><a class="nav-link" href="index.php?module=modif&amp;action=modif">Modif.</a></li><?php
				}				
			}
			if(isset($_SESSION['virtuel']) && $_SESSION['droits'] == 0)
			{
				?><li class="nav-item"><a class="nav-link" href="index.php?module=observateur&amp;action=observateur">Observateurs</a></li><?php
			}
			?>		
		</ul>		
	</div>	  
</nav>