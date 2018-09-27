<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);


// maximise les possibilités de reprise d'upload en cas d'arrêt intempestif
ignore_user_abort(true);


class UploadAjaxABCIServeur extends SetMessages
{
	private $version = '5.0';
	/* - IMPORTANT : Vous pouvez modifier les valeurs de $this->reponse_upload['upabci_erreur'] qui sont dans Le fichier "Classes/Messages.php", MAIS PAS les valeurs de $this->reponse_upload['upabci_resultat'] qui sont des commandes ajax et qui de toutes façons n'apparaîtront jamais dans la réponse html.
	
	- IMPORTANT : Si vous étendez cette classe et que vous surchargez la fonction "Transfert()", faire attention de supprimer le cookie identifiant le fichier en cas de succès, ainsi que d'attribuer la valeur "upload_ok" à la variable $this->reponse_upload['upabci_resultat'] afin de permettre au script javascript de se poursuivre correctement.
	*/


	//  --------------- Gestion des erreurs fatales ----------------------
	
	//cf mode d'emploi, chapître CONFIGURATION DU SCRIPT PHP D'UPLOAD
	
	/* Les messages d'erreurs seront concaténés au message défini dans la classe javascript avec "info.status.erreur".
	
	- Si aucune des deux fonctions "cathErrorServeur()" ou "setModeDebug()" n'est utilisée, les erreurs fatales ne seront pas transmises et seul le message javascript s'affichera.
	
	- La fonction "cathErrorServeur($tableau)" demande un tableau en paramètre et permet de personnaliser le retour des erreurs fatales. L'index des valeurs est constitué par une suite de mots génériques renvoyés par l'erreur du serveur, et les valeurs sont constituées soit du message personnalisé à afficher, soit d'un tableau constitué du message personnalisé comme premier élément et de la valeur "true" (ou d'une valeur non nulle) comme second élément pour indiquer de supprimer le fichier temporaire et l'éventuel cookie du fichier. Exemple :

	$tab_erreurs = [];
	
	$tab_erreurs['Allowed memory size'] = ["Mémoire insuffisante, le fichier est trop gros pour être redimensionné.",true]; // le cookie et le fichier de sauvegarde seront supprimés
	
	$tab_erreurs['Maximum execution time'] = "Le temps d'exécution maximum du script est dépassé, rechargez votre image et réessayez !"; // le cookie et le fichier de sauvegarde seront préservés

	$up->cathErrorServeur($tab_erreurs);

	Voir le mode d'emploi pour plus d'explications. 
	*/
	
	public function cathErrorServeur ($array_erreur)
	{
		is_array($array_erreur) && count($array_erreur) > 0 ? $this->config_erreur_serveur = $array_erreur : '';
	}


