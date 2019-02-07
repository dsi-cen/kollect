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
                            <?php include_once "git.php" ?>
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