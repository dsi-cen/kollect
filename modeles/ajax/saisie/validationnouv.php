<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function cherche_coordonnee($x, $y)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idcoord FROM obs.coordonnee WHERE x = :x AND y = :y ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':x', $x);
	$req->bindValue(':y', $y);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function insere_coordonnee($x,$y,$alt,$lat,$lng,$l93,$utm,$utm1,$l935)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO obs.coordonnee (x, y, altitude, lat, lng, codel93, utm, utm1, codel935) VALUES(:x, :y, :alt, :lat, :lng, :l93, :utm, :utm1, :l935) ") or die(print_r($bdd->errorInfo()));
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
function inser_coordgeo($idcoord,$geo,$poly)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO obs.coordgeo (idcoord, geo, poly) VALUES(:idcoord, :geo, :poly) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idcoord', $idcoord);
	$req->bindValue(':geo', $geo);
	$req->bindValue(':poly', $poly);
	$req->execute();
	$req->closeCursor();
}
function insere_biogeo($x,$y,$idcoord)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idbiogeo FROM referentiel.refbiogeo WHERE poly @> :recherche ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':recherche', '('.$x.','.$y.')');
	$req->execute();
	$idbiogeo = $req->fetchColumn();
	$req->closeCursor();
	$req = $bdd->prepare("INSERT INTO obs.biogeo (idcoord, idbiogeo) VALUES(:idcoord, :idbiogeo) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idcoord', $idcoord);
	$req->bindValue(':idbiogeo', $idbiogeo);
	$req->execute();
	$req->closeCursor();
}
function insere_site($codecom, $idcoord, $rqsite, $site)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO obs.site (idcoord, codecom, site, rqsite) VALUES(:idcoord, :codecom, :site, :rqsite) ") or die(print_r($bdd->errorInfo()));
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
function insere_fiche($codecom,$date1mysql,$date2mysql,$decade,$idcoord,$idsite,$obs,$iddep1,$pr,$floutage,$plusobser,$typedon,$source)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO obs.fiche (iddep, codecom, idsite, date1, date2, idobser, decade, localisation, idcoord, floutage, plusobser, typedon, source)
						VALUES(:iddep, :codecom, :idsite, :date1, :date2, :obs, :decade, :pr, :idcoord, :floutage, :plusobser, :typedon, :source) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':iddep', $iddep1);
	$req->bindValue(':codecom', $codecom);
	$req->bindValue(':idsite', $idsite);
	$req->bindValue(':date1', $date1mysql);
	$req->bindValue(':date2', $date2mysql);
	$req->bindValue(':obs', $obs, PDO::PARAM_INT);
	$req->bindValue(':decade', $decade);
	$req->bindValue(':idcoord', $idcoord);
	$req->bindValue(':pr', $pr);
	$req->bindValue(':floutage', $floutage);
	$req->bindValue(':plusobser', $plusobser);
	$req->bindValue(':typedon', $typedon);
	$req->bindValue(':source', $source);
	if ($req->execute())
	{
		$idfiche = $bdd->lastInsertId('obs.fiche_idfiche_seq');
	}
	$req->closeCursor();
	return $idfiche;	
}
function insere_fichesup($idfiche,$h1,$h2,$tempdeb,$tempfin)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO obs.fichesup (idfiche, hdebut, hfin, meteo, tempdebut, tempfin)
						VALUES(:idfiche, :h1, :h2, :meteo, :tdeb, :tfin) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idfiche', $idfiche);
	$req->bindValue(':h1', $h1);
	$req->bindValue(':h2', $h2);
	$req->bindValue(':meteo', '');
	$req->bindValue(':tdeb', $tempdeb);
	$req->bindValue(':tfin', $tempfin);
	$req->execute();
	$req->closeCursor();
}
function insere_plusobser($idfiche,$idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO obs.plusobser (idfiche, idobser) 	VALUES(:idfiche, :idobser) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idfiche', $idfiche);
	$req->bindValue(':idobser', $idobser);
	$req->execute();
	$req->closeCursor();
}
function verifobs($idfiche,$cdref)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idobs FROM obs.obs WHERE idfiche = :idfiche AND cdref = :cdref ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idfiche', $idfiche);
	$req->bindValue(':cdref', $cdref);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;	
}
function insere_obs($idfiche,$cdnom,$cdref,$iddet,$dates,$nomvar,$rq,$vali,$nb,$statutobs,$idetude,$idproto)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO obs.obs (idfiche, cdnom, cdref, iddet, nb, rqobs, validation, datesaisie, observa, statutobs, idprotocole, idetude)
						VALUES(:idfiche, :cdnom, :cdref, :iddet, :nb, :rq, :vali, :datesaisie, :var, :statut, :idproto, :idetude) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idfiche', $idfiche);
	$req->bindValue(':cdnom', $cdnom);
	$req->bindValue(':iddet', $iddet);
	$req->bindValue(':var', $nomvar);
	$req->bindValue(':vali', $vali);
	$req->bindValue(':datesaisie', $dates);
	$req->bindValue(':cdref', $cdref);
	$req->bindValue(':rq', $rq);
	$req->bindValue(':nb', $nb);
	$req->bindValue(':statut', $statutobs);
	$req->bindValue(':idproto', $idproto);
	$req->bindValue(':idetude', $idetude);
	if ($req->execute())
	{
		$idobs = $bdd->lastInsertId('obs.obs_idobs_seq');
	}
	$req->closeCursor();
	return $idobs;	
}
function insere_ligneobs($idobs,$stade,$ndiff,$m,$f,$denom,$idetat,$idmethode,$idpros,$idstbio,$nbmin,$nbmax,$sexe,$tdenom,$idcomp)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO obs.ligneobs (idobs, stade, ndiff, male, femelle, denom, idetatbio, idmethode, idpros, idstbio, nbmin, nbmax, sexe, tdenom, idcomp)
						VALUES(:idobs, :stade, :ndiff, :m, :f, :denom, :etat, :meth, :pros, :bio, :nbmin, :nbmax, :sexe, :tdenom, :idcomp) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':stade', $stade);
	$req->bindValue(':ndiff', $ndiff);
	$req->bindValue(':m', $m);
	$req->bindValue(':f', $f);
	$req->bindValue(':denom', $denom);
	$req->bindValue(':etat', $idetat);
	$req->bindValue(':meth', $idmethode);
	$req->bindValue(':pros', $idpros);
	$req->bindValue(':bio', $idstbio);
	$req->bindValue(':nbmin', $nbmin);
	$req->bindValue(':nbmax', $nbmax);
	$req->bindValue(':sexe', $sexe);
	$req->bindValue(':tdenom', $tdenom);
	$req->bindValue(':idcomp', $idcomp);
	if ($req->execute())
	{
		$idligneobs = $bdd->lastInsertId('obs.ligneobs_idligne_seq');
	}
	$req->closeCursor();
	return $idligneobs;	
}
function insere_identif($idligneobs,$idobs,$idfiche,$dates)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO obs.identif (idligne, idobs, idfiche, dates) VALUES(:idligne, :idobs, :idfiche, :dates) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':idligne', $idligneobs);
	$req->bindValue(':idfiche', $idfiche);
	$req->bindValue(':dates', $dates);
	$req->execute();
	$req->closeCursor();
}
function modif_obs($idobs,$nbmod)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("UPDATE obs.obs SET nb = :nb WHERE idobs = :idobs ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':nb', $nbmod);
	$req->execute();
	$req->closeCursor();
}
function modif_listeob($cdref,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("UPDATE $nomvar.liste SET locale = :locale WHERE cdref = :cdref ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':cdref', $cdref);
	$req->bindValue(':locale', 'oui');
	$req->execute();
	$req->closeCursor();
}
function insere_lister($cdref,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO referentiel.liste (cdnom,nom,auteur,nomvern,observatoire,rang,vali)
						SELECT cdnom, nom, auteur, nomvern, '$nomvar' AS observatoire, rang, 2 AS vali FROM $nomvar.liste
						WHERE cdref = :cdref AND cdref = cdnom AND rang = 'ES' ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':cdref', $cdref);
	$req->execute();
	$req->closeCursor();
}
function modif($idobs,$idmembre,$nom,$datem,$cdref)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO site.modif (typeid, numid, typemodif, modif, datemodif, idmembre)
						VALUES(:typeid, :id, :type, :modif, :datem, :idm) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $cdref);
	$req->bindValue(':typeid', 'Espèce');
	$req->bindValue(':type', 'Ajout espèce locale en saisie');
	$req->bindValue(':modif', 'Ajout de '.$nom.'. idobs = '.$idobs.'');
	$req->bindValue(':datem', $datem);
	$req->bindValue(':idm', $idmembre);
	$req->execute();
	$req->closeCursor();
}
function insere_aves($idobs,$stade,$code)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO obs.aves (idobs, code, stade) VALUES(:idobs, :code, :stade) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':stade', $stade);
	$req->bindValue(':code', $code);
	$req->execute();
	$req->closeCursor();
}
function insere_mort($idobs,$stade,$idmort)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO obs.obsmort (idobs, mort, stade) VALUES(:idobs, :idmort, :stade) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':stade', $stade);
	$req->bindValue(':idmort', $idmort);
	$req->execute();
	$req->closeCursor();
}
function insere_obsplte($idobs,$stade,$nbplte,$cdnom)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO obs.obsplte (idobs,nb,cdnom,stade) VALUES(:idobs, :nb, :cdnom, :stade) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':stade', $stade);
	$req->bindValue(':nb', $nbplte);
	$req->bindValue(':cdnom', $cdnom);
	$req->execute();
	$req->closeCursor();
}
function insere_habitat($idobs,$cdhab,$cdref)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO obs.obshab (idobs, cdhab, cdnom) VALUES(:idobs, :cdhab, :cdnom) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':cdhab', $cdhab);
	$req->bindValue(':cdnom', $cdref);
	$req->execute();
	$req->closeCursor();
}
function liste_photo($cdref)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT COUNT(idphoto) AS nb FROM site.photo WHERE cdnom = :cdnom ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':cdnom', $cdref);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;
}
/*function espece($cdref,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT espece, genre FROM $nomvar.liste WHERE cdnom = :cdnom ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':cdnom', $cdref);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}*/
function chp_fiche($idfiche)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT codecom, date1, idobser FROM obs.fiche WHERE idfiche = :idfiche ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idfiche', $idfiche);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function insere_photo($cdref,$idobser,$datep,$codecom,$stade,$nomphoto,$dates,$sexe,$obser,$idobs,$ordre)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO site.photo (cdnom, idobser, datephoto, codecom, stade, nomphoto, datesaisie, sexe, observatoire, idobs, ordre) VALUES(:cdnom, :idobser, :datep, :codecom, :stade, :nom, :dates, :sexe, :obser, :idobs, :ordre) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':cdnom', $cdref);
	$req->bindValue(':idobser', $idobser);
	$req->bindValue(':datep', $datep);
	$req->bindValue(':codecom', $codecom);
	$req->bindValue(':stade', $stade);
	$req->bindValue(':nom', $nomphoto);
	$req->bindValue(':dates', $dates);
	$req->bindValue(':sexe', $sexe);
	$req->bindValue(':obser', $obser);
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':ordre', $ordre);
	$req->execute();
	$req->closeCursor();
}
function insere_obscoll($idobs,$detcol,$typegen,$pregen,$codegen,$sexe,$detgen,$stade)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO obs.obscoll (idobs, iddetcol, iddetgen, codegen, sexe, idprep, typedet, stade) VALUES(:idobs, :detcol, :detgen, :codegen, :sexe, :pregen, :typedet, :stade) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':detcol', $detcol);
	$req->bindValue(':detgen', $detgen);
	$req->bindValue(':pregen', $pregen);
	$req->bindValue(':codegen', $codegen);
	$req->bindValue(':sexe', $sexe);
	$req->bindValue(':typedet', $typegen);
	$req->bindValue(':stade', $stade);
	$req->execute();
	$req->closeCursor();
}
/*function result_vali_auto($idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT vali.validation(:idobs) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idobs', $idobs);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;
}*/

