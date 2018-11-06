<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
session_start();

function info_fiche($idfiche)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT codecom, decade, idcoord, geo, localisation, CONCAT(fiche.idobser, ', ', string_agg(DISTINCT plusobser.idobser::text, ', ')) AS idobser, idsite, plusobser, fichesup.idfiche AS plusfiche, date1, codel93 FROM obs.fiche 
						LEFT JOIN obs.coordgeo USING(idcoord)
						LEFT JOIN obs.plusobser USING(idfiche)
						LEFT JOIN obs.fichesup USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						WHERE idfiche = :idfiche
						GROUP BY codecom, decade, idcoord, geo, localisation, fiche.idobser, idsite, plusobser, fichesup.idfiche, date1, codel93 ");
	$req->bindValue(':idfiche', $idfiche);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function rphoto($idfiche)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idphoto FROM obs.obs
						INNER JOIN site.photo ON obs.idobs = photo.idobs
						WHERE idfiche = :idfiche ");
	$req->bindValue(':idfiche', $idfiche);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function moddatephoto($idphoto,$date1mysql)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE site.photo SET datephoto = :date1 WHERE idphoto = :idphoto ");
	$req->bindValue(':idphoto', $idphoto, PDO::PARAM_INT);
	$req->bindValue(':date1', $date1mysql);
	$req->execute();
	$req->closeCursor();
}
function modif_coord($idcoord,$x,$y,$alt,$lat,$lng,$l93,$utm,$utm1,$l935)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE obs.coordonnee SET x = :x, y = :y, altitude = :alt, lat = :lat, lng = :lng, codel93 = :codel93, utm = :utm, utm1 = :utm1, codel935 = :l935  WHERE idcoord = :idcoord ");
	$req->bindValue(':idcoord', $idcoord);
	$req->bindValue(':x', $x);
	$req->bindValue(':y', $y);
	$req->bindValue(':alt', $alt);
	$req->bindValue(':lat', $lat);
	$req->bindValue(':lng', $lng);
	$req->bindValue(':codel93', $l93);
	$req->bindValue(':utm', $utm);
	$req->bindValue(':utm1', $utm1);
	$req->bindValue(':l935', $l935);
	$req->execute();
	$req->closeCursor();
}
function insere_coordonnee($x,$y,$alt,$lat,$lng,$l93,$utm,$utm1,$l935)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO obs.coordonnee (x, y, altitude, lat, lng, codel93, utm, utm1, codel935) VALUES(:x, :y, :alt, :lat, :lng, :l93, :utm, :utm1, :l935) ");
	$req->bindValue(':x', $x);
	$req->bindValue(':y', $y);
	$req->bindValue(':alt', $alt);
	$req->bindValue(':lat', $lat);
	$req->bindValue(':lng', $lng);
	$req->bindValue(':l93', $l93);
	$req->bindValue(':utm', $utm);
	$req->bindValue(':utm1', $utm1);
	$req->bindValue(':l935', $l935);
	if ($req->execute())
	{
		$idcoord = $bdd->lastInsertId('obs.coordonnee_idcoord_seq');
	}
	$req->closeCursor();
	return $idcoord;	
}
function supprime_geo($idcoord)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("DELETE FROM obs.coordgeo WHERE idcoord = :idcoord");
	$req->bindValue(':idcoord', $idcoord);
	$req->execute();
	$req->closeCursor();
} 
function modfif_geo($idcoord,$geo)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE obs.coordgeo SET geo = :geo WHERE idcoord = :idcoord ");
	$req->bindValue(':idcoord', $idcoord);
	$req->bindValue(':geo', $geo);
	$req->execute();
	$req->closeCursor();
}
function insere_geo($idcoord,$geo)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO obs.coordgeo (idcoord, geo) VALUES(:idcoord, :geo) ");
	$req->bindValue(':idcoord', $idcoord);
	$req->bindValue(':geo', $geo);
	$req->execute();
	$req->closeCursor();
}
function insere_site($codecom,$idcoord,$rqsite,$site)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO obs.site (idcoord, codecom, site, rqsite) VALUES(:idcoord, :codecom, :site, :rqsite) ");
	$req->bindValue(':codecom', $codecom);
	$req->bindValue(':idcoord', $idcoord);
	$req->bindValue(':rqsite', $rqsite);
	$req->bindValue(':site', $site);
	if ($req->execute())
	{
		$idsite = $bdd->lastInsertId('obs.site_idsite_seq');
	}
	$req->closeCursor();
	return $idsite;	
}
function modif_site($idsite,$idcoord,$codecom,$site,$rqsite)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE obs.site SET idcoord = :idcoord, codecom = :codecom, site = :site, rqsite = :rqsite WHERE idsite = :idsite ");
	$req->bindValue(':idsite', $idsite);
	$req->bindValue(':codecom', $codecom);
	$req->bindValue(':idcoord', $idcoord);
	$req->bindValue(':rqsite', $rqsite);
	$req->bindValue(':site', $site);
	$req->execute();
	$req->closeCursor();
}
function modif_fiche($idfiche,$codecom,$date1mysql,$date2mysql,$decade,$idcoord,$idsite,$obs,$iddep,$pr,$floutage,$plusobser,$typedon,$source,$org,$etude)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE obs.fiche SET iddep = :iddep, codecom = :codecom, idsite = :idsite, date1 = :date1, date2 = :date2, idobser = :idobser, decade = :decade, localisation = :pr, idcoord = :idcoord, floutage = :floutage, plusobser = :plusobser, typedon = :typedon, source = :source, idorg = :org, idetude = :etude WHERE idfiche = :idfiche ");
	$req->bindValue(':iddep', $iddep);
	$req->bindValue(':codecom', $codecom);
	$req->bindValue(':idsite', $idsite);
	$req->bindValue(':date1', $date1mysql);
	$req->bindValue(':date2', $date2mysql);
	$req->bindValue(':idobser', $obs, PDO::PARAM_INT);
	$req->bindValue(':decade', $decade);
	$req->bindValue(':idcoord', $idcoord);
	$req->bindValue(':pr', $pr);
	$req->bindValue(':floutage', $floutage);
	$req->bindValue(':plusobser', $plusobser);
	$req->bindValue(':typedon', $typedon);
	$req->bindValue(':source', $source);
	$req->bindValue(':idfiche', $idfiche);
	$req->bindValue(':org', $org);
    $req->bindValue(':etude', $etude);
	$ok = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $ok;
}
function sup_plusobser($idfiche)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("DELETE FROM obs.plusobser WHERE idfiche = :idfiche ");
	$req->bindValue(':idfiche', $idfiche);
	$req->execute();
	$req->closeCursor();
} 
function insere_plusobser($idfiche,$idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO obs.plusobser (idfiche, idobser) 	VALUES(:idfiche, :idobser) ");
	$req->bindValue(':idfiche', $idfiche);
	$req->bindValue(':idobser', $idobser);
	$req->execute();
	$req->closeCursor();
}
function insere_fichesup($idfiche,$h1,$h2,$tempdeb,$tempfin)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO obs.fichesup (idfiche, hdebut, hfin, meteo, tempdebut, tempfin) VALUES(:idfiche, :h1, :h2, :meteo, :tdeb, :tfin) ");
	$req->bindValue(':idfiche', $idfiche);
	$req->bindValue(':h1', $h1);
	$req->bindValue(':h2', $h2);
	$req->bindValue(':meteo', '');
	$req->bindValue(':tdeb', $tempdeb);
	$req->bindValue(':tfin', $tempfin);
	$req->execute();
	$req->closeCursor();
}
function modif_fichesup($idfiche,$h1,$h2,$tempdeb,$tempfin)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE obs.fichesup SET hdebut = :h1, hfin = :h2, tempdebut = :tdeb, tempfin = :tfin WHERE idfiche = :idfiche ");
	$req->bindValue(':idfiche', $idfiche, PDO::PARAM_INT);
	$req->bindValue(':h1', $h1);
	$req->bindValue(':h2', $h2);
	$req->bindValue(':tdeb', $tempdeb);
	$req->bindValue(':tfin', $tempfin);
	$req->execute();
	$req->closeCursor();
}
function modif_obs($idobs,$vali)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE obs.obs SET validation = :vali, datesaisie = :dates WHERE idobs = :idobs ");
	$req->bindValue(':idobs', $idobs, PDO::PARAM_INT);
	$req->bindValue(':vali', $vali);
	$req->bindValue(':dates', date("Y-m-d"));
	$req->execute();
	$req->closeCursor();
}
function liste_obs($idfiche)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT liste.cdnom, idobs, vali AS tvali, validation FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN referentiel.liste ON obs.cdref = liste.cdnom
						WHERE idfiche = :idfiche ");
	$req->bindValue(':idfiche', $idfiche);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function vali_auto($idobs,$typev)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT vali.validation(:idobs,:vali) ");
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':vali', $typev);
	$req->execute();
	$req->closeCursor();
}

