<?php
/*
Classe utilisée afin de grouper les messsages pour faciliter d'éventuelles personnalisations ou traductions.

Vous pouvez modifier les valeurs des messages sans contrainte.

Ces tableaux sont compilés par la classe "SetMessages" dans le tableau "$messages" en fonction des besoins de chaque script. Cette méthode vous permettra d'ajouter d'autres tableaux ou messages plus librement. Cependant, si vous ajoutez des messages dans un tableau ou si vous créez d'autres tableaux de messages, veillez à ne pas utiliser des index existants utilisés par d'autres classes dans le même script, notamment :

- Le tableau "communs" est chargé systématiquement par défaut, 
- Pour chaque upload de fichier, le tableau "uploadServeur" est utilisé, 
- Pour traiter ou optimiser les images, le tableau "redimCrop" est utilisé, 
- La classe de connexion bdd utilise le tableau "connexionBdd". 

Tous les index des messages des tableaux ci-dessus qui sont chargés automatiquement en fonction des classes utilisées, sont préfixés par "UpAb". Pour éviter tout conflit IL SUFFIT DONC DE :

1/ NE PAS AJOUTER DES MESSAGES AVEC DES INDEX COMMENÇANTS PAR "UpAb" dans les tableaux que vous ajouterez directement (explicitement) dans vos scripts avec SetMessages, ex : new SetMessages('inscription'); dans le fichier "UploadAjaxABCI_Inscription.php".

2/ RESERVER les index commançant par "UpAb" pour les messages des tableaux qui sont ajoutés automatiquement (implicitement) par les classes qui sont appelées en fonction des besoins (ex : new SetMessages('redimCrop'); dans le fichier "Classes/CropRedim.php") ET VERIFIER dans ce cas qu'il n'y ait pas de doublon d'index dans tous les autres tableaux.

Respectez cette convention si vous souhaitez pouvoir ajouter des messages plus librement dans vos scripts d'upload. Il vous sera ainsi très facile d'éviter les conflits d'index pour les messages que vous ajoutez explicitement dans vos scripts, tout en étant protégé des conflits avec les index des messages utilisés dans les classes apellées par ces mêmes scripts ;)

En cas de conflit entre les index, les derniers chargés seront ignorés.


Note : Comme indiqué en commentaire le tableau "inscriptionClient" n'est pas utilisé côté serveur mais côté client en javascript dans l'exemple "UploadAjaxABCI_Inscription_Photo_Crop.php". Ainsi tous les messages utilisés dans cet exemple, côté client et côté serveur, sont regroupés ici. 
*/
header('Content-type: text/html; charset=UTF-8');

abstract class Messages {


	/* *** ---- Tableaux de messages ajoutés automatiqument en fonction des classes utilisées  ---- *** */
	
	
	// INITIALISÉ AUTOMATIQUEMENT par SetMessages() DANS TOUS LES CAS. Messages couramment utilisés dans les scripts. 
	final public static function communs()
	{
		$tab = [
		  'UpAbVerifToken'				=> "Connexion non valide ou perdue. Rafraîchissez la page et recharger éventuellement votre fichier, si celui-ci dispose d'une sauvegarde automatique elle sera utilisée."
		, 'UpAbAllowedMemorySize'		=> "Le fichier a une résolution trop grande. Réduisez votre fichier avant le téléchargement."
		, 'UpAbMaximumExecutionTime'	=> "Le temps d'exécution maximum du script est dépassé. Réduisez votre fichier avant le téléchargement."
		, 'UpAbExtensionFichier'		=> "Extension du fichier non valide."
		, 'UpAbImageNonValide'			=> "Image non valide."
		, 'UpAbConfigChmod'				=> "Echec de la configuration des droits d'accès du fichier."
		, 'UpAbMaxSizeFichier'			=> "Dépassement de la taille maximale autorisée."
		, 'UpAbRenomme'					=> "renommé "		
		, 'UpAbCopieFichier'			=> "Erreur dans la copie du fichier original."
		, 'UpAbAucunFichierTraite'		=> "Aucun fichier traité."
		, 'UpAbFicherExistDeja'			=> "Ce fichier existe déjà."
		, 'UpAbTaitementFin'			=> "Traitement terminé."
		// Message par défaut (ne pas supprimer)	
		, 'UpAbIndefini' 				=> "Erreur non documentée." 
		];
		return $tab;
	}


