<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';	

function info($cdnom,$observa)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT cdsup, cdtaxsup, genre, famille FROM $observa.liste WHERE cdnom = :cdnom AND rang = 'ES' ");
	$req->bindValue(':cdnom', $cdnom);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function rmax_cdnom()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT max(cdnom) AS max FROM referentiel.liste WHERE cdnom >= 1000000 ");
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function insere_liste($observa,$cdnom,$cdsup,$cdtaxsup,$nom,$genre,$famille)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO $observa.liste (cdnom, cdref, cdsup, cdtaxsup, nom, genre, rang, famille, locale)
						VALUES(:cdnom, :cdref, :cdsup, :cdtaxsup, :nom, :genre, :rang, :famille, :locale) ");
	$req->bindValue(':cdnom', $cdnom);
	$req->bindValue(':cdref', $cdnom);
	$req->bindValue(':cdsup', $cdsup);
	$req->bindValue(':cdtaxsup', $cdtaxsup);
	$req->bindValue(':nom', $nom);
	$req->bindValue(':genre', $genre);
	$req->bindValue(':rang', 'COM');
	$req->bindValue(':famille', $famille);
	$req->bindValue(':locale', 'oui');	
	$req->execute();
	$req->closeCursor();
}
function insere_lister($observa,$cdnom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO referentiel.liste (cdnom,nom,auteur,nomvern,observatoire,rang,vali)
						SELECT cdnom, nom, auteur, nomvern, '$observa' AS observatoire, rang, 0 AS vali FROM $observa.liste
						WHERE cdref = :cdref AND cdref = cdnom AND rang = 'COM' ");
	$req->bindValue(':cdref', $cdnom);
	$req->execute();
	$req->closeCursor();
}
function insere_similaire($cdnom,$simi,$com)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO referentiel.similaire (cdnom,simi,com) VALUES(:cdnom, :simi, :com) ");
	$req->bindValue(':cdnom', $cdnom);					
	$req->bindValue(':simi', $simi);
	$req->bindValue(':com', $com);
	$req->execute();
	$req->closeCursor();
}

if(isset($_POST['cdnom']))
{
	$cdnom = $_POST['cdnom'];
	$nom = $_POST['nom'];
	$observa = $_POST['observa'];
	
	$tabcdnom = explode(",", $cdnom);
	$nbtabcdnom = count($tabcdnom);
	if($nbtabcdnom > 1)
	{
		$info = info($tabcdnom[0],$observa);
		if($info != false)
		{
			$maxcdnom = rmax_cdnom();
			$cdnomcom = (!empty($maxcdnom['max'])) ? $maxcdnom['max'] + 1 : 1000000;
			
			insere_liste($observa,$cdnomcom,$info['cdsup'],$info['cdtaxsup'],$nom,$info['genre'],$info['famille']);
			insere_lister($observa,$cdnomcom);
			
			for($i = 0; $i <= $nbtabcdnom - 1; $i++)
			{
				$tmp = $tabcdnom[$i];
				foreach($tabcdnom as $n)
				{
					if($n != $tmp)
					{
						insere_similaire($tmp,$n,$cdnomcom);
					}
				}				 
			}			
			$retour['statut'] = 'Oui';
			$retour['cdnom'] = $cdnomcom;
		}
		else
		{
			$retour['statut'] = 'Non';
		}	
	}
	else
	{
		$retour['statut'] = 'Non';
	}
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert">Aucun observatoire de choisit.</div>';
}
echo json_encode($retour);