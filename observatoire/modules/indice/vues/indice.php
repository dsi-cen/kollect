<section class="container-fluid mb-3">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<div class="card card-body">
				<h1 class="h2">Calcul des indices de rareté des <?php echo $nomd;?></h1>
			</div>
		</div>		
	</header>
	<div class="row mt-2">
		<div class="col-md-12 col-lg-12">
			<div class="card card-body">
				<p class="font14">
					La methode pour calculé les indices de rareté de l'observatoire est repris sur la base du calcul du coefficient de rareté défini par Boullet et al. (1999) et
					d'une pondération proposée par Hauguel et Wattez (2008).<br />
					Les indices de rareté proposé sont donc pondérés par la pression de prospection.
				</p>
				<h2 class="h4">Mode de calcul</h2>
				<p class="font14">Le coefficient de rareté (Ir) est calculé à partir du nombre de mailles <?php echo $libmaille;?> <?php echo $rjson_site['ad1'];?> <?php echo $rjson_site['lieu'];?> : <b><?php echo $mt;?></b></p>
				<blockquote class="blockquote">Ir = 100 - (Nb de maille espèce/Nb de maille totale) x 100</blockquote>
				<p class="font14">Ce coefficient est ensuite redressé en fonction du nombre de mailles (m : <?php echo $m;?> actuellement) ayant un nombre d'<?php echo $choixobs;?> considéré comme minimum : <b><?php echo $rjson_obser['indice']['valchoix'];?></b>.</p>  
				<blockquote class="blockquote">M = (m/Nb de maille totale) x 100 - Actuellement M = (<?php echo $m;?>/<?php echo $emprise['nbmaille'];?>) x 100 = <?php echo $M;?></blockquote>
				<p class="font14">Le coefficient de rareté redressé (Ird) est calculé avec la formule :</p>
				<blockquote class="blockquote">Ird = Ir + (M - (Ir x M/100))</blockquote>
				<p class="font14">Le coefficient de rareté redressé (Ird) est amené à évoluer en fonction de la pression d'observations pour rejoindre progressivement le coefficient de rareté (Ir). Plus M est petit, plus Ird = Ir. M n'est pas figé : chaque observation enregistrée sur le site contribue à le faire évolué.</p>
				<h2 class="h4">Classes des indices</h2>
				<table class="table table-sm table-hover">
					<thead class="thead-light border-top-0">
						<tr>
							<th class="border-bottom-0"></th>
							<th colspan="4" class="text-center bg-warning">Ir</th>
							<th colspan="4" class="text-center bg-danger">Ird</th>
						</tr>
						<tr>
							<th class="border-bottom-0 border-top-0"></th>
							<th colspan="2" class="text-center">Coeff rareté</th>
							<th colspan="2" class="text-center">Nb mailles</th>
							<th colspan="2" class="text-center">Coeff rareté p</th>
							<th colspan="2" class="text-center">Nb mailles</th>
						</tr>
						<tr>
							<th class="border-top-0"></th>
							<th class="text-center"><</th>
							<th class="text-center">>=</th>
							<th></th><th></th>
							<th class="text-center"><</th>
							<th class="text-center">>=</th>
							<th></th><th></th>
						</tr>
					</thead>
					<tbody class="text-center">
						<?php echo $tab;?>
					</tbody>
				</table>
				<?php
				if(!empty($date))
				{
					?>
					<p>
						Pour les <?php echo $nomd;?>, les indices sont calculés que pour les observations effectuées depuis <b>le 1er janvier <?php echo $an;?></b>. Aussi la classe, <b>D?</b> est rajoutée (Présumé disparu, espèce non revue depuis le 01/01/<?php echo $an;?>). 
					</p>
					<?php
				}
				?>
				<p class="">
					<b>Exemple 1</b><br />
					Espèce présente sur <?php echo $val1;?> mailles, <b>Ir</b> = <?php echo $ir1;?><br />
					Espèce qualifiée de <b><?php echo $indice1[0];?></b> avec le coefficient de rareté et de <b><?php echo $indice1[1];?></b> avec le coefficient pondéré. Sur le site, cette espèce sera notée comme <?php echo $indice1[1];?><br />
					<b>Exemple 2</b><br />
					Espèce présente sur <?php echo $val2;?> mailles, <b>Ir</b> = <?php echo $ir2;?><br />
					Espèce qualifiée de <b><?php echo $indice2[0];?></b> avec le coefficient de rareté et de <b><?php echo $indice2[1];?></b> avec le coefficient pondéré. Sur le site, cette espèce sera notée comme <?php echo $indice2[1];?>
				</p>
				<h3 class="h5">Bibliographie</h3>
				<p>
					- BOULLET, V., DESSE, A. & HENDOUX, F., 1999. Inventaire de la flore vasculaire du Nord - Pas-de-Calais (Ptéridophytes et Spermatophytes): raretés, protections, menaces et statuts. Bulletin de la Société botanique du Nord de la France, 52(1):67p.<br />
					- HAUGUEL, J-C. & WATTEZ, J-R., 2008. Inventaire des bryophytes de Picardie, présence, rareté et menaces. CRP/CBNBL, 38p.<br />
					- VANAPPELGHEM, C., 2010. Comment estimer la rareté régionale d'une espèce ? Méthode de calcul du coefficient de rareté pondéré et exemple d'application. Le Héron, 43(3):189-196.
				</p>
			</div>
		</div>		
	</div>
</section>