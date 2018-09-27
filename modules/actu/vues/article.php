<section class="container">
    <header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<div class="d-flex justify-content-start">
					<h1 class="h2"><?php echo $titre;?></h1>
					<ol class="breadcrumb ml-auto mb-0">
						<li class="breadcrumb-item"><a href="index.php?module=actu&amp;action=actu">Actualités</a></li>
						<?php
						if(isset($actutheme))
						{
							?><li class="breadcrumb-item"><a href="index.php?module=actu&amp;action=actu&amp;theme=<?php echo $actu['theme'];?>"><?php echo $actutheme;?></a></li><?php
						}
						if(isset($retour))
						{
							?>
							<li class="breadcrumb-item"><a href="index.php?module=actu&amp;action=listetag&amp;choix=<?php echo urlencode($retour);?>"><?php echo $retour;?></a></li>
							<li class="breadcrumb-item active">Article</li>
							<?php
						}
						else
						{
							?><li class="breadcrumb-item active">Article</li><?php
						}
						?>					
					</ol>					
				</div>
			</div>
		</div>		
	</header>
	<div class="row mt-2">
		<div class="col-md-8 col-lg-8">
			<article class="card card-body">
				<p>
					<span class="float-left"><i class="fa fa-clock-o"></i> Publié le <?php echo $actu['datefr'];?></span>
					<span class="float-right">Vue <?php echo $compte;?> fois</span>
				</p>				
				<h2 class="h4"><?php echo $actu['soustitre'];?></h2>
				<hr />
				<?php echo $actu['actu'];?>
				<p>Auteur : <?php echo $auteuractu;?></p>
			</article>
		</div>
		<div class="col-md-4 col-lg-4">
			<aside class="card card-body">
				<?php
				if (!empty ($actu['nom']))
				{
					?>
					<a class="image-popup-no-margins" href="photo/article/P800/<?php echo $actu['nom'];?>.jpg">
						<img src="photo/article/P400/<?php echo $actu['nom'];?>.jpg" alt= "<?php echo $actu['info'];?>" class="img-fluid">
					</a>
					<p class="text-md-right"><?php echo $actu['info'];?>&nbsp;&nbsp;<?php echo $actu['auteur'];?>&nbsp;&nbsp;<i class="fa fa-copyright"></i></p>
					<?php
				}
				if (!empty ($actu['tag']))
				{
					?>
					<h3 class="h5">Mots-clés</h3>
					<?php
					foreach ($tag as $n)
					{
						?>
						<span class="float-left"><a class="badge color1_bg blanc" href="index.php?module=actu&amp;action=listetag&amp;choix=<?php echo urlencode($n);?>"><i class="fa fa-tag"></i> <?php echo $n;?></a></span> 
						<?php
					}					
				}
				if (!empty ($actu['nomdoc']) && $tel == 'oui')
				{
					?>
					<h3 class="h5 mt-1">Télécharger :</h3>
					<a class="badge color1_bg blanc" href="<?php echo $file;?>" title="<?php echo $actu['nomdoc'];?>"><i class="fa fa-download fa-2x"></i></a>
					(<?php echo $taille;?>)				
					<?php
				}			
				?>
				<h3 class="h5 mt-1">Partager cet article</h3>
				<div>
					<a target="_blank" title="Twitter" href="https://twitter.com/share?url=<?php echo urlencode($url);?>&text=<?php echo $titre;?>" rel="nofollow" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=400,width=700');return false;"><img src="dist/img/twitter_icon.png" alt="Twitter" /></a>
					<a target="_blank" title="Facebook" href="https://www.facebook.com/sharer.php?u=<?php echo urlencode($url);?>&t=<?php echo $titre;?>" rel="nofollow" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=500,width=700');return false;"><img src="dist/img/facebook_icon.png" alt="Facebook" /></a>
					<a target="_blank" title="Google +" href="https://plus.google.com/share?url=<?php echo $url;?>&hl=fr" rel="nofollow" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=450,width=650');return false;"><img src="dist/img/gplus_icon.png" alt="Google Plus" /></a>
					<a target="_blank" title="Linkedin" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode($url);?>&title=<?php echo $titre;?>" rel="nofollow" onclick="javascript:window.open(this.href, '','menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=450,width=650');return false;"><img src="dist/img/linkedin_icon.png" alt="Linkedin" /></a>
					<a title="Envoyer par mail" href="mailto:?subject=<?php echo $titre;?>&body=<?php echo urlencode($url);?>" rel="nofollow"><img src="dist/img/email_icon.png" alt="email" /></a>
				</div>
			</aside>
		</div>
	</div>
	<div class="clearfix m-t-2"></div>
</section>
<script>
$(document).ready(function() {
	$('.image-popup-no-margins').magnificPopup({
		type: 'image',
		closeOnContentClick: true,
		closeBtnInside: false,
		fixedContentPos: true,
		mainClass: 'mfp-no-margins mfp-with-zoom', 
		image: { verticalFit: true },
		zoom: { enabled: true, duration: 300 }
	});
});
</script>