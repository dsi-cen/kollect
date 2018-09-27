<section class="container mb-3">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<h1 class="h2">Page contact - <?php echo $rjson_site['titre'];?></h1>				
			</div>
		</div>		
	</header>
	<div class="row mt-2">
		<div class="col-md-12 col-lg-12">
			<div class="card card-body">
				<?php
				if($okform == 'oui')
				{
					?>
					<form id="cont" action="#" method="post">
						<div class="form-inline">
							<input type="text" class="form-control" id="nom" name="nom" size="30" required="" placeholder="Votre nom">
							<input type="email" class="ml-2 form-control" id="mail" name="mail" size="30" required="" placeholder="Votre mail">
						</div>
						<div class="form-inline mt-3">
							<label for="observa">Si votre demande porte sur une espèce particulières, un observatoire : </label>
							<select id="observa" name="observa" class="ml-2 form-control">
								<option value="NR">Général</option>
								<?php
								foreach($rjson_site['observatoire'] as $n)
								{
									?>
									<option value="<?php echo $n['nomvar'];?>" <?php if($choix == $n['nomvar']) echo 'selected="selected"';?>><?php echo $n['nom'];?></option>
									<?php
								}
								?>
							</select>
						</div>
						<div class="form-group mt-3">
							<label for="dem">Votre demande</label>
							<textarea class="form-control" id="dem" name="dem" rows="5"></textarea>
						</div>
						<button type="submit" class="btn btn-success">Envoyer</button>
					</form>
					<?php
				}
				else
				{
					if(isset($err))
					{
						?><?php echo $err;?><?php
					}
					else
					{
						if($ok == 'oui')
						{
							?><div class="alert alert-success" role="alert">Votre demande a bien été envoyé.</div><?php
						}
						else
						{
							?><div class="alert alert-warning" role="alert">Votre demande n'a pu être envoyé.</div><?php
						}
					}
				}
				?>
			</div>
		</div>		
	</div>	
</section>