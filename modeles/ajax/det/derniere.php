<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
//session_start();
	
function liste()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT idpdet, prenom, nom, to_char(datesaisie, 'DD/MM/YYYY') AS datefr, nomphoto, vali, typef, idobs, observa FROM site.photodet
						INNER JOIN site.membre ON membre.idmembre = photodet.idm
						ORDER BY datesaisie DESC
						LIMIT 20 ") or die(print_r($bdd->errorInfo()));
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function listemembre($idm)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idpdet, to_char(datesaisie, 'DD/MM/YYYY') AS datefr, nomini, vali, typef, idobs, observa FROM site.photodet
						WHERE idm = :idm
						ORDER BY datesaisie DESC
						LIMIT 10 ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idm', $idm);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function nbliste()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT COUNT(*) AS nb FROM site.photodet ") or die(print_r($bdd->errorInfo()));
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;
}
function nblistem($idm)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM site.photodet WHERE idm = :idm ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idm', $idm);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;
}

//$idm = $_SESSION['idmembre'];

$liste = liste();
//$perso = listemembre($idm);

$json_site = file_get_contents('../../../json/site.json');
$rjson = json_decode($json_site, true);
foreach($rjson['observatoire'] as $n)
{
	$tabobserva[$n['nomvar']] = $n['nom'];
}

if($liste != false)
{
	$l = '<table id="tblliste" class="table table-sm">';
	$l .= '<thead><tr><th></th><th>Observateur</th><th>Date demande</th><th>Observatoire</th><th></th><th></th></tr></thead>';
	$l .= '<tbody>';
	foreach($liste as $n)
	{
		if($n['observa'] != 'NR' && $n['observa'] != '')
		{
			$observa = $tabobserva[$n['observa']];
		}	
		else
		{
			$observa = '';
		}
		$l .= '<tr>';
		$l .= '<td><a href="index.php?module=det&amp;action=suivi&amp;id='.$n['idpdet'].'"><i class="fa fa-eye text-info"></i></a></td><td>'.$n['prenom'].' '.$n['nom'].'</td><td>'.$n['datefr'].'</td><td>'.$observa.'</td>';
		$l .= ($n['typef'] == 'photo') ? '<td><i class="fa fa-camera text-info"></i></td>' : '<td><i class="fa fa-volume-off text-info"></i></td>';
		if($n['vali'] == 'oui') { $l .= '<td><i class="fa fa-check text-success"></i></td>'; }
		elseif($n['vali'] == 'non') { $l .= '<td><i class="fa fa-check text-warning"></i></td>'; }
		elseif($n['vali'] == 'nde') { $l .= '<td><i class="fa fa-times text-success"></i></td>'; }
		$l .= '</tr>';
	}
	$l .= '</tbody></table>';
	$nbliste = nbliste();
	if($nbliste > 10) 
	{
		$l .= '<a class="btn btn-outline-success btn-sm" href="index.php?module=det&amp;action=liste" role="button">Voir toutes les demandes</a>';
	}
}	
else
{
	$l = 'Aucune demande de détermination';
}

/*if($perso != false)
{
	$p = '<hr />';
	$p .= '<h2 class="h5">Vos dix dernières demandes</h2>';
	$p .= '<table id="tblperso" class="table table-sm">';
	$p .= '<thead><tr><th></th><th>Date demande</th><th>Votre photo ou son</th><th>Observatoire</th><th></th><th></th></tr></thead>';
	$p .= '<tbody>';
	foreach($perso as $n)
	{
		if($n['observa'] != 'NR' && $n['observa'] != '')
		{
			$observa = $tabobserva[$n['observa']];
		}	
		else
		{
			$observa = '';
		}
		$p .= '<tr>';
		$p .= '<td><a href="index.php?module=det&amp;action=suivi&amp;id='.$n['idpdet'].'"><i class="fa fa-eye text-info"></i></a></td><td>'.$n['datefr'].'</td><td>'.$n['nomini'].'</td><td>'.$observa.'</td>';
		$p .= ($n['typef'] == 'photo') ? '<td><i class="fa fa-camera text-info"></i></td>' : '<td><i class="fa fa-volume-off text-info"></i></td>';
		if($n['vali'] == 'oui') { $p .= '<td><i class="fa fa-check text-success"></i></td>'; }
		elseif($n['vali'] == 'non') { $p .= '<td><i class="fa fa-check text-warning"></i></td>'; }
		elseif($n['vali'] == 'nde') { $p .= '<td><i class="fa fa-times text-success"></i></td>'; }
		$p .= '</tr>';
	}
	$p .= '</tbody></table>';
	$nblistem = nblistem($idm);
	if($nblistem > 10) 
	{
		$p .= '<a class="btn btn-outline-success btn-sm" href="index.php?module=det&amp;action=liste&amp;perso=oui" role="button">Voir toutes vos demandes</a>';
	}
	
	$retour['perso'] = $p;
}	*/

$retour['liste'] = $l;	
$retour['statut'] = 'Oui';
	
echo json_encode($retour);