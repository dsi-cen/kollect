<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';	
function table($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT table_name FROM information_schema.tables WHERE table_schema='$nomvar' AND table_name='liste'") or die(print_r($bdd->errorInfo()));
	$table = $req->rowCount();
	$req->closeCursor();
	return $table;		
}
function rechercher_rang($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT idrang, rang FROM $nomvar.rang ORDER BY idrang") or die(print_r($bdd->errorInfo()));
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}
function recherche_famille($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT cdnom, famille, locale FROM $nomvar.famille ORDER BY famille") or die(print_r($bdd->errorInfo()));
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}
function recherche_souses($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT cdnom, cdtaxsup, nom, auteur, nomvern, locale FROM $nomvar.liste WHERE rang = 'SSES' and cdnom = cdref ORDER BY nom") or die(print_r($bdd->errorInfo()));
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}
function recherche_sfst($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT liste.cdnom, nom, genre.genre, genre.cdnom AS cdnomgenre, sousfamille, sousfamille.cdnom AS cdnomsf, famille, liste.nomvern, liste.auteur, liste.locale, genre.locale AS localeg, sousfamille.locale AS localesf FROM $nomvar.liste 
						LEFT JOIN $nomvar.genre ON genre.cdnom = liste.cdtaxsup
						LEFT JOIN $nomvar.soustribu ON soustribu.cdnom = genre.cdtaxsup OR soustribu.cdnom = genre.cdsup
						LEFT JOIN $nomvar.tribu ON tribu.cdnom = soustribu.cdsup OR tribu.cdnom = genre.cdsup
						LEFT JOIN $nomvar.sousfamille ON sousfamille.cdnom = genre.cdsup OR sousfamille.cdnom = tribu.cdsup
						WHERE cdref = liste.cdnom AND rang = 'ES' 
						ORDER BY nom") or die(print_r($bdd->errorInfo()));
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;	
}
function recherche_sft($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT liste.cdnom, nom, genre.genre, genre.cdnom AS cdnomgenre, sousfamille, sousfamille.cdnom AS cdnomsf, famille, liste.nomvern, liste.auteur, liste.locale, genre.locale AS localeg, sousfamille.locale AS localesf FROM $nomvar.liste 
						LEFT JOIN $nomvar.genre ON genre.cdnom = liste.cdtaxsup
						LEFT JOIN $nomvar.tribu ON tribu.cdnom = genre.cdsup 
						LEFT JOIN $nomvar.sousfamille ON sousfamille.cdnom = genre.cdsup OR sousfamille.cdnom = tribu.cdsup
						WHERE cdref = liste.cdnom AND rang = 'ES' 
						ORDER BY nom") or die(print_r($bdd->errorInfo()));
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;	
}
function recherche_sf($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT liste.cdnom, nom, genre.genre, genre.cdnom AS cdnomgenre, sousfamille, sousfamille.cdnom AS cdnomsf, famille, liste.nomvern, liste.auteur, liste.locale, genre.locale AS localeg, sousfamille.locale AS localesf FROM $nomvar.liste 
						LEFT JOIN $nomvar.genre ON genre.cdnom = liste.cdtaxsup
						LEFT JOIN $nomvar.sousfamille ON sousfamille.cdnom = genre.cdsup
						WHERE cdref = liste.cdnom AND rang = 'ES' 
						ORDER BY nom") or die(print_r($bdd->errorInfo()));
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;	
}
function recherche_tax($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT liste.cdnom, nom, genre.genre, genre.cdnom AS cdnomgenre, '' AS sousfamille, famille, liste.nomvern, liste.auteur, liste.locale, genre.locale AS localeg FROM $nomvar.liste 
						LEFT JOIN $nomvar.genre ON genre.cdnom = liste.cdtaxsup
						WHERE cdref = liste.cdnom AND rang = 'ES' 
						ORDER BY nom") or die(print_r($bdd->errorInfo()));
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;	
}
function verifliste($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->prepare("SELECT $nomvar.liste.cdnom FROM $nomvar.liste
						INNER JOIN referentiel.liste ON referentiel.liste.cdnom = $nomvar.liste.cdnom
						WHERE observatoire != :observa ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$liste = $req->rowCount();
	$req->closeCursor();
	return $liste;		
}
function observadble($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT DISTINCT observatoire FROM $nomvar.liste
						INNER JOIN referentiel.liste ON referentiel.liste.cdnom = $nomvar.liste.cdnom ") or die(print_r($bdd->errorInfo()));
	$liste = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}

if(isset($_POST['sel']))
{
	$nomvar = $_POST['sel'];
	$table = table($nomvar);
	if($table > 0)	
	{
		$verif = verifliste($nomvar);
		if($verif > 0)
		{
			$dbl = observadble($nomvar);
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert">Votre observatoire comprends des taxons ('.$verif.') déjà présent dans un autre observatoire : <b>'.$dbl['observatoire'].'</b> ! Vous devez les supprimer ou les décocher de l\'observatoire ayant comme identifiant : <b>'.$dbl['observatoire'].'</b>.</div>';
		}
		else
		{
			$listerang = rechercher_rang($nomvar);
			foreach ($listerang as $n)
			{
				if ($n['idrang'] == 1)
				{
					$soussp = recherche_souses($nomvar);
				}
				elseif ($n['idrang'] == 5)
				{
					$stribu = 'oui';
				}
				elseif ($n['idrang'] == 6)
				{
					$tribu = 'oui';
				}
				elseif ($n['idrang'] == 7)
				{
					$sfamille = 'oui';
					$retour['sbfm'] = 'oui';
				}
				elseif ($n['idrang'] == 8)
				{
					$famille = recherche_famille($nomvar);
				}
			}
			if(isset($sfamille) && isset($tribu) && isset($stribu))
			{
				$taxon = recherche_sfst($nomvar);
				foreach($taxon as $n)
				{
					$tabf[] = $n['famille'];
					if($n['sousfamille'] == '')
					{
						$tabsfam[$n['famille']] = ['fam'=>$n['famille'],'sfam'=>$n['sousfamille'],'locale'=>$n['localesf'],'cdnom'=>$n['cdnomsf']];
					}
					else
					{
						$tabsfam[$n['cdnomsf']] = ['fam'=>$n['famille'],'sfam'=>$n['sousfamille'],'locale'=>$n['localesf'],'cdnom'=>$n['cdnomsf']];
					}				
					$tabgenre[$n['cdnomgenre']] = ['genre'=>$n['genre'],'sfam'=>$n['sousfamille'],'fam'=>$n['famille'],'locale'=>$n['localeg'],'cdnom'=>$n['cdnomgenre']];
				}			
			}
			elseif(isset($sfamille) && isset($tribu) && !isset($stribu))
			{
				$taxon = recherche_sft($nomvar);
				foreach ($taxon as $n)
				{
					$tabf[] = $n['famille'];
					if($n['sousfamille'] == '')
					{
						$tabsfam[$n['famille']] = ['fam'=>$n['famille'],'sfam'=>$n['sousfamille'],'locale'=>$n['localesf'],'cdnom'=>$n['cdnomsf']];
					}
					else
					{
						$tabsfam[$n['cdnomsf']] = ['fam'=>$n['famille'],'sfam'=>$n['sousfamille'],'locale'=>$n['localesf'],'cdnom'=>$n['cdnomsf']];
					}	
					$tabgenre[$n['cdnomgenre']] = array('genre'=>$n['genre'],'sfam'=>$n['sousfamille'],'fam'=>$n['famille'],'locale'=>$n['localeg'],'cdnom'=>$n['cdnomgenre']);		
				}
			}
			elseif(isset($sfamille) && !isset($tribu))
			{
				$taxon = recherche_sf($nomvar);
				foreach ($taxon as $n)
				{
					$tabf[] = $n['famille'];
					if($n['sousfamille'] == '')
					{
						$tabsfam[$n['famille']] = ['fam'=>$n['famille'],'sfam'=>$n['sousfamille'],'locale'=>$n['localesf'],'cdnom'=>$n['cdnomsf']];
					}
					else
					{
						$tabsfam[$n['cdnomsf']] = ['fam'=>$n['famille'],'sfam'=>$n['sousfamille'],'locale'=>$n['localesf'],'cdnom'=>$n['cdnomsf']];
					}	
					$tabgenre[$n['cdnomgenre']] = array('genre'=>$n['genre'],'sfam'=>$n['sousfamille'],'fam'=>$n['famille'],'locale'=>$n['localeg'],'cdnom'=>$n['cdnomgenre']);		
				}
			}	
			else
			{
				$taxon = recherche_tax($nomvar);
				foreach($taxon as $n)
				{
					$tabf[] = $n['famille'];
					$tabsfam[$n['famille']]	= array('fam'=>$n['famille'],'sfam'=>'');
					$tabgenre[$n['cdnomgenre']] = array('genre'=>$n['genre'],'sfam'=>'','fam'=>$n['famille'],'locale'=>$n['localeg'],'cdnom'=>$n['cdnomgenre']);		
				}
			}
			$tabf = array_flip($tabf);
					
			//$tab = null;
			$tab = '<button type="button" class="btn btn-warning" id="Bttnon">Mettre tous comme non présent</button>';
			$nbfamille = count($famille);
			foreach($famille as $f)
			{
				if(isset($tabf[$f['cdnom']]))
				{
					if($nbfamille > 1)
					{
						if($f['locale'] == 'oui')
						{
							$tab .= '<div class="mt-1" id="c'.$f['cdnom'].'"><input type="checkbox" title="locale" value="oui" class="sel" id="FM-'.$f['cdnom'].'" checked> <i class="fa fa-trash curseurlien text-danger" title="Supprimer" onclick="sup('.$f['cdnom'].',\'FM\');"></i> <button type="button" onclick="cacher(this,\''.$f['cdnom'].'\');">+</button><b> '.$f['famille'].'</b></div>';
						}
						else
						{
							$tab .= '<div class="mt-1" id="c'.$f['cdnom'].'"><input type="checkbox" title="locale" value="oui" class="sel" id="FM-'.$f['cdnom'].'"> <i class="fa fa-trash curseurlien text-danger" title="Supprimer" onclick="sup('.$f['cdnom'].',\'FM\');"></i> <button type="button" onclick="cacher(this,\''.$f['cdnom'].'\');">+</button><b> '.$f['famille'].'</b></div>';
						}				
					}
					else
					{
						$tab .= '<div class="mt-1"><button type="button" onclick="cacher(this,\''.$f['cdnom'].'\');">+</button><b> '.$f['famille'].'</b></div>';
					}				
					$tab .= '<div id="'.$f['cdnom'].'" style="display:none;">';
					foreach($tabsfam as $sf)
					{
						if($sf['fam'] == $f['cdnom'])
						{
							if($sf['sfam'] != '')
							{
								if($sf['locale'] == 'oui')
								{
									$tab .= '<ul id="'.$sf['cdnom'].'"><li><input type="checkbox" title="locale" value="oui" class="sel curseurlien" id="SBFM-'.$sf['cdnom'].'" checked> <i class="fa fa-trash curseurlien text-danger" title="Supprimer" onclick="sup('.$sf['cdnom'].',\'SBFM\');"></i> <b>'.$sf['sfam'].'</b>';											
								}
								else
								{
									$tab .= '<ul id="'.$sf['cdnom'].'"><li><input type="checkbox" title="locale" value="oui" class="sel curseurlien" id="SBFM-'.$sf['cdnom'].'"> <i class="fa fa-trash curseurlien text-danger" title="Supprimer" onclick="sup('.$sf['cdnom'].',\'SBFM\');"></i> <b>'.$sf['sfam'].'</b>';
								}
							}
							else
							{
								$tab .= '<ul class="list-unstyled"><li>';
							}
							foreach($tabgenre as $g)
							{
								if(($g['sfam'] == $sf['sfam']) and ($g['fam'] == $f['cdnom']))
								{
									if($g['locale'] == 'oui')
									{
										$tab .= '<ul id="'.$g['cdnom'].'"><li><input type="checkbox" title="locale" value="oui" class="sel curseurlien" id="GN-'.$g['cdnom'].'" checked> <i class="fa fa-trash curseurlien text-danger" title="Supprimer" onclick="sup('.$g['cdnom'].',\'GN\');"></i> '.$g['genre'].'';
									}
									else
									{
										$tab .= '<ul id="'.$g['cdnom'].'"><li><input type="checkbox" title="locale" value="oui" class="sel curseurlien" id="GN-'.$g['cdnom'].'"> <i class="fa fa-trash curseurlien text-danger" title="Supprimer" onclick="sup('.$g['cdnom'].',\'GN\');"></i> '.$g['genre'].'';
									}								
									foreach($taxon as $t)
									{
										if(($t['genre'] == $g['genre']) and ($t['sousfamille'] == $sf['sfam']) and ($t['famille'] == $g['fam']))
										{
											if($t['locale'] == 'oui')
											{
												$tab .= '<ul id="'.$t['cdnom'].'"><li><input type="checkbox" title="locale" value="'.$t['locale'].'" class="sel curseurlien" id="ES-'.$t['cdnom'].'" checked> <i class="fa fa-trash curseurlien text-danger" title="Supprimer" onclick="sup('.$t['cdnom'].',\'ES\');"></i> <i> '.$t['nom'].'</i>&nbsp;'.$t['auteur'].' '.$t['nomvern'].'';
											}
											else
											{
												$tab .= '<ul id="'.$t['cdnom'].'"><li><input type="checkbox" title="locale" value="'.$t['locale'].'" class="sel curseurlien" id="ES-'.$t['cdnom'].'"> <i class="fa fa-trash curseurlien text-danger" title="Supprimer" onclick="sup('.$t['cdnom'].',\'ES\');"></i> <i> '.$t['nom'].'</i>&nbsp;'.$t['auteur'].' '.$t['nomvern'].'';
											}
											foreach($soussp as $sp)
											{
												if (($sp['cdtaxsup'] == $t['cdnom']))
												{
													if ($sp['locale'] == 'oui')
													{
														$tab .= '<ul id="'.$sp['cdnom'].'"><li><input type="checkbox" title="locale" value="'.$sp['locale'].'" class="sel curseurlien" id="SSES-'.$sp['cdnom'].'" checked> <i class="fa fa-trash curseurlien text-danger" title="Supprimer" onclick="sup('.$sp['cdnom'].',\'SSES\');" ></i><i>'.$sp['nom'].'</i> '.$sp['auteur'].' '.$sp['nomvern'].'</li></ul>';
													}
													else
													{
														$tab .= '<ul id="'.$sp['cdnom'].'"><li><input type="checkbox" title="locale" value="'.$sp['locale'].'" class="sel curseurlien" id="SSES-'.$sp['cdnom'].'"> <i class="fa fa-trash curseurlien text-danger" title="Supprimer" onclick="sup('.$sp['cdnom'].',\'SSES\');" ></i><i>'.$sp['nom'].'</i> '.$sp['auteur'].' '.$sp['nomvern'].'</li></ul>';
													}												
												}
											}$tab .= '</li></ul>';									
										}
									}$tab .= '</li></ul>';
								}							
							}$tab .= '</li></ul>';							
						}
							
					}
					$tab .= '</div>';
				}
			}
			$retour['statut'] = 'Oui';
			$retour['tab'] = $tab;
		}
	}
	else
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert">Vous devez au péalable choisir <a href="index.php?module=observatoire&amp;action=espece">la liste</a></div>';
	}	
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert">Aucun observatoire de choisit.</div>';
}
echo json_encode($retour);