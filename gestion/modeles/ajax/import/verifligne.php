<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function liste($prem,$dern)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT DISTINCT idobs, idfiche, cdnom, idligne, stade, idetatbio, nb FROM obs.obs t1
						INNER JOIN obs.ligneobs USING(idobs)
						WHERE EXISTS (
							SELECT * FROM obs.obs t2
							WHERE t1.idobs <> t2.idobs
							AND   t1.idfiche = t2.idfiche
							AND   t1.cdnom = t2.cdnom )
						AND (idobs >= :prem AND idobs <= :dern)
						order by idfiche ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':prem', $prem);
	$req->bindValue(':dern', $dern);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function mod_ligneobs($idligne,$idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("UPDATE obs.ligneobs SET idobs = :idobs WHERE idligne = :idligne ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idligne', $idligne, PDO::PARAM_INT);
	$req->bindValue(':idobs', $idobs, PDO::PARAM_INT);
	$req->execute();
	$req->closeCursor();	
}
function modif_obs($idobs,$nbmod)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT nb FROM obs.obs WHERE idobs = :idobs ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idobs', $idobs);
	$req->execute();
	$nb = $req->fetchColumn();
	$req->closeCursor();
	$nbplus = $nb + $nbmod;
	$req = $bdd->prepare("UPDATE obs.obs SET nb = :nb WHERE idobs = :idobs ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':nb', $nbplus);
	$req->execute();
	$req->closeCursor();
}
function sup_obs($idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("DELETE FROM obs.obs WHERE idobs = :idobs ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idobs', $idobs, PDO::PARAM_INT);
	$req->execute();
	$req->closeCursor();	
}
function sup_ligneobs($idligne)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("DELETE FROM obs.ligneobs WHERE idligne = :idligne ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idligne', $idligne, PDO::PARAM_INT);
	$req->execute();
	$req->closeCursor();	
}
function verif()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT * FROM obs.obs
						WHERE NOT EXISTS (SELECT * FROM obs.ligneobs WHERE obs.idobs = ligneobs.idobs)") or die(print_r($bdd->errorInfo()));
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}

if(isset($_POST['prem']) && isset($_POST['dern']))
{
	$prem = $_POST['prem'];
	$dern = $_POST['dern'];
	$nb = 0;
	$liste = liste($prem,$dern);
	if(count($liste) > 0)
	{
		foreach($liste as $n)
		{
			$tabfiche[] = [$n['idfiche'],$n['cdnom']];
		}
		$tabtmp = array_map( 'serialize' , $tabfiche );
		$tabtmp = array_unique($tabtmp);
		$tabfiche = array_map( 'unserialize' , $tabtmp );
		
		foreach($tabfiche as $cle => $t)
		{
			foreach($liste as $cle1 => $l)
			{
				if($cle == $cle1)
				{
					$tab[] = ['idfiche' => $l['idfiche'], 'idobs' => $l['idobs'], 'cdnom' => $l['cdnom'], 'idligne' => $l['idligne'], 'stade' => $l['stade'], 'etat'=> $l['idetatbio'], 'nb'=> $l['nb']];
				}
			}
		}
		unset($tabfiche);
				
		foreach($liste as $n)
		{
			foreach($tab as $t)
			{
				if($n['idfiche'] == $t['idfiche'])
				{
					if($n['cdnom'] == $t['cdnom'])
					{
						if(($n['stade'] != $t['stade']) || ($n['idetatbio'] != $t['etat']))
						{
							$nb++;
							//$er[] = [$n['idfiche'],'anc'=>$n['idobs'].'/'.$n['idligne'],'nou'=>$t['idobs'].'/'.$n['idligne'],$n['cdnom'],'nb'=>$n['nb']];							
							modif_obs($t['idobs'],$n['nb']);
							mod_ligneobs($n['idligne'],$t['idobs']);
							sup_obs($n['idobs']);																	
						}								
					}	
				}
			}
		}
		unset($tab);
	}
	$liste = liste($prem,$dern);
	if(count($liste) > 0)
	{
		foreach($liste as $n)
		{
			$tabfiche[] = [$n['idfiche'],$n['cdnom']];
		}
		$tabtmp = array_map( 'serialize' , $tabfiche );
		$tabtmp = array_unique($tabtmp);
		$tabfiche = array_map( 'unserialize' , $tabtmp );
		
		foreach($tabfiche as $cle => $t)
		{
			foreach($liste as $cle1 => $l)
			{
				if($cle == $cle1)
				{
					sup_ligneobs($l['idligne']);
					sup_obs($l['idobs']);
				}
			}
		}				
	}
	/*$verif = verif();
	if(count($verif) > 0)
	{
		foreach($verif as $n)
		{
			sup_obs($n['idobs']);
		}	
	}	*/
	$retour['statut'] = 'Oui';
	$retour['nb'] = $nb;
}
else
{
	$retour['statut'] = 'Non';
}
echo json_encode($retour);