	// Fonction à utiliser juste après l'initialisation de la classe pour retourner les erreurs fatales non attrapées par la fonction "cathErrorServeur()" précédente. A n'utiliser qu'en phase de développement.
	public function setModeDebug ()
	{
		ini_set('display_errors', 1);
		$this->mode_debug = true;
	}
	
	
	/*  --------------- Récupération des paramètres de la requête Ajax ----------------------
	
	cf mode d'emploi, chapître CONFIGURATION DU SCRIPT PHP D'UPLOAD
	
	Vous pourrez récupérer les valeurs des champs input éventuellement ajoutés dans le formulaire avec la fonction php "urldecode" :

	$ma_variable = isset($_POST['ma_variable']) ? urldecode($_POST['ma_variable']) : null;
	
	
	Récupération des paramètres spécifiques de la requête Ajax 
	Des paramètres spécifiques à la classe javascript peuvent être récupérés en complément :
	
	- public $UpAbci_form est un tableau de données spécifique à la classe. Il est renseigné dans tous les cas, excepté pour la requête complémentaire de confirmation de fin de traitement du formulaire qui est envoyée si et uniquement si l'option de configuration javascript  "config.queryFormEnd" = true. Dans ce cas et uniquement pour cette requête additionnelle, le tableau sera vide et vous pourrez récupérer les informations depuis "public $UpAbci_formEnd" évoqué plus bas.
	
	Liste des index renseignés  :
	
	Dans tous les cas :
		- id_form
		- uniqid_form
		- iteration_form 	nombre d'itérations de la requête ajax pour le formulaire (remise à zéro pour chaque nouvelle soumission du formulaire)
	
	
	Si un fichier est joint au formulaire :
		- input_name		nom de l'input de type file
		- uniqid_file
		- cook_name
		- name
		- size
		- type
		- lastModified
		- qte_save		quantité sauvegardée en continu
		- qte_upload
		- result *		"ok_done", "backup_done", "error_done", "backup_fail", 	"error_fail", ou "0_0" 
						préfixe = état du fichier, suffixe = état de la requête.
		- time_start
		- time_end
		- iteration 	nombre d'itérations de la requête ajax pour le fichier
	
	* A noter que "result" est le résultat final renseigné en javascript au retour de la requête ajax. Sa valeur sera donc toujours 0_0 côté serveur excepté si vous configurez l'option config.queryFormEnd = true pour envoyer une requête additionnelle en fin de traitement du formulaire.
			
	Suivant le contexte, la requête ajax envoie d'autres variables  :
	
		
	- public $UpAbci_formEnd 	Uniquement disponible à la fin du traitement du formulaire si l'option de 	
								configuration javascript "config.queryFormEnd = true". 
								Retourne un tableau de tableaux d'informations (1 pour chaque fichier)
								avec le même contenu que "$UpAbci_form" cité plus haut. 
								Si aucun fichier n'est joint, le tableau  renverra seulement les trois index 
								"id_form", "uniqid_form" et "iteration_form".
								Si l'option javascript n'a pas été configurée ou si ce n'est pas la fin du 	
								traitement du formulaire $UpAbci_formEnd retourne un tableau vide.
	
	- protected $UpAbci_blobSlice   	renvoie "true" si le fichier est en plusieurs morceaux
			
	- protected $UpAbci_fileEnd 		renvoie "true" si c'est la fin du fichier (dernière partie).
			
	- protected $UpAbci_fragment 		renvoie le fragment de ficher, ou la valeur 1 si une sauvegarde complète
										est trouvée lors de la sélection du fichier.
	
	
	La méthode getParam($index) retourne les valeurs du tableau "$UpAbci_form" sinon éventuellement les valeurs de "$UpAbci_formEnd[0]" si  $index = "id_form" ou  $index = "uniqid_form"  ou $index = "iteration_form" (pour être pratique).
	
	Exemple :
	$name = $up->getParam('name');  // retourne le nom du fichier
	(retourne false si l'index n'existe pas)
	*/
	
	public function getParam ($index)
	{
		if(isset($this->UpAbci_form[$index]))
		{
			return $this->UpAbci_form[$index];
		}
		else if(isset($this->UpAbci_formEnd[0][$index]))
		{
			switch ($index)
			{
				case 'id_form' :
				case 'uniqid_form' :
				case 'iteration_form' : return $this->UpAbci_formEnd[0][$index];break;
			} 
		}
		return false;
	}
	
	
	/* 
	getFileEnd() retourne true si c'est la fin du fichier sinon false. Retourne indéfini si aucun fragment de fichier n'est joint dans la requête. 
	A NOTER que cette fonction retourne la valeur $UpAbci_fileEnd (cité plus haut) envoyée par javascript avant tout traitement du fichier par le serveur. Utilisez la fonction "getTempAdressFileComplete()" après la fonction "Upload()" pour avoir confirmation que le fichier est correctement enregistré dans le dossier temporaire du serveur ou "getTransfertOk()" après la fonction "Transfert ()" pour avoir confirmation que le fichier est transféré correctement dans son emplacement définitif.
	*/
	public function getFileEnd()
	{
		return $this->UpAbci_fileEnd;
	}
	
	
	public function saveAll ($value = true)
	{ // A utiliser impérativement AVANT la fonction Upload (sinon elle ne fonctionnera pas). Cette fonction permet d'utiliser le répertoire temporaire pour stocker tous les fichiers y compris ceux dont la taille est inférieure à la taille des fragemnts. 
		$this->save_all = $value;
	}
	
	
	public function getFragment ()
	{ // retourne true si un fichier est joint dans la requête (ou si une sauvegarde complète a été trouvée pour le fichier).
		return isset($this->UpAbci_fragment);
	}


	public function getFragmentSize ()
	{ // retourne la taille du fragment. NE PAS UTILISER pour tester la présence d'un fragment car si une sauvergarde complète est trouvée cette valeur sera indéfinie donc égale à zéro. Seule l'utilisation de getFragment() doit être utilisée pour tester la présence d'un fichier ou d'un fragment joint au post du formulaire.
		return isset($this->UpAbci_fragment['size']) ? $this->UpAbci_fragment['size'] : 0;
	}


