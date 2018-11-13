<?php 
include '../../../../global/configbase.php';
include '../../../lib/pdo2.php';

function recherche_statut($cdnom,$cdprotect,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT cdprotect, type, lr, article, intitule, arrete, annee, url FROM statut.statut
						INNER JOIN statut.libelle USING(cdprotect)
						INNER JOIN $nomvar.liste ON liste.cdnom = statut.cdnom
						WHERE cdref = :cdnom AND cdprotect IN($cdprotect) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':cdnom', $cdnom);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}

if(isset($_POST['cdnom'])) 
{
	$cdnom = $_POST['cdnom'];
	$nomvar = $_POST['nomvar'];
	
	$json = file_get_contents('../../../../json/'.$nomvar.'.json');
	$rjson = json_decode($json, true);
	
	if(isset($rjson['statut']))
	{
		foreach($rjson['statut'] as $cle => $n)
		{
			foreach($n as $a)
			{
				$tabstatut[] = $a;				
			}			
		}
		$cdprotect = implode("','", $tabstatut);
		$cdprotect = "'".$cdprotect."'";
		$statut = recherche_statut($cdnom,$cdprotect,$nomvar);
		if(!empty($statut))
		{
			$liste = null;
			foreach($statut as $n)
			{
				$tabtype[] = $n['type'];				
			}
			$tabtype = array_unique($tabtype);
			foreach($tabtype as $n)
			{
				if($n == 'DH') { $tabordre[] = 1; }
				elseif($n == 'PN') { $tabordre[] = 2; }
				elseif($n == 'PR') { $tabordre[] = 3; }
				elseif($n == 'PD') { $tabordre[] = 4; }
				elseif($n == 'Z') { $tabordre[] = 5; }
                elseif($n == 'LRM') { $tabordre[] = 10; }
				elseif($n == 'LRE') { $tabordre[] = 9; }
				elseif($n == 'LRF') { $tabordre[] = 8; }
				elseif($n == 'LRR') { $tabordre[] = 7; }
				elseif($n == 'LRD') { $tabordre[] = 6; }
				elseif($n == 'A') { $tabordre[] = 11; }
				elseif($n == 'I') { $tabordre[] = 12; }
			}	
			$type = array_combine($tabordre, $tabtype);
			ksort($type);
			foreach($type as $t)
			{
				if($t == 'DH')
				{
					$liste .= '<br /><h3 class="h5">Directive Européenne</h3>';
					$liste .= '<ul>';
					foreach($statut as $n)
					{
						if($n['type'] == $t)
						{
							$liste .= '<li><b>'.$n['article'].' - '.$n['intitule'].'</b> - '.$n['arrete'].' <a href="'.$n['url'].'"><i class="fa fa-link text-primary"></i></a></li>';
						}
					}
					$liste .= '</ul>';				
				}
				elseif($t == 'PN')
				{
					$liste .= '<br /><h3 class="h5">Protection France</h3>';
					$liste .= '<ul>';
					foreach($statut as $n)
					{
						if($n['type'] == $t)
						{
							$liste .= '<li><b>'.$n['article'].' - '.$n['intitule'].'</b> - '.$n['arrete'].' <a href="'.$n['url'].'"><i class="fa fa-link text-primary"></i></a></li>';
						}
					}
					$liste .= '</ul>';	
				}
				elseif($t == 'PR')
				{
					$liste .= '<br /><h3 class="h5">Protection Régionale</h3>';
					$liste .= '<ul>';
					foreach($statut as $n)
					{
						if($n['type'] == $t)
						{
							$liste .= '<li><b>'.$n['article'].' - '.$n['intitule'].'</b> - '.$n['arrete'].' <a href="'.$n['url'].'"><i class="fa fa-link text-primary"></i></a></li>';
						}
					}
					$liste .= '</ul>';	
				}
				elseif($t == 'PD')
				{
					$liste .= '<br /><h3 class="h5">Protection Départementale</h3>';
					$liste .= '<ul>';
					foreach($statut as $n)
					{
						if($n['type'] == $t)
						{
							$liste .= '<li><b>'.$n['article'].' - '.$n['intitule'].'</b> - '.$n['arrete'].' <a href="'.$n['url'].'"><i class="fa fa-link text-primary"></i></a></li>';
						}
					}
					$liste .= '</ul>';	
				}
				elseif($t == 'Z')
				{
					$liste .= '<br /><h3 class="h5">Espèce déterminante ZNIEFF</h3>';
					$liste .= '<ul>';
					foreach($statut as $n)
					{
						if($n['type'] == $t)
						{
							$liste .= '<li><b>'.$n['intitule'].'</b> <a href="'.$n['url'].'"><i class="fa fa-link text-primary"></i></a></li>';
						}
					}
					$liste .= '</ul>';	
				}
                elseif($t == 'LRM')
                {
                    if(!isset($listerouge)) {
                        $listerouge = '<br /><h3 class="h5">Liste Rouge <i class="fa fa-info-circle text-info curseurlien" title="Information liste rouge" data-toggle="modal" data-target="#infolr"></i></h3>';
                        $liste .= $listerouge;
                    }
                    $liste .= '<h4 class="h6">Mondiale</h4>';
                    foreach($statut as $n)
                    {
                        if($n['type'] == $t)
                        {
                            $lr = $n['lr'];
                            $lrp = ($lr == 'CR*') ? 'CR' : $lr;
                            $liste .= '<dl class="row mt-1">';
                            $liste .= '<dd class="col-sm-1 text-center"><span class="fa-stack"><i class="fa fa-circle fa-stack-2x '.$lrp.'"></i><i class="fa fa-stack-1x font13 '.$lrp.'t">'.$lr.'</span></i></dd>';
                            $liste .= '<dd class="col-sm-11"><b>'.$n['intitule'].' - '.$n['annee'].'</b> '.$n['arrete'].' <a href="'.$n['url'].'"><i class="fa fa-link text-primary"></i></a></dd>';
                            $liste .= '</dl>';
                            //$liste .= '<p><span class="fa-stack"><i class="fa fa-circle fa-stack-2x '.$lrp.'"></i><i class="fa fa-stack-1x font13 '.$lrp.'t">'.$lr.'</span></i>';
                            //$liste .= '<b>'.$n['intitule'].' - '.$n['annee'].'</b> '.$n['arrete'].' <a href="'.$n['url'].'"><i class="fa fa-link"></i></a></p>';
                        }
                    }
                }
				elseif($t == 'LRE')
				{
                    if(!isset($listerouge)) {
                        $listerouge = '<br /><h3 class="h5">Liste Rouge <i class="fa fa-info-circle text-info curseurlien" title="Information liste rouge" data-toggle="modal" data-target="#infolr"></i></h3>';
                        $liste .= $listerouge;
                    }
					$liste .= '<h4 class="h6">Européenne</h4>';
					foreach($statut as $n)
					{
						if($n['type'] == $t)
						{
							$lr = $n['lr']; 
							$lrp = ($lr == 'CR*') ? 'CR' : $lr;
							$liste .= '<dl class="row mt-1">';
							$liste .= '<dd class="col-sm-1 text-center"><span class="fa-stack"><i class="fa fa-circle fa-stack-2x '.$lrp.'"></i><i class="fa fa-stack-1x font13 '.$lrp.'t">'.$lr.'</span></i></dd>';
							$liste .= '<dd class="col-sm-11"><b>'.$n['intitule'].' - '.$n['annee'].'</b> '.$n['arrete'].' <a href="'.$n['url'].'"><i class="fa fa-link text-primary"></i></a></dd>';
							$liste .= '</dl>';
							//$liste .= '<p><span class="fa-stack"><i class="fa fa-circle fa-stack-2x '.$lrp.'"></i><i class="fa fa-stack-1x font13 '.$lrp.'t">'.$lr.'</span></i>';
							//$liste .= '<b>'.$n['intitule'].' - '.$n['annee'].'</b> '.$n['arrete'].' <a href="'.$n['url'].'"><i class="fa fa-link"></i></a></p>';
						}
					}					
				}
				elseif($t == 'LRF')
				{
					if(!isset($listerouge)) 
					{ 
						$listerouge = '<br /><h3 class="h5">Liste Rouge <i class="fa fa-info-circle text-info curseurlien" title="Information liste rouge" data-toggle="modal" data-target="#infolr"></i></h3>';
						$liste .= $listerouge; 
					}
					$liste .= '<h4 class="h6">Française</h4>';
					foreach($statut as $n)
					{
						if($n['type'] == $t)
						{
							$lr = $n['lr']; 
							$lrp = ($lr == 'CR*') ? 'CR' : $lr;
							$liste .= '<dl class="row mt-1">';
							$liste .= '<dd class="col-sm-1 text-center"><span class="fa-stack"><i class="fa fa-circle fa-stack-2x '.$lrp.'"></i><i class="fa fa-stack-1x font13 '.$lrp.'t">'.$lr.'</span></i></dd>';
							$liste .= '<dd class="col-sm-11"><b>'.$n['intitule'].' - '.$n['annee'].'</b> '.$n['arrete'].' <a href="'.$n['url'].'"><i class="fa fa-link text-primary"></i></a></dd>';							
							$liste .= '</dl>';
							//$liste .= '<p><span class="fa-stack"><i class="fa fa-circle fa-stack-2x '.$lrp.'"></i><i class="fa fa-stack-1x font13 '.$lrp.'t">'.$lr.'</span></i>';
							//$liste .= '<b>'.$n['intitule'].' - '.$n['annee'].'</b> '.$n['arrete'].' <a href="'.$n['url'].'"><i class="fa fa-link"></i></a></p>';
						}
					}					
				}
				elseif($t == 'LRR')
				{
					if(!isset($listerouge)) 
					{ 
						$listerouge = '<br /><h3 class="h5">Liste Rouge <i class="fa fa-info-circle text-info curseurlien" title="Information liste rouge" data-toggle="modal" data-target="#infolr"></i></h3>';
						$liste .= $listerouge; 
					}
					$liste .= '<h4 class="h6">Régionale</h4>';
					foreach($statut as $n)
					{
						if($n['type'] == $t)
						{
							$lr = $n['lr']; 
							$lrp = ($lr == 'CR*') ? 'CR' : $lr;
							$liste .= '<dl class="row mt-1">';
							$liste .= '<dd class="col-sm-1 text-center"><span class="fa-stack"><i class="fa fa-circle fa-stack-2x '.$lrp.'"></i><i class="fa fa-stack-1x font13 '.$lrp.'t">'.$lr.'</span></i></dd>';
							$liste .= '<dd class="col-sm-11"><b>'.$n['intitule'].' - '.$n['annee'].'</b> '.$n['arrete'].' <a href="'.$n['url'].'"><i class="fa fa-link text-primary"></i></a></dd>';							
							$liste .= '</dl>';						
							//$liste .= '<p><span class="'.$lrp.'">'.$lr.'</span> <b>'.$n['intitule'].' - '.$n['annee'].'</b> '.$n['arrete'].' <a href="'.$n['url'].'"><i class="fa fa-link"></i></a></p>';
						}
					}					
				}
				elseif($t == 'A')
				{
					$liste .= '<h3 class="h5">Autre</h3>';
					//$liste .= '<ul>';
					foreach($statut as $n)
					{
						if($n['type'] == $t)
						{
							if($n['lr'] != '')
							{
								$liste .= '<dl class="row mt-1">';
								$liste .= '<dd class="col-sm-1 text-center">'.$n['lr'].'</dd>';
								$liste .= '<dd class="col-sm-11"><b>'.$n['intitule'].' - '.$n['annee'].'</b> '.$n['arrete'].' <a href="'.$n['url'].'"><i class="fa fa-link text-primary"></i></a></dd>';
								$liste .= '</dl>';	
							}
							else					
							{
								$liste .= '<li><b>'.$n['article'].' - '.$n['intitule'].'</b> - '.$n['arrete'].' <a href="'.$n['url'].'"><i class="fa fa-link text-primary"></i></a></li>';
							}
						}
					}
					//$liste .= '</ul>';	
				}
				elseif($t == 'I')
				{
					$liste .= '<h3 class="h5">Invasif</h3>';
					foreach($statut as $n)
					{
						if($n['type'] == $t)
						{
							if($n['lr'] != '')
							{
								$liste .= '<dl class="row mt-1">';
								$liste .= '<dd class="col-sm-1 text-center">'.$n['lr'].'</dd>';
								$liste .= '<dd class="col-sm-11"><b>'.$n['intitule'].' - '.$n['annee'].'</b> '.$n['arrete'].' <a href="'.$n['url'].'"><i class="fa fa-link text-primary"></i></a></dd>';
								$liste .= '</dl>';	
							}
							else					
							{
								$liste .= '<li><b>'.$n['article'].' - '.$n['intitule'].'</b> - '.$n['arrete'].' <a href="'.$n['url'].'"><i class="fa fa-link text-primary"></i></a></li>';
							}
						}
					}	
				}
			}			
		}		
	}
	
	$retour['statut'] = 'Oui';	
	$retour['liste'] = $liste;		
}
else
{
	$retour['statut'] = 'Non';
}	
echo json_encode($retour);