	/* INITIALISÉ AUTOMATIQUEMENT dans la classe d'upload serveur "UploadAjaxABCIServeur"
	
	(une fois le script configuré et fonctionnel seuls les index suivant pourront s'afficher dans quelques cas : 'UpAbServerFichierTemp', 'UpAbServerVerifSauv' et 'UpAbServerVerifFile'. Pour les autres la presonnalisation/traduction est superflue.
	 */
	final public static function uploadServeur()
	{
		$tab = [
		  'UpAbServerFichierTemp'		=> "Fichier temporaire non valide."
		, 'UpAbServerFichierId'			=> "Identifiant de fichier non valide."
		, 'UpAbServerNomInvalide'		=> "Nom de fichier non valide."
		, 'UpAbServerTailleInvalide'	=> "Taille du fichier non valide."
		, 'UpAbServerSourceInvalide'	=> "Provenance du fichier non valide."
		, 'UpAbServerOuvertureTemp'		=> "Erreur d'ouverture du fichier temporaire."
		, 'UpAbServerOuvertureContent'	=> "Erreur d'ouverture du contenu téléchargé."
		, 'UpAbServerLectureContent'	=> "Erreur de lecture du contenu téléchargé."
		, 'UpAbServerEcritureContent'	=> "Erreur d'écriture du contenu téléchargé."
		, 'UpAbServerVerifSauv'			=> "Erreurs possibles : la sauvegarde utilisée a été enregistrée lors d'un instant critique. Ou vous avez téléchargé ce même fichier simultanément depuis deux fenêtres différentes."
		, 'UpAbServerVerifFile'			=> "Erreurs dans la vérification de l'intégrité du fichier."
		, 'UpAbServerDestFile'			=> "Destination du fichier non valide."
		, 'UpAbServerFichierTransfert'	=> "Problème dans le transfert du fichier."
		];
		return $tab;
	}
	
	
	/* INITIALISÉ AUTOMATIQUEMENT dans les classes de traitement des images "CropRedim" et "RedimImage"
	
	(une fois le script configuré et fonctionnel ces erreurs ont très peu de chance d'apparaître, la traduction/personnalisation est superflue)	
	*/
	final public static function redimCrop()
	{
		$tab = [
		  'UpAbImageCreationSource'			=> "Erreur de création de l'image source."
		, 'UpAbImageCreationDestination'	=> "Erreur de création de l'image de destination."
		, 'UpAbImageRedimension'			=> "Erreur de redimensionnement."
		, 'UpAbImageEnregistrement'			=> "Erreur d'enregistrement de l'image."
		, 'UpAbImageDimensions'				=> "Dimensions de l'image non valides."	
		];		
		return $tab;
	}


	// INITIALISÉ AUTOMATIQUEMENT dans la classe "C_PDO"
	
	final public static function connexionBdd()
	{
		$tab = [
		'UpAbConnectBdd'				=> "Erreur de connexion à la base de donnée."
		];	
		return $tab;
	}
	
	
	/* ---- Fin des tableaux de messages ajoutés automatiquement en fonction des classes utilisées ---- */
	
	
	
	// Initialisé dans le fichier "UploadAjaxABCI_VerifFileExist.php"
	final public static function verifFileExist()
	{
		$tab = [
		'paramRequeteVerif'				=> "Paramètre de la requête de vérification des fichiers non valide."
		];
		return $tab;
	}
	
	
	// Initialisé dans le fichier "UploadAjaxABCI_Php_Load.php"
	final public static function php_load()
	{
		$tab = [
		  'fichierTelec'				=> "fichier téléchargé."
		, 'fichiersTelec'				=> "fichiers téléchargés."
		];
		return $tab;
	}
	
	
	// Initialisé dans le fichier "UploadAjaxABCI_Php_Load_Controle_input_text.php"
	final public static function php_load_controle_input ()
	{
		$tab = [
		  'titreObligatoire'			=> "Le titre est obligatoire."
		, 'titreExiste'					=> "Ce titre existe déjà."
		, 'titreEnregistre'				=> "Titre enregistré : "
		, 'fichiersJoints'				=> "Fichiers joints : "
		, 'aucunFichierJoint'			=> "(aucun fichier joint)"
		, 'rajouterFichierTitre'		=> "Rajouter des fichiers pour ce titre"
		, 'facultatif'					=> "(facultatif)"
		];
		return $tab;		
	}
	
	
	// Initialisé dans le fichier "UploadAjaxABCI_Php_Load_Crop_multiple.php"
	final public static function php_load_crop_multiple ()
	{
		$tab = [
		  'cropTelechargementFin'		=> "Crop et téléchargement terminé"
		, 'telechargementFin'			=> "Téléchargement terminé"
		];
		return $tab;		
	}
	