	public function getCleanFileName ()
	{ // nom du fichier nettoyé (alternativement vous pouvez utiliser "CleanFileName($nom_fichier)")
		return $this->cleanFname;
	}
		
	
	public function getFileDestination ()
	{ // destination avec nom de fichier nettoyé
		return $this->file_destination;
	}
	

	public function ReturnOctets($val)
	{ // retourne des octets depuis une chaine formatée comme 100 Mo ou 1 g
		$val = str_replace([',',' '],['.',''],$val);
		$val = rtrim($val, "oO");
	
		$last = strtolower(substr($val,-1));
	
		switch($last)
		{
			case 't':  $val *= 1024;
			case 'g':  $val *= 1024;
			case 'm': $val *= 1024;
			case 'k':  $val *= 1024;
		}
		return $val;
	}
	
	
	public function addInfosServer ($value)
	{ // Ajoute un message texte ou html dans le retour d'information général du formulaire ayant la classe "UpAbci_infosServer". Pourra également être récupéré en second paramètre des fonctions javascript événementielles "config.func_FileEndEach" et "config.func_FormEnd"
		$this->reponse_upload = array_merge($this->reponse_upload,["upabci_infos_server" => $value]);
	}
	
	
	public function addMixteServer($mixte)
	{ // Envoie un contenu texte, html ou un tableau de données qui pourra être récupéré en troisième paramètre des fonctions javascript événementielles "config.func_FileEndEach" et "config.func_FormEnd". N'affiche rien dans le html. Vous devrez exploiter ces données comme bon vous semble.
		$this->reponse_upload = array_merge($this->reponse_upload,["upabci_mixte_server" => $mixte]);
	}


	/* Stoppe la soumission du formulaire. Cette commande ne sort pas du script en cours, utilisez exitReponseAjax() par la suite.
	Si l'option de configuration javascript config.queryFormEnd = true l'éventuelle requête de confirmation de fin de formulaire sera envoyée uniquement si le paramètre $query_end = true. 
	*/
	public function stopForm($query_end = false)
	{	
		$this->reponse_upload['upabci_stop_form'] = trim($query_end) != false ? 1 : 0; // ne pas modifier
	}

	
	/* 
	Sort du script en ajoutant un message qui sera concaténé au statut "info.status.erreur" de la classe javascript et envoyé dans le bloc html du fichier ayant la classe "UpAbci_status". Ne transmet PAS les éventuels messages précédemment ajoutés avec les fonctions addInfosServer() ou addMixteServer(), pour ce faire utilisez plutôt la fonction addStatusErreur($value). 
	*/
	public function exitStatusErreur ($value)
	{ 
		exit(json_encode(['upabci_erreur' => $value]));
	}


	// à utiliser AVANT la fonction "Upload" (si vous l'utilisez après, le fichier devra être téléchargé avant que le visiteur puisse avoir l'information)
	public function VerifExtensions($fichier,$extensions)
	{
		$filesExtensions = is_array($extensions) ? array_map('strtolower',$extensions) : [];
		$extension_fichier = strtolower(pathinfo($fichier, PATHINFO_EXTENSION));
		// si le tableau des extensions autorisées est vide on accepte toutes les extensions					 
		if (count($filesExtensions) == 0 || in_array($extension_fichier,$filesExtensions))				 
		return true;
		else
		return false;                  
	}


	/* Ajoute un message qui sera concaténé au statut "info.status.erreur" de la classe javascript et envoyé dans le bloc html ayant la classe "UpAbci_status". Ne sort pas du script, utilisez "exitReponseAjax()" par la suite. 
	A utiliser après la fonction "Upload" si vous souhaitez préserver un fichier partiellement ou totalement téléchargé.
	*/
	public function addStatusErreur($value)
	{
		if(empty($this->reponse_upload['upabci_erreur'])) 
		{
			$this->reponse_upload['upabci_erreur'] = $value;
		}
		$this->reponse_upload['upabci_resultat'] = 'add_status_erreur';// ne pas modifier
		return false;
	}
	
	
	// à utiliser APRES la fonction "Upload"
	public function getTempAdressFileComplete()
	{ // retourne l'adresse du fichier temporaire s'il est complet et valide sinon false
		return $this->fichier_verif ? $this->file_temp_address : false;
	}


