		</div>		<footer class="footer bg">			<p class="text-center mb-0 blanc">				<?php				if($rjson_site['logo'] != 'non')				{					if($rjson_site['lien'] != 'non')					{						?><a href="<?php echo $rjson_site['lien'];?>"><img class="float-left" src="../dist/img/<?php echo $rjson_site['logo'];?>" height=60 alt=""/></a><?php					}					else					{						?><img class="float-left" src="../dist/img/<?php echo $rjson_site['logo'];?>" height=60 alt=""/><?php					}				}				?>				Propulsé par <a href="http://obsnat.fr/">obsNat</a> &copy; 2015 - <?php echo date('Y');?>				<span class="float-left ml-2"><a href="../index.php?module=cgu&amp;action=mention">Mentions légales</a></span>				<span class="float-right mr-2"><a href="../index.php?module=cgu&amp;action=cgu">Conditions d'utilisation</a></span>									</p>		</footer>								<?php echo $script;?>			</body></html>