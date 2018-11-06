<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function cdref($cdnom,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	if($nomvar != 'aucun')
	{
		$req = $bdd->prepare("SELECT cdref FROM $nomvar.liste WHERE cdnom = :cdnom ") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':cdnom', $cdnom, PDO::PARAM_INT);
	}
	else
	{
		$req = $bdd->prepare("SELECT cdref FROM referentiel.taxref WHERE cdnom = :cdnom ") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':cdnom', $cdnom, PDO::PARAM_INT);
	}
	$req->execute();
	$cdref = $req->fetchColumn();
	$req->closeCursor();
	return $cdref;	
}
function determinateur($idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idobser FROM import.impobser WHERE idobseror = :idobser ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idobser', $idobser, PDO::PARAM_INT);
	$req->execute();
	$iddet = $req->fetchColumn();
	$req->closeCursor();
	$iddetok = ($iddet != '') ? $iddet : null;
	return $iddetok;	
}
function idfiche($idficheor)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idfiche FROM import.fiche WHERE idor = :idficheor ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idficheor', $idficheor);
	$req->execute();
	$idfiche = $req->fetchColumn();
	$req->closeCursor();
	return $idfiche;
}
function insere_obs($idor,$idfiche,$cdnom,$cdref,$iddet,$nbtotal,$dates,$nomvar,$rq,$statutobs,$idproto,$idetude,$idstade,$denom,$bio,$methode,$coll,$stbio,$vali,$ndiff,$m,$f,$nbmin,$nbmax,$sexe,$tdenom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO obs.obs (idfiche, cdnom, cdref, iddet, nb, rqobs, validation, datesaisie, observa, statutobs, idprotocole, idetude)
						VALUES(:idfiche, :cdnom, :cdref, :iddet, :nb, :rq, :vali, :datesaisie, :var, :statut, :idproto, :idetude) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idfiche', $idfiche, PDO::PARAM_INT);
	$req->bindValue(':cdnom', $cdnom, PDO::PARAM_INT);
	$req->bindValue(':cdref', $cdref, PDO::PARAM_INT);
	$req->bindValue(':iddet', $iddet);
	$req->bindValue(':nb', $nbtotal);
	$req->bindValue(':rq', $rq);
	$req->bindValue(':vali', $vali, PDO::PARAM_INT);
	$req->bindValue(':datesaisie', $dates);
	$req->bindValue(':var', $nomvar);
	$req->bindValue(':statut', $statutobs);
	$req->bindValue(':idproto', $idproto, PDO::PARAM_INT);
	$req->bindValue(':idetude', $idetude, PDO::PARAM_INT);
	if($req->execute())
	{
		$idobs = $bdd->lastInsertId('obs.obs_idobs_seq');
	}
	$req->closeCursor();
	if(isset($idobs))
	{
		if($statutobs == 'Pr')
		{
			$req = $bdd->prepare("INSERT INTO obs.ligneobs (idobs, stade, ndiff, male, femelle, denom, idetatbio, idmethode, idpros, idstbio, nbmin, nbmax, sexe, tdenom)
								VALUES(:idobs, :stade, :ndiff, :m, :f, :denom, :etat, :meth, :pros, :bio, :nbmin, :nbmax, :sexe, :tdenom) ") or die(print_r($bdd->errorInfo()));
			$req->bindValue(':idobs', $idobs);
			$req->bindValue(':stade', $idstade, PDO::PARAM_INT);
			$req->bindValue(':ndiff', $ndiff);
			$req->bindValue(':m', $m);
			$req->bindValue(':f', $f);
			$req->bindValue(':denom', $denom);
			$req->bindValue(':etat', $bio);
			$req->bindValue(':meth', $methode);
			$req->bindValue(':pros', $coll);
			$req->bindValue(':bio', $stbio);
			$req->bindValue(':nbmin', $nbmin);
			$req->bindValue(':nbmax', $nbmax);
			$req->bindValue(':sexe', $sexe);
			$req->bindValue(':tdenom', $tdenom);
			if($req->execute())
			{
				$idligneobs = $bdd->lastInsertId('obs.ligneobs_idligne_seq');
			}
			$req->closeCursor();
			if(isset($idligneobs))
			{
				$req = $bdd->prepare("INSERT INTO obs.identif (idligne, idobs, idfiche, idorigine, dates) VALUES(:idligne, :idobs, :idfiche, :idor, :dates) ") or die(print_r($bdd->errorInfo()));
				$req->bindValue(':idobs', $idobs, PDO::PARAM_INT);
				$req->bindValue(':idligne', $idligneobs, PDO::PARAM_INT);
				$req->bindValue(':idfiche', $idfiche, PDO::PARAM_INT);
				$req->bindValue(':dates', $dates);
				$req->bindValue(':idor', $idor);
				$req->execute();
				$req->closeCursor();
			}
		}
	}
	else
	{
		$idobs = '';
	}
	return $idobs;
}
if(isset($_POST['fichier'])) 
{
	$fichier = $_POST['fichier'];
	$premiere = $_POST['nbdeb'];
	$formatdate = 'Y-m-d';
	$datej = date('Y-m-d');
	$nbtraitement = 500;
	$mescdref = $_POST['mescdref'];
	$nber = $_POST['nber'];
	
	if($premiere == 0)
	{
		$errimport = '../../../tmp/errimport.csv';
		if(file_exists($errimport))
		{
			unlink($errimport);
		}
	}	
	if(($liste = fopen("../../../tmp/".$fichier, "r")) !== FALSE) 
	{
		fseek($liste, $premiere);
		$nbtrait = 0;
		$nbligne = $_POST['nbligne'];
		$i = 0;
		while(($data = fgetcsv($liste, 1000, ";")) !== FALSE) 
		{
			if($i++ < $nbtraitement)
			{
				$nbligne++; $nbtrait++;
				//attribution observatoire
				$nomvar = ($data[6] != '') ? $data[6] : 'aucun';
				//attribution cdref
				$cdref = cdref($data[2],$nomvar);
				//verifier si cdref existe ou pas
				if($cdref == '')
				{
					$tabcdreftmp = $nbligne;
				}
				//date saisie
				if($data[5] == '')
				{
					$dates = $datej;			
				}
				else
				{
					if(preg_match('#^([0-9]{2})/([0-9]{2})/([0-9]{4})$#', $data[5]))
					{
						$date1tmp = DateTime::createFromFormat('d/m/Y', $data[5]);
						$dates = $date1tmp->format('Y-m-d');
					}
					elseif(preg_match('#^([0-9]{4})-([0-9]{2})-([0-9]{2})$#', $data[5]))
					{
						$dates = $data[5];
					}
				}
				//attribution déterminateur
				$iddet = (!preg_match('#[^0-9]#', $data[3]) && !empty($data[3])) ? determinateur($data[3]) : null;
				//calcul nombre et denombrement
				$denom = ($data[15] != '') ? $data[15] : 'NSP';
				$tdenom = ($data[22] != '') ? $data[22] : 'NSP';
				if($data[8] == 'Pr')
				{
					if($denom == 'Co')
					{
						if($tdenom == 'IND' || $tdenom == 'NSP')
						{
							$ndiff = (!preg_match('#[^0-9]#', $data[12]) && !empty($data[12])) ? $data[12] : 0;
							$m = (!preg_match('#[^0-9]#', $data[13]) && !empty($data[13])) ? $data[13] : 0;
							$f = (!preg_match('#[^0-9]#', $data[14]) && !empty($data[14])) ? $data[14] : 0;
							$nbtotal = $ndiff + $m + $f;
							if($nbtotal == 0) { $nbtotal = 1; }
							if($ndiff != 0 && $m != 0 && $f != 0) { $sexe = 5; }
							elseif($ndiff != 0 && $m != 0 && $f != 0) { $sexe = 5; }
							elseif($ndiff == 0 && $m != 0 && $f != 0) { $sexe = 5; }
							elseif($ndiff != 0 && $m != 0 && $f == 0) { $sexe = 5; }
							elseif($ndiff != 0 && $m == 0 && $f != 0) { $sexe = 5; }
							elseif($ndiff != 0 && $m == 0 && $f == 0) { $sexe = 0; }
							elseif($ndiff == 0 && $m != 0 && $f == 0) { $sexe = 3; }
							elseif($ndiff == 0 && $m == 0 && $f != 0) { $sexe = 2; }
							elseif($ndiff == 0 && $m == 0 && $f == 0) { $sexe = 0; }
							$nbmax = $nbtotal; $nbmin = $nbtotal;							
						}
						else
						{
							$nbmin = $data[20]; $nbmax = $data[21];
							if($tdenom == 'CPL') 
							{
								$ndiff = 0; $m = $nbmin; $f = $nbmin; $nbtotal = $m + $f; $sexe = 5;
							}
							else
							{
								$ndiff = 0; $m = 0; $f = 0; $nbtotal = $nbmin; $sexe = 6;
							}						
						}
					}
					elseif($denom == 'Es')
					{
						$nbmax = $data[21]; $nbmin = $data[20];
						if($tdenom == 'IND' || $tdenom == 'NSP')
						{
							$ndiff = (!preg_match('#[^0-9]#', $data[12]) && !empty($data[12])) ? $data[12] : 0;
							$m = (!preg_match('#[^0-9]#', $data[13]) && !empty($data[13])) ? $data[13] : 0;
							$f = (!preg_match('#[^0-9]#', $data[14]) && !empty($data[14])) ? $data[14] : 0;
							if($ndiff != 0 || $m != 0 || $f != 0) 
							{
								$nbtotal = $ndiff + $m + $f; $nbmax = $nbtotal; $nbmin = $nbtotal;
								if($ndiff != 0 && $m != 0 && $f != 0) { $sexe = 5; }
								elseif($ndiff != 0 && $m != 0 && $f != 0) { $sexe = 5; }
								elseif($ndiff == 0 && $m != 0 && $f != 0) { $sexe = 5; }
								elseif($ndiff != 0 && $m != 0 && $f == 0) { $sexe = 5; }
								elseif($ndiff != 0 && $m == 0 && $f != 0) { $sexe = 5; }
								elseif($ndiff != 0 && $m == 0 && $f == 0) { $sexe = 0; }
								elseif($ndiff == 0 && $m != 0 && $f == 0) { $sexe = 3; }
								elseif($ndiff == 0 && $m == 0 && $f != 0) { $sexe = 2; }
							} 
							else
							{
								$ndiff = 0; $m = 0; $f = 0; $sexe = 6;	
								if(!empty($nbmin) && !empty($nbmax)) { $nbtotal = ($nbmax - ($nbmin - 1)) / 2; }
								elseif(!empty($nbmin) && empty($nbmax)) { $nbtotal = $nbmin; }
							}
						}
						else
						{
							$ndiff = 0; $m = 0; $f = 0; $sexe = 6;	
							if(!empty($nbmin) && !empty($nbmax)) { $nbtotal = ($nbmax - ($nbmin - 1)) / 2; }
							elseif(!empty($nbmin) && empty($nbmax)) { $nbtotal = $nbmin; }
							if($tdenom == 'CPL')
							{
								$sexe = 5;
								$m = $nbtotal; $f = $nbtotal; $nbtotal = $nbtotal * 2;
							}							
						}									
					}
					elseif($denom == 'NSP')
					{
						$sexe = 6;
						$ndiff = 1; $m = 0; $f = 0;
						$nbmax = 1; $nbmin = 1; $nbtotal = 1;
					}
				}
				else
				{
					$nbtotal = 0; $ndiff = 0; $m = 0; $f = 0; $nbmax = 0; $nbmin = 0; $sexe = 0;
				}
				//methode collecte
				if(preg_match('#[^0-9]#', $data[18]))
				{
					$idpros = 0;
				}
				else
				{
					$idpros = ($data[18] != '') ? $data[18] : 0;					
				}
				//verification idfiche
				$idfiche = idfiche($data[1]);
				//insertion obs
				if(!isset($tabcdreftmp) && $idfiche != 0 && $idfiche != '')
				{
					$idobs = insere_obs($data[0],$idfiche,$data[2],$cdref,$iddet,$nbtotal,$dates,$nomvar,$data[4],$data[8],$data[9],$data[10],$data[11],$denom,$data[16],$data[17],$idpros,$data[19],$data[7],$ndiff,$m,$f,$nbmin,$nbmax,$sexe,$tdenom);
					if($nbtrait == 1) { $retour['prem'] = $idobs; }
					else { $retour['dern'] = $idobs; }
					if($idobs == '')
					{
						$tabcdreftmp = $nbligne;						
					}					
				}
				if(isset($tabcdreftmp))
				{
					$datarq = ($data[4] == '') ? $data[4] : '"'.$data[4].'"';
					$tabtt = $data[0].';'.$data[1].';'.$data[2].';'.$data[3].';'.$datarq.';'.$data[5].';'.$data[6].';'.$data[7].';'.$data[8].';'.$data[9].';'.$data[10].';'.$data[11].';'.$data[12].';'.$data[13].';'.$data[14].';'.$data[15].';'.$data[16].';'.$data[17].';'.$data[18].';'.$data[19].';'.$data[20].';'.$data[21].';'.$data[22];
					$instab = $tabtt."\n";
					file_put_contents('../../../tmp/errimport.csv', $instab, FILE_APPEND);
					$tabcdref[] = $tabcdreftmp;
					unset($tabcdreftmp);
				}
				$derniere = ftell($liste);
			}			
			unset($data);
		}		
		fclose($liste);
		if(isset($tabcdref))
		{
			$nberencours = count($tabcdref);
			$listeligne = implode(", ", $tabcdref);
			$retour['mescdref'] = ($mescdref != '') ? $mescdref.', '.$listeligne : $listeligne;
			$retour['nber'] = $nber + $nberencours;
		}
		else
		{
			$retour['mescdref'] = $mescdref;
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
		$retour['mes'] = '<div class="alert alert-danger" role="alert">Aucun fichier.</div>';
	}
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert">Aucun fichier.</div>';
}
echo json_encode($retour);