	// à utiliser APRES la fonction "Upload"
	public function getTempAdressFile()
	{ // retourne l'adresse du fichier temporaire. Peut être un fragment de fichier.
		return is_file($this->file_temp_address)? $this->file_temp_address : false;
	}
	
	
	/* A utiliser AVANT la fonction "Transfert" et de préférence après avoir vérifié que le fichier est complet afin de minimiser l'utilisation de cette fonction qui peut être assez gourmande en ressource (en fonction du nombre de fichiers du répertoire) si le deuxième paramètre est renseigné pour renommer les fichiers en mode incrémental.
	
	 "RenameIdenticName()" renomme le fichier téléchargé si un fichier de même nom existe déjà sur le serveur. 
	Par défaut la fonction ajoute un identifiant unique (uniqid) au nom des fichiers.
	- Avec un second argument optionnel quelconque (ex : $up->RenameIdenticName($destination_fichier,'incr');) le nom des fichiers est incrémenté.  
	- Un troisième argument optionnel casse sensivitive est également disponible, mais à n'utiliser que sur les serveurs casse sensitive (NE PAS UTILISER AVEC LES SERVEURS WINDOWS).
	- Ne touchez pas aux paramètres 4 et 5.
	*/
	public function RenameIdenticName($adresse_fichier, $incr = false, $unix = false, $stop = 0, $isfile = false)
	{
		if ($isfile || is_file($adresse_fichier))
		{
			$info = pathinfo($adresse_fichier);
			$extension = isset($info['extension']) && $info['extension'] != '' ? '.'.$info['extension'] : null;
			$dossier = $info['dirname'];
			$filename = $info['filename'];
			
			if (trim($incr) != false && $stop < 90)// le stop arbitrtaire est une mesure de sécurité au cas où...
			{
				$file = addcslashes($filename,'.');			
				$ext = isset($extension) ? addcslashes($extension,'.') : null;									
	
				$match = trim($unix) != false ? '#^'.$file.'-[0-9]+'.$ext.'$#' : '#^'.$file.'-[0-9]+'.$ext.'$#i';
				
				$tab_identique = [];
				
				$files = new RegexIterator(new DirectoryIterator($dossier),$match);
				foreach ($files as $fileinfo) $tab_identique[] = $fileinfo->getFilename();
	
				natsort($tab_identique);
				
				$dernier = array_pop($tab_identique);
				
				unset($tab_identique);
							
				$dernier = isset($dernier)? pathinfo($dernier, PATHINFO_FILENAME) : '';
				
				$file = preg_replace_callback('#([0-9]+$)#', create_function('$matches','return $matches[1]+1;'), $dernier, '1', $count);
	
				$filename = !empty($count)? $file : $filename.'-1';
			}
			else
			{
				$filename .= '-'.uniqid();
			}
																														
			$filename = isset($extension) ? $filename.$extension : $filename;												
																					 
			$adresse = $dossier.'/'.$filename;
			
			if (!is_file($adresse)) return $adresse;
			else																													
			return Rename_fich($adresse_fichier, $incr, $unix, ++$stop, true);                        
		}																				 
		else 
		{
			return $adresse_fichier;
		}
	}


    /******* IMPORTANT  ******* 
	
	Les deux fonctions "deleteCookieSave()" et "setTransfertOk()" doivent être utilisées dans les cas particuliers où l'on utilise pas la fonction "Transfert()" qui se charge habituellement de ces tâches si tout le processus d'upload est terminé et ok. 
	
	En complément vous devrez également supprimer le fichier temporaire habituellement déplacé vers son emplacement définitif par la fonction "Transfert()". Vous pourrez récupérer son adresse avec "getTempAdressFileComplete()". Ce point est facultatif pour un fonctionnement correct des scripts mais permet de ne pas encombrer inutilement le dossier des fichiers temporaires. 
	
	Par contre l'effacement du cookie de sauvegarde avec "deleteCookieSave()" quand le fichier est complet, et la transmission du status ok avec "setTransfertOk()" quand tout le processus est ok, sont indispensables au bon fonctionnement de la classe javascript si l'on utilise pas la fonction "Transfert()".
	
	L'utilisation de ces deux fonctions conjointement à l'utilisation de la fonction "Tranfert()" se traduira le plus souvent par un bug côté php.
	*/

	// à utiliser APRES la fonction "Upload" et après avoir testé que le fichier est complet : supprime le cookie de sauvegarde (cf note IMPORTANT ci-dessus)
	public function deleteCookieSave()
	{
		setcookie($this->cookie_name,"",time()-3600,$this->cookie_path);
	}

	// à utiliser APRES la fonction "Upload" et après avoir testé que le fichier est complet : donne le status ok à la requête ajax (cf note IMPORTANT ci-dessus)
	public function setTransfertOk()
	{
		$this->reponse_upload['upabci_resultat'] = 'upload_ok'; // ne pas modifier
	}

