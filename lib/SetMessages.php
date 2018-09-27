<?php
class SetMessages extends Messages 
{
	private static $messages = [];
	public static $erreur_controle = [];
		
	/* Chaque initialisation de la classe "SetMessages()" additionne des nouveaux tableaux de messages dans "$messages" qui a comme base minimum : 
	- Le tableau "communs()"
	- Et tous les tableaux chargés automatiquement en fonction de l'utilisation des classes php. 
	Cf fichier "Classes/Messages.php" pour plus de détails.
	*/
	public function __construct() 
	{
		// On charge toujours le tableau des messages les plus communs 
		if(empty(self::$messages)) self::$messages += self::communs();
		
		// Charge les argmuments du constructeur
		foreach (func_get_args() as $methode)
		{
			if(method_exists('Messages',$methode)) self::$messages += self::$methode();
		}		
	}


	final public static function setMess($index)
	{
		if (empty(self::$messages)) {new self();}
		
		return isset(self::$messages[$index])? self::$messages[$index] : self::$messages['UpAbIndefini'];
	}
	

	final public static function erreurControle ()
	{		
		return !empty(self::$erreur_controle) ? self::$erreur_controle : false;
    }
}
?>