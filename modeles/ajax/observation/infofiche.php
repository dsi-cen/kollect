<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
session_start();

function recherche_obs($idfiche)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT liste.nom, nomvern, rang, nb, observa, auteur, obs.cdref FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE idfiche = :idfiche AND observa != 'aucun'
						ORDER BY observa, liste.nom ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idfiche', $idfiche);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_membre($idfiche)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idm FROM obs.fiche
						INNER JOIN referentiel.observateur USING(idobser)
						WHERE idfiche = :idfiche ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idfiche', $idfiche);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_observa($idfiche)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT COUNT(DISTINCT cdref) AS nb, observa FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE idfiche = :idfiche AND observa != 'aucun'
						GROUP BY observa ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idfiche', $idfiche);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}

if (isset($_POST['idfiche']))
{
	$idfiche = htmlspecialchars($_POST['idfiche']);
	$latin = (isset($_SESSION['latin'])) ? $_SESSION['latin'] : '';
	$json_site = file_get_contents('../../../json/site.json');
	$rjson_site = json_decode($json_site, true);
	
	$fiche = recherche_obs($idfiche);
	if(count($fiche) > 1)
	{
		$observa = recherche_observa($idfiche);
		foreach($observa as $n)
		{
			$tabobserva[$n['observa']] = $n['nb'];
		}
		
		$idm = recherche_membre($idfiche);
		if(isset($_SESSION['idmembre']) && $idm['idm'] == $_SESSION['idmembre'])
		{
			$retour['mod'] = 'oui';
		}		
		foreach($fiche as $n)
		{
			foreach($rjson_site['observatoire'] as $d)
			{
				if($d['nomvar'] == $n['observa'])
				{
					$nobserva = (isset($tabobserva[$n['observa']])) ? $tabobserva[$n['observa']] : '';
						
					$sel[] = ['nom'=>$d['nom'], 'nomvar'=>$d['nomvar'], 'nb'=>$nobserva];
										
					if($d['latin'] == 'oui' && $latin == 'oui')
					{
						$tabobs[] = array('latin'=>'oui', 'nomlat'=>$n['nom'], 'nomfr'=>$n['nomvern'], 'nb'=>$n['nb'], 'nomvar'=>$d['nomvar'], 'cdnom'=>$n['cdref'], 'auteur'=>$n['auteur'], 'rang'=>$n['rang']);
					}
					elseif($d['latin'] == 'oui' && ($latin == 'defaut' || $latin == ''))
					{
						$tabobs[] = array('latin'=>'oui', 'nomlat'=>$n['nom'], 'nomfr'=>$n['nomvern'], 'nb'=>$n['nb'], 'nomvar'=>$d['nomvar'], 'cdnom'=>$n['cdref'], 'auteur'=>$n['auteur'], 'rang'=>$n['rang']);
					}
					elseif($d['latin'] == 'non' && $latin == 'oui')
					{
						$tabobs[] = array('latin'=>'oui', 'nomlat'=>$n['nom'], 'nomfr'=>$n['nomvern'], 'nb'=>$n['nb'], 'nomvar'=>$d['nomvar'], 'cdnom'=>$n['cdref'], 'auteur'=>$n['auteur'], 'rang'=>$n['rang']);
					}
					elseif($d['latin'] == 'non' || $latin == 'non') 
					{
						$tabobs[] = array('latin'=>'non', 'nomlat'=>$n['nom'], 'nomfr'=>$n['nomvern'], 'nb'=>$n['nb'], 'nomvar'=>$d['nomvar'], 'cdnom'=>$n['cdref'], 'auteur'=>$n['auteur'], 'rang'=>$n['rang']);
					}			
				}			
			}
		}
		$tabtmp = array_map( 'serialize' , $sel );
		$tabtmp = array_unique( $tabtmp );
		$sel = array_map( 'unserialize' , $tabtmp );
		
		$liste = null;
		foreach($sel as $s)
		{
			$liste .= '<h6>'.$s['nom'].' ('.$s['nb'].')</h6>';
			$liste .= '<ul>';
			foreach($tabobs as $n)
			{
				if($n['nomvar'] == $s['nomvar'])
				{
					if ($n['latin'] == 'oui')
					{	
						$liste .= ($n['rang'] != 'GN') ? '<li><a href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d='.$n['nomvar'].'&amp;id='.$n['cdnom'].'"><i>'.$n['nomlat'].' '.$n['auteur'].'</i></a></li>' : '<li><a href="observatoire/index.php?module=fiche&amp;action=ficheg&amp;d='.$n['nomvar'].'&amp;id='.$n['cdnom'].'"><i>'.$n['nomlat'].' sp. '.$n['auteur'].'</i></a></li>';
					}
					else
					{
						if($n['nomfr'] != '')
						{
							$liste .= '<li><a href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d='.$n['nomvar'].'&amp;id='.$n['cdnom'].'" title="'.$n['nomlat'].'">'.$n['nomfr'].'</a></li>';
						}
						else
						{
							$liste .= ($n['rang'] != 'GN') ? '<li><a href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d='.$n['nomvar'].'&amp;id='.$n['cdnom'].'"><i>'.$n['nomlat'].' '.$n['auteur'].'</i></a></li>' : '<li><a href="observatoire/index.php?module=fiche&amp;action=ficheg&amp;d='.$n['nomvar'].'&amp;id='.$n['cdnom'].'"><i>'.$n['nomlat'].' sp. '.$n['auteur'].'</i></a></li>';
						}											
					}
				}
			}
			$liste .= '</ul>';
		}
		$url = 'http://'.dirname($_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
		$urlval = str_replace('/modeles/ajax/observation', '/index.php?module=observation&action=fiche&idfiche='.$idfiche.'', $url);
		$retour['lien'] = $urlval;
		$retour['liste'] = $liste;
		$retour['statut'] = 'Oui';
	}
	else
	{
		$retour['statut'] = 'Non';
	}
	echo json_encode($retour);
}

	