if(isset($_POST['idcoord']) && isset($_POST['codesite']) && isset($_POST['idfiche'])) 
{
	$idm = (isset($_SESSION['idmorigin'])) ? $_SESSION['idmorigin'] : $_SESSION['idmembre'];
	$idfiche = $_POST['idfiche'];
	$idcoordr = $_POST['idcoord'];
	$idsiter = $_POST['codesite'];
	$geo = $_POST['typepoly'];
	$codecom = $_POST['codecom'];
	$iddep = $_POST['iddep'];
	$site = $_POST['site'];
	$rqsite = 'Modification le '.date("d/m/Y").' idm - '.$idm;
	$pr = $_POST['pr'];
	
	$info = info_fiche($idfiche);
	if(!empty($info['geo']) && empty($geo) && ($info['idsite'] == $idsiter))
	{
		$retour['geohaut'] = 'sup';
		supprime_geo($idcoordr);
	}
	if($info['idcoord'] != $idcoordr)
	{
		$retour['modcoord'] = 'Oui';
		$x = $_POST['x'];
		$y = $_POST['y'];
		$alt = (empty($_POST['alt'])) && $_POST['alt'] != '0' ? null : $_POST['alt'];
		$lat = $_POST['lat'];
		$lng = $_POST['lng'];
		$l93 = $_POST['l93'];
		$l935 = $_POST['l935'];
		$utm = (isset($_POST['utm'])) ? $_POST['utm'] : '';
		$utm1 = (isset($_POST['utm1'])) ? $_POST['utm1'] : '';
		
		if($l93 != $info['codel93']) { $valioui = 'oui'; }
		
		if($info['idsite'] != $idsiter || $info['localisation'] == 2)
		{
			$retour['modsite'] = 'Oui';
			$idcoord = ($_POST['idcoord'] != 'Nouv') ? $_POST['idcoord'] : insere_coordonnee($x,$y,$alt,$lat,$lng,$l93,$utm,$utm1,$l935);
			if($_POST['idcoord'] == 'Nouv' && !empty($geo))
			{
				$retour['geonouv'] = 'inser';
				insere_geo($idcoord,$geo);
			}
			$retour['site'] = 'inser';
			$idsite = ($idsiter == 'Nouv') ? insere_site($codecom,$idcoord,$rqsite,$site) : $idsiter;			
		}
		else
		{
			$idcoord = $info['idcoord'];
			$idsite = $info['idsite'];
			modif_coord($idcoord,$x,$y,$alt,$lat,$lng,$l93,$utm,$utm1,$l935);
			if($info['geo'] != $geo)
			{
				$retour['modgeo'] = 'Oui';
				if(empty($info['geo']) && !empty($geo))
				{
					$retour['geo'] = 'inser';
					insere_geo($idcoord,$geo);
				}
				elseif(!empty($info['geo']) && !empty($geo))
				{
					$retour['geo'] = 'mod';
					modfif_geo($idcoord,$geo);
				}
				elseif(!empty($info['geo']) && empty($geo))
				{
					$retour['geo'] = 'sup';
					supprime_geo($idcoord);
				}
			}
			modif_site($idsite,$idcoord,$codecom,$site,$rqsite);		
		}
	}
	else
	{
		$idcoord = $idcoordr;
		if($idsiter != 'Nouv')
		{
			$idsite = $idsiter;
			modif_site($idsite,$idcoord,$codecom,$site,$rqsite);
		}
		else
		{
			if($pr == 1 && $site != '')
			{
				$rqsite = 'Insertion via fiche de saisie';	
				$idsite = insere_site($codecom,$idcoord,$rqsite,$site);
				$retour['site'] = 'inser';
			}
			else
			{
				$idsite = $info['idsite'];
			}			
		}
	}
	$date1 = DateTime::createFromFormat('d/m/Y', $_POST['date']);
	$date1mysql = $date1->format('Y-m-d');
	$date2 = DateTime::createFromFormat('d/m/Y', $_POST['date2']);
	$date2mysql = $date2->format('Y-m-d');
	if($date1 == $date2)
	{
		//récupération de la décade
		list($a,$m,$j)=explode("-",$date1mysql);
		//list($a,$m,$j) = explode("-",$date1);
		$Jrs = "$j";
		$Mois = "$m";
		switch ($Mois)
		{
			case 1:$DMois = "Ja";break;
			case 2:$DMois = "Fe";break;
			case 3:$DMois = "Ma";break;
			case 4:$DMois = "Av";break;
			case 5:$DMois = "M";break;
			case 6:$DMois = "Ju";break;
			case 7:$DMois = "Jl";break;
			case 8:$DMois = "A";break;
			case 9:$DMois = "S";break;
			case 10:$DMois = "O";break;
			case 11:$DMois = "N";break;
			case 12:$DMois = "D";break;
		}
		if ($Jrs >= 1 && $Jrs <= 10) { $Djrs = "1"; }
		elseif ($Jrs >= 11 && $Jrs <= 20) { $Djrs = "2"; }
		elseif ($Jrs >= 21 && $Jrs <= 31) { $Djrs = "3"; }
		$decade = $DMois . $Djrs;
	}
	else
	{
		$decade = '';
	}
	$typedon = $_POST['typedon'];
	$floutage = ($typedon == 'Pr') ? $_POST['floutage'] : 0;
	$source = $_POST['source'];
	$org = $_POST['org'];
    $etude = $_POST['etude'];
	$obs = explode(", ", $_POST['idobser']);
	$plusobser = (count($obs) > 1) ? 'oui' : 'non';
	$ok = modif_fiche($idfiche,$codecom,$date1mysql,$date2mysql,$decade,$idcoord,$idsite,$obs[0],$iddep,$pr,$floutage,$plusobser,$typedon,$source,$org,$etude);
	$unseul = ($info['plusobser'] == 'non') ? substr($info['idobser'], 0, -2) : $info['idobser'];
	if($unseul != $_POST['idobser'])
	{
		sup_plusobser($idfiche);
		if(count($obs) > 1)
		{
			$obsor = $obs[0];
			foreach($obs as $n)
			{
				if($n != $obsor)
				{
					insere_plusobser($idfiche,$n);
				}
			}		
		}
	}
	if($info['decade'] != $decade) { $valioui = 'oui'; }
	
	if(!empty($_POST['heure']) || !empty($_POST['heure2']) || !empty($_POST['tempdeb']) || !empty($_POST['tempfin']))
	{
		$h1 = (!empty($_POST['heure'])) ? $_POST['heure'] : null;
		$h2 = (!empty($_POST['heure2'])) ? $_POST['heure2'] : null;
		$tempdeb = (!empty($_POST['tempdeb'])) ? $_POST['tempdeb'] : null;
		$tempfin = (!empty($_POST['tempfin'])) ? $_POST['tempfin'] : null;
		if($info['plusfiche'] == '')
		{
			insere_fichesup($idfiche,$h1,$h2,$tempdeb,$tempfin);
		}
		else
		{
			modif_fichesup($idfiche,$h1,$h2,$tempdeb,$tempfin);
		}
	}
	if($info['date1'] != $date1mysql)
	{
		$existphto = rphoto($idfiche);
		if(count($existphto) > 0)
		{
			foreach($existphto as $n)
			{
				moddatephoto($n['idphoto'],$date1mysql);
			}
		}
	}
	//validation
	if(isset($valioui))
	{
		//faire modif date qd mm
		$listeobs = liste_obs($idfiche);
		foreach($listeobs as $n)
		{
			if($n['validation'] == 5 || $n['validation'] == 7)
			{
				$vali = $n['validation'];
				modif_obs($n['idobs'],$vali);
			}
			else
			{
				$vali = ($n['tvali'] == 0 || $n['tvali'] == '') ? 1 : 6;
				modif_obs($n['idobs'],$vali);
				if($n['tvali'] == 1 || $n['tvali'] == 2)
				{
					vali_auto($n['idobs'],$n['tvali']);
				}
			}			
		}
	}
	
	$retour['statut'] = ($ok == 'oui') ? 'Oui' : 'Erreur ! Modification peut-être pas totalement effectué';
}
else 
{
	$retour['statut'] = 'Tous les champs ne sont pas parvenus';
}   
echo json_encode($retour);
