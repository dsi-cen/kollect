<?php
if (isset($_POST['db']) && isset($_POST['host']) && isset($_POST['user']) && isset($_POST['pass']))
{
	define('SQL_DSN', 'pgsql:dbname='.$_POST['db'].';host='.$_POST['host'].'');
	define('SQL_USERNAME', ''.$_POST['user'].'');
	define('SQL_PASSWORD', ''.$_POST['pass'].'');
	include '../lib/pdo2.php';
	
	try
	{
		$sql = new PDO(SQL_DSN, SQL_USERNAME, SQL_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		$er = 'ok';
		$sql = null;
	}	
	catch (Exception $e)
	{
		$er = 'non';
	}
	if ($er == 'ok')
	{
		$config = '<?php 
		define("SQL_DSN", "pgsql:dbname='.$_POST['db'].';host='.$_POST['host'].'");
		define("SQL_USERNAME", "'.$_POST['user'].'");
		define("SQL_PASSWORD", "'.$_POST['pass'].'");';	
				
		$filename = '../global/configbase.php';
		if (!$fp = @fopen($filename, 'w+')) 
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-success" role="alert">Connexion avec la base réussie.</div>
			<div class="alert alert-warning" role="alert">Impossible de créer ou d\'écrire le fichier configbase.php. Assurez vous d\'avoir les droits nécessaires (CHMOD). </dv>';
			echo json_encode($retour);	
			exit;			
		} 
		else 
		{
			fwrite($fp, $config);
			fclose($fp);
			$retour['statut'] = 'Oui';
			$retour['mes'] = '<div class="alert alert-success" role="alert">Connexion avec la base réussie.<br />
			Un fichier avec vos paramètres de connexion a été crée : configbase.php dans le répertoire global</dv>';
		}
	}
	else
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert">Mauvais paramètres de connexion.</dv>';
	}	
	echo json_encode($retour);	
}