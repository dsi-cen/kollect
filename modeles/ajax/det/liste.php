<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
session_start();

function liste($choix,$observa)
{
	$where = 'non';
	$strQuery = "SELECT idpdet, prenom, nom, to_char(datesaisie, 'DD/MM/YYYY') AS datefr, vali, datesaisie, observa, typef FROM site.photodet INNER JOIN site.membre ON membre.idmembre = photodet.idm";
	if(!empty($observa)) { $strQuery .= " WHERE observa = :observa";  $where = 'oui'; }
	if($choix == 1) { $strQuery .= ($where == 'non') ? " WHERE vali = 'oui'" : " AND vali = 'oui' "; }
	elseif($choix == 2) { $strQuery .= ($where == 'non') ? " WHERE vali = 'non' OR vali IS NULL" : " AND (vali = 'non' OR vali IS NULL) "; }
	elseif($choix == 3) { $strQuery .= ($where == 'non') ? " WHERE vali = 'nde'" : " AND (vali = 'nde') "; }
	$strQuery .= " ORDER BY datesaisie DESC ";
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	if(!empty($observa))
	{
		$req = $bdd->prepare($strQuery);
		$req->bindValue(':observa', $observa);
		$req->execute();
	}
	else
	{
		$req = $bdd->query($strQuery);
	}	
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function listeperso($choix,$idm,$observa)
{
	$where = 'non';
	$strQuery = "SELECT idpdet, to_char(datesaisie, 'DD/MM/YYYY') AS datefr, vali, datesaisie, nomini, observa, typef FROM site.photodet WHERE idm = :idm";
	if(!empty($observa)) { $strQuery .= " AND observa = :observa"; }
	if($choix == 1) { $strQuery .= " AND vali = 'oui' "; }
	elseif($choix == 2) { $strQuery .= " AND (vali = 'non' OR vali IS NULL) "; }
	elseif($choix == 3) { $strQuery .= " AND (vali = 'nde') "; }
	$strQuery .= " ORDER BY datesaisie DESC ";
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':idm', $idm);
	if(!empty($observa)) { $req->bindValue(':observa', $observa); }
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
if(isset($_POST['choix']))
{
	$perso = $_POST['perso'];
	$choix = $_POST['choix'];
	$observa = htmlspecialchars($_POST['observa']);
	
	if($perso == 'oui')
	{
		$idm = $_SESSION['idmembre'];
		$liste = listeperso($choix,$idm,$observa);
	}
	else
	{
		$liste = liste($choix,$observa);
	}	
	
	if($liste != false)
	{
		$json_site = file_get_contents('../../../json/site.json');
		$rjson = json_decode($json_site, true);
		foreach($rjson['observatoire'] as $n)
		{
			$tabobserva[$n['nomvar']] = $n['nom'];
		}
			
		$l = '<table id="tblliste" class="table table-sm table-striped" cellspacing="0" width="100%">';
		$l .= ($perso == 'oui') ? '<thead><tr><th></th><th>Id</th><th>Date demande</th><th>Votre photo/son</th><th>Observatoire</th><th>Statut</th><th></th></tr></thead>' : '<thead><tr><th></th><th>Id</th><th>Date demande</th><th>Observateur</th><th>Observatoire</th><th>Statut</th><th></th></tr></thead>';
		$l .= '</table>';
		foreach($liste as $n)
		{
			if($perso == 'non') { $obser = $n['prenom'].' '.$n['nom']; }
			$voir = '<a href="index.php?module=det&amp;action=suivi&amp;id='.$n['idpdet'].'"><i class="fa fa-eye text-info"></i></a>';
			if($n['vali'] == 'oui') { $vali = '<i class="fa fa-check text-success"></i>'; }
			elseif($n['vali'] == 'non') { $vali = '<i class="fa fa-check text-warning"></i>'; }
			elseif($n['vali'] == 'nde') { $vali = '<i class="fa fa-times text-success"></i>'; }
			if($n['observa'] != 'NR' && $n['observa'] != '')
			{
				$observa = $tabobserva[$n['observa']];
			}	
			else
			{
				$observa = '';
			}
			$typef = ($n['typef'] == 'photo') ? '<i class="fa fa-camera text-info"></i>' : '<i class="fa fa-volume-off text-info"></i>';
			$tridate = ['tri'=>$n['datesaisie'],'date'=>$n['datefr']];
			$tristatut = ['tri'=>$n['vali'],'vali'=>$vali];
			$data[] = ($perso == 'oui') ? [$voir,$n['idpdet'],$tridate,$n['nomini'],$observa,$tristatut,$typef] : [$voir,$n['idpdet'],$tridate,$obser,$observa,$tristatut,$typef];			
		}
		$retour['data'] = $data;
	}	
	else
	{
		$l = 'Aucune demande pour ce critère';
	}
	
	$retour['liste'] = $l;	
	$retour['statut'] = 'Oui';	
}
else
{
	$retour['statut'] = 'Non';
}
echo json_encode($retour);