	/***** Fin de la remarque IMPORTANT *****/
	
	
	// à utiliser normalement APRES la fonction "Transfert" 
	public function addStatusOk ($value)
	{ // ajoute un message qui sera concaténé au statut "this.info.status.ok" de la classe javascript et envoyé dans le bloc html ayant la classe "UpAbci_status"
		$this->reponse_upload = array_merge($this->reponse_upload,['upabci_ok' => $value]);
	}
	
	
	// à utiliser APRES la fonction "Transfert" (a le même effet que de tester le retour de la fonction Transfert())
	public function getTransfertOk ()
	{ // le fichier est complet et a été déplacé avec succès vers son emplacement définitif
		return isset($this->reponse_upload['upabci_resultat']) && $this->reponse_upload['upabci_resultat'] == 'upload_ok';
	}
	
	
	// Retour d'information OBLIGATOIRE et INDISPENSABLE pour le script ajax excepté si la fonction exitStatusErreur() a été utilisée (on peut alternativement utiliser la fonction "getReponseAjax" dans d'autres contextes).
	public function exitReponseAjax()
	{
		exit(json_encode($this->reponse_upload));
	}


	// Alternative à la fonction exitReponseAjax mais ne sort pas du script
	public function getReponseAjax()
	{
		return $this->reponse_upload;
	}

	// ------------------------------------------------------------------------------- 
	// -------------------------------------------------------------------------------
	
	
	// Variables ajax 
	public $UpAbci_form = [];
	public $UpAbci_formEnd = [];
	protected $UpAbci_fragment;
	protected $UpAbci_blobSlice;
	protected $UpAbci_fileEnd;

	
	// Variables php
	protected $dossier_destination;
	protected $dossier_temporaire;
	protected $cleanFname;
	protected $cookie_time;
	protected $cookie_path;
	protected $verif_filesize_sup2Go;
	protected $cookie_name;
	protected $cookie_filesize;
	protected $file_temp_address;
	protected $file_destination;
	protected $fichier_verif = false;
	protected $reponse_upload = [];
	protected $config_erreur_serveur = [];
	protected $mode_debug = false;
	protected $save_all = false;


	public function __construct($dossier_destination = null, $dossier_temporaire = null, $cookie_heures = null, $cookie_path = null, $verif_filesize_sup2Go = false)
	{
		// Initialise les messages serveur contenus dans "Classes/Messages.php"
		new SetMessages('uploadServeur');
		
		// Interception des erreurs fatales
		register_shutdown_function([$this, 'Shutdown']);

		
		$this->dossier_destination = trim($dossier_destination);
		$this->dossier_temporaire = trim($dossier_temporaire);
		$this->cookie_time = is_numeric($cookie_heures) && $cookie_heures > 0 ? time()+3600*$cookie_heures : time()+3600*24;
		$this->cookie_path = trim($cookie_path) != false ?  trim($cookie_path) : '/';
		$this->verif_filesize_sup2Go = trim($verif_filesize_sup2Go) != false;
		
		$this->GetPostFile();		
	}


	private function GetPostFile()
	{
		$UpAbci_form = isset($_POST['UpAbci_form']) ? $_POST['UpAbci_form'] : null;
		if(!empty($UpAbci_form)) 
		{
			parse_str($UpAbci_form, $this->UpAbci_form);			
		}

		$UpAbci_formEnd = isset($_POST['UpAbci_formEnd']) && is_array($_POST['UpAbci_formEnd']) ? $_POST['UpAbci_formEnd'] : [] ;
		if(count($UpAbci_formEnd) > 0)
		{
			$output = [];
			foreach($UpAbci_formEnd as $value)
			{
				parse_str($value, $output);
				$this->UpAbci_formEnd[] = $output;
			}
		}
		
		
		$this->UpAbci_fragment = isset($_FILES['UpAbci_fragment']) ? $_FILES['UpAbci_fragment'] : null;
		$this->UpAbci_fragment = !isset($this->UpAbci_fragment) && filter_input(INPUT_POST, 'UpAbci_fragment') ? 1 : $this->UpAbci_fragment;// si post UpAbci_fragment existe c'est que le script javascript à trouvé une sauvegarde complète et a remplacé file UpAbci_fragment par post UpAbci_fragment.
		
		if(isset($this->UpAbci_fragment)) 
		{
			$this->cleanFname = $this->CleanFileName($this->getParam("name"));				
			
			$this->UpAbci_blobSlice = urldecode(filter_input(INPUT_POST, 'UpAbci_blobSlice'));
			$this->UpAbci_blobSlice = $this->UpAbci_blobSlice == 1;
			
			$this->UpAbci_fileEnd = urldecode(filter_input(INPUT_POST, 'UpAbci_fileEnd'));
			$this->UpAbci_fileEnd = $this->UpAbci_fileEnd == 1;
			
			$this->cookie_name = $this->getParam('cook_name');
			
			$cook_save = isset($_COOKIE[$this->cookie_name]) ? urldecode($_COOKIE[$this->cookie_name]) : null;
			$cook_save = explode('|',$cook_save);
			$cook_temp_adresse = !empty($cook_save[0]) && ctype_alnum($cook_save[0]) ? $cook_save[0] : null;
			
			$this->cookie_filesize = isset($cook_save[1]) ? intval($cook_save[1]) : 0; 
			
			
			$this->file_temp_address = isset($cook_temp_adresse) ? $this->dossier_temporaire.$cook_temp_adresse : $this->dossier_temporaire.hash("sha256",(uniqid($this->getParam("uniqid_form"),true).uniqid($this->getParam("uniqid_file"),true)));	

			$this->file_destination = $this->dossier_destination.$this->cleanFname;	
		}
	}
		
	
	
