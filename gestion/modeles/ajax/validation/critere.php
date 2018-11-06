<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';
	
function insere_critère($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->prepare("INSERT INTO vali.critere (cdnom,stade,observa) 
						SELECT cdnom, 'Tous' AS stade, '$nomvar' AS observa FROM referentiel.liste
						WHERE NOT EXISTS (SELECT * FROM vali.critere WHERE critere.cdnom = liste.cdnom)
						AND vali = 2 AND observatoire = :observa ");
	$req->bindValue(':observa', $nomvar);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;	
}
function supcritere($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("DELETE FROM vali.critere 
						WHERE NOT EXISTS (SELECT * FROM referentiel.liste WHERE liste.cdnom = critere.cdnom AND vali = 2) AND observa = :observa ");
	$req->bindValue(':observa', $nomvar);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;
}
function liste($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT cdnom, nom, nomvern, stade, photo, son, loupe, bino FROM vali.critere
						INNER JOIN referentiel.liste USING(cdnom) 
						WHERE observa = :observa  
						ORDER BY nom ");
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}

if(isset($_POST['sel']))
{
	$nomvar = $_POST['sel'];
		
	$vali = supcritere($nomvar);
	if($vali == 'oui')
	{
		$vali = insere_critère($nomvar);
		if($vali == 'oui')
		{
			$liste = liste($nomvar);
			if($liste != false)
			{
				$json = file_get_contents('../../../../json/'.$nomvar.'.json');
				$rjson = json_decode($json, true);
				$tabstade['Tous'] = 0;
				foreach($rjson['saisie']['stade'] as $cle => $n)
				{
					$tabstade[$cle] = $n;
				}	
				
				$retour['stade'] = $tabstade;
								
				$mod = '<i class="fa fa-pencil curseurlien text-warning"></i>';
				$l = '<p>Liste établie à partir des espèces notées à valider manuellement.</p>';
				$l .= '<table id="tblliste" class="table table-sm table-striped" cellspacing="0" width="100%">';
				$l .= '<thead><tr><th>Nom</th><th>Nom français</th><th>Stade</th><th>Photo</th><th>Son</th><th>Loupe</th><th>Bino</th><th></th></tr></thead>';
				$l .= '</table>';
				foreach($liste as $n)
				{
					$data[] = [$n['nom'],$n['nomvern'],$n['stade'],$n['photo'],$n['son'],$n['loupe'],$n['bino'],$mod,'DT_RowId'=>$n['cdnom']];
				}
				$retour['data'] = $data;
			}	
			else
			{
				$l = 'Aucune espèce a valider manuellement pour cet observatoire'; 
			}
			$retour['liste'] = $l;
			$retour['statut'] = 'Oui';		
		}	
		else
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Erreur ! insertion.</p></div>';
		}
	}	
	else
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Erreur ! suppression.</p></div>';
	}	
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert">Aucun observatoire de choisit.</div>';
}
echo json_encode($retour);