		<?php
		if (isset($pasdebdp))
		{
			?></div><?php
		}
		else
		{
				?>
				<footer class="footer color4_bg">
                    <p class="text-center mb-0 blanc">
                        Propulsé par <a href="https://obsnat.fr/" class="blanc"><b>obsNat</b></a> -
                        <a href="http://cen-aquitaine.org/" class="blanc">Cen Aquitaine</a> &copy; 2018 - <?php echo date('Y');?>
                        <span class="ml-5">
                            <img class="mr-2" src="./dist/img/git.png" height="25px" width="25px">
                            Branch : <span class="badge color1_bg blanc"> <?php echo implode('/', array_slice(explode('/', file_get_contents('./.git/HEAD')), 2)); ?> </span>
                            Commit : <span class="badge color1_bg blanc"> <?php echo substr(file_get_contents('./.git/refs/heads/master'),0,7);?> </span>
                        </span>
                        <span class="float-left ml-5"><a href="index.php?module=cgu&amp;action=mention" class="blanc">Mentions légales</a></span>
                        <span class="float-right mr-5"><a href="index.php?module=cgu&amp;action=cgu" class="blanc">Conditions d'utilisation</a></span>
                    </p>
				</footer>				
			</div>
			<?php
		}
		?>
		<?php echo $script;?>
	</body>
</html>