	public function Upload ()
	{			
		if(isset($this->UpAbci_fragment)) 
		{
			// Permet de récupérer le fichier temporaire s'il existe, s'il est complet et s'il n'est pas corrompu. Peut-être utile au cas où une erreur php se serait produite lors d'un traitement après l'upload complet (crop etc.). Evite d'attendre à nouveau pour le téléchargement lors des essais ultérieurs.
			if ($this->cookie_filesize == $this->getParam("size") && $this->UpAbci_fileEnd && $this->UpAbci_fragment === 1)
			{
				$size_upload = @filesize($this->file_temp_address);
				
				if($size_upload == $this->getParam("size"))
				{
					$this->fichier_verif = true;
					return true;
				}
			}
			
			// si $this->UpAbci_fragment === 1 => pas de fichier joint mais uniquement ses coordonnées pour récupérer la sauvegarde. Si l'on a passé la condition précédente c'est que la sauvegarde est non valide et l'on sort.
			if ($this->UpAbci_fragment === 1) 
			{
				$this->reponse_upload['upabci_erreur'] = SetMessages::setMess('UpAbServerFichierTemp');
				setcookie($this->cookie_name,"",time()-3600,$this->cookie_path);
				@unlink($this->file_temp_address);
				return false;
			}

			// Vérifs			
			if($this->getParam("uniqid_file") == '')
			{
				$this->reponse_upload['upabci_erreur'] = SetMessages::setMess('UpAbServerFichierId');
				return false;
			}
						
			if($this->getParam("name") == '')
			{
				$this->reponse_upload['upabci_erreur'] = SetMessages::setMess('UpAbServerNomInvalide');
				return false;
			}
				
			if($this->getParam("size") == '')
			{
				$this->reponse_upload['upabci_erreur'] = SetMessages::setMess('UpAbServerTailleInvalide');
				return false;
			}
			
			if(!is_uploaded_file($this->UpAbci_fragment['tmp_name']))
			{
				$this->reponse_upload['upabci_erreur'] = SetMessages::setMess('UpAbServerSourceInvalide');
				return false;					
			}
			
			// uploads
			if(!$this->UpAbci_blobSlice && !$this->save_all) // si le fichier est d'un seul morceau et que saveAll() n'a pas été configuré
			{
				$this->file_temp_address = $this->UpAbci_fragment['tmp_name'];
			}
			else
			{
				 // On ouvre ou on crée le fichier
				$fichier_cible = @fopen($this->file_temp_address, 'a+');
				if($fichier_cible === false)
				{
					$this->reponse_upload['upabci_erreur'] = SetMessages::setMess('UpAbServerOuvertureTemp');
					return false;
				}
							
				// On ouvre le contenu téléchargé
				$upload_file = @fopen($this->UpAbci_fragment['tmp_name'], 'rb');
				if($upload_file === false)
				{
					$this->reponse_upload['upabci_erreur'] = SetMessages::setMess('UpAbServerOuvertureContent');
					return false;
				}
				
				// On lit son contenu dans une variable
				$upload_size = $this->UpAbci_fragment['size'];
				$upload_content = @fread($upload_file, $upload_size);
				if($upload_content === false)
				{
					$this->reponse_upload['upabci_erreur'] = SetMessages::setMess('UpAbServerLectureContent');
					setcookie($this->cookie_name,"",time()-3600,$this->cookie_path);
					@unlink($this->file_temp_address);
					return false;
				}	
				
				fclose($upload_file);
				
				// On l'écrit dans le fichier temporaire
				if(@fwrite($fichier_cible, $upload_content) === false)
				{
					$this->reponse_upload['upabci_erreur'] = SetMessages::setMess('UpAbServerEcritureContent');
					return false;
				}	
				
				unset($upload_content);
				
				fclose($fichier_cible);
				
				$new_file_size = $this->cookie_filesize + $upload_size;
						
				setcookie($this->cookie_name,pathinfo($this->file_temp_address,PATHINFO_FILENAME).'|'.$new_file_size,$this->cookie_time,$this->cookie_path);
			
				if (!$this->UpAbci_fileEnd)
				{
					if( !((isset($this->reponse_upload['upabci_resultat']) && $this->reponse_upload['upabci_resultat'] == 'add_status_erreur') || isset($this->reponse_upload['upabci_stop_form'])))
					{
						$this->reponse_upload['upabci_resultat'] = 'continu'; // ne pas modifier
						return true;
					}
				}
			}
			
			if ($this->UpAbci_fileEnd)
			{
				$this->fichier_verif = true;

				// vérification de l'intégrité du fichier (automatique pour les fichiers de moins de 2 Go)
				if ($this->verif_filesize_sup2Go || $this->getParam("size") < $this->ReturnOctets('2 Go'))
				{
					$size_upload = @filesize($this->file_temp_address);
					
					if($size_upload != $this->getParam("size"))
					{
						$this->fichier_verif = false;
						
						if($this->UpAbci_blobSlice || $this->save_all)
						{
							setcookie($this->cookie_name,"",time()-3600,$this->cookie_path);
							
							$this->reponse_upload['upabci_erreur'] = SetMessages::setMess('UpAbServerVerifSauv');
							return false;
						}
						else
						{
							$this->reponse_upload['upabci_erreur'] = SetMessages::setMess('UpAbServerVerifFile');
							return false;
						}
					}
				}
			}
			
			return true;
		}
		
		return false;
	}
	
	
	
