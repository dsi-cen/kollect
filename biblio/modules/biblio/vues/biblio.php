<section>
	<div class="d-flex flex-row header p-2">
		<div class="container">
			<div class="row">
				<header class="col-md-12 col-lg-12">
					<div class="d-flex justify-content-start">
						<h1 class="h4 text-uppercase ctitre">Détail de la référence</h1>
						<ol class="breadcrumb ml-auto mb-0 p-1 small">							
							<li class="breadcrumb-item"><a href="index.php">Bibliographie</a></li>
							<li class="breadcrumb-item active">Référence</li>
						</ol>
					</div>
				</header>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row mt-5">
			<article class="col-md-8 col-lg-8 biblio">
				<h2 class="h4 ctitre"><?php echo $biblio['titre'];?></h2>
				<p class="">
					<span class="font-weight-bold ctitre"><?php echo $libaut;?> : </span><?php echo $auteur;?><br />
					<span class="font-weight-bold ctitre">Année de publication : </span><?php echo $biblio['annee'];?>
				</p>
				<hr />
				<p>
					<span class="font-weight-bold ctitre">Type de publication : </span><?php echo $biblio['typep'];?><br />
					<span class="font-weight-bold ctitre">Publication : </span><?php echo $biblio['publi'];?><br />
					<?php
					if(!empty($biblio['tome']))
					{
						?><span class="font-weight-bold ctitre">Volume : </span><?php echo $biblio['tome'];?><br /><?php
					}
					if(!empty($biblio['fascicule']))
					{
						?><span class="font-weight-bold ctitre">Fascicule : </span><?php echo $biblio['fascicule'];?><br /><?php
					}
					if(!empty($biblio['page']))
					{
						?><span class="font-weight-bold ctitre">Pagination : </span><?php echo $biblio['page'];?><br /><?php
					}
					if(!empty($biblio['isbn']))
					{
						?><span class="font-weight-bold ctitre">ISBN : </span><a href="https://www.google.fr/search?q=<?php echo $isbn;?>"><?php echo $biblio['isbn'];?></a><br /><?php
					}
					if(!empty($biblio['resume']))
					{
						?><span class="font-weight-bold ctitre">Résumé : </span><?php echo $biblio['resume'];?><?php
					}
					?>
				</p>
			</article>
			<aside class="col-md-4 col-lg-4">
				<?php
				if(isset($observa))
				{
					?>
					<h3 class="h5 ctitre">Observatoire</h3>
					<div class="mb-3">
						<a href="index.php?module=liste&amp;action=liste&amp;choix=observa&amp;id=<?php echo $biblio['observa'];?>"><?php echo $observa;?></a>
					</div>
					<?php
				}
				if(isset($tabmot))
				{
					?>
					<h3 class="h5 ctitre">Thèmes (mots-clés)</h3>
					<div class="mb-3">
						<?php
						foreach($tabmot as $n)
						{
							?><a href="index.php?module=liste&amp;action=liste&amp;choix=mot&amp;id=<?php echo $n['idmc'];?>" class="mt-1 ml-1 badge tagbiblio p-2"><i class="fa fa-tag"></i>&nbsp;<?php echo $n['mot'];?></a><?php
						}
						?>
					</div>
					<?php
				}
				if(isset($tabtaxon1))
				{
					?>
					<h3 class="h5 ctitre">Espèces citées</h3>
					<div class="mb-3">
						<?php
						foreach($tabtaxon1 as $n)
						{
							?><a href="../observatoire/index.php?module=fiche&amp;action=fiche&amp;d=<?php echo $n['observa'];?>&amp;id=<?php echo $n['cdnom'];?>" class=""><?php echo $n['nom'];?>, </a><?php
						}
						if(isset($tabtaxon2))
						{
							?><br /><a href="#taxon" data-toggle="modal">Voir plus...</a><?php
						}
						?>						
					</div>
					<?php
				}
				if(isset($tabcom))
				{
					?>
					<h3 class="h5 ctitre">Communes</h3>
					<div class="mb-3">
						<?php
						foreach($tabcom as $n)
						{
							?><a href="index.php?module=liste&amp;action=liste&amp;choix=com&amp;id=<?php echo $n['codecom'];?>" class="mt-1 ml-1 badge tagbiblio p-2"><i class="fa fa-tag"></i>&nbsp;<?php echo $n['commune'];?></a><?php
						}
						?>
					</div>
					<?php
				}
				if(!empty($biblio['url']))
				{
					?>
					<h3 class="h5 ctitre">Accès direct</h3>
					<div class="mb-3">
						<a href="<?php echo $biblio['url'];?>"><span class="btn color3_bg blanc btn-sm"><i class="fa fa-external-link fa-lg"></i></span></a>
					</div>
					<?php
				}
				?>
				<h3 class="h5 ctitre">Partager</h3>
				<div class="mb-3">
					<a target="_blank" title="Twitter" href="https://twitter.com/share?url=<?php echo urlencode($url);?>&text=<?php echo $titre;?>" rel="nofollow" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=400,width=700');return false;"><img src="../dist/img/twitter_icon.png" alt="Twitter" width="20" height="20" /></a>
					<a target="_blank" title="Facebook" href="https://www.facebook.com/sharer.php?u=<?php echo urlencode($url);?>&t=<?php echo $titre;?>" rel="nofollow" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=500,width=700');return false;"><img src="../dist/img/facebook_icon.png" alt="Facebook" width="20" height="20" /></a>
					<a target="_blank" title="Google +" href="https://plus.google.com/share?url=<?php echo $url;?>&hl=fr" rel="nofollow" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=450,width=650');return false;"><img src="../dist/img/gplus_icon.png" alt="Google Plus" width="20" height="20" /></a>
					<a target="_blank" title="Linkedin" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode($url);?>&title=<?php echo $titre;?>" rel="nofollow" onclick="javascript:window.open(this.href, '','menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=450,width=650');return false;"><img src="../dist/img/linkedin_icon.png" alt="Linkedin" width="20" height="20" /></a>
					<a title="Envoyer par mail" href="mailto:?subject=<?php echo $titre;?>&body=<?php echo urlencode($url);?>" rel="nofollow"><img src="../dist/img/email_icon.png" alt="email" width="20" height="20"/></a>						
				</div>
				<?php
				if(isset($_SESSION['idmembre']) && $_SESSION['droits'] >= 3)
				{
					?>
					<h3 class="h5 ctitre">Gestion</h3>
					<a href="../gestion/index.php?module=biblio&amp;action=biblio&amp;id=<?php echo $id;?>"><i class="text-warning fa fa-pencil fa-lg" title="Modifier la réf"></i></a>
					<?php					
				}
				?>
			</aside>
		</div>
	</div>
</section>
<div class="modal fade" id="taxon">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">				
				<h4 class="modal-title citre">Référence <?php echo $id;?></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>				
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
							<h5 class="ctitre">Suite des espèces citées</h5>
							<?php
							if(isset($tabtaxon2))
							{
								foreach($tabtaxon2 as $n)
								{
									?><a href="../observatoire/index.php?module=fiche&amp;action=fiche&amp;d=<?php echo $n['observa'];?>&amp;id=<?php echo $n['cdnom'];?>" class=""><?php echo $n['nom'];?>, </a><?php
								}
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>	