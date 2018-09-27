<?php 
session_start();
if(isset($_POST["idfiche"]))
{
	$idfiche = htmlspecialchars($_POST['idfiche']);
	$ordre = $_POST['ordre'];
	include '../../../global/configbase.php';
	include '../../../lib/pdo2.php';
	
	function liste_obsa($idfiche,$ordre)
	{
		$bdd = PDO2::getInstance();
		$bdd->query('SET NAMES "utf8"');
		if($ordre == 'A')
		{
			$req = $bdd->prepare("SELECT idobs, nom, nomvern, observatoire, nb FROM obs.obs 
								INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
								WHERE idfiche = :idfiche
								ORDER BY nom ") or die(print_r($bdd->errorInfo()));
		}
		else
		{
			$req = $bdd->prepare("SELECT idobs, nom, nomvern, observatoire, nb FROM obs.obs 
								INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
								WHERE idfiche = :idfiche
								ORDER BY nomvern ") or die(print_r($bdd->errorInfo()));
		}
		$req->bindValue(':idfiche', $idfiche);
		$req->execute();
		$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
		$req->closeCursor();
		return $resultat;		
	}
	function liste_obss($idfiche)
	{
		$bdd = PDO2::getInstance();
		$bdd->query('SET NAMES "utf8"');
		$req = $bdd->prepare("SELECT idobs, nom, nomvern, observatoire, nb FROM obs.obs 
							INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
							WHERE idfiche = :idfiche
							ORDER BY idobs ") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':idfiche', $idfiche);
		$req->execute();
		$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
		$req->closeCursor();
		return $resultat;		
	}
	$latin = (isset($_SESSION['latin'])) ? $_SESSION['latin'] : '';
	$liste = ($ordre == 'A' || $ordre == 'V') ? liste_obsa($idfiche,$ordre) : liste_obss($idfiche);
	$json = file_get_contents('../../../json/site.json');
	$rjson = json_decode($json, true);
	$listeobs = null;
	if($ordre == 'A' || $ordre == 'V')
	{
		$listeobs .= '<span class="small mr-2">Trié par ordre de saisie</span><span id="S'.$idfiche.'"><i class="fa fa-caret-down curseurlien obst"></i></span><br />';
	}
	else
	{
		$listeobs .= '<span class="small mr-2">Trié par nom latin</span><span id="A'.$idfiche.'"><i class="fa fa-caret-down curseurlien obst"></i></span><span class="small mr-2 ml-2">Trié par nom français</span><span id="V'.$idfiche.'"><i class="fa fa-caret-down curseurlien obst"></i></span><br />';
	}
	foreach($liste as $n)
	{
		if($latin == 'oui')
		{
			$listeobs .= '<li id="T'.$n['idobs'].'"><i class="fa fa-pencil curseurlien modobs" title="Modifier l\'observation"></i> <i class="fa fa-trash text-danger curseurlien suppobs" title="Supprimer la donnée"></i> '.$n['nb'].' <i>'.$n['nom'].'</i></li>';
		}
		else
		{
			foreach ($rjson['observatoire'] as $d)
			{
				if($d['nomvar'] == $n['observatoire'])
				{
					if($d['latin'] == 'oui' && ($latin == 'defaut' || $latin == ''))
					{
						$listeobs .= '<li id="T'.$n['idobs'].'"><i class="fa fa-pencil curseurlien modobs" title="Modifier l\'observation"></i> <i class="fa fa-trash text-danger curseurlien suppobs" title="Supprimer la donnée"></i> '.$n['nb'].' <i>'.$n['nom'].'</i></li>';
					}
					elseif($d['latin'] == 'non' || $latin == 'non') 
					{
						if($n['nomvern'] != '')
						{
							$listeobs .= '<li id="T'.$n['idobs'].'"><i class="fa fa-pencil curseurlien modobs" title="Modifier l\'observation"></i> <i class="fa fa-trash text-danger curseurlien suppobs" title="Supprimer la donnée"></i> '.$n['nb'].' <i title="'.$n['nom'].'">'.$n['nomvern'].'</i></li>';
						}
						else
						{
							$listeobs .= '<li id="T'.$n['idobs'].'"><i class="fa fa-pencil curseurlien modobs" title="Modifier l\'observation"></i> <i class="fa fa-trash text-danger curseurlien suppobs" title="Supprimer la donnée"></i> '.$n['nb'].' <i>'.$n['nom'].'</i></li>';
						}
					}
				}
			}
		}		
	}
	echo $listeobs;		
}