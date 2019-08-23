<?php
include '../global/configbase.php';
include '../lib/pdo2.php';

function schema($n)
{
	$bdd = PDO2::getInstanceinstall();		
	$req = $bdd->query("SELECT schema_name FROM information_schema.schemata WHERE schema_name = '$n' ");
	$sch = $req->rowCount();
	$req->closeCursor();
	return $sch;		
}
function creerschema($n)
{
	$bdd = PDO2::getInstanceinstall();		
	$req = $bdd->query("CREATE SCHEMA $n");
	$req->closeCursor();
}
function sql($n)
{
	set_time_limit(0);
	file_put_contents('progression.txt', 'Creation des shémas et tables');
	$bdd = PDO2::getInstanceinstall();
	$req = null;
	$filename = $n.'.sql';
	$req = file_get_contents($filename);
	$req = str_replace("\n","",$req);
	$req = str_replace("\r","",$req);
	$bdd->exec($req);
	unset($req);
}
function sqlcom($n,$compte)
{
	set_time_limit(0);
	file_put_contents('progression.txt', 'Enregistrement de la table '.$n.' dans shéma install');
	file_put_contents('progressionb.txt', $compte);	
	$bdd = PDO2::getInstanceinstall();
	$req = null;
	$filename = $n.'.sql';
	$req = file_get_contents($filename);
	$bdd->exec($req);
	unset($req);
}
function sqltaxref($n,$compte)
{
	set_time_limit(0);
	$comptetaxref = $compte - 3;
	file_put_contents('progression.txt', 'Enregistrement table taxref '.$comptetaxref.' sur 11');
	file_put_contents('progressionb.txt', $compte);	
	$bdd = PDO2::getInstanceinstall();
	$req = null;
	$filename = $n.'.sql';
	$req = file_get_contents($filename);
	$bdd->exec($req);
	unset($req);
}
function table()
{
	$bdd = PDO2::getInstanceinstall();		
	$req = $bdd->query("SELECT * FROM pg_tables WHERE schemaname = 'install' ");
	$table = $req->rowCount();
	$req->closeCursor();
	return $table;		
}
		
$schema = array('site','obs','referentiel','install','import','statut','vali');
foreach($schema as $n)
{
	$sch = schema($n);
	if($sch > 0)
	{
		$tab[] = '<span class="text-warning">Le schema '.$n.' existait déjà. Les tables n\'ont pas été installées</span><br />';
		$retour['statut'] = 'Oui';
	}
	else
	{
		$schc = creerschema($n);
		$tab[] = '<span class="text-success">Le schema '.$n.' a été créé.</span><br />';
		$sql = sql($n);
		$tab[] = '<span class="text-success">Les tables du schema '.$n.' ont été créés.</span><br />';
		$retour['statut'] = 'Oui';
	}	
}
$schema1 = array('install2','communepoly','communegeo');
$compte = 1;
foreach($schema1 as $n)
{
	sqlcom($n,$compte);
	$compte++;
	$tab[] = '<span class="text-success">La table '.$n.' a été ajouté au schema install.</span><br />';
	$retour['statut'] = 'Oui';		
}
$taxref = array('taxref','taxref1','taxref2','taxref3','taxref4','taxref5','taxref6','taxref7','taxref8','taxref9','taxref10','taxref11');
$compte = 3;
foreach($taxref as $n)
{
	sqltaxref($n,$compte);
	$compte++;
	$tabtaxref = '<span class="text-success">La table taxref a été ajouté au schema referentiel.</span><br />';
	$retour['statut'] = 'Oui';		
}
$tab[] = $tabtaxref;

$table = table();
if($table >= 8)
{
	$retour['statut'] = 'Oui';
	$tab[] = '<div class="alert alert-success" role="alert">L\'installation des tables est finie. Remplissez le formulaire au-dessus.</div>';
}
else
{
	$retour['statut'] = 'Non';
	$tab[] = '<div class="alert alert-danger" role="alert">Problème lors de l\'installation des tables</div>';
}
$mes = null;
foreach ($tab as $n)
{
	$mes .= $n;
}
$retour['mes'] = $mes;

creerschema('outils');

$bdd = PDO2::getInstanceinstall();
$req = null;
$req = file_get_contents('function_get_user.sql');
$bdd->exec($req);
$req = file_get_contents('function_set_user.sql');
$bdd->exec($req);
unset($req);

creerschema('obs_historique');

$bdd = PDO2::getInstanceinstall();
$req = null;
$req = file_get_contents('triggers_historique.sql');
$bdd->exec($req);
unset($req);

// Create mv to optimize export speed
$bdd = PDO2::getInstanceinstall();
$req = null;
$req = file_get_contents('synthese_obs_nflou.sql');
$bdd->exec($req);
unset($req);

// Create mv to optimize export speed
$bdd = PDO2::getInstanceinstall();
$req = null;
$req = file_get_contents('synthese_obs_flou.sql');
$bdd->exec($req);
unset($req);

// Create status mv
$bdd = PDO2::getInstanceinstall();
$req = null;
$req = file_get_contents('synthese_status.sql');
$bdd->exec($req);
unset($req);

echo json_encode($retour);
