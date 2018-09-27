<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';	
function statut()
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT cdprotect, article, intitule, type FROM statut.libelle ORDER BY cdprotect") or die(print_r($bdd->errorInfo()));
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}

if(isset($_POST['sel']))
{
	$nomvar = ($_POST['sel']);
	$liste = statut();
	
	$json = file_get_contents('../../../../json/'.$nomvar.'.json');
	$rjson = json_decode($json, true);
	
	if(isset($rjson['statut']))
	{
		foreach($rjson['statut'] as $cle => $n)
		{
			$tabstatut[] = array($cle=>$n);
			foreach($n as $a)
			{
				$tab[] = $a;				
			}			
		}		
	}	
	
	$libelle = null;
	foreach($liste as $n)
	{
		$libelle .= '<tr>';
		if(isset($tab))
		{
			if(in_array($n['cdprotect'], $tab))
			{
				$libelle .= '<td><input type="checkbox" id="'.$n['type'].'-'.$n['cdprotect'].'" class="sel" checked></td>';
				$libelle .= '<td></td>';
			}
			else
			{
				$libelle .= '<td><input type="checkbox" id="'.$n['type'].'-'.$n['cdprotect'].'" class="sel"></td>';
				$libelle .= '<td><i class="fa fa-trash curseurlien text-danger" title="Supprimer ce statut" onclick="sup(\''.$n['cdprotect'].'\')"></i></td>';
			}
		}
		else
		{
			$libelle .= '<td><input type="checkbox" id="'.$n['type'].'-'.$n['cdprotect'].'" class="sel"></td>';
			$libelle .= '<td><i class="fa fa-trash curseurlien text-danger" title="Supprimer ce statut" onclick="sup(\''.$n['cdprotect'].'\')"></i></td>';
		}
		$libelle .= '<td class="text-xs-center"><i class="fa fa-eye curseurlien text-primary" onclick="listetaxon(\''.$n['cdprotect'].'\')" title="Voir les taxons"></i></td>';
		$libelle .= '<td class="text-xs-center"><i class="fa fa-pencil curseurlien text-warning" onclick="modif(\''.$n['cdprotect'].'\')" title="Modifier"></i></td>';
		$libelle .= '<td>'.$n['type'].'</td><td>'.$n['cdprotect'].'</td><td>'.$n['article'].'</td><td>'.$n['intitule'].'</td>';
		$libelle .= '</tr>';
	}	
	$retour['liste'] = $libelle;
	$retour['statut'] = 'Oui';
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Aucun observatoire de choisit.</p></div>';
}
echo json_encode($retour);