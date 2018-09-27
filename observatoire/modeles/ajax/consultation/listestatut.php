<?php
include '../../../../global/configbase.php';
include '../../../lib/pdo2.php';
session_start();

function listestatut($idobser,$observa,$codecom,$idsite,$site,$date1,$date2,$typedate,$vali,$photo,$son,$droit,$decade,$poly,$dist,$lat,$lng,$etude,$orga,$indice,$statut,$typedon,$flou,$pr,$habitat,$stade,$etatbio,$methode,$prospect,$statbio,$proto,$genre,$famille,$aves,$mort,$maille,$okstatut,$okindice)
{
	$ir = ($okindice == 'oui') ? ', ir' : null;
	$where = 'non';
	if($okstatut == 'oui') { $strQuery = "WITH sel AS ( "; } else { $strQuery = null; }
	$strQuery .= 'SELECT COUNT(idobs) AS nb, liste.nom, liste.nomvern, liste.cdref'.$ir.' FROM obs.obs INNER JOIN obs.fiche USING(idfiche) INNER JOIN '.$observa.'.liste ON liste.cdnom = obs.cdref';
	if($stade != 0 || $etatbio != 'NR' || $methode != 0 || $prospect != 0 || $statbio != 0) { $strQuery .= ' INNER JOIN obs.ligneobs USING(idobs)'; }
	if(!empty($genre)) { $strQuery .= ' INNER JOIN '.$observa.'.genre ON genre.cdnom = liste.cdtaxsup'; }
	if(!empty($idobser)) { $strQuery .= ' LEFT JOIN obs.plusobser ON plusobser.idfiche = fiche.idfiche'; }
	if(!empty($poly) || !empty($dist) || !empty($maille)) { $strQuery .= " INNER JOIN obs.coordonnee USING(idcoord)"; }
	if(!empty($site)) { $strQuery .= ' INNER JOIN obs.site USING(idsite)'; }
	if(!empty($aves)) { $strQuery .= ' INNER JOIN obs.aves USING(idobs)'; }
	if(!empty($mort)) { $strQuery .= ' INNER JOIN obs.obsmort USING(idobs)'; }
	if($okindice == 'oui') { $strQuery .= ' INNER JOIN referentiel.liste AS l ON l.cdnom = obs.cdref'; }
	if(!empty($statut)) { $strQuery .= ' INNER JOIN statut.statut ON statut.cdnom = obs.cdref'; }
	if($habitat != 'NR') { $strQuery .= ' INNER JOIN obs.obshab USING(idobs) INNER JOIN referentiel.eunis USING(cdhab)'; }
	if($photo == 'oui') { $strQuery .= ' INNER JOIN site.photo USING(idobs)'; }
	if($son == 'oui') { $strQuery .= ' INNER JOIN site.son USING(idobs)'; }
	if((!empty($idsite) || !empty($codecom) || !empty($site) || !empty($poly) || !empty($dist)) && $droit == 'non') { $strQuery .= ' LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref'; }
	if(!empty($poly)) { $strQuery .= " WHERE polygon(path'$poly') @> (lng::text || ',' || lat::text)::point"; $where = 'oui'; }
	if(!empty($dist)) { $strQuery .= " WHERE (6366*acos(cos(radians(:lat))*cos(radians(lat))*cos(radians(lng)-radians(:lng))+sin(radians(:lat))*sin(radians(lat)))) < :dist"; $where = 'oui'; }
	if(!empty($genre)) { $strQuery .= ($where == 'non') ? ' WHERE genre.cdnom IN('.$genre.')' : ' AND (genre.cdnom IN('.$genre.'))'; $where = 'oui'; }
	if(!empty($famille)) { $strQuery .= ($where == 'non') ? ' WHERE liste.famille IN('.$famille.')' : ' AND (liste.famille IN('.$famille.'))'; $where = 'oui'; }
	if(!empty($aves)) { $strQuery .= ($where == 'non') ? ' WHERE '.$aves.'' : ' AND '.$aves.''; $where = 'oui'; }
	if(!empty($mort)) { $strQuery .= ($where == 'non') ? ' WHERE mort = :mort' : ' AND (mort = :mort)'; $where = 'oui'; }
	if($stade != 0) { $strQuery .= ($where == 'non') ? ' WHERE stade = :stade' : ' AND (stade = :stade)'; $where = 'oui'; }
	if($etatbio != 'NR') { $strQuery .= ($where == 'non') ? ' WHERE idetatbio = :etatbio' : ' AND (idetatbio = :etatbio)'; $where = 'oui'; }
	if($methode != 0) { $strQuery .= ($where == 'non') ? ' WHERE idmethode = :methode' : ' AND (idmethode = :methode)'; $where = 'oui'; }
	if($prospect != 0) { $strQuery .= ($where == 'non') ? ' WHERE idpros = :prospect' : ' AND (idpros = :prospect)'; $where = 'oui'; }
	if($statbio != 0) { $strQuery .= ($where == 'non') ? ' WHERE idstbio = :statbio' : ' AND (idstbio = :statbio)'; $where = 'oui'; }
	if(!empty($idobser)) { $strQuery .= ($where == 'non') ? " WHERE (fiche.idobser = :idobser OR plusobser.idobser = :idobser)" : " AND (fiche.idobser = :idobser OR plusobser.idobser = :idobser)"; $where = 'oui'; }
	if(!empty($maille)) { $strQuery .= ($where == 'non') ? ' WHERE codel93 IN('.$maille.')' : ' AND (codel93 IN('.$maille.'))'; $where = 'oui'; }
	if(!empty($codecom)) { $strQuery .= ($where == 'non') ? ' WHERE fiche.codecom IN('.$codecom.')' : ' AND (fiche.codecom IN('.$codecom.'))'; $where = 'oui'; }
	if(!empty($idsite)) { $strQuery .= ($where == 'non') ? ' WHERE fiche.idsite IN('.$idsite.')' : ' AND (fiche.idsite IN('.$idsite.'))'; $where = 'oui'; }
	if(!empty($site)) { $strQuery .= ($where == 'non') ? " WHERE site ILIKE :site" : " AND (site ILIKE :site)"; $where = 'oui'; }
	if(!empty($typedate) && $typedate == 'obs') { $strQuery .= ($where == 'non') ? " WHERE (date1 >= :date1 AND date1 <= :date2)" : " AND (date1 >= :date1 AND date1 <= :date2)"; $where = 'oui'; }
	if(!empty($typedate) && $typedate == 'saisie') { $strQuery .= ($where == 'non') ? " WHERE (datesaisie >= :date1 AND datesaisie <= :date2)" : " AND (datesaisie >= :date1 AND datesaisie <= :date2)"; $where = 'oui'; }
	if(!empty($decade)) { $strQuery .= ($where == 'non') ? " WHERE decade = :decade" : " AND (decade = :decade)"; $where = 'oui'; }
	if(!empty($vali)) { $strQuery .= ($where == 'non') ? ' WHERE '.$vali.'' : ' AND ('.$vali.')'; $where = 'oui'; }
	if(!empty($indice)) { $strQuery .= ($where == 'non') ? ' WHERE ir IN('.$indice.')' : ' AND (ir IN('.$indice.'))'; $where = 'oui'; }
	if(!empty($statut)) { $strQuery .= ($where == 'non') ? ' WHERE '.$statut.'' : ' AND ('.$statut.')'; $where = 'oui'; }
	if($habitat != 'NR') { $strQuery .= ($where == 'non') ? " WHERE lbcode LIKE :habitat" : " AND (lbcode LIKE :habitat)"; $where = 'oui'; }
	if($etude != 0) { $strQuery .= ($where == 'non') ? " WHERE idetude = :etude" : " AND (idetude = :etude)"; $where = 'oui'; }
	if($proto != 0) { $strQuery .= ($where == 'non') ? " WHERE idprotocole = :proto" : " AND (idprotocole = :proto)"; $where = 'oui'; }
	if($orga != 'NR') { $strQuery .= ($where == 'non') ? " WHERE idorg = :orga" : " AND (idorg = :orga)"; $where = 'oui'; }
	if($typedon != 'NR') { $strQuery .= ($where == 'non') ? " WHERE typedon = :typedon" : " AND (typedon = :typedon)"; $where = 'oui'; }
	if($flou != 'NR') { $strQuery .= ($where == 'non') ? " WHERE floutage = :flou" : " AND (floutage = :flou)"; $where = 'oui'; }
	if($pr != 'NR') { $strQuery .= ($where == 'non') ? " WHERE localisation = :pr" : " AND (localisation = :pr)"; $where = 'oui'; }
	if((!empty($idsite) || !empty($codecom) || !empty($site) || !empty($poly) || !empty($dist)) && $droit == 'non') { $strQuery .= " AND (sensible <= 1 OR sensible IS NULL) AND floutage <= 1"; }
	$strQuery .= ($where == 'non') ? " WHERE liste.rang = 'ES' OR liste.rang ='SSES'" : " AND (liste.rang = 'ES' OR liste.rang ='SSES')"; 
	$strQuery .= ' GROUP BY liste.cdref, liste.nom, liste.nomvern'.$ir.'';
	if($okstatut == 'oui') { $strQuery .= "), sel1 as ( SELECT DISTINCT cdref, nom, nomvern, nb, cdprotect, lr, type$ir FROM sel LEFT JOIN statut.statut ON statut.cdnom = sel.cdref LEFT JOIN statut.statutsite USING(cdprotect)"; }
	if($okstatut == 'oui') { $strQuery .= ") SELECT cdref, nom, nomvern, nb$ir, array_to_json(array_agg(cdprotect)) AS cd, array_to_json(array_agg(lr)) AS lr FROM sel1 GROUP BY cdref, nom, nomvern, nb$ir"; }
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare($strQuery);
	if(!empty($idobser)) { $req->bindValue(':idobser', $idobser); }	
	if(!empty($site)) { $req->bindValue(':site', '%'.$site.'%'); }
	if(!empty($dist)) { $req->bindValue(':lat', $lat); $req->bindValue(':lng', $lng); $req->bindValue(':dist', $dist); }
	if(!empty($date1)) { $req->bindValue(':date1', $date1); }
	if(!empty($date2)) { $req->bindValue(':date2', $date2); }
	if(!empty($decade)) { $req->bindValue(':decade', $decade); }
	if(!empty($mort)) { $req->bindValue(':mort', $mort); }
	if($stade != 0) { $req->bindValue(':stade', $stade); }
	if($etatbio != 'NR') { $req->bindValue(':etatbio', $etatbio); }
	if($methode != 0) { $req->bindValue(':methode', $methode); }
	if($prospect != 0) { $req->bindValue(':prospect', $prospect); }
	if($statbio != 0) { $req->bindValue(':statbio', $statbio); }
	if($etude != 0) { $req->bindValue(':etude', $etude); }
	if($proto != 0) { $req->bindValue(':proto', $proto); }
	if($orga != 'NR') { $req->bindValue(':orga', $orga); }
	if($typedon != 'NR') { $req->bindValue(':typedon', $typedon); }
	if($flou != 'NR') { $req->bindValue(':flou', $flou); }
	if($pr != 'NR') { $req->bindValue(':pr', $pr); }
	if($habitat != 'NR') { $req->bindValue(':habitat', $habitat.'%'); }
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();	
	$rreq = $strQuery;
	return array($resultat, $rreq);
}
function recherchestatut($observa)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT statutsite.type, cdprotect, intitule, article FROM statut.statutsite 
						INNER JOIN statut.libelle USING(cdprotect)
						WHERE observa = :observa ");
	$req->bindValue(':observa', $observa);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}

