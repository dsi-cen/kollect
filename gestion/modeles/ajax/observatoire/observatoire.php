<?php
function classe($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT DISTINCT classe FROM $nomvar.famille") or die(print_r($bdd->errorInfo()));
	$classe = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $classe;
}
function liste_stade()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT idstade, stade FROM referentiel.stade ORDER BY stade") or die(print_r($bdd->errorInfo()));
	$stade = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $stade;
}
function liste_methode()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT idmethode, methode FROM referentiel.methode ORDER BY methode") or die(print_r($bdd->errorInfo()));
	$methode = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();	
	return $methode;
}
function liste_collecte()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT idpros, prospection FROM referentiel.prospection ORDER BY prospection") or die(print_r($bdd->errorInfo()));
	$collecte = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();	
	return $collecte;
}
function liste_statutbio()
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->query("SELECT idstbio, statutbio, libelle FROM referentiel.occstatutbio ORDER BY statutbio") or die(print_r($bdd->errorInfo()));
    $statutbio = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $statutbio;
}
function liste_comportement()
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->query("SELECT idcomp, libcomp, mdcomp FROM referentiel.comportement ORDER BY idcomp") or die(print_r($bdd->errorInfo()));
    $comportement = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $comportement;
}
function liste_protocole()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT idprotocole, protocole FROM referentiel.protocole ORDER BY protocole ") or die(print_r($bdd->errorInfo()));
	$protocole = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();	
	return $protocole;
}
function liste_mort()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT idmort, cause FROM referentiel.occmort ORDER BY cause ") or die(print_r($bdd->errorInfo()));
	$protocole = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();	
	return $protocole;
}
if(isset($_POST['sel']))
{
	include '../../../../global/configbase.php';
	include '../../../../lib/pdo2.php';	
	
	$nomvar = $_POST['sel'];
	
	$classe = classe($nomvar);
	$retour['classe'] = $classe['classe'];	
	
	$json = file_get_contents('../../../../json/'.$nomvar.'.json');
	$rjson = json_decode($json, true);
		
	$stade = liste_stade();
	if(isset($rjson['saisie']['stade']))
	{
		foreach($rjson['saisie']['stade'] as $cle => $n)
		{
			$tabstade[] = array('label'=>$cle,'value'=>$n,'selected'=>true);
			$tabstadeid[] = $n;			
		}	
		foreach($stade as $n)
		{
			if(!in_array($n['idstade'], $tabstadeid))
			{
				$tabstade[] = array('label'=>$n['stade'],'value'=>$n['idstade']);
			}		
		}
		$retour['stade'] = $tabstade;
	}
	else
	{
		foreach($stade as $n)
		{
			$tabstade[] = array('label'=>$n['stade'],'value'=>$n['idstade']);
		}
		$retour['stade'] = $tabstade;		
	}
	
	$methode = liste_methode();
	if(isset($rjson['saisie']['methode']) && !isset($rjson['saisie']['methode']['']))
	{
		foreach($rjson['saisie']['methode'] as $cle => $n)
		{
			$tabmethode[] = array('label'=>$cle,'value'=>$n,'selected'=>true);
			$tabmethodeid[] = $n;			
		}	
		foreach($methode as $n)
		{
			if(!in_array($n['idmethode'], $tabmethodeid))
			{
				$tabmethode[] = array('label'=>$n['methode'],'value'=>$n['idmethode']);
			}		
		}
		$retour['methode'] = $tabmethode;
	}
	else
	{
		foreach($methode as $n)
		{
			$tabmethode[] = array('label'=>$n['methode'],'value'=>$n['idmethode']);
		}
		$retour['methode'] = $tabmethode;
	}
	
	$collecte = liste_collecte();
	if(isset($rjson['saisie']['collecte']) && !isset($rjson['saisie']['collecte']['']))
	{
		foreach($rjson['saisie']['collecte'] as $cle => $n)
		{
			$tabcollecte[] = array('label'=>$cle,'value'=>$n,'selected'=>true);
			$tabcollecteid[] = $n;			
		}	
		foreach($collecte as $n)
		{
			if(!in_array($n['idpros'], $tabcollecteid))
			{
				$tabcollecte[] = array('label'=>$n['prospection'],'value'=>$n['idpros']);
			}		
		}
		$retour['collecte'] = $tabcollecte;
	}
	else
	{
		foreach($collecte as $n)
		{
			$tabcollecte[] = array('label'=>$n['prospection'],'value'=>$n['idpros']);
		}
		$retour['collecte'] = $tabcollecte;
	}
	
	$statutbio = liste_statutbio();
	if(isset($rjson['saisie']['statutbio']) && !isset($rjson['saisie']['statutbio']['']))
	{
		foreach($rjson['saisie']['statutbio'] as $cle => $n)
		{
			$tabstbio[] = array('label'=>$cle,'value'=>$n,'selected'=>true);
			$tabstbioid[] = $n;			
		}	
		foreach($statutbio as $n)
		{
			if(!in_array($n['idstbio'], $tabstbioid))
			{
				$tabstbio[] = array('label'=>$n['statutbio'],'value'=>$n['idstbio']);
			}		
		}
		$retour['statutbio'] = $tabstbio;
	}
	else
	{
		foreach($statutbio as $n)
		{
			$tabstbio[] = array('label'=>$n['statutbio'],'value'=>$n['idstbio']);
		}
		$retour['statutbio'] = $tabstbio;
	}

    $comportement = liste_comportement();
    if(isset($rjson['saisie']['comportement']) && !isset($rjson['saisie']['comportement']['']))
    {
        foreach($rjson['saisie']['comportement'] as $cle => $n)
        {
            $tabcomp[] = array('label'=>$cle,'value'=>$n,'selected'=>true);
            $tabcompid[] = $n;
        }
        foreach($comportement as $n)
        {
            if(!in_array($n['idcomp'], $tabcompid))
            {
                $tabcomp[] = array('label'=>$n['libcomp'],'value'=>$n['idcomp']);
            }
        }
        $retour['comportement'] = $tabcomp;
    }
    else
    {
        foreach($comportement as $n)
        {
            $tabcomp[] = array('label'=>$n['libcomp'],'value'=>$n['idcomp']);
        }
        $retour['comportement'] = $tabcomp;
    }

    $mort = liste_mort();
	if(isset($rjson['saisie']['mort']) && !isset($rjson['saisie']['mort']['']))
	{
		foreach($rjson['saisie']['mort'] as $cle => $n)
		{
			$tabmort[] = array('label'=>$cle,'value'=>$n,'selected'=>true);
			$tabmortid[] = $n;			
		}	
		foreach($mort as $n)
		{
			if(!in_array($n['idmort'], $tabmortid))
			{
				$tabmort[] = array('label'=>$n['cause'],'value'=>$n['idmort']);
			}		
		}
		$retour['mort'] = $tabmort;
	}
	else
	{
		foreach($mort as $n)
		{
			$tabmort[] = array('label'=>$n['cause'],'value'=>$n['idmort']);
		}
		$retour['mort'] = $tabmort;
	}
	
	$protocole = liste_protocole();
	if(isset($rjson['saisie']['protocole']) && !isset($rjson['saisie']['protocole']['']))
	{
		foreach($rjson['saisie']['protocole'] as $cle => $n)
		{
			$tabproto[] = array('label'=>$cle,'value'=>$n,'selected'=>true);
			$tabprotoid[] = $n;			
		}	
		foreach($protocole as $n)
		{
			if(!in_array($n['idprotocole'], $tabprotoid))
			{
				$tabproto[] = array('label'=>$n['protocole'],'value'=>$n['idprotocole']);
			}		
		}
		$retour['protocole'] = $tabproto;
	}
	else
	{
		foreach($protocole as $n)
		{
			$tabproto[] = array('label'=>$n['protocole'],'value'=>$n['idprotocole']);
		}
		$retour['protocole'] = $tabproto;
	}
	$denom = [['id'=>'COL','label'=>'Colonie'],['id'=>'CPL','label'=>'Couple'],['id'=>'HAM','label'=>'Hampe florale'],['id'=>'IND','label'=>'Nombre d\'individus observés'],['id'=>'NID','label'=>'Nombre de nids observés'],['id'=>'NSP','label'=>'Inconnu'],['id'=>'PON','label'=>'Nombre de pontes observées'],['id'=>'SURF','label'=>'Zone occupée, en mètres carrés'],['id'=>'TIGE','label'=>'Nombre de tiges observées'],['id'=>'TOUF','label'=>'Nombre de touffes observées']];
	if(isset($rjson['saisie']['denom']) && !isset($rjson['saisie']['denom']['']))
	{
		foreach($rjson['saisie']['denom'] as $cle => $n)
		{
			$tabdenom[] = array('label'=>$cle,'value'=>$n,'selected'=>true);
			$tabdenomid[] = $n;			
		}
		foreach($denom as $n)
		{
			if(!in_array($n['id'], $tabdenomid))
			{
				$tabdenom[] = array('label'=>$n['label'],'value'=>$n['id']);
			}		
		}
		$retour['denom'] = $tabdenom;
	}
	else
	{
		foreach($denom as $n)
		{
			$tabdenom[] = array('label'=>$n['label'],'value'=>$n['id']);
		}
		$retour['denom'] = $tabdenom;
	}
	
	$retour['stbio'] = (isset($rjson['saisie']['stbio'])) ? $rjson['saisie']['stbio'] : 'vivant'; 	
	$retour['aves'] = (isset($rjson['saisie']['aves'])) ? $rjson['saisie']['aves'] : '';
	$retour['plteh'] = (isset($rjson['saisie']['plteh'])) ? $rjson['saisie']['plteh'] : '';
	$retour['listebota'] = (isset($rjson['saisie']['listebota'])) ? $rjson['saisie']['listebota'] : '';
	$retour['locale'] = (isset($rjson['saisie']['locale'])) ? $rjson['saisie']['locale'] : '';	
	$retour['mf'] = (isset($rjson['saisie']['mf'])) ? $rjson['saisie']['mf'] : '';	
	$retour['collection'] = (isset($rjson['saisie']['col'])) ? $rjson['saisie']['col'] : '';
	$retour['statut'] = 'Oui';
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert">Aucun observatoire de choisit.</div>';
}
echo json_encode($retour);