<?php
include '../global/configbase.php';
include '../lib/pdo2.php';

function table()
{
	$bdd = PDO2::getInstanceinstall();		
	$req = $bdd->query("SELECT table_name FROM information_schema.tables WHERE table_schema='referentiel' AND table_name='mgrs10'") or die(print_r($bdd->errorInfo()));
	$table = $req->rowCount();
	$req->closeCursor();
	return $table;		
}
function vidertable()
{
	$bdd = PDO2::getInstanceinstall();		
	$bdd->exec("DELETE FROM referentiel.mgrs10 ");
}
function creermgrs10()
{
	$bdd = PDO2::getInstanceinstall();		
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("CREATE TABLE referentiel.mgrs10 (mgrs character varying(8) NOT NULL, geo text, CONSTRAINT mgrs10_pkey PRIMARY KEY (mgrs))") or die(print_r($bdd->errorInfo()));
	$req->closeCursor();
}
function inserutm()
{
	$bdd = PDO2::getInstanceinstall();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("INSERT INTO referentiel.mgrs10 (mgrs,geo) 
						SELECT mgrs, geo FROM install.mgrs10 ") or die(print_r($bdd->errorInfo()));	
	$nbins = $req->rowCount();
	$req->closeCursor();					
	return $nbins;	
}
if (isset($_POST['utm']) && isset($_POST['lat']) && isset($_POST['lng']))
{
	$utm = $_POST['utm'];
	if ($utm == 'oui')
	{
		copy('mgrs100.geojson', '../emprise/mgrs100.geojson');
		$table = table();
		if ($table > 0)	{
			vidertable();
		} else {
			creermgrs10();			
		}
		$nbins = inserutm();
		if ($nbins == 0)
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Problème lors de la creation et insertion des mailles.</p></div>';
			echo json_encode($retour);	
			exit;
		}
	}
		
	$json = file_get_contents('../emprise/emprise.json');
	$rjson = json_decode($json, true);
	$emp = $rjson['emprise'];
	$filename = '../emprise/emprise.json';
	$datajson = array();
	$datajson["emprise"] = $emp;
	$datajson["contour2"] = 'non';
	$datajson["utm"] = $utm;
	$datajson["lambert5"] = 'non';
	$datajson["biogeo"] = 'oui';
	$datajson["nbmaille"] = 5878;
	$datajson["lat"] = floatval($_POST['lat']);
	$datajson["lng"] = floatval($_POST['lng']);
	$datajson["sw"] = $_POST['sw'];
	$datajson["ne"] = $_POST['ne'];
	$datajson["stylecontour"] = $rjson['stylecontour'];
	$datajson["stylemaille"] = array("color"=>"#ff7800","weight"=>5,"opacity"=>0.2);
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
	}	
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Tous les paramètres ne sont pas configurés</p></div>';
}
echo json_encode($retour, JSON_NUMERIC_CHECK);	