<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function vidertable()
{
	$bdd = PDO2::getInstance();		
	$bdd->exec("DELETE FROM import.fiche ");
}
function lambert93versWgs84($x,$y)
{
	$x = number_format($x, 10, '.', '');
	$y = number_format($y, 10, '.', '');
	$b6  = 6378137.0000;
	$b7  = 298.257222101;
	$b8  = 1/$b7;
	$b9  = 2*$b8-$b8*$b8;
	$b10 = sqrt($b9);
	$b13 = 3.000000000;
	$b14 = 700000.0000;
	$b15 = 12655612.0499;
	$b16 = 0.7256077650532670;
	$b17 = 11754255.426096;
	$delx = $x - $b14;
	$dely = $y - $b15;
	$gamma = atan( -($delx) / $dely );
	$r = sqrt(($delx*$delx)+($dely*$dely));
	$latiso = log($b17/$r)/$b16;
	$sinphiit0 = tanh($latiso+$b10*atanh($b10*sin(1)));
	$sinphiit1 = tanh($latiso+$b10*atanh($b10*$sinphiit0));
	$sinphiit2 = tanh($latiso+$b10*atanh($b10*$sinphiit1));
	$sinphiit3 = tanh($latiso+$b10*atanh($b10*$sinphiit2));
	$sinphiit4 = tanh($latiso+$b10*atanh($b10*$sinphiit3));
	$sinphiit5 = tanh($latiso+$b10*atanh($b10*$sinphiit4));
	$sinphiit6 = tanh($latiso+$b10*atanh($b10*$sinphiit5));
	$longrad = $gamma/$b16+$b13/180*pi();
	$latrad = asin($sinphiit6);
	$long = ($longrad/pi()*180);
	$lat = ($latrad/pi()*180);
	$lat = round($lat, 4);
	$long = round($long, 5);
	return array($lat,$long);	
}
function Wgs84verslambert93($latitude,$longitude)
{
	$c= 11754255.426096;
    $e= 0.0818191910428158;
    $n= 0.725607765053267;
    $xs= 700000;
    $ys= 12655612.049876;
	$lat_rad= $latitude/180*PI();
    $lat_iso= atanh(sin($lat_rad))-$e*atanh($e*sin($lat_rad));
	$x= (($c*exp(-$n*($lat_iso)))*sin($n*($longitude-3)/180*PI())+$xs);
    $y= ($ys-($c*exp(-$n*($lat_iso)))*cos($n*($longitude-3)/180*PI()));
	$x = round($x);
	$y = round($y);
	return array($x,$y);
}
function codel93($x,$y)
{
	$n = strlen($x);
	if($n == 5) { $codel93 = 'E00'.substr($x, 0, 1).'N'.substr($y, 0, 3); }
	elseif($n == 6) { $codel93 = 'E0'.substr($x, 0, 2).'N'.substr($y, 0, 3); }
	elseif($n == 7) { $codel93 = 'E'.substr($x, 0, 3).'N'.substr($y, 0, 3); }
	return $codel93;
}
function codel935($x,$y)
{
	$x3 = (substr($x, 2, 1) >= 5) ? '5' : '0';
	$y4 = (substr($y, 3, 1) >= 5) ? '5' : '0';
	$codel935 = 'E0'.substr($x, 0, 2).$x3.'N'.substr($y, 0, 3).$y4;
	return $codel935;
}
function attribution($x,$y,$lat,$lng,$codel93,$alti,$utm,$codel935)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idcoord FROM obs.coordonnee WHERE x = :x AND y = :y ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':x', $x);
	$req->bindValue(':y', $y);
	$req->execute();
	$idcoord = $req->fetchColumn();
	$req->closeCursor();
	if($idcoord == '')
	{
		$req = $bdd->prepare("INSERT INTO obs.coordonnee (x, y, altitude, lat, lng, codel93, utm, codel935) VALUES (:x, :y, :alti, :lat, :lng, :codel93, :utm, :codel935) ") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':x', $x);
		$req->bindValue(':y', $y);
		$req->bindValue(':lat', $lat);
		$req->bindValue(':lng', $lng);
		$req->bindValue(':codel93', $codel93);
		$req->bindValue(':codel935', $codel935);
		$req->bindValue(':utm', $utm);
		$req->bindValue(':alti', $alti);
		$idcoord = ($req->execute()) ? $bdd->lastInsertId('obs.coordonnee_idcoord_seq') : 0;
		$req->closeCursor();
	}	
	return $idcoord;	
}
function attributionsite($site,$idcoord,$codecom)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idsite FROM obs.site WHERE idcoord = :idcoord AND codecom = :codecom AND site = :site ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idcoord', $idcoord, PDO::PARAM_INT);
	$req->bindValue(':codecom', $codecom);
	$req->bindValue(':site', $site);
	$req->execute();
	$idsite = $req->fetchColumn();
	$req->closeCursor();
	if($idsite == '')
	{
		$req = $bdd->prepare("INSERT INTO obs.site (idcoord, codecom, site) VALUES (:idcoord, :codecom, :site) ") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':idcoord', $idcoord);
		$req->bindValue(':codecom', $codecom);
		$req->bindValue(':site', $site);
		$idsite = ($req->execute()) ? $bdd->lastInsertId('obs.site_idsite_seq') : 0;
		$req->closeCursor();
	}	
	return $idsite;	
}
function idobser($idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idobser FROM import.impobser WHERE idobseror = :idobser ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idobser', $idobser, PDO::PARAM_INT);
	$req->execute();
	$idobser = $req->fetchColumn();
	$req->closeCursor();
	return $idobser;	
}
function coordcom($codecom)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT x, y FROM referentiel.commune WHERE codecom = :codecom ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':codecom', $codecom);
	$req->execute();
	$coord = $req->fetch();
	$req->closeCursor();
	return $coord;	
}
function insere_fiche($idor,$iddep,$codecom,$idsite,$date1,$date2,$idobser,$decade,$pre,$idcoord,$plusobser,$typedon,$floutage,$source,$idorg)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idfiche FROM obs.fiche
						WHERE iddep = :iddep AND codecom = :codecom AND idsite = :idsite AND date1 = :date1 AND date2 = :date2 AND idcoord = :idcoord AND idobser = :obs 
						ORDER BY idfiche ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':iddep', $iddep);
	$req->bindValue(':codecom', $codecom);
	$req->bindValue(':idsite', $idsite);
	$req->bindValue(':date1', $date1);
	$req->bindValue(':date2', $date2);
	$req->bindValue(':obs', $idobser, PDO::PARAM_INT);
	$req->bindValue(':idcoord', $idcoord);
	$req->execute();
	$idfiche = $req->fetchColumn();
	$req->closeCursor();
	if($idfiche != '')
	{
		$req = $bdd->prepare("INSERT INTO import.fiche (idor, idfiche, idcoord) VALUES (:idor, :idfiche, :idcoord) ") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':idor', $idor, PDO::PARAM_INT);
		$req->bindValue(':idfiche', $idfiche, PDO::PARAM_INT);
		$req->bindValue(':idcoord', $idcoord, PDO::PARAM_INT);
		$req->execute();
		$req->closeCursor();
		$ajoutobs = 'non';
	}
	else
	{
		$req = $bdd->prepare("INSERT INTO obs.fiche (iddep, codecom, idsite, date1, date2, idobser, decade, localisation, idcoord, floutage, plusobser, typedon, source, idorg)
						VALUES(:iddep, :codecom, :idsite, :date1, :date2, :obs, :decade, :pr, :idcoord, :floutage, :plusobser, :typedon, :source, :org) ") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':iddep', $iddep);
		$req->bindValue(':codecom', $codecom);
		$req->bindValue(':idsite', $idsite);
		$req->bindValue(':date1', $date1);
		$req->bindValue(':date2', $date2);
		$req->bindValue(':obs', $idobser, PDO::PARAM_INT);
		$req->bindValue(':decade', $decade);
		$req->bindValue(':idcoord', $idcoord);
		$req->bindValue(':pr', $pre);
		$req->bindValue(':floutage', $floutage);
		$req->bindValue(':plusobser', $plusobser);
		$req->bindValue(':typedon', $typedon);
		$req->bindValue(':source', $source);
		$req->bindValue(':org', $idorg);
		$idfiche = ($req->execute()) ? $bdd->lastInsertId('obs.fiche_idfiche_seq') : 0;
		$req->closeCursor();
		if($idfiche != 0)
		{
			$req = $bdd->prepare("INSERT INTO import.fiche (idor, idfiche, idcoord) VALUES (:idor, :idfiche, :idcoord) ") or die(print_r($bdd->errorInfo()));
			$req->bindValue(':idor', $idor, PDO::PARAM_INT);
			$req->bindValue(':idfiche', $idfiche, PDO::PARAM_INT);
			$req->bindValue(':idcoord', $idcoord, PDO::PARAM_INT);
			$req->execute();
			$req->closeCursor();
		}
		$ajoutobs = 'oui';
	}
	return array($ajoutobs, $idfiche);	
}
function insere_plusobser($idfiche,$idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idobser FROM import.impobser WHERE idobseror = :idobser ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idobser', $idobser, PDO::PARAM_INT);
	$req->execute();
	$idobser = $req->fetchColumn();
	$req->closeCursor();	
	$req = $bdd->prepare("INSERT INTO obs.plusobser (idfiche, idobser) 	VALUES(:idfiche, :idobser) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idfiche', $idfiche);
	$req->bindValue(':idobser', $idobser);
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

if(isset($_POST['fichier'])) 
{
	$fichier = $_POST['fichier'];
	$premiere = $_POST['nbdeb'];
	$cdate = $_POST['datef'];
	$nbtraitement = 500;
	
	if($premiere == 0)
	{
		vidertable();
	}	
	if(($liste = fopen("../../../tmp/".$fichier, "r")) !== FALSE) 
	{
		fseek($liste, $premiere);
		$nbligne = 0;
		$i = 0;
		while(($data = fgetcsv($liste, 1000, ";")) !== FALSE) 
		{
			if($i++ < $nbtraitement)
			{
				$nbligne++;
				//gestion des coordonnees
				if($data[14] == 1)
				{
					if(empty($data[6]) && !empty($data[8])) 
					{
						$x = round($data[8]);
						$y = round($data[9]);
						$coord = lambert93versWgs84($x,$y);
						$lat = $coord[0];
						$lng = $coord[1];
						$alti = ($data[12] != '') ? $data[12] : 0;
					}
					elseif(empty($data[8]) && !empty($data[6])) 
					{
						$lat = round($data[6], 4);
						$lng = round($data[7], 5);
						$coord = Wgs84verslambert93($lat,$lng);
						$x = $coord[0];
						$y = $coord[1];	
						$alti = ($data[12] != '') ? $data[12] : null;
					}
					elseif(!empty($data[6]) && !empty($data[8])) 
					{
						$lat = round($data[6], 4);
						if(!empty($data[7])) {$lng = round($data[7], 5);}
						if(!empty($data[8])) {$x = round($data[8]);}
						$y = round($data[9]);
						$alti = ($data[12] != '') ? $data[12] : null;
					}	
					$codel93 = ($data[10] == '') ? codel93($x,$y) : $data[10];
					$codel935 = codel935($x,$y);
					$idcoord = attribution($x,$y,$lat,$lng,$codel93,$alti,$data[11],$codel935);
					if($_POST['biogeo'] == 'oui') { insere_biogeo($x,$y,$idcoord); }
					//gestion des sites
					//$site = ($data[5] == '') ? 'NR' : $data[5];					
					$idsite = ($data[5] == '') ? 0 : attributionsite($data[5],$idcoord,$data[1]);						
				}
				elseif($data[14] == 2)
				{
					if(empty($data[6]) && !empty($data[8])) 
					{
						$x = round($data[8]);
						$y = round($data[9]);
						$coord = lambert93versWgs84($x,$y);
						$lat = $coord[0];
						$lng = $coord[1];
						$alti = ($data[12] != '') ? $data[12] : null;
					}
					elseif(empty($data[8]) && !empty($data[6])) 
					{
						$lat = round($data[6], 4);
						$lng = round($data[7], 5);
						$coord = Wgs84verslambert93($lat,$lng);
						$x = $coord[0];
						$y = $coord[1];
						$alti = ($data[12] != '') ? $data[12] : null;
					}
					elseif(!empty($data[6]) && !empty($data[8])) 
					{
						if($data[6] != '') {$lat = round($data[6], 4);}
						if($data[7] != '') {$lng = round($data[7], 5);}
						if($data[8] != '') {$x = round($data[8]);}
						if($data[9] != '') {$y = round($data[9]);}
						$alti = ($data[12] != '') ? $data[12] : null;
					}	
					elseif(empty($data[6]) && empty($data[8]))
					{
						$coordcom = coordcom($data[1]);
						$x = $coordcom['x'];
						$y = $coordcom['y'];
						$alti = null;
						$coord = lambert93versWgs84($x,$y);
						$lat = $coord[0];
						$lng = $coord[1];								
					}
					$codel93 = ($data[10] == '') ? codel93($x,$y) : $data[10];
					$codel935 = codel935($x,$y);
					$idcoord = attribution($x,$y,$lat,$lng,$codel93,$alti,$data[11],$codel935);
					if($_POST['biogeo'] == 'oui') { insere_biogeo($x,$y,$idcoord); }
					$idsite = 0;
				}
				elseif($data[14] == 3)
				{
					$idcoord = 0;
					$idsite = 0;							
				}
				//attribution idobservateur
				$obs1 = explode(",", $data[13]);
				$plusobser = (count($obs1) > 1) ? 'oui' : 'non';
				$idobser = idobser($obs1[0]);
				//date et decade
				if($cdate == 'fr')
				{
					$date1tmp = DateTime::createFromFormat('d/m/Y', $data[3]);
					$date1 = $date1tmp->format('Y-m-d');
					if($data[4] == '')
					{
						$date2 = $date1;
					}
					else
					{
						$date2tmp = DateTime::createFromFormat('d/m/Y', $data[4]);
						$date2 = $date2tmp->format('Y-m-d');
					}
				}
				elseif($cdate == 'us')
				{
					$date1 = $data[3];
					$date2 = $data[4];
					if($date2 == '')
					{
						$date2 = $date1;
					}
				}				
				//calcul decade
				if ($date1 == $date2)
				{
					list($a,$m,$j) = explode("-", $date1);
					
					switch ($m)
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
					if ($j >= 1 && $j <= 10) { $Djrs = '1'; }
					elseif ($j >= 11 && $j <= 20) { $Djrs = '2'; }
					elseif ($j >= 21 && $j <= 31) { $Djrs = '3'; }
					$decade = $DMois.$Djrs;			
				}
				else
				{
					$decade = '';
				}
				//iddep
				$iddep = ($data[2] == '') ? substr($data[1], 0, 2) : $data[2];
				$typedon = $data[15];
				$floutage = ($typedon == 'Pr') ? $data[16] : 0;
				$source =  $data[17];
				//organisme
				$idorg = (!preg_match('#[^0-9]#', $data[18]) && !empty($data[18])) ? $data[18] : 1;
				//insertion fiche
				$idfiche = insere_fiche($data[0],$iddep,$data[1],$idsite,$date1,$date2,$idobser,$decade,$data[14],$idcoord,$plusobser,$typedon,$floutage,$source,$idorg);
				//si plusieurs observateurs
				if($idfiche[0] == 'oui')
				{
					if($plusobser == 'oui')
					{
						$obsor = $obs1[0]; 
						foreach($obs1 as $n)
						{
							if($n != $obsor)
							{
								insere_plusobser($idfiche[1],$n);
							}
						}
					}
				}
				$derniere = ftell($liste);
			}			
			unset($data);
		}		
		fclose($liste);
		//A faire : table commune x y centre
		//insere_fiche1($tabfiche);
		$retour['statut'] = 'Oui';
		$retour['nb'] = $nbligne;
		$retour['derniere'] = $derniere;
	}	
	else
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Aucun fichier.</p></div>';
	}
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Aucun fichier.</p></div>';
}
echo json_encode($retour);