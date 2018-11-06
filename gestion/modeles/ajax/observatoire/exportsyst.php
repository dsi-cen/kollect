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
function recherche_es($nomvar,$ordreok)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	if ($ordreok == 'non')
	{
		$req = $bdd->query("SELECT cdnom, nom, '' AS ordre, '' AS gen1, '' AS gen2, rang, locale FROM $nomvar.liste WHERE cdnom = cdref AND rang = 'ES' ") or die(print_r($bdd->errorInfo()));
	}
	else
	{
		$req = $bdd->query("SELECT liste.cdnom, nom, ordre, COALESCE(gen1, '') AS gen1, COALESCE(gen2, '') AS gen2, liste.rang, locale FROM $nomvar.liste
						LEFT JOIN $nomvar.systematique ON systematique.cdnom = liste.cdnom
						WHERE liste.cdnom = cdref AND liste.rang = 'ES' ") or die(print_r($bdd->errorInfo()));		
	}	
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}
function recherche_esfr($nomvar,$ordreok)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	if ($ordreok == 'non')
	{
		$req = $bdd->query("SELECT cdnom, nom, nomvern, '' AS ordre, '' AS gen1, '' AS gen2, rang, locale FROM $nomvar.liste WHERE cdnom = cdref AND rang = 'ES' ") or die(print_r($bdd->errorInfo()));
	}
	else
	{
		$req = $bdd->query("SELECT liste.cdnom, nom, nomvern, ordre, COALESCE(gen1, '') AS gen1, COALESCE(gen2, '') AS gen2, liste.rang, locale FROM $nomvar.liste
						LEFT JOIN $nomvar.systematique ON systematique.cdnom = liste.cdnom
						WHERE liste.cdnom = cdref AND liste.rang = 'ES' ") or die(print_r($bdd->errorInfo()));
	}
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}
function recherche_sbfm($nomvar,$ordreok)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	if ($ordreok == 'non')
	{
		$req = $bdd->query("SELECT cdnom, sousfamille AS nom, '' AS ordre, '' AS gen1, '' AS gen2, 'SBFM' AS rang, locale FROM $nomvar.sousfamille ") or die(print_r($bdd->errorInfo()));
	}	
	else
	{
		$req = $bdd->query("SELECT sousfamille.cdnom, sousfamille AS nom, ordre, COALESCE(gen1, '') AS gen1, COALESCE(gen2, '') AS gen2, 'SBFM' AS rang, locale FROM $nomvar.sousfamille
							LEFT JOIN $nomvar.systematique ON systematique.cdnom = sousfamille.cdnom ") or die(print_r($bdd->errorInfo()));
	}	
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}
function recherche_sbfmfr($nomvar,$ordreok)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	if ($ordreok == 'non')
	{
		$req = $bdd->query("SELECT cdnom, sousfamille AS nom, nomvern, '' AS ordre, '' AS gen1, '' AS gen2, 'SBFM' AS rang, locale FROM $nomvar.sousfamille ") or die(print_r($bdd->errorInfo()));
	}
	else
	{
		$req = $bdd->query("SELECT sousfamille.cdnom, sousfamille AS nom, nomvern, ordre, COALESCE(gen1, '') AS gen1, COALESCE(gen2, '') AS gen2, 'SBFM' AS rang, locale FROM $nomvar.sousfamille
							LEFT JOIN $nomvar.systematique ON systematique.cdnom = sousfamille.cdnom ") or die(print_r($bdd->errorInfo()));
	}	
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}
function recherche_fm($nomvar,$ordreok)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	if ($ordreok == 'non')
	{
		$req = $bdd->query("SELECT cdnom, famille AS nom, '' AS ordre, '' AS gen1, '' AS gen2, 'FM' AS rang, locale FROM $nomvar.famille ") or die(print_r($bdd->errorInfo()));
	}
	else
	{
		$req = $bdd->query("SELECT famille.cdnom, famille AS nom, systematique.ordre, COALESCE(gen1, '') AS gen1, COALESCE(gen2, '') AS gen2, 'FM' AS rang, locale FROM $nomvar.famille
							LEFT JOIN $nomvar.systematique ON systematique.cdnom = famille.cdnom  ") or die(print_r($bdd->errorInfo()));
	}
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}
function recherche_fmfr($nomvar,$ordreok)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	if ($ordreok == 'non')
	{
		$req = $bdd->query("SELECT cdnom, famille AS nom, nomvern, '' AS ordre, '' AS gen1, '' AS gen2, 'FM' AS rang, locale FROM $nomvar.famille ") or die(print_r($bdd->errorInfo()));
	}
	else
	{
		$req = $bdd->query("SELECT famille.cdnom, famille AS nom, nomvern, systematique.ordre, COALESCE(gen1, '') AS gen1, COALESCE(gen2, '') AS gen2, 'FM' AS rang, locale FROM $nomvar.famille
							LEFT JOIN $nomvar.systematique ON systematique.cdnom = famille.cdnom  ") or die(print_r($bdd->errorInfo()));
	}
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}

