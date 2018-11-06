<?php 
include '../global/configbase.php';
include '../lib/pdo2.php';

function table($ntbl)
{
	$bdd = PDO2::getInstanceinstall();		
	$req = $bdd->query("SELECT table_name FROM information_schema.tables WHERE table_schema='referentiel' AND table_name='$ntbl'");
	$table = $req->rowCount();
	$req->closeCursor();
	return $table;		
}
function vidertable($ntbl)
{
	$bdd = PDO2::getInstanceinstall();		
	$bdd->exec("DELETE FROM referentiel.$ntbl");
}
function insercommune($idreg)
{
	$bdd = PDO2::getInstanceinstall();
	$nbins = $bdd->exec("INSERT INTO referentiel.commune (codecom,commune,x,y,iddep,lng,lat,poly,geojson) 
						SELECT codecom, commune, x, y, departement.iddep, communefr.lng, communefr.lat, communefrpoly.poly, communefrpoly.geojson FROM install.communefr
						INNER JOIN install.communefrpoly USING(codecom)
						INNER JOIN install.departement USING(iddep)
						INNER JOIN install.region ON region.idreg = departement.idreg OR region.idreg = departement.idregn
						WHERE region.idreg IN($idreg) ");
	return $nbins;	
}
function inserdepartement($idreg)
{
	$bdd = PDO2::getInstanceinstall();
	$nbins2 = $bdd->exec("INSERT INTO referentiel.departement (iddep,departement,idreg,poly,geojson,lat,lng) 
						SELECT iddep, departement, departement.idreg, poly, geojson, lat, lng FROM install.departement
						INNER JOIN install.region ON region.idreg = departement.idreg OR region.idreg = departement.idregn
						WHERE region.idreg IN($idreg) ");
	return $nbins2;	
}
function creercommune()
{
	$bdd = PDO2::getInstanceinstall();		
	$bdd->query("SET NAMES 'UTF8'");	
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
function creerdepartement()
{
	$bdd = PDO2::getInstanceinstall();		
	$req = $bdd->query("CREATE TABLE referentiel.departement (
						iddep character (2) NOT NULL,
						departement character varying(30),
						idreg character(2),
						poly character varying(15),
						geojson text,
						lat real,
						lng real,
						CONSTRAINT departement_pkey PRIMARY KEY (iddep))");
	$req->closeCursor();
}
function contour()
{
	$bdd = PDO2::getInstanceinstall();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT communegeo.codecom, communegeo.geojson FROM install.communegeo  
						INNER JOIN install.communefr ON communefr.codecom = communegeo.codecom
						INNER JOIN referentiel.departement USING(iddep) ");
	$reg = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $reg;		
}
function contour2()
{
	$bdd = PDO2::getInstanceinstall();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT iddep, departementgeo.geojson FROM install.departementgeo 
						INNER JOIN referentiel.departement USING(iddep) ");
	$reg2 = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $reg2;		
}

if (isset($_POST['idreg']))
{
	$idreg = $_POST['idreg'];
	
	$ntbl = 'commune';
	$table = table($ntbl);
	if ($table > 0)	{
		vidertable($ntbl);
	} else {
		creercommune();			
	}
	$nbins = insercommune($idreg);
	if($nbins > 1)
	{
		$ntbl = 'departement';
		$table = table($ntbl);
		if ($table > 0)	{ 
			vidertable($ntbl);
		} else {
			creerdepartement();			
		}
		$nbins2 = inserdepartement($idreg);
		if ($nbins2 > 1)
		{
			$reg2 = contour2();
			$geo2 = array('type'=> 'FeatureCollection','crs'=>array('type'=>'name','properties'=>array('name'=>'urn:ogc:def:crs:EPSG::2154')), 'features' => array());
			foreach ($reg2 as $n)
			{
				$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
				$feature['properties']['iddep'] = $n['iddep'];
				$feature['geometry'] = array('type' => 'LineString', 'coordinates' => array($n['geojson']));
				$geo2['features'][] = $feature;
			}
			$json2 = json_encode($geo2, JSON_NUMERIC_CHECK);
			$json2 = str_replace('["[','[[',$json2);
			$json2 = str_replace(']"]',']]',$json2);
		
			$filename = '../emprise/contour2.geojson';
			if (!$fp = @fopen($filename, 'w+')) 
			{
				$retour['statut'] = 'Non';
				$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Impossible de créer ou d\'écrire le fichier contour2.json dans le répertoire emprise. Assurez vous d\'avoir les droits nécessaires (CHMOD).</p></div>';
				echo json_encode($retour);	
				exit;
			} 
			else 
			{
				fwrite($fp, $json2);
				fclose($fp);
				$retour['statut'] = 'Oui';
				$retour['mes'] = '<div class="alert alert-success" role="alert"><p>L\'emprise a été crée</p></div>';
			}				
			if($nbins2 <= 6)
			{
				$reg = contour();
				$geo = array('type'=> 'FeatureCollection','crs'=>array('type'=>'name','properties'=>array('name'=>'urn:ogc:def:crs:EPSG::2154')), 'features' => array());
				foreach ($reg as $n)
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
					$retour['mes'] = '<div class="alert alert-danger" role="alert">Impossible de créer ou d\'écrire le fichier contour.json dans le répertoire emprise. Assurez vous d\'avoir les droits nécessaires (CHMOD).</div>';
					echo json_encode($retour);	
					exit;
				} 
				else 
				{
					fwrite($fp, $json);
					fclose($fp);
					$retour['statut'] = 'Oui';
					$retour['mes'] = '<div class="alert alert-success" role="alert">L\'emprise a été crée</div>';
				}							
			}
			else
			{
				copy('../emprise/contour2.geojson', '../emprise/contour.geojson');
			}
		}
		else
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert">Problème lors de la création de la table departement</div>';
			echo json_encode($retour);	
			exit;
		}
		$filename = '../emprise/emprise.json';
		$datajson = array();
		$datajson["contour2"] = ($nbins2 <= 6) ? 'oui' : 'non';
		$datajson["emprise"] = ($nbins2 <= 6) ? 'dep' : 'reg';
		$datajson["stylecontour"] = array("color"=>"#03f","weight"=>5,"opacity"=>0.2);
		$datajson["stylecontour2"] = array("color"=>"#B27335","weight"=>3);		
		$ajson = json_encode($datajson);
		if (!$fp = @fopen($filename, 'w+')) 
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert">Impossible de créer ou d\'écrire le fichier emprise.json dans le répertoire emprise. Assurez vous d\'avoir les droits nécessaires (CHMOD).</div>';
			echo json_encode($retour);	
			exit;
		} 
		else 
		{
			fwrite($fp, $ajson);
			fclose($fp);
			$retour['statut'] = 'Oui';
			$retour['mes'] = '<div class="alert alert-success" role="alert">L\'emprise a été crée</div>';
		}
	}
	else
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert">Problème lors de la création de la table commune</div>';
		echo json_encode($retour);	
		exit;
	}
		
	echo json_encode($retour);
}