	/*
	Initialisé dans les fichiers "UploadAjaxABCI_Inscription.php" et "UploadAjaxABCI_Php_Load_Inscription.php"
	
	- Concernant le fichier "UploadAjaxABCI_Inscription.php" le préfixe des index avec underscore ( _ ) correspond au nom des champs du formulaire html. A modifier en fonctions d'éventuels changements du nom des champs du formulaire en même temps que le script "UploadAjaxABCI_Inscription.php" qui défini le nom des champs à contrôler. Le suffixe de l'index (après le '_' qui suit le nom du champ) correspond au nom de la fonction de contrôle, complété par un second identifiant quand celle-ci peut retourner plusieurs messages d'erreurs différents.
	*/
	final public static function inscription()
	{
		$tab = [
		  'login_requis' 				=> "Le login est requis"
		, 'mail_requis'					=> "L'email est requis"
		, 'pass_requis'					=> "Le mot de passe est requis"
		, 'pass_c_requis'				=> "Confirmation non valide"

		, 'mail_validMail' 				=> "Email non valide"

		, 'login_existBdd' 				=> "Ce login est déjà utilisé"
		, 'mail_existBdd' 				=> "Cet email est déjà utilisé"	
		, 'login_existBdd_fail' 		=> "Erreur dans la requête de vérification du login."
		, 'mail_existBdd_fail' 			=> "Erreur dans la requête de vérification du mail."		
		
		, 'pass_minChar' 				=> "Minimum 6 caractères"
		, 'pass_c_equal' 				=> "Confirmation non valide"

		, 'loginIndefini'				=> "L'identifiant d'enregistrement est invalide. Téléchargement du fichier abandonné."	

		// les erreurs ci-dessous ne s'afficheront normalement que si la configuration du script serveur est défectueuse (erreur de requête)
		, 'requeteInsertion'			=> "Erreur d'enregistrement de vos identifiants. Réessayez et prévenez l'administrateur du site en cas d'échecs persistants."
		, 'insertionNonFichier'			=> "Erreur d'enregistrement du nom du fichier joint. Réessayez en rechargeant votre fichier et prévenez l'administrateur du site en cas d'échecs persistants."
		];
		return $tab;
	}

	
	// Cette fonction n'est pas utilisée côté serveur mais récupérée côté client depuis le script "UploadAjaxABCI_Inscription_Photo_Crop.php" pour afficher les messages du script javascript
	final public static function inscriptionClient()
	{
		$tab = [
		  'traitementTermine' 			=> "Vous êtes enregistré, traitement terminé. Vous allez être redirigé automatiquement dans les prochaines secondes, ou <a href='index.html'>cliquer ici</a>."
		, 'confirmPassErreur' 			=> "Confirmation non valide"
		, 'passMinErreur' 				=> "Minimum 6 caractères"
		, 'traitementFichier' 			=> "Traitement du fichier en cours, patientez."
		, 'enregistrementEchec'			=> "Erreur de traitement. Vérifiez les informations sous les champs du formulaire."
		, 'telechargementEchec' 		=> "Vous êtes enregistré mais un problème a eu lieu durant le téléchargement du fichier. Consultez l'information correspondante.<br>Réessayez en rechargeant un fichier puis cliquez sur le bouton d'envoi, ou cliquez directement sur le bouton d'envoi pour finaliser l'enregistrement sans avatar."	

		// S'affichera en cas d'erreur de destination de la requête ajax  
		, 'erreurRequeteChamp' 			=> "Erreur dans la requête de contrôle des champs du formulaire."
		
		// Status des méthodes javascript
		, 'statusOk' 					=> "Fichier enregistré"
		, 'statusInProgress'			=> 'Téléchargement en cours'
		, 'statusStop' 					=> 'Arrêt'
		, 'statusErrorSize' 			=> 'Dépassement de la taille maximale autorisée.' // message si dépassement de "config.fileSizeMax" (non utilisé dans l'exemple).
		, 'statusErrorExtension' 		=> 'Extension non valide.'
		, 'statusErrorServer' 			=> "Echec. "// complété automatiquement par le message serveur
		, 'remainingTimeComputeWaiting' => 'calcul en cours' // s'affiche durant la période définie par la variable "config.remainingTimeCompute".
		];
		return $tab;
	}
}
?>