<?php 
include '../global/configbase.php';
include '../lib/pdo2.php';

function table($ntbl)
{
	$bdd = PDO2::getInstanceinstall();		
	$req = $bdd->query("SELECT table_name FROM information_schema.tables WHERE table_schema='referentiel' AND table_name='$ntbl'") or die(print_r($bdd->errorInfo()));
	$table = $req->rowCount();
	$req->closeCursor();
	return $table;		
}
function vidertable($ntbl)
{
	$bdd = PDO2::getInstanceinstall();		
	$bdd->exec("DELETE FROM referentiel.$ntbl");
}
function insercommune($tabcom)
{
	$nbins = 0;
	$bdd = PDO2::getInstanceinstall();
	$req = $bdd->prepare("INSERT INTO referentiel.commune (codecom,commune,x,y,iddep,lng,lat,poly,geojson) 
						SELECT communefr.codecom, commune, x, y, iddep, lng, lat, poly, geojson FROM install.communefr
						INNER JOIN install.communefrpoly ON communefrpoly.codecom = communefr.codecom
						WHERE communefr.codecom = :idcom ") or die(print_r($bdd->errorInfo()));
	foreach ($tabcom as $n)
	{					
		$req->bindValue(':idcom', $n);
		$req->execute();
		$nbins++;
	}
	$req->closeCursor();
	return $nbins;	
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
						CONSTRAINT commune_pkey PRIMARY KEY (codecom))") or die(print_r($bdd->errorInfo()));
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
						CONSTRAINT departement_pkey PRIMARY KEY (iddep))") or die(print_r($bdd->errorInfo()));
	$req->closeCursor();
}
function listedep()
{
	$bdd = PDO2::getInstanceinstall();		
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT DISTINCT iddep FROM referentiel.commune ") or die(print_r($bdd->errorInfo()));
	$listedep = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $listedep;		
}
function inserdepartement($listedep)
{
	$nbins = 0;
	$bdd = PDO2::getInstanceinstall();
	$req = $bdd->prepare("INSERT INTO referentiel.departement (iddep,departement,idreg,poly,geojson,lat,lng) 
						SELECT iddep, departement, idreg, poly, geojson, lat, lng FROM install.departement
						WHERE iddep = :iddep ") or die(print_r($bdd->errorInfo()));
	foreach ($listedep as $n)
	{					
		$req->bindValue(':iddep', $n['iddep']);
		$req->execute();
		$nbins++;
	}
	$req->closeCursor();
	return $nbins;
}
function com($idcom)
{
	$bdd = PDO2::getInstanceinstall();		
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT codecom, geojson FROM install.communegeo WHERE codecom IN($idcom) ") or die(print_r($bdd->errorInfo()));
	$com = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $com;		
}
/*function contour2($tabdep)
{
	$bdd = PDO2::getInstanceinstall();		
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT iddep, departementgeo.geojson FROM install.departementgeo WHERE iddep = :iddep ") or die(print_r($bdd->errorInfo()));
	foreach ($tabdep as $n)
	{					
		$req->bindValue(':iddep', $n);
		$req->execute();	
		$dep2[] = $req->fetch(PDO::FETCH_ASSOC);
	}	
	$req->closeCursor();
	return $dep2;		
}*/
function contour2($idparc)
{
	$bdd = PDO2::getInstanceinstall();		
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT id, poly, geojson FROM install.pnr WHERE id = :idparc ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idparc', $idparc);
	$req->execute();
	$parc = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $parc;		
}
/*function verifdep($listedep)
{
	$oktabdep = 'non';
	$tabdep = null;
	$bdd = PDO2::getInstanceinstall();		
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT COUNT(codecom) FROM install.communefr 
						WHERE NOT EXISTS (SELECT codecom FROM referentiel.commune
						WHERE (commune.codecom = communefr.codecom)) AND iddep = :iddep ") or die(print_r($bdd->errorInfo()));
	foreach ($listedep as $n)
	{					
		$req->bindValue(':iddep', $n['iddep']);
		$req->execute();
		$nb = $req->fetchColumn();
		if($nb == 0)
		{
			$tabdep[] = $n['iddep'];
			$oktabdep = 'oui';
		}		
	}
	$req->closeCursor();
	return array($oktabdep, $tabdep);	
}*/

if (isset($_POST['idcom']))
{
	$idcom = $_POST['idcom'];
	$idparc = $_POST['idparc'];
	$ntbl = 'commune';
	$table = table($ntbl);
	if ($table > 0)	{
		vidertable($ntbl);
	} else {
		creercommune();				
	}
	$idcom = substr($idcom, 0, -2);
	$idcom1 = str_replace("'","",$idcom);
	$tabcom = explode(',', $idcom1);
	$nbins = insercommune($tabcom);
	if ($nbins > 1)
	{
		$com = com($idcom);	
		$geo = array('type'=> 'FeatureCollection','crs'=>array('type'=>'name','properties'=>array('name'=>'urn:ogc:def:crs:EPSG::2154')), 'features' => array());
		foreach ($com as $n)
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
			$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Impossible de créer ou d\'écrire le fichier contour.geojson dans le répertoire emprise. Assurez vous d\'avoir les droits nécessaires (CHMOD).</p></div>';
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
		
		//$datajson = array();
		
		$listedep = listedep();
		//$nbiddep = count($listedep);
		//if ($nbiddep > 1)
		//{
			$ntbl = 'departement';
			$table = table($ntbl);
			if ($table > 0)	{
				vidertable($ntbl);
			} else {
				creerdepartement();			
			}
			$nbins2 = inserdepartement($listedep);
			/*if ($nbins2 > 1)
			{
				$tabdep = verifdep($listedep);
				if($tabdep[0] == 'oui')
				{
					$dep2 = contour2($tabdep[1]);
					$geo2 = array('type'=> 'FeatureCollection','crs'=>array('type'=>'name','properties'=>array('name'=>'urn:ogc:def:crs:EPSG::2154')), 'features' => array());
					foreach ($dep2 as $n)
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
						$datajson["contour2"] = "oui";
						$datajson["stylecontour2"] = array("color"=>"#B27335","weight"=>3);
					}
				}
				else
				{
					$datajson["contour2"] = "non";
				}
			}*/			
		//}
		$parc = contour2($idparc);
		$geo2 = array('type'=> 'FeatureCollection','crs'=>array('type'=>'name','properties'=>array('name'=>'urn:ogc:def:crs:EPSG::2154')), 'features' => array());
		//foreach ($parc as $n)
		//{
			$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
			$feature['properties']['id'] = $parc['id'];
			$feature['geometry'] = array('type' => $parc['poly'], 'coordinates' => array($parc['geojson']));
			$geo2['features'][] = $feature;
		//}
		$json2 = json_encode($geo2, JSON_NUMERIC_CHECK);
		$json2 = str_replace('["[','[',$json2);
		$json2 = str_replace(']"]',']',$json2);
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
			$datajson["contour2"] = "oui";
			$datajson["stylecontour2"] = array("color"=>"#B27335","weight"=>3);
		}
		/*else
		{
			$datajson["contour2"] = "non";
		}*/
		$filename = '../emprise/emprise.json';
		$datajson = array();
		$datajson["contour2"] = 'non';
		$datajson["emprise"] = 'parc';
		$datajson["stylecontour"] = array("color"=>"#03f","weight"=>5,"opacity"=>0.2);
		$datajson["stylecontour2"] = array("color"=>"#B27335","weight"=>3);		
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
	}
	else
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Problème lors de la création de la table commune</p></div>';
		echo json_encode($retour);	
		exit;
	}		
}
echo json_encode($retour);