if (isset($_POST['sel']))
{
	$nomvar = $_POST['sel'];
	$nomfr = $_POST['nomfr'];
	$table = table($nomvar);
	if ($table > 0)	
	{
		$listerang = rechercher_rang($nomvar);
		$table = tablesys($nomvar);
		$ordreok = ($table > 0) ? 'oui' : 'non';
		if ($nomfr == 'non')
		{		
			foreach ($listerang as $n)
			{
				if ($n['idrang'] == 2)
				{
					$es = recherche_es($nomvar,$ordreok);
				}
				elseif ($n['idrang'] == 7)
				{
					$sbfm = recherche_sbfm($nomvar,$ordreok);
				}
				elseif ($n['idrang'] == 8)
				{
					$fm = recherche_fm($nomvar,$ordreok);
				}
			}
			if (isset($sbfm))
			{
				$taxon = array_merge($fm, $sbfm, $es);
			}
			else
			{
				$taxon = array_merge($fm, $es);
			}
			if ($ordreok == 'non')
			{
				foreach ($taxon as $key => $row) 
				{
					$nom[$key]  = $row['nom'];
				}		
				array_multisort($nom, SORT_ASC, $taxon);
			}
			elseif ($ordreok == 'oui')
			{
				foreach ($taxon as $key => $row) 
				{
					$nom[$key]  = $row['nom'];
					$ordre[$key]  = $row['ordre'];
				}		
				array_multisort($ordre, SORT_ASC, $nom, SORT_ASC, $taxon);
			}
		}
		else
		{
			foreach ($listerang as $n)
			{
				if ($n['idrang'] == 2)
				{
					$es = recherche_esfr($nomvar,$ordreok);
				}
				elseif ($n['idrang'] == 7)
				{
					$sbfm = recherche_sbfmfr($nomvar,$ordreok);
				}
				elseif ($n['idrang'] == 8)
				{
					$fm = recherche_fmfr($nomvar,$ordreok);
				}
			}
			if (isset($sbfm))
			{
				$taxon = array_merge($fm, $sbfm, $es);
			}
			else
			{
				$taxon = array_merge($fm, $es);
			}
			if ($ordreok == 'non')
			{
				foreach ($taxon as $key => $row) 
				{
					$nom[$key]  = $row['nom'];
				}		
				array_multisort($nom, SORT_ASC, $taxon);
			}
			elseif ($ordreok == 'oui')
			{
				foreach ($taxon as $key => $row) 
				{
					$nom[$key]  = $row['nom'];
					$ordre[$key]  = $row['ordre'];
				}		
				array_multisort($ordre, SORT_ASC, $nom, SORT_ASC, $taxon);
			}			
		}
		$retour['statut'] = 'Oui';
		$retour['taxon'] = $taxon;
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