<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
session_start();

function recherche_obs($idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT to_char(date1, 'DD/MM/YYYY') AS datefr, site, commune, fiche.codecom, fiche.iddep, liste.nom, nomvern, nb, sensible, fiche.floutage, localisation, fiche.idobser, observateur.prenom, observateur.nom AS nomobs, obs.cdnom, obs.cdref, observa, fiche.idcoord, plusobser, idm, iddet, fiche.idfiche, statutobs FROM obs.fiche
						INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
						LEFT JOIN referentiel.commune ON commune.codecom = fiche.codecom
						LEFT JOIN obs.site ON site.idsite = fiche.idsite
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser
						LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref
						WHERE idobs = :idobs ");
	$req->bindValue(':idobs', $idobs);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_det($iddet)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT nom, prenom FROM referentiel.observateur WHERE idobser = :iddet ");
	$req->bindValue(':iddet', $iddet);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_com($idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idm, commentaire, prenom, nom, to_char(datecom, 'DD/MM/YYYY - HH24:MI') AS datefr FROM site.comobs 
						INNER JOIN site.membre ON membre.idmembre = comobs.idm
						WHERE idobs = :idobs 
						ORDER BY datecom ");
	$req->bindValue(':idobs', $idobs);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_ligne($idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT stade.stade, ndiff, male, femelle, denom, nbmin, nbmax, tdenom, idetatbio FROM obs.ligneobs
						INNER JOIN referentiel.stade ON stade.idstade = ligneobs.stade
						WHERE idobs = :idobs ");
	$req->bindValue(':idobs', $idobs);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_sp($cdnom,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT nom FROM $nomvar.liste WHERE cdnom = :cdnom ");
	$req->bindValue(':cdnom', $cdnom);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_xy($idcoord)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT lat, lng FROM obs.coordonnee WHERE idcoord = :idcoord ");
	$req->bindValue(':idcoord', $idcoord);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function cherche_observateur($idfiche)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT nom, prenom, idm, contact FROM obs.plusobser
						INNER JOIN referentiel.observateur ON observateur.idobser = plusobser.idobser
						LEFT JOIN site.prefmembre ON prefmembre.idmembre = observateur.idm
						WHERE idfiche = :idfiche
						ORDER BY idplus ");
	$req->bindValue(':idfiche', $idfiche, PDO::PARAM_INT);
	$req->execute();
	$obsplus = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $obsplus;
}
function cherche_photo($idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT observateur AS auteur, nomphoto, observatoire, stade.stade FROM site.photo
						INNER JOIN referentiel.observateur USING(idobser)
						LEFT JOIN referentiel.stade ON stade.idstade = photo.stade
						WHERE idobs = :idobs");
	$req->bindValue(':idobs', $idobs);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function sensible($idfiche)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT sensible, sensible.cdnom FROM obs.obs
						LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref
						WHERE idfiche = :idfiche ");
	$req->bindValue(':idfiche', $idfiche, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}

if(isset($_POST['idobs']))
{
	$idobs = htmlspecialchars($_POST['idobs']);
	$photo = htmlspecialchars($_POST['photo']);
	$obs = recherche_obs($idobs);
	$com = recherche_com($idobs);
	$det = recherche_det($obs['iddet']);
	if(isset($_SESSION['idmembre']) && $obs['idm'] == $_SESSION['idmembre'] || (isset($_SESSION['virtobs']) && $obs['idobser'] == $_SESSION['idmembre']))
	{
		$retour['mod'] = 'oui';
	}
	if($obs['statutobs'] != 'No')
	{
		$ligne = recherche_ligne($idobs);
	}	
	if($obs['cdnom'] != $obs['cdref'])
	{
		$nomvar = $obs['observa'];
		$nomor = recherche_sp($obs['cdnom'],$nomvar);
		$retour['diffcdref'] = $nomor['nom'];
	}
	$favatar = '../../../photo/avatar/'.$obs['prenom'].''.$obs['idm'].'.jpg';
	if($obs['plusobser'] == 'oui')
	{
		$obs2[] = (file_exists($favatar)) ? '<img src="photo/avatar/'.$obs['prenom'].''.$obs['idm'].'.jpg" width=36 height=36 alt="" class="rounded-circle"/> '.$obs['prenom'].' '.$obs['nomobs'] : '<img src="photo/avatar/usera.jpg" width=36 height=36 alt="" class="img-circle"/> '.$obs['prenom'].' '.$obs['nomobs'];
		$obsplus = cherche_observateur($obs['idfiche']);
		foreach($obsplus as $o)
		{
			if(isset($_SESSION['idmembre']) && $o['idm'] == $_SESSION['idmembre']) { $retour['adphoto'] = 'oui'; }
			$favatar = '../../../photo/avatar/'.$o['prenom'].''.$o['idm'].'.jpg';
			$obs2[] = (file_exists($favatar)) ? '<img src="photo/avatar/'.$o['prenom'].''.$o['idm'].'.jpg" width=36 height=36 alt="" class="rounded-circle"/> '.$o['prenom'].' '.$o['nom'] : '<img src="photo/avatar/usera.jpg" width=36 height=36 alt="" class="img-circle"/> '.$o['prenom'].' '.$o['nom'];
			$favatar = null;
		}
		$observateur = implode('<br />', $obs2);
	}
	else
	{
		$observateur = (file_exists($favatar)) ? '<img src="photo/avatar/'.$obs['prenom'].''.$obs['idm'].'.jpg" width=36 height=36 alt="" class="rounded-circle"/> '.$obs['prenom'].' '.$obs['nomobs'] : '<img src="photo/avatar/usera.jpg" width=36 height=36 alt="" class="img-circle"/> '.$obs['prenom'].' '.$obs['nomobs'];
	}
	$idm = $obs['idm'];
	if($obs['statutobs'] != 'No')
	{
		foreach($ligne as $n)
		{
			switch($n['idetatbio'])
			{
				case 0:$etat = 'Inconu'; break;
				case 1:$etat = 'Non renseigné'; break;
				case 2:$etat = 'Observé vivant'; break;
				case 3:$etat = 'Trouvé mort'; break;
			}
			$etatbio = ', Etat biologique : '.$etat;			
			
			if($n['denom'] == 'Co' && ($n['tdenom'] == 'IND' || $n['tdenom'] == '' || $n['tdenom'] == 'NSP'))
			{
				if(($n['ndiff'] != 0 && !empty($n['ndiff'])) && ($n['male'] != 0 && !empty($n['male'])) && ($n['femelle'] != 0 && !empty($n['femelle'])))
				{
					$tmpligne[] = '- '.$n['stade'].' ( '.$n['ndiff'].' non différencié(s) & '.$n['male']. ' <i class="fa fa-mars"></i> & '.$n['femelle']. ' <i class="fa fa-venus"></i> )'.$etatbio;
				}
				elseif(($n['ndiff'] == 0 || empty($n['ndiff'])) && ($n['male'] != 0 && !empty($n['male'])) && ($n['femelle'] != 0 && !empty($n['femelle'])))
				{
					$tmpligne[] = '- '.$n['stade'].' ( '.$n['male']. ' <i class="fa fa-mars"></i> & '.$n['femelle']. ' <i class="fa fa-venus"></i> )'.$etatbio;
				}
				elseif(($n['ndiff'] == 0 || empty($n['ndiff'])) && ($n['male'] == 0 || empty($n['male'])) && ($n['femelle'] != 0 && !empty($n['femelle'])))
				{
					$tmpligne[] = '- '.$n['stade'].' ( '.$n['femelle']. ' <i class="fa fa-venus"></i> )'.$etatbio;
				}
				elseif(($n['ndiff'] == 0 || empty($n['ndiff'])) && ($n['male'] != 0 || !empty($n['male'])) && ($n['femelle'] == 0 || !empty($n['femelle'])))
				{
					$tmpligne[] = '- '.$n['stade'].' ( '.$n['male']. ' <i class="fa fa-mars"></i> )'.$etatbio;
				}
				elseif(($n['ndiff'] != 0 && !empty($n['ndiff'])) && ($n['male'] == 0 || empty($n['male'])) && ($n['femelle'] == 0 || empty($n['femelle'])))
				{
					$tmpligne[] = '- '.$n['stade'].' ( '.$n['ndiff'].' non différencié(s) )'.$etatbio;
				}
				elseif(($n['ndiff'] != 0 && !empty($n['ndiff'])) && ($n['male'] != 0 && !empty($n['male'])) && ($n['femelle'] == 0 || empty($n['femelle'])))
				{
					$tmpligne[] = '- '.$n['stade'].' ( '.$n['ndiff'].' non différencié(s) & '.$n['male']. ' <i class="fa fa-mars"></i> )'.$etatbio;
				}
				elseif(($n['ndiff'] != 0 && !empty($n['ndiff'])) && ($n['male'] == 0 || empty($n['male'])) && ($n['femelle'] != 0 && !empty($n['femelle'])))
				{
					$tmpligne[] = '- '.$n['stade'].' ( '.$n['ndiff'].' non différencié(s) & '.$n['femelle']. ' <i class="fa fa-venus"></i> )'.$etatbio;
				}
				elseif(($n['ndiff'] == 0 && empty($n['ndiff'])) && ($n['male'] == 0 && empty($n['male'])) && ($n['femelle'] == 0 && empty($n['femelle'])))
				{
					$tmpligne[] = '- '.$n['stade'].' (présent)'.$etatbio;
				}				
			}
			elseif($n['denom'] == 'Co' && ($n['tdenom'] != 'IND' || $n['tdenom'] != '' || $n['tdenom'] != 'NSP'))
			{
				if($n['tdenom'] == 'COL') { $denom = ($n['nbmin'] > 1) ? $n['nbmin'].' colonies' : $n['nbmin'].' colonie'; }
				elseif($n['tdenom'] == 'CPL') { $denom = ($n['nbmin'] > 1) ? $n['nbmin'].' couples' : $n['nbmin'].' couple'; }
				elseif($n['tdenom'] == 'HAM') { $denom = ($n['nbmin'] > 1) ? $n['nbmin'].' hampes florales' : $n['nbmin'].' hampe florale'; }
				elseif($n['tdenom'] == 'NID') { $denom = ($n['nbmin'] > 1) ? $n['nbmin'].' nids' : $n['nbmin'].' nid'; }
				elseif($n['tdenom'] == 'PON') { $denom = ($n['nbmin'] > 1) ? $n['nbmin'].' pontes' : $n['nbmin'].' ponte'; }
				elseif($n['tdenom'] == 'SURF') { $denom = ($n['nbmin'] > 1) ? 'Sur '.$n['nbmin'].' mètres carrés' : 'Sur '.$n['nbmin'].' mètre carré'; }
				elseif($n['tdenom'] == 'TIGE') { $denom = ($n['nbmin'] > 1) ? $n['nbmin'].' tiges' : $n['nbmin'].' tige'; }
				elseif($n['tdenom'] == 'TOUF') { $denom = ($n['nbmin'] > 1) ? $n['nbmin'].' touffes' : $n['nbmin'].' touffe'; }
				$tmpligne[] = '- '.$n['stade'].' ( '.$denom.' )';				
			}
			elseif($n['denom'] == 'Es' && ($n['tdenom'] == 'IND' || $n['tdenom'] == '' || $n['tdenom'] == 'NSP'))
			{
				if($n['nbmin'] == $n['nbmax'])
				{
					if(($n['ndiff'] != 0 && !empty($n['ndiff'])) && ($n['male'] != 0 && !empty($n['male'])) && ($n['femelle'] != 0 && !empty($n['femelle'])))
					{
						$tmpligne[] = '- '.$n['stade'].' ( environ '.$n['ndiff'].' non différencié(s) & '.$n['male']. ' <i class="fa fa-mars"></i> & '.$n['femelle']. '<i class="fa fa-venus"></i> )'.$etatbio;
					}
					elseif(($n['ndiff'] == 0 || empty($n['ndiff'])) && ($n['male'] != 0 && !empty($n['male'])) && ($n['femelle'] != 0 && !empty($n['femelle'])))
					{
						$tmpligne[] = '- '.$n['stade'].' ( environ '.$n['male']. ' <i class="fa fa-mars"></i> & '.$n['femelle']. ' <i class="fa fa-venus"></i> )'.$etatbio;
					}
					elseif(($n['ndiff'] == 0 || empty($n['ndiff'])) && ($n['male'] == 0 || empty($n['male'])) && ($n['femelle'] != 0 && !empty($n['femelle'])))
					{
						$tmpligne[] = '- '.$n['stade'].' ( environ '.$n['femelle']. ' <i class="fa fa-venus"></i> )'.$etatbio;
					}
					elseif(($n['ndiff'] == 0 || empty($n['ndiff'])) && ($n['male'] != 0 || !empty($n['male'])) && ($n['femelle'] == 0 || !empty($n['femelle'])))
					{
						$tmpligne[] = '- '.$n['stade'].' ( environ '.$n['male']. ' <i class="fa fa-mars"></i> )'.$etatbio;
					}
					elseif(($n['ndiff'] != 0 && !empty($n['ndiff'])) && ($n['male'] == 0 || empty($n['male'])) && ($n['femelle'] == 0 || empty($n['femelle'])))
					{
						$tmpligne[] = '- '.$n['stade'].' ( environ '.$n['ndiff'].' non différencié(s) )'.$etatbio;
					}
					elseif(($n['ndiff'] != 0 && !empty($n['ndiff'])) && ($n['male'] != 0 && !empty($n['male'])) && ($n['femelle'] == 0 || empty($n['femelle'])))
					{
						$tmpligne[] = '- '.$n['stade'].' ( environ '.$n['ndiff'].' non différencié(s) & '.$n['male']. ' <i class="fa fa-mars"></i> )'.$etatbio;
					}
					elseif(($n['ndiff'] != 0 && !empty($n['ndiff'])) && ($n['male'] == 0 || empty($n['male'])) && ($n['femelle'] != 0 && !empty($n['femelle'])))
					{
						$tmpligne[] = '- '.$n['stade'].' ( environ '.$n['ndiff'].' non différencié(s) & '.$n['femelle']. ' <i class="fa fa-venus"></i> )'.$etatbio;
					}							
				}
				else
				{
					$tmpligne[] = '- '.$n['stade'].' ( entre '.$n['nbmin'].' et '.$n['nbmax'].' )';
				}	
			}
			elseif($n['denom'] == 'Es' && ($n['tdenom'] != 'IND' || $n['tdenom'] != '' || $n['tdenom'] != 'NSP'))
			{
				if($n['tdenom'] == 'COL') { $denom = 'colonies'; }
				elseif($n['tdenom'] == 'CPL') { $denom = 'couples'; }
				elseif($n['tdenom'] == 'HAM') { $denom = 'hampes florales'; }
				elseif($n['tdenom'] == 'NID') { $denom = 'nids'; }
				elseif($n['tdenom'] == 'PON') { $denom = 'pontes'; }
				elseif($n['tdenom'] == 'SURF') { $denom = 'mètres carrés'; }
				elseif($n['tdenom'] == 'TIGE') { $denom = 'tiges'; }
				elseif($n['tdenom'] == 'TOUF') { $denom = 'touffes'; }					
				$tmpligne[] = '- '.$n['stade'].' ( entre '.$n['nbmin'].' et '.$n['nbmax'].' '.$denom.' )';
			}
			elseif($n['denom'] == 'NSP')
			{
				$tmpligne[] = '- '.$n['stade'].' ( Espèce présente )';
			}
		}
		$ligne = implode('<br /> ', $tmpligne);
		$retour['determinateur'] = $det['prenom'].' '.$det['nom'];
	}
	else
	{
		$ligne = 'Absence de contact';
		$retour['determinateur'] = '';
	}
	$retour['ligne'] = $ligne;
	$retour['date'] = $obs['datefr'];
	//floutage et sensible
	$sensible = sensible($obs['idfiche']);
	$floutage = $obs['floutage'];
	$flousen = ($obs['sensible'] != '') ? $obs['sensible'] : 0;
	foreach($sensible as $n)
	{
		$tabsensible[] = ($n['sensible'] != '') ? $n['sensible'] : 0;
	}
	$flousen = max($tabsensible);
	if($floutage == 0 && $flousen == 0)
	{
		$retour['lieu'] = ''.$obs['site'].' '.$obs['commune'].' ('.$obs['iddep'].')';
		if($obs['localisation'] == 1 || $obs['localisation'] == 2)
		{
			$coord = recherche_xy($obs['idcoord']);
			$retour['coord'] = $coord['lat'].','.$coord['lng'];
			$retour['pre'] = $obs['localisation'];
		}		
	}
	elseif($floutage == 1 || ($flousen <= 1 && $flousen > 0))
	{
		$retour['lieu'] = ($obs['sensible'] == 1) ? 'Espèce sensible - localisation à la commune ('.$obs['commune'].')' : ''.$obs['commune'].' ('.$obs['iddep'].')';
	}
	elseif($floutage == 2 || $flousen <= 2 && $flousen > 1)
	{
		$retour['lieu'] = ($obs['sensible'] == 2) ? 'Espèce sensible - localisation à la maille 10 x 10' : 'Localisation à la maille 10 x 10 soit par décision de l\'observateur ou par manque de précision ou bien du fait de la présence d\'espèces sensibles dans le relevé dont dépend cette observation.';
	}
	elseif($floutage == 3 || $flousen <= 3)
	{
		$retour['lieu'] = ($obs['sensible'] == 3) ? 'Espèce sensible - localisation au département' : 'Localisation au département ('.$obs['iddep'].') soit par décision de l\'observateur ou par manque de précision.';
	}
	else
	{
		$retour['lieu'] = 'Localisation non diffusable soit par décision de l\'observateur ou par manque de précision';
	}
	$retour['observateur'] = $observateur;	
	//commentaires		
	if(count($com) >= 1)
	{
		$mediacom = '<hr>';		
		$mediacom .= '<h5>Commentaire(s) :</h5>';
		$mediacom .= '<div class="border p-2">';		
		foreach($com as $n)
		{
			$mediacom .= '<div class="media">';
			$favatar = '../../../photo/avatar/'.$n['prenom'].''.$n['idm'].'.jpg';
			if (file_exists($favatar))
			{
				$favatar = 'photo/avatar/'.$n['prenom'].''.$n['idm'].'.jpg';
				$mediacom .= '<img class="d-flex mr-3 rounded-circle" src="'.$favatar.'" width=30 height=30 alt="avatar">';
			}
			else
			{
				$mediacom .= '<img class="d-flex mr-3 rounded-circle" src="photo/avatar/usera.jpg" width=30 height=30 alt="">';
			}
			$mediacom .= '<div class="media-body">';
			$mediacom .= '<h6 class="mt-0">Par '.$n['prenom'].' '.$n['nom'].', '.$n['datefr'].'</h6>';
			$mediacom .= '<p>'.$n['commentaire'].'</p>';
			$mediacom .= '</div></div>';			
		}
		$mediacom .= '</div>';
		$retour['commentaire'] = $mediacom;
	}
	//Photos
	if($photo == 'oui')
	{
		$listephoto = cherche_photo($idobs);
		$obsphoto = null;
		foreach($listephoto as $n)
		{
			$obsphoto .= '<div class="col-md-6">';
			$obsphoto .= '<a href="photo/P800/'.$n['observatoire'].'/'.$n['nomphoto'].'.jpg" title="'.$n['auteur'].' - '.$n['stade'].'">';
			$obsphoto .= '<img src="photo/P200/'.$n['observatoire'].'/'.$n['nomphoto'].'.jpg" class="img-thumbnail" alt="">';
			$obsphoto .= '</a><br />';
			$obsphoto .= '<span class="xsmall fa fa-copyright"> '.$n['auteur'].' - '.$n['stade'].'</span>';
			$obsphoto .= '</div>';
		}
		$retour['photo'] = $obsphoto;
	}
	$url = 'http://'.dirname($_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
	$urlval = str_replace('/modeles/ajax/observation', '/index.php?module=observation&action=detail&idobs='.$idobs.'', $url);
	$retour['lien'] = $urlval;
	$retour['idfiche'] = $obs['idfiche'];
	$retour['statut'] = 'Oui';
	echo json_encode($retour);
}

	