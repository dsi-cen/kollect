<?php 
include '../global/configbase.php';
include '../lib/pdo2.php';

function table()
{
	$bdd = PDO2::getInstanceinstall();		
	$req = $bdd->query("SELECT table_name FROM information_schema.tables WHERE table_schema='referentiel' AND table_name='commune'");
	$table = $req->rowCount();
	$req->closeCursor();
	return $table;		
}
function vidertable()
{
	$bdd = PDO2::getInstanceinstall();		
	$bdd->exec("DELETE FROM referentiel.commune");
}
function insercommune($iddep)
{
	$bdd = PDO2::getInstanceinstall();
	$nbins = $bdd->exec("INSERT INTO referentiel.commune (codecom,commune,x,y,altitude,iddep,lng,lat,poly,geojson) 
						SELECT communefr.codecom, commune, x, y, altitude, iddep, lng, lat, poly, geojson FROM install.communefr
						INNER JOIN install.communefrpoly ON communefrpoly.codecom = communefr.codecom
						WHERE iddep IN($iddep) ");
	return $nbins;	
}
function insercommunec($idcom)
{
	$bdd = PDO2::getInstanceinstall();
	$nbins2 = $bdd->exec("INSERT INTO referentiel.commune (codecom,commune,x,y,iddep,lng,lat,poly,geojson) 
						SELECT communefr.codecom, commune, x, y, iddep, lng, lat, poly, geojson FROM install.communefr
						INNER JOIN install.communefrpoly ON communefrpoly.codecom = communefr.codecom
						WHERE communefr.codecom IN($idcom) ");
	return $nbins2;	
}
function creercommune()
{
	$bdd = PDO2::getInstanceinstall();		
	$bdd->query('SET NAMES "utf8"');	
	$req = $bdd->query("CREATE TABLE referentiel.commune (
						codecom character varying(5) NOT NULL,
						commune character varying(145),
						x integer,
						y integer,
						iddep character(2),
						lng real,
						lat real,
						poly character varying(15),
						geojson text,
						CONSTRAINT commune_pkey PRIMARY KEY (codecom))");
	$req->closeCursor();
}
function dep($iddep)
{
	$bdd = PDO2::getInstanceinstall();		
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT communegeo.codecom, geojson FROM install.communegeo 
						INNER JOIN install.communefr ON communefr.codecom = communegeo.codecom  
						WHERE iddep IN($iddep) ");
	$dep = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $dep;		
}
function com($idcom)
{
	$bdd = PDO2::getInstanceinstall();		
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT codecom, geojson FROM install.communegeo WHERE codecom IN($idcom) ");
	$com = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $com;		
}

if (isset($_POST['iddep']) && isset($_POST['idcom']))
{
	$iddep = $_POST['iddep'];
	$idcom = $_POST['idcom'];
	$table = table();
	if ($table > 0)	{
		vidertable();
	} else {
		creercommune();			
	}	
	if (!empty ($iddep))
	{
		$nbins = insercommune($iddep);
		if ($nbins > 1)
		{
			$dep = dep($iddep);				
		}
		else
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Problème lors de la création de la table commune</p></div>';
			echo json_encode($retour);	
			exit;
		}		
	}
	if (!empty ($idcom))
	{
		$idcom = substr($idcom, 0, -2);
		$nbins2 = insercommunec($idcom);
		if ($nbins2 >= 1)
		{
			$com = com($idcom);			
		}
		else
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Problème lors de la création de la table commune</p></div>';
			echo json_encode($retour);	
			exit;
		}		
	}	
	if (isset($dep) && isset($com))
	{
		$contour = array_merge($dep,$com);
	}	
	elseif (isset ($dep) && !isset ($com))
	{
		$contour = $dep;
	}
	elseif (isset ($com) && !isset ($dep))
	{
		$contour = $com;
	}

	$geo = array('type'=> 'FeatureCollection','crs'=>array('type'=>'name','properties'=>array('name'=>'urn:ogc:def:crs:EPSG::2154')), 'features' => array());
	foreach ($contour as $n)
	{
		$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
		$feature['properties']['codecom'] = $n['codecom'];
		$feature['geometry'] = array('type' => 'LineString', 'coordinates' => $n['geojson']);
		$geo['features'][] = $feature;
	}
	$json = json_encode($geo, JSON_NUMERIC_CHECK);
	$json = str_replace('"[','[',$json);
	$json = str_replace(']"',']',$json);

	$filename = '../emprise/contour.geojson';
	if (!$fp = @fopen($filename, 'w+')) 
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Impossible de créer ou d\'écrire le fichier contour.json dans le répertoire emprise. Assurez vous d\'avoir les droits nécessaires (CHMOD).</p></div>';
		echo json_encode($retour);	
		exit;
	} 
	else 
	{
		fwrite($fp, $json);
		fclose($fp);
		$retour['statut'] = 'Oui';
		$retour['mes'] = '<div class="alert alert-success" role="alert"><p>L\'emprise a été crée</p></div>';
	}	
	$filename = '../emprise/emprise.json';
	$datajson = array();
	$datajson["emprise"] = "com";
	$datajson["contour2"] = "non";
	$datajson["stylecontour"] = array("color"=>"#03f","weight"=>5,"opacity"=>0.2);
	$ajson = json_encode($datajson);
	if (!$fp = @fopen($filename, 'w+')) 
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Impossible de créer ou d\'écrire le fichier emprise.json dans le répertoire emprise. Assurez vous d\'avoir les droits nécessaires (CHMOD).</p></div>';
		echo json_encode($retour);	
		exit;
	} 
	else 
	{
		fwrite($fp, $ajson);
		fclose($fp);
		$retour['statut'] = 'Oui';
		$retour['mes'] = '<div class="alert alert-success" role="alert"><p>L\'emprise a été crée</p></div>';
	}

	echo json_encode($retour);
}