	public function Transfert ($file_destination = null)
	{		
		$this->file_destination = trim($file_destination) != '' ? $file_destination : $this->file_destination;
				
		if($this->UpAbci_fileEnd)
		{
			if(isset($_COOKIE[$this->cookie_name]) || $this->save_all)
			{
				setcookie($this->cookie_name,"",time()-3600,$this->cookie_path);
			}		
			
			if(trim($this->file_destination) == '')
			{
				$this->reponse_upload['upabci_erreur'] = SetMessages::setMess('UpAbServerDestFile'); 
				return false;
			}
			
			if(!is_file($this->file_temp_address) || !$this->fichier_verif)
			{
				$this->reponse_upload['upabci_erreur'] = SetMessages::setMess('UpAbServerFichierTemp');
				return false;
			}
			
			if(@rename($this->file_temp_address,$this->file_destination)) 
			{
				$this->reponse_upload['upabci_resultat'] = 'upload_ok'; // ne pas modifier
				return true;
			}
			else
			{
				$this->reponse_upload['upabci_resultat'] = false; // ne pas modifier
				$this->reponse_upload['upabci_erreur'] = SetMessages::setMess('UpAbServerFichierTransfert');
				return false;
			}
		}
		
		return false;
	}



	// La fonction "CleanFileName" est utilisée par défaut dans la fonction "GetPostFile" elle-même appelée par le constructeur de la classe
	public function CleanFileName($nom_fichier)
	{
		$info = pathinfo($nom_fichier);
		$extension = isset($info['extension']) && $info['extension'] != '' ? '.'.$info['extension'] : null;
		$dossier = $info['dirname'] != '.' ? $info['dirname'].'/' : null  ;
		$filename = $info['filename'];
		
		$cible = [
		'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ă', 'Ą',
		'Ç', 'Ć', 'Č', 'Œ',
		'Ď', 'Đ',
		'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ă', 'ą',
		'ç', 'ć', 'č', 'œ',
		'ď', 'đ',
		'È', 'É', 'Ê', 'Ë', 'Ę', 'Ě',
		'Ğ',
		'Ì', 'Í', 'Î', 'Ï', 'İ',
		'Ĺ', 'Ľ', 'Ł',
		'è', 'é', 'ê', 'ë', 'ę', 'ě',
		'ğ',
		'ì', 'í', 'î', 'ï', 'ı',
		'ĺ', 'ľ', 'ł',
		'Ñ', 'Ń', 'Ň',
		'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ő',
		'Ŕ', 'Ř',
		'Ś', 'Ş', 'Š',
		'ñ', 'ń', 'ň',
		'ò', 'ó', 'ô', 'ö', 'ø', 'ő',
		'ŕ', 'ř',
		'ś', 'ş', 'š',
		'Ţ', 'Ť',
		'Ù', 'Ú', 'Û', 'Ų', 'Ü', 'Ů', 'Ű',
		'Ý', 'ß',
		'Ź', 'Ż', 'Ž',
		'ţ', 'ť',
		'ù', 'ú', 'û', 'ų', 'ü', 'ů', 'ű',
		'ý', 'ÿ',
		'ź', 'ż', 'ž',
		'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р',
		'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'р',
		'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я',
		'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я'
		];
					 
		$rempl = [
		'A', 'A', 'A', 'A', 'A', 'A', 'AE', 'A', 'A',
		'C', 'C', 'C', 'CE',
		'D', 'D',
		'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'a', 'a',
		'c', 'c', 'c', 'ce',
		'd', 'd',
		'E', 'E', 'E', 'E', 'E', 'E',
		'G',
		'I', 'I', 'I', 'I', 'I',
		'L', 'L', 'L',
		'e', 'e', 'e', 'e', 'e', 'e',
		'g',
		'i', 'i', 'i', 'i', 'i',
		'l', 'l', 'l',
		'N', 'N', 'N',
		'O', 'O', 'O', 'O', 'O', 'O', 'O',
		'R', 'R',
		'S', 'S', 'S',
		'n', 'n', 'n',
		'o', 'o', 'o', 'o', 'o', 'o',
		'r', 'r',
		's', 's', 's',
		'T', 'T',
		'U', 'U', 'U', 'U', 'U', 'U', 'U',
		'Y', 'Y',
		'Z', 'Z', 'Z',
		't', 't',
		'u', 'u', 'u', 'u', 'u', 'u', 'u',
		'y', 'y',
		'z', 'z', 'z',
		'A', 'B', 'B', 'r', 'A', 'E', 'E', 'X', '3', 'N', 'N', 'K', 'N', 'M', 'H', 'O', 'N', 'P',
		'a', 'b', 'b', 'r', 'a', 'e', 'e', 'x', '3', 'n', 'n', 'k', 'n', 'm', 'h', 'o', 'p',
		'C', 'T', 'Y', 'O', 'X', 'U', 'u', 'W', 'W', 'b', 'b', 'b', 'E', 'O', 'R',
		'c', 't', 'y', 'o', 'x', 'u', 'u', 'w', 'w', 'b', 'b', 'b', 'e', 'o', 'r'
		];
			 
		$nom_fichier = str_replace($cible, $rempl, $filename);// préserve le maximum de caractères utiles

		$nom_fichier = preg_replace('#[^.a-z0-9_-]+#i', '-', $nom_fichier);// uniquement alphanumérique et . et _ et -
		$nom_fichier = preg_replace('#-{2,}#','-',$nom_fichier);// supprime les occurences successives de '-'
		
		// Supprime le dernier "-" de remplacement excepté si ce caractère existait déjà à la fin du nom original
		$nom_fichier = mb_substr($filename, -1) != "-" ? rtrim($nom_fichier,'-') : $nom_fichier;
		
		return $dossier.$nom_fichier.$extension;
	}
	
	
	
	public function Shutdown()
	{
		$fatal_error = false;
		if ($error = error_get_last())
		{
			switch($error['type'])
			{
				case E_ERROR:
				case E_CORE_ERROR:
				case E_COMPILE_ERROR:
				case E_USER_ERROR:
				$fatal_error = true;
				break;
			}
		}
	
		if ($fatal_error)
		{
			$message = null;
			foreach($this->config_erreur_serveur as $key => $value)
			{
				if (strpos($error['message'],$key) !== false)
				{
					if(is_array($value))
					{
						$message = isset($value[0])? $value[0] : '';
						if(isset($value[1]) && trim($value[1]) != false)
						{
							setcookie($this->cookie_name,"",time()-3600,$this->cookie_path);
							@unlink($this->file_temp_address);
						}
					}
					else
					{
						$message = $value;
					}
				}
			}

			if(!isset($message) && $this->mode_debug)
			{
				$message = $error['message'];
			}
			
			exit($message);
		}
	}
}
?>