if(isset($_POST['idobser']) && isset($_POST['com']) && isset($_POST['idfiche']) && isset($_POST['cdnom'])) 
{
    if($_POST['idobser'] != NULL && $_POST['cdnom'] != NULL)
	{
		if ($_POST['idfiche'] == 'Nouv') //Nouvelle fiche.
		{
			//précision, coordonnées, site			
			if ($_POST['codesite'] == 'Nouv') //Nouveau site.
			{
				$pr = $_POST['pr'];
				if ($pr == 1)//précision au site
				{
					$com = htmlspecialchars($_POST['com']);
					$codecom = $_POST['codecom'];
					$iddep = $_POST['codedep'];
					//Insertion coordonnée
					$x = $_POST['x'];
					$y = $_POST['y'];
					$alt = $_POST['alt'];
					$lat = $_POST['lat'];
					$lng = $_POST['lng'];
					$l93 = $_POST['l93'];
					$l935 = $_POST['l935'];
					$utm = $_POST['utm'];
					$utm1 = $_POST['utm1'];
					$idcoord = insere_coordonnee($x,$y,$alt,$lat,$lng,$l93,$utm,$utm1,$l935);
					//si objet dessiner
					$geo = $_POST['typepoly'];
					$poly = $_POST['poly'];
					if(!empty($geo)) { inser_coordgeo($idcoord,$geo,$poly); }					
					//pour utilisation biogeo 
					if($_POST['biogeo'] == 'oui') { insere_biogeo($x,$y,$idcoord); }					
					//Insertion site
					$site = $_POST['site'];
					if($site != '')
					{
						$rqsite = 'Insertion via fiche de saisie';					
						$idsite = insere_site($codecom, $idcoord, $rqsite, $site);
					}
					else
					{
						$idsite = 0;
					}
				}
				elseif ($pr == 2)//précision à la commune
				{
					$com = htmlspecialchars($_POST['com']);
					$codecom = $_POST['codecom'];
					$iddep = $_POST['codedep'];
					//Insertion coordonnée
					$x = $_POST['x'];
					$y = $_POST['y'];
					$coord = cherche_coordonnee($x, $y);
					if ($coord['idcoord'] != '')
					{
						$idcoord = $coord['idcoord'];
					}
					else
					{
						$alt = null;
						$lat = $_POST['lat'];
						$lng = $_POST['lng'];
						$l93 = $_POST['l93'];
						$utm = $_POST['utm'];
						$utm1 = $_POST['utm1'];
						$l935 = $_POST['l935'];
						$idcoord = insere_coordonnee($x,$y,$alt,$lat,$lng,$l93,$utm,$utm1,$l935);
					}				
					$idsite = 0;
					$site = '';
				}
				elseif ($pr == 3)//précision au département
				{
					$iddep = $_POST['codedep'];
					$idsite = 0;
					$idcoord = 0;
					$codecom = null;
					$site = '';
					$com = '';
				}					
			}
			else //Site déjà connu.
			{
				$idsite = $_POST['codesite'];
				$idcoord = $_POST['idcoord'];
				$codecom = $_POST['codecom'];
				$iddep = $_POST['codedep'];
				$com = $_POST['com'];
				$site = $_POST['site'];
				$pr = 1;
			}
			//Gestion date
			//$date1 = $_POST['date'];
			//$date2 = $_POST['date2'];
			$date1 = DateTime::createFromFormat('d/m/Y', $_POST['date']);
			$date1mysql = $date1->format('Y-m-d');
			$date2 = DateTime::createFromFormat('d/m/Y', $_POST['date2']);
			$date2mysql = $date2->format('Y-m-d');
			if ($date1 == $date2)
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
			//insertion fiche
			$typedon = $_POST['typedon'];
			$floutage = ($typedon == 'Pr') ? $_POST['floutage'] : 0;
			$source = $_POST['source'];
			$obs = explode(", ", $_POST['idobser']);
			$plusobser = (count($obs) > 1) ? 'oui' : 'non';
			$idfiche = insere_fiche($codecom,$date1mysql,$date2mysql,$decade,$idcoord,$idsite,$obs[0],$iddep,$pr,$floutage,$plusobser,$typedon,$source);
			//si plusieurs observateurs
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
			//si fiche sup
			if(!empty($_POST['heure']) || !empty($_POST['heure2']) || !empty($_POST['tempdeb']) || !empty($_POST['tempfin']))
			{
				$h1 = (!empty($_POST['heure'])) ? $_POST['heure'] : null;
				$h2 = (!empty($_POST['heure2'])) ? $_POST['heure2'] : null;
				$tempdeb = (!empty($_POST['tempdeb'])) ? $_POST['tempdeb'] : null;
				$tempfin = (!empty($_POST['tempfin'])) ? $_POST['tempfin'] : null;
				insere_fichesup($idfiche,$h1,$h2,$tempdeb,$tempfin);
			}
			$retour['fiche'] = array('idfiche'=>$idfiche, 'commune'=>$com, 'site'=>$site, 'date'=>$_POST['date']);
			$retour['nouveau'] = 'Oui';
			$retour['statut'] = 'Oui';
		}
		else // IdFiche connu dans la base
		{
			$idfiche = $_POST['idfiche'];
			$retour['fiche'] = array('idfiche'=>$idfiche, 'commune'=>$_POST['com'], 'site'=>$_POST['site'], 'date'=>$_POST['date']);
			$retour['nouveau'] = 'Non';
			$retour['statut'] = 'Oui';
		}
		//Gestion espèce
		if($idfiche != 0)
		{
			$statutobs = $_POST['statutobs'];
			if($statutobs == 'Pr')
			{
				$denom = $_POST['denom'];
				$tdenom = (isset($_POST['tdenom'])) ? $_POST['tdenom'] : 'NSP';
				if($denom == 'Co')
				{
					if($tdenom == 'IND' || $tdenom == 'NSP')
					{
						$ndiff = ($_POST['ndiff'] != '') ? $_POST['ndiff'] : 0;
						$m = (isset($_POST['male']) && $_POST['male'] != '') ? $_POST['male'] : 0;
						$f = (isset($_POST['femelle']) && $_POST['femelle'] != '') ? $_POST['femelle'] : 0;
						$nb = $ndiff + $m + $f;
						if($nb == 0) { $nb = 1; }
						if($ndiff != 0 && $m != 0 && $f != 0) { $sexe = 5; }
						elseif($ndiff != 0 && $m != 0 && $f != 0) { $sexe = 5; }
						elseif($ndiff == 0 && $m != 0 && $f != 0) { $sexe = 5; }
						elseif($ndiff != 0 && $m != 0 && $f == 0) { $sexe = 5; }
						elseif($ndiff != 0 && $m == 0 && $f != 0) { $sexe = 5; }
						elseif($ndiff != 0 && $m == 0 && $f == 0) { $sexe = 0; }
						elseif($ndiff == 0 && $m != 0 && $f == 0) { $sexe = 3; }
						elseif($ndiff == 0 && $m == 0 && $f != 0) { $sexe = 2; }
						elseif($ndiff == 0 && $m == 0 && $f == 0) { $sexe = 0; }
						$nbmax = $nb; $nbmin = $nb;
					}
					else
					{
						$nbmax = $_POST['nbmax']; $nbmin = $_POST['nbmin'];
						if($tdenom == 'CPL') 
						{
							$ndiff = 0; $m = $nbmin; $f = $nbmin; $nb = $m + $f; $sexe = 5;
						}
						else
						{
							$ndiff = 0; $m = 0; $f = 0; $nb = $nbmin; $sexe = 6;
						}						
					}
				}
				elseif($denom == 'Es')
				{
					$nbmax = $_POST['nbmax']; $nbmin = $_POST['nbmin'];
					if($tdenom == 'IND' || $tdenom == 'NSP')
					{
						$ndiff = ($_POST['ndiff'] != '') ? $_POST['ndiff'] : 0;
						$m = (isset($_POST['male']) && $_POST['male'] != '') ? $_POST['male'] : 0;
						$f = (isset($_POST['femelle']) && $_POST['femelle'] != '') ? $_POST['femelle'] : 0;
						if($ndiff != 0 || $m != 0 || $f != 0) 
						{
							$nb = $ndiff + $m + $f; $nbmax = $nb; $nbmin = $nb;
							if($ndiff != 0 && $m != 0 && $f != 0) { $sexe = 5; }
							elseif($ndiff != 0 && $m != 0 && $f != 0) { $sexe = 5; }
							elseif($ndiff == 0 && $m != 0 && $f != 0) { $sexe = 5; }
							elseif($ndiff != 0 && $m != 0 && $f == 0) { $sexe = 5; }
							elseif($ndiff != 0 && $m == 0 && $f != 0) { $sexe = 5; }
							elseif($ndiff != 0 && $m == 0 && $f == 0) { $sexe = 0; }
							elseif($ndiff == 0 && $m != 0 && $f == 0) { $sexe = 3; }
							elseif($ndiff == 0 && $m == 0 && $f != 0) { $sexe = 2; }
						} 
						else
						{
							$ndiff = 0; $m = 0; $f = 0; $sexe = 6;	
							if(!empty($nbmin) && !empty($nbmax)) { $nb = ($nbmax - ($nbmin - 1)) / 2; }
							elseif(!empty($nbmin) && empty($nbmax)) { $nb = $nbmin; }
						}
					}
					else
					{
						$ndiff = 0; $m = 0; $f = 0;
						$sexe = 6;
						if(!empty($nbmin) && !empty($nbmax)) { $nb = ($nbmax - ($nbmin - 1)) / 2; }
						elseif(!empty($nbmin) && empty($nbmax)) { $nb = $nbmin; }
						if($tdenom == 'CPL')
						{
							$sexe = 5;
							$m = $nb; $f = $nb; $nb = $nb * 2;
						}
					}
				}
				elseif($denom == 'NSP')
				{
					$sexe = 6;
					$ndiff = 1; $m = 0; $f = 0;
					$nbmax = 1; $nbmin = 1; $nb = 1;
				}				
			}
			else
			{
				$nb = 0;
			}
			$iddet = $_POST['iddet'];
			$nom = (!empty($_POST['latin'])) ? $_POST['latin'] : $_POST['nomf'];
			if($_POST['idobs'] == 'Nouv') //Nouvelle obs.
			{	
				$rq = htmlspecialchars($_POST['rq']);
				$dates = date("Y-m-d");				
				$cdnom = $_POST['cdnom'];
				$cdref = $_POST['cdref'];
				$nomvar = $_POST['sel'];
				$idetude = $_POST['etude'];
				$idproto = $_POST['protocol'];
				$vali = ($_POST['newsp'] == 'oui') ? 7 : 6;
				//insertion obs
				$verifobs = verifobs($idfiche,$cdref);
				if($verifobs == 0)
				{
					if(isset($_POST['image-data']) && !empty($_POST['image-data']))// si photo
					{
						$photo = 'oui';
					}					
					$idobs = insere_obs($idfiche,$cdnom,$cdref,$iddet,$dates,$nomvar,$rq,$vali,$nb,$statutobs,$idetude,$idproto);
					if($_POST['newsp'] == 'oui')
					{
						modif_listeob($cdref,$nomvar);
						insere_lister($cdref,$nomvar);
						$datem = date("Y-m-d H:i:s");
						$idmembre = $_POST['idm'];
						modif($idobs,$idmembre,$nom,$datem,$cdref);
					}
					//$vali = result_vali_auto($idobs);
					//$retour['vali'] = $vali;
					$retour['idobs'] = $idobs;
					$stade = $_POST['stade'];
					//insertion ligneobs
					if($statutobs == 'Pr')
					{
						$idetat = $_POST['etatbio'];
						$idmethode = $_POST['obsmethode'];
						$idpros = (isset($_POST['obscoll'])) ? $_POST['obscoll'] : 0;
						$idstbio = (isset($_POST['bio'])) ? $_POST['bio'] : 0;
                        $idcomp = $_POST['comportement'];
						$idligneobs = insere_ligneobs($idobs,$stade,$ndiff,$m,$f,$denom,$idetat,$idmethode,$idpros,$idstbio,$nbmin,$nbmax,$sexe,$tdenom,$idcomp);
						insere_identif($idligneobs,$idobs,$idfiche,$dates);
						//insertion habitat
						if(isset($_POST['cdhab']) && $_POST['cdhab'] != '')
						{
							insere_habitat($idobs,$_POST['cdhab'],$cdref);
						}						
						$retour['obs'] = array('idligneobs'=>$idligneobs, 'nom'=>$nom, 'stade'=>$_POST['stadeval'], 'nb'=>$nb);
					}
					else
					{
						$retour['obs'] = array('idligneobs'=>'', 'nom'=>$nom, 'stade'=>'', 'nb'=>0);
					}
				}
				else
				{
					if(isset($_POST['image-data']) && !empty($_POST['image-data']))// si photo
					{
						$photo = 'non';
					}
					$retour['obs'] = array('idligneobs'=>'', 'nom'=>$nom, 'stade'=>'', 'nb'=> '');
					$retour['verifobs'] = 'oui';
				}
			}
			else// insertion ligne stade etat bio
			{
				if(isset($_POST['image-data']) && !empty($_POST['image-data']))// si photo
				{
					$photo = 'oui';
				}				
				$idobs = $_POST['idobs'];
				$stade = $_POST['stade'];
				$idetat = $_POST['etatbio'];
				$idmethode = $_POST['obsmethode'];
				$idpros = (isset($_POST['obscoll'])) ? $_POST['obscoll'] : 0;
				$idstbio = (isset($_POST['bio'])) ? $_POST['bio'] : 0;
                $idcomp = $_POST['comportement'];
				if ($_POST['denom'] != 'NSP')
				{
					$nbor = $_POST['nb'];
					$nbmod = $nbor + $nb;
					modif_obs($idobs,$nbmod);
				}
				$idligneobs = insere_ligneobs($idobs,$stade,$ndiff,$m,$f,$denom,$idetat,$idmethode,$idpros,$idstbio,$nbmin,$nbmax,$sexe,$tdenom,$idcomp);
				$dates = date("Y-m-d");	
				insere_identif($idligneobs,$idobs,$idfiche,$dates);
				$retour['idobs'] = $idobs;
				$retour['obs'] = array('idligneobs'=>$idligneobs, 'nom'=>$nom, 'stade'=>$_POST['stadeval'], 'nb'=>$nbmod);
			}
			//plante hote
			if(isset($_POST['cdnombota']) && $_POST['cdnombota'] != '')
			{
				$cdnombota = explode(",", $_POST['cdnombota']);
				if(count($cdnombota) > 1)
				{
					$nbplte = explode(",", $_POST['nbplte']);
					foreach($cdnombota as $n)
					{
						$tabcdnom[] = $n;
					}
					foreach($nbplte as $n)
					{
						$tabnbplte[] = $n;
					}
					$tabplte = array_combine($tabcdnom,$tabnbplte);
					foreach($tabplte as $cle => $n)
					{
						$nbplte = ($n != '') ? $n : null;
						insere_obsplte($idobs,$stade,$nbplte,$cle);
					}
				}
				else
				{
					$nbplte = ($_POST['nbplte'] != '') ? $_POST['nbplte'] : null;
					insere_obsplte($idobs,$stade,$nbplte,$cdnombota[0]);
				}				
			}
			//collection - genitalia
			if(!empty($_POST['iddetcol']) || !empty($_POST['idprep']))
			{
				$typegen = (isset($_POST['typegen'])) ? $_POST['typegen'] : '';
				$codegen = (isset($_POST['codegen'])) ? $_POST['codegen'] : '';
				$sexegen = (isset($_POST['sexegen'])) ? $_POST['sexegen'] : '';
				$detcol = (!empty($_POST['iddetcol'])) ? $_POST['iddetcol'] : null;
				$pregen = (!empty($_POST['idprep'])) ? $_POST['idprep'] : null;
				$detgen = (!empty($_POST['iddetgen'])) ? $_POST['iddetgen'] : null;
				insere_obscoll($idobs,$detcol,$typegen,$pregen,$codegen,$sexegen,$detgen,$stade);
			}
			//code nicheur piaf
			if(isset($_POST['indnid']) && $_POST['indnid'] != 0)
			{
				insere_aves($idobs,$stade,$_POST['indnid']);
			}
			//cause de la mort
			if(isset($_POST['mort']) && ($_POST['mort'] != 0 && $_POST['mort'] != 1))
			{
				insere_mort($idobs,$stade,$_POST['mort']);
			}
			// si photo
			if(isset($photo))
			{
				$retour['photo'] = 'oui';
				if($photo == 'oui' && isset($idobs))
				{
					$dossier_destination1 = '../../../photo/P800/'.$nomvar.'/';
					$dossier_destination2 = '../../../photo/P400/'.$nomvar.'/';
					$dossier_destination3 = '../../../photo/P200/'.$nomvar.'/';
					$nbphoto = liste_photo($cdref);
					//$sp = espece($cdref,$nomvar);
					//$g = $sp['genre'][0];
					//$nomphoto = ($nbphoto == 0) ? $g.'-'.$sp['espece'].''.$cdref : $g.'-'.$sp['espece'].''.$cdref.'-'.$nbphoto;
					$nomphoto = $nomvar . time();
					$nomfichier = $nomphoto.'.jpg';
					$img = $_POST['image-data'];
					$exp = explode(',', $img);
					$data = base64_decode($exp[1]);
					$file = $dossier_destination1 . $nomfichier;
					if(file_put_contents($file, $data) !== false) 
					{
						require '../../../lib/RedimImageJpg.php';
						$orien = $_POST['orien'];
						$repSource = $dossier_destination1;
						$repDest = $dossier_destination2;
						$redim = ($orien == 'paysage') ? fctredimimage(400,266,$repDest,'',$repSource,$nomfichier) : fctredimimage(200,300,$repDest,'',$repSource,$nomfichier);
						$repDest = $dossier_destination3;
						$redim = ($orien == 'paysage') ? fctredimimage(200,133,$repDest,'',$repSource,$nomfichier) : fctredimimage(100,150,$repDest,'',$repSource,$nomfichier);
						if ($redim == true) 
						{ 
							//enregistrement bdd
							$pfiche = chp_fiche($idfiche);							
							
							$sexe = $_POST['sexe'];
							$dates = date("Y-m-d H:i:s");							
							$ordre = ($nbphoto == 0) ? 1 : $nbphoto + 1 ;
							if(isset($_POST['opph']))
							{
								$idobserp = ($_POST['opph'] != $pfiche['idobser']) ? $_POST['opph'] : $pfiche['idobser'];								
							}
							else
							{
								$idobserp = $pfiche['idobser'];
							}							
							insere_photo($cdref,$idobserp,$pfiche['date1'],$pfiche['codecom'],$stade,$nomphoto,$dates,$sexe,$nomvar,$idobs,$ordre);
						}
					}
				}
			}
		}
	}
	else
	{
		$retour['statut'] = 'Tous les champs ne sont pas remplis';
	}
} 
else 
{$retour['statut'] = 'Tous les champs ne sont pas parvenus';}
   
echo json_encode($retour);
