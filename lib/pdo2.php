<?php

class PDO2 extends PDO {

	private static $_instance;
	
	public function __construct() {	}
	
	public static function getInstance() {
		if (!isset(self::$_instance)) 
		{
			try 
			{
				self::$_instance = new PDO(SQL_DSN, SQL_USERNAME, SQL_PASSWORD);
			}
			catch (PDOException $e) 
			{
				echo $e;
			}
		} 
		// RLE : Historisation des modifications en base de données par passation de l'idm via $_SESSION
		session_start();
		$idm = (isset($_SESSION['idmorigin'])) ? $_SESSION['idmorigin'] : $_SESSION['idmembre'];
		self::$_instance->query("SELECT outils.set_user('" . $idm . "');");
		// Fin de l'historisation
		return self::$_instance; 
	}
	public static function getInstanceinstall() {
		if (!isset(self::$_instance)) 
		{
			try 
			{
				self::$_instance = new PDO(SQL_DSN, SQL_USERNAME, SQL_PASSWORD);
			}
			catch (PDOException $e) 
			{
				echo $e;
			}
		}
		return self::$_instance; 
	}
}