if(isset($_POST['choixloca']))
{
	$observa = $_POST['observa'];
	$idobser = $_POST['idobser'];
	$choixtax = $_POST['choixtax'];
	$choixloca = $_POST['choixloca'];
	$photo = (isset($_POST['photo'])) ? 'oui' : 'non';
	$son = (isset($_POST['son'])) ? 'oui' : 'non';
	$lat = $_POST['lat'];
	$lng = $_POST['lng'];
	$etude = $_POST['etude'];
	$proto = $_POST['proto'];
	$orga = $_POST['orga'];
	$typedon = $_POST['typedon'];
	$flou = $_POST['flou'];
	$pr = $_POST['pr'];
	$habitat = $_POST['habitat'];
	$stade = $_POST['stade'];
	$etatbio = $_POST['etatbio'];
	$methode = $_POST['methode'];
	$prospect = $_POST['prospect'];
	$statbio = $_POST['statbio'];
	$cataves = (isset($_POST['cataves'])) ? $_POST['cataves'] : 'NR';
	$aves = (isset($_POST['aves'])) ? $_POST['aves'] : 0;
	$mort = (isset($_POST['mort']) && $_POST['mort'] != 0 && $_POST['etatbio'] == 3) ? $_POST['mort'] : null;
	$okstatut = (isset($_POST['statut'])) ? 'oui' : 'non';
	$okindice = (isset($_POST['indice'])) ? 'oui' : 'non';
	
	$droit = ((isset($_SESSION['droits']) && $_SESSION['droits'] >= 1) || $_POST['d'] == 'oui') ? 'oui' : 'non';
	
	if(!empty($choixtax))
	{
		$genre = ($choixtax == 'genre') ? $_POST['rchoixtax'] : null;
		$famille = ($choixtax == 'famille') ? $_POST['rchoixtax'] : null;
	}
	else
	{
		$genre = null; $famille = null;
	}	
	if(!empty($choixloca))
	{
		$codecom = ($choixloca == 'commune') ? $_POST['rchoixloca'] : null;
		$idsite = ($choixloca == 'site') ? $_POST['rchoixloca'] : null;
		$site = ($choixloca == 'sitee') ? $_POST['sitee'] : null;
		$poly = ($choixloca == 'poly') ? $_POST['poly'] : null;
		$dist = ($choixloca == 'cercle') ? $_POST['rayon'] : null;
		$maille = ($choixloca == 'maille') ? $_POST['rchoixloca'] : null;
	}
	else
	{
		$codecom = null; $idsite = null; $site = null; $poly = null; $dist = null; $maille = null;
	}
	$date1 = null; $date2 = null; $typedate = null;
	if(isset($_POST['date']) && !empty($_POST['date']))
	{
		$typedate = 'obs';
		$date1 = DateTime::createFromFormat('d/m/Y', $_POST['date']);
		$date1 = $date1->format('Y-m-d');
		$date2 = DateTime::createFromFormat('d/m/Y', $_POST['date2']);
		$date2 = $date2->format('Y-m-d');
	}
	if(isset($_POST['dates']) && !empty($_POST['dates']))
	{
		$typedate = 'saisie';
		$date1 = DateTime::createFromFormat('d/m/Y', $_POST['dates']);
		$date1 = $date1->format('Y-m-d');
		$date2 = DateTime::createFromFormat('d/m/Y', $_POST['dates2']);
		$date2 = $date2->format('Y-m-d');
	}
	$decade = ($_POST['decade'] != 'NR') ? $_POST['decade'] : null;
	$indice = (!empty($_POST['rindice'])) ? $_POST['rindice'] : null;
		
	if($_POST['vali'] != 'NR')
	{
		if($_POST['vali'] == 8 || $_POST['vali'] == 9)
		{
			$vali = ($_POST['vali'] == 8) ? 'validation IN(1,2)' : 'validation IN(3,4)';
		}	
		else { $vali = 'validation = '.$_POST['vali'].''; }
	} else { $vali = null; }	
	if(!empty($_POST['rstatut']))
	{
		if(empty($_POST['rlr']))
		{
			$statut = 'cdprotect IN('.$_POST['rstatut'].')';
		}
		else
		{
			$tmp = explode(',', $_POST['rstatut']);
			$statut = null; $con = 'non';
			$t = explode('cd:', $_POST['rlr']);
			foreach($t as $n)
			{
				if(!empty($n))
				{
					$cd = strstr($n, ':', true);
					$lr = substr(strstr($n, ':', false), 1);
					$tablr[$cd] = $lr;
				}
			}
			foreach($tmp as $n)
			{
				if(isset($tablr[$n]))
				{
					$statut = ($con == 'non') ? 'cdprotect = '.$n.' AND lr IN('.$tablr[$n].')' : $statut.' OR (cdprotect = '.$n.' AND lr IN('.$tablr[$n].'))';
					$con = 'oui';
				}
				else
				{
					$statut = ($con == 'non') ? 'cdprotect = '.$n.'' : $statut.' OR (cdprotect = '.$n.')';
					$con = 'oui';
				}				
			}
			$statut = '('.$statut.')';
		}
	} 
	else
	{
		$statut = null;
	}
	if($cataves != 'NR')
	{
		if($aves == 0)
		{
			if($cataves == 'tous') { $aves = 'aves.code >= 1'; }
			if($cataves == 'possible') { $aves = '(aves.code = 1 OR aves.code = 2)'; }
			if($cataves == 'probable') { $aves = '(aves.code >= 4 AND aves.code < 10)'; }
			if($cataves == 'certain') { $aves = 'aves.code >= 10'; }
		}		
	}
	if($aves != 0) 
	{ 
		$aves = 'aves.code IN('.$_POST['raves'].')';	
	}
	
	$liste = listestatut($idobser,$observa,$codecom,$idsite,$site,$date1,$date2,$typedate,$vali,$photo,$son,$droit,$decade,$poly,$dist,$lat,$lng,$etude,$orga,$indice,$statut,$typedon,$flou,$pr,$habitat,$stade,$etatbio,$methode,$prospect,$statbio,$proto,$genre,$famille,$aves,$mort,$maille,$okstatut,$okindice);
	$retour['rq'] = $liste[1];
	
	if($liste[0] != false)
	{
		if($okstatut == 'oui')
		{
			$statut = recherchestatut($observa);
			$nbstatut = count($statut) - 1;
			$nbcol = ($okindice == 'oui') ? $nbstatut + 5 : $nbstatut + 4;
			$pn = 1; $pr = 1; $lre = 1; $lrf = 1; $lrr = 1; $a = 1;
			foreach($statut as $n)
			{
				if($n['type'] == 'DH')
				{
					if($n['cdprotect'] == 'CDH2') { $type = 1; $pos = 1; $nom = 'DH.A2'; }
					if($n['cdprotect'] == 'CDH4') { $type = 1; $pos = 2; $nom = 'DH.A4'; }
					if($n['cdprotect'] == 'CDO1') { $type = 1; $pos = 1; $nom = 'DO.A1'; }
				}
				elseif($n['type'] == 'PN') { $type = 2; $pos = $pn; $pn++; $nom = $n['cdprotect']; }
				elseif($n['type'] == 'PR') { $type = 3; $pos = $pr; $pr++; $nom = $n['cdprotect']; }
				elseif($n['type'] == 'LRE') { $type = 4; $pos = $lre; $lre++; $nom = $n['cdprotect']; }
				elseif($n['type'] == 'LRF') { $type = 5; $pos = $lrf; $lrf++; $nom = $n['cdprotect']; }
				elseif($n['type'] == 'LRR') { $type = 6; $pos = $lrr; $lrr++; $nom = $n['cdprotect']; }
				elseif($n['type'] == 'Z') { $type = 7; $pos = 1; $nom = 'ZNIEFF'; }
				elseif($n['type'] == 'A') { $type = 8; $pos = $a; $a++; $nom = $n['cdprotect'];}
				elseif($n['type'] == 'I') { $type = 9; $pos = $a; $a++; $nom = $n['cdprotect'];}
				$tab[] = ['cd'=>$n['cdprotect'],'type'=>$type,'pos'=>$pos,'nom'=>$nom,'lib'=>$n['intitule'],'article'=>$n['article']];
			}
			foreach($tab as $k => $n)
			{
				$tmp[$k]  = $n['type'];
				$tmp1[$k]  = $n['pos'];
			}
			array_multisort($tmp, SORT_ASC, $tmp1, SORT_ASC, $tab);
		}			
		foreach($liste[0] as $k => $n)
		{
			$data[] = ($okindice == 'oui') ? [$n['cdref'],$n['nom'],$n['nomvern'],$n['nb'],$n['ir']] : [$n['cdref'],$n['nom'],$n['nomvern'],$n['nb']];
			if($okstatut == 'oui')
			{
				$cd = json_decode($n['cd']);
				$lr = json_decode($n['lr']);
				
				for($i = 0; $i <= $nbstatut; $i++)
				{
					if(in_array($tab[$i]['cd'], $cd))
					{
						if($tab[$i]['type'] == 1 || $tab[$i]['type'] == 2 || $tab[$i]['type'] == 3 || $tab[$i]['type'] == 7)
						{
							$dd = 'Oui';
						}
						else
						{
							$keycd = array_search($tab[$i]['cd'], $cd);
							$dd = $lr[$keycd];
						}
					}
					else
					{
						$dd = '';
					}
					$data[$k][] = $dd;
				}
			}
		}
						
		$l = null; $expli = null;
		if($droit == 'non')
		{
			$l .= '<p>Sauf espèces sensibles et/ou floutées lors de la saisie</p>';
		}
		$l .= '<table id="listestatut" class="table table-hover table-sm" cellspacing="0" width="100%">';
		$l .= '<thead><tr><th>Nom</th><th>Nom français</th><th title="Observation">Nb</th>';
		if($okindice == 'oui') { $l .= '<th>IR</th>'; }
		if($okstatut == 'oui')
		{
			$expli .= '<p class="mt-2">';
			foreach($tab as $n)
			{
				$l .= '<th>'.$n['nom'].'</th>';
				$expli .= '<b>'.$n['nom'].'</b> : '.$n['lib'].' '.$n['article'].'<br />'; 
			}
			$expli .= '</p>';
		}
		$l .= '</tr></thead>';
		$l .= '<tbody>';
		
		foreach($data as $n)
		{
			$l .= '<tr>';
			$l .= '<td><a href="index.php?module=fiche&amp;action=fiche&amp;d='.$observa.'&amp;id='.$n[0].'"><i>'.$n[1].'</i></a></td>';
			$l .= '<td>'.$n[2].'</td><td>'.$n[3].'</td>';
			if($okindice == 'oui') { $l .= '<td>'.$n[4].'</td>'; }
			if($okstatut == 'oui')
			{
				if($okindice == 'oui')
				{
					for($i = 5; $i <= $nbcol; $i++)
					{
						$l .= '<td>'.$n[$i].'</td>';
					}
				}
				else
				{
					for($i = 4; $i <= $nbcol; $i++)
					{
						$l .= '<td>'.$n[$i].'</td>';
					}
				}
				
			}
			$l .= '</tr>';
		}
		$l .= '</tbody></table>';
		$retour['expli'] = $expli;
	}
	else
	{
		$l = 'Aucune espèce pour ces critères';
		$expli = '';
	}
	
	$retour['tbl'] = $l.$expli;
	$retour['d'] = ($droit == 'oui') ? 'oui' : 'non';
	$retour['statut'] = 'Oui';
	echo json_encode($retour);
}	