<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';	
function table($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT table_name FROM information_schema.tables WHERE table_schema='$nomvar' AND table_name='liste'") or die(print_r($bdd->errorInfo()));
	$table = $req->rowCount();
	$req->closeCursor();
	return $table;		
}
function tablesys($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT table_name FROM information_schema.tables WHERE table_schema='$nomvar' AND table_name='systematique'") or die(print_r($bdd->errorInfo()));
	$table = $req->rowCount();
	$req->closeCursor();
	return $table;		
}
function rechercher_rang($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT idrang, rang FROM $nomvar.rang ORDER BY idrang") or die(print_r($bdd->errorInfo()));
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}
function recherche_es($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT COUNT(cdnom) AS nb FROM $nomvar.liste WHERE cdnom = cdref AND rang = 'ES' ") or die(print_r($bdd->errorInfo()));
	$liste = $req->fetchColumn();
	$req->closeCursor();
	return $liste;		
}
function recherche_es1($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT COUNT(cdnom) AS nb FROM $nomvar.systematique WHERE rang = 'ES' AND (ordre = 0 OR ordre IS NULL) ")  or die(print_r($bdd->errorInfo()));
	$liste = $req->fetchColumn();
	$req->closeCursor();
	return $liste;		
}
function recherche_sbfm($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT COUNT(cdnom) AS nb FROM $nomvar.sousfamille ") or die(print_r($bdd->errorInfo()));
	$liste = $req->fetchColumn();
	$req->closeCursor();
	return $liste;		
}
function recherche_sbfm1($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT COUNT(cdnom) AS nb FROM $nomvar.systematique WHERE rang = 'SBFM' AND (ordre = 0 OR ordre IS NULL) ") or die(print_r($bdd->errorInfo()));
	$liste = $req->fetchColumn();
	$req->closeCursor();
	return $liste;		
}
function recherche_fm($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT COUNT(cdnom) AS nb FROM $nomvar.famille ") or die(print_r($bdd->errorInfo()));
	$liste = $req->fetchColumn();
	$req->closeCursor();
	return $liste;		
}
function recherche_fm1($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT COUNT(cdnom) AS nb FROM $nomvar.systematique WHERE rang = 'FM' AND (ordre = 0 OR ordre IS NULL) ") or die(print_r($bdd->errorInfo()));
	$liste = $req->fetchColumn();
	$req->closeCursor();
	return $liste;		
}
if (isset($_POST['sel']))
{
	$nomvar = ($_POST['sel']);
	$table = table($nomvar);
	if ($table > 0)	
	{
		$listerang = rechercher_rang($nomvar);
		$table = tablesys($nomvar);
		$ordreok = ($table > 0) ? 'oui' : 'non';
		$mes = null;
		$mes = '<ul>';
		foreach ($listerang as $n)
		{
			if ($n['idrang'] == 2)
			{
				$es = recherche_es($nomvar);
				if ($ordreok == 'oui')
				{
					$es1 = recherche_es1($nomvar);
					$mes .= '<li>Espèces : '.$es1.' ('.$es.' en tout)</li>';
				}
				else
				{
					$mes .= '<li>Espèces : '.$es.'</li>';
				}				
			}
			elseif ($n['idrang'] == 7)
			{
				$sbfm = recherche_sbfm($nomvar);
				if ($ordreok == 'oui')
				{
					$sbfm1 = recherche_sbfm1($nomvar);
					$mes .= '<li>Sous-famille : '.$sbfm1.' ('.$sbfm.' en tout)</li>';
				}
				else
				{
					$mes .= '<li>Sous-famille : '.$sbfm.'</li>';
				}
			}
			elseif ($n['idrang'] == 8)
			{
				$fm = recherche_fm($nomvar);
				if ($ordreok == 'oui')
				{
					$fm1 = recherche_fm1($nomvar);
					$mes .= '<li>Familles : '.$fm1.' ('.$fm.' en tout)</li>';
				}
				else
				{
					$mes .= '<li>Familles : '.$fm.'</li>';
				}				
			}
		}
		$mes .= '</ul>';
		if (isset($sbfm))
		{
			if ($ordreok == 'oui')
			{
				$taxon1 = $fm1 + $es1 + $sbfm1;
				$taxon = $fm + $es + $sbfm;
			}
			else
			{
				$taxon = $fm + $es + $sbfm;
			}
		}
		else
		{
			if ($ordreok == 'oui')
			{
				$taxon1 = $fm1 + $es1;
				$taxon = $fm + $es;
			}
			else
			{
				$taxon = $fm + $es;
			}
		}
		if ($ordreok == 'oui')
		{
			$ligne = 'Vous devez attribuer un numéro d\'ordre à '.$taxon1.' lignes ('.$taxon.' en tout)';
		}
		else
		{
			$ligne = 'Vous devez attribuer un numéro d\'ordre à '.$taxon.' lignes';
		}
		$json = file_get_contents('../../../../json/'.$nomvar.'.json');
		$rjson = json_decode($json, true);
		$gen1 = (isset($rjson['gen1'])) ? $rjson['gen1'] : '';
		$gen2 = (isset($rjson['gen2'])) ? $rjson['gen2'] : '';
		
		$retour['statut'] = 'Oui';		
		$retour['mes'] = '<div class="alert alert-warning" role="alert"><p>'.$ligne.'</p>'.$mes.'</div>';
		$retour['gen1'] = $gen1;
		$retour['gen2'] = $gen2;
	}
	else
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Vous devez au péalable choisir <a href="index.php?module=observatoire&amp;action=espece">la liste</a></p></div>';
	}	
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Aucun observatoire de choisit.</p></div>';
}
echo json_encode($retour);