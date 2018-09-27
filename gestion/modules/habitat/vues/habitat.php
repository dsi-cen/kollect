<section class="container blanc">
	<div class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<header>	
				<h1>Configuration de la liste des habitats</h1>
			</header>					
		</div>
	</div>
	<div class="row mt-2">
		<div class="col-md-12 col-lg-12">				
			<p>
				Cocher les habitats présents sur votre emprise.<br />
				Vous pouvez faire des suppressions (<i class="fa fa-trash curseurlien text-danger"></i>).<b> Attention, dans ce cas ils sont réellement supprimés.</b>
			</p>
			<div id="mes"></div>
			<?php
			foreach($niv1 as $n)
			{
				?>
				<div class="mb-1" id="n-<?php echo $n['cdhab'];?>">
					<input type="checkbox" title="locale" value="oui" class="niv" id="n1-h<?php echo $n['cdhab'];?>" <?php if($n['locale'] == 'oui') { echo 'checked'; }?>>
					<i class="fa fa-trash curseurlien text-danger supn" title="Supprimer"></i>
					<button id="<?php echo $n['cdhab'];?>" class="btn btn-sm idniv fond2" type="button"><span class="fa fa-plus"></span></button>
					<b><?php echo $n['lbcode'];?></b> <?php echo $n['lbhabitat'];?>
				</div>
				<ul id="h<?php echo $n['cdhab'];?>" class="collapse mb-3">
					<?php
					foreach($niv2 as $n2)
					{
						if($n2['cdhabsup'] == $n['cdhab'])
						{
							?>
							<li id="<?php echo $n2['cdhab'];?>">
								<input type="checkbox" title="locale" value="oui" class="niv" id="n2-<?php echo $n2['cdhab'];?>" <?php if($n2['locale'] == 'oui') { echo 'checked'; }?>>
								<i class="fa fa-trash curseurlien text-danger sup" title="Supprimer"></i>
								<b><?php echo $n2['lbcode'];?></b> <?php echo $n2['lbhabitat'];?>
								<?php
								foreach($niv3 as $n3)
								{
									if($n3['cdhabsup'] == $n2['cdhab'])
									{
										?>
										<ul id="<?php echo $n3['cdhab'];?>">
											<li>
												<input type="checkbox" title="locale" value="oui" class="niv" id="n3-<?php echo $n3['cdhab'];?>" <?php if($n3['locale'] == 'oui') { echo 'checked'; }?>>
												<i class="fa fa-trash curseurlien text-danger sup" title="Supprimer"></i>
												<b><?php echo $n3['lbcode'];?></b> <?php echo $n3['lbhabitat'];?>
												<?php
												foreach($niv4 as $n4)
												{
													if($n4['cdhabsup'] == $n3['cdhab'])
													{
														?>
														<ul id="<?php echo $n4['cdhab'];?>">
															<li>
																<input type="checkbox" title="locale" value="oui" class="niv" id="n4-<?php echo $n4['cdhab'];?>" <?php if($n4['locale'] == 'oui') { echo 'checked'; }?>>
																<i class="fa fa-trash curseurlien text-danger sup" title="Supprimer"></i>
																<b><?php echo $n4['lbcode'];?></b> <?php echo $n4['lbhabitat'];?>
																<?php
																foreach($niv5 as $n5)
																{
																	if($n5['cdhabsup'] == $n4['cdhab'])
																	{
																		?>
																		<ul id="<?php echo $n5['cdhab'];?>">
																			<li>
																				<input type="checkbox" title="locale" value="oui" class="niv" id="n5-<?php echo $n5['cdhab'];?>" <?php if($n5['locale'] == 'oui') { echo 'checked'; }?>>
																				<i class="fa fa-trash curseurlien text-danger sup" title="Supprimer"></i>
																				<b><?php echo $n5['lbcode'];?></b> <?php echo $n5['lbhabitat'];?>
																				<?php
																				foreach($niv6 as $n6)
																				{
																					if($n6['cdhabsup'] == $n5['cdhab'])
																					{
																						?>
																						<ul id="<?php echo $n6['cdhab'];?>">
																							<li>
																								<input type="checkbox" title="locale" value="oui" class="niv" id="n6-<?php echo $n6['cdhab'];?>" <?php if($n6['locale'] == 'oui') { echo 'checked'; }?>>
																								<i class="fa fa-trash curseurlien text-danger sup" title="Supprimer"></i>
																								<b><?php echo $n6['lbcode'];?></b> <?php echo $n6['lbhabitat'];?>
																							</li>
																						</ul>
																						<?php
																					}
																				}
																				?>	
																			</li>
																		</ul>
																		<?php
																	}
																}
																?>															
															</li>
														</ul>
														<?php
													}
												}
												?>
											</li>
										</ul>
										<?php
									}							
								}
								?>
							</li>
							<?php
						}
					}
					?>
				</ul>
				<?php
			}
			?>
		</div>		
	</div>
</section>
<input id="souvenir1" type="hidden" value="non"/>
<div id="dia1" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Confirmation</h4>
			</div>
			<div class="modal-body">
				<p>Voulez-vous vraiment supprimer la(les) ligne(s) en rouges ?.</p>
				<input type="checkbox" id="souvenir"> Ne plus afficher ce message.
				<input id="rang" type="hidden"/><input id="idsup" type="hidden"/>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Annuler</button>
				<button type="button" class="btn btn-success" data-dismiss="modal" id="bttdia1">Oui</button>
			</div>
		</div>
	</div>
</div>
