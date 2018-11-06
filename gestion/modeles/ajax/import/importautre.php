<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function recupid($condi)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	if(!empty($condi))
	{
		$req = $bdd->query("SELECT DISTINCT idobs, idorigine FROM obs.identif WHERE $condi ") or die(print_r($bdd->errorInfo()));
	}
	else
	{
		$req = $bdd->query("SELECT DISTINCT idobs, idorigine FROM obs.identif WHERE idorigine IS NOT NULL ") or die(print_r($bdd->errorInfo()));
	}
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function insere_plte($tab)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO obs.obsplte (idobs,nb,cdnom,stade) VALUES(:idobs, :nb, :cdnom, :idstade) ") or die(print_r($bdd->errorInfo()));
	foreach($tab as $n)
	{
		$req->execute(array('idobs'=>$n['idobs'],'nb'=>$n['nb'],'cdnom'=>$n['cdnom'],'idstade'=>$n['idstade']));
	}	
}
function insere_coll($tab)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO obs.obscoll (idobs,iddetcol,iddetgen,codegen,sexe,idprep,typedet,stade) VALUES(:idobs, :iddetcol, :iddetgen, :code, :sexe, :idprep, :typedet, :idstade) ") or die(print_r($bdd->errorInfo()));
	foreach($tab as $n)
	{
		$req->execute(array('idobs'=>$n['idobs'],'iddetcol'=>$n['iddetcol'],'iddetgen'=>$n['iddetgen'],'code'=>$n['code'],'sexe'=>$n['sexe'],'idprep'=>$n['idprep'],'typedet'=>$n['typedet'],'idstade'=>$n['idstade']));
	}	
}
function insere_hab($tab)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO obs.obshab (idobs,cdhab,cdnom) VALUES(:idobs, :cdhab, :cdnom) ") or die(print_r($bdd->errorInfo()));
	foreach($tab as $n)
	{
		$req->execute(array('idobs'=>$n['idobs'],'cdhab'=>$n['cdhab'],'cdnom'=>$n['cdnom']));
	}	
}
function insere_mort($tab)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO obs.obsmort (idobs,mort,stade) VALUES(:idobs, :idmort, :idstade) ") or die(print_r($bdd->errorInfo()));
	foreach($tab as $n)
	{
		$req->execute(array('idobs'=>$n['idobs'],'idmort'=>$n['idmort'],'idstade'=>$n['idstade']));
	}	
}
function insere_piaf($tab)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO obs.aves (idobs,code,stade) VALUES(:idobs, :code, :idstade) ") or die(print_r($bdd->errorInfo()));
	foreach($tab as $n)
	{
		$req->execute(array('idobs'=>$n['idobs'],'code'=>$n['code'],'idstade'=>$n['idstade']));
	}
}

if(isset($_POST['fichier'])) 
{
	$fichier = $_POST['fichier'];
	$premiere = $_POST['nbdeb'];
	$nbtraitement = 500;
	$meser = $_POST['meser'];
	$nber = $_POST['nber'];
	
	if(($liste = fopen("../../../tmp/".$fichier, "r")) !== FALSE) 
	{
		fseek($liste, $premiere);
		$nbtrait = 0;
		$nbligne = $_POST['nbligne'];
		$i = 0;
		
		$condi = $_POST['condi'];
		$listeid = recupid($condi);
		foreach($listeid as $n)
		{
			$tabid[] = $n['idorigine'];
		}
		$tabid = array_flip($tabid);
				
		while(($data = fgetcsv($liste, 1000, ";")) !== FALSE) 
		{
			if($i++ < $nbtraitement)
			{
				$nbligne++; $nbtrait++;
						
				if(isset($tabid[$data[0]]))
				{
					$idobs = $listeid[$tabid[$data[0]]]['idobs'];
					
					if($_POST['choix'] == 'plte')
					{
						$nb = (!preg_match('#[^0-9]#', $data[1]) && !empty($data[1])) ? $data[1] : null;
						$tabplte[] = ['idobs'=>$idobs,'nb'=>$nb,'cdnom'=>$data[2],'idstade'=>$data[3]];
					}
					elseif($_POST['choix'] == 'coll')
					{
						$iddetcol = (!preg_match('#[^0-9]#', $data[1]) && !empty($data[1])) ? $data[1] : null;
						$iddetgen = (!preg_match('#[^0-9]#', $data[2]) && !empty($data[2])) ? $data[2] : null;
						$idprep = (!preg_match('#[^0-9]#', $data[5]) && !empty($data[5])) ? $data[5] : null;
						$tabcol[] = ['idobs'=>$idobs,'iddetcol'=>$iddetcol,'iddetgen'=>$iddetgen,'code'=>$data[3],'sexe'=>$data[4],'idprep'=>$idprep,'typedet'=>$data[6],'idstade'=>$data[7]];
					}
					elseif($_POST['choix'] == 'hab')
					{
						$tabhab[] = ['idobs'=>$idobs,'cdhab'=>$data[1],'cdnom'=>$data[2]];
					}
					elseif($_POST['choix'] == 'mort')
					{
						$tabmort[] = ['idobs'=>$idobs,'idmort'=>$data[1],'idstade'=>$data[2]];
					}
					elseif($_POST['choix'] == 'piaf')
					{
						$tabpiaf[] = ['idobs'=>$idobs,'code'=>$data[1],'idstade'=>$data[2]];
					}			
				}
				else
				{
					$taber[] = $nbligne;
				}						
				$derniere = ftell($liste);
			}			
			unset($data);			
		}		
		fclose($liste);
		unset($tabid);
		unset($listeid);
		
		if(isset($tabplte)) { insere_plte($tabplte); }
		elseif(isset($tabcol)) { insere_coll($tabcol); }
		elseif(isset($tabhab)) { insere_hab($tabhab); }
		elseif(isset($tabmort)) { insere_mort($tabmort); }	
		elseif(isset($tabpiaf)) { insere_piaf($tabpiaf); }
		
		if(isset($taber))
		{
			$nberencours = count($taber);
			$listeligne = implode(", ", $taber);
			$retour['meser'] = ($meser != '') ? $meser.', '.$listeligne : $listeligne;
			$retour['nber'] = $nber + $nberencours;
		}
		else
		{
			$retour['meser'] = $meser;
			$retour['nber'] = $nber;
		}
					
		$retour['statut'] = 'Oui';
		$retour['nb'] = $nbtrait;
		$retour['nbligne'] = $nbligne;
		$retour['derniere'] = $derniere;
	}	
	else
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Aucun fichier.</p></div>';
	}
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Aucun fichier.</p></div>';
}
echo json_encode($retour);