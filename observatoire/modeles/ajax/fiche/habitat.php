<?php 
include '../../../../global/configbase.php';
include '../../../lib/pdo2.php';

function habitat($cdnom)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT COUNT(idobs) AS nb, lbcode, cdhab, lbhabitat FROM obs.obshab
						INNER JOIN referentiel.eunis USING(cdhab)
						WHERE cdnom = :cdnom
						GROUP BY lbcode, cdhab, lbhabitat
						ORDER BY lbcode ") or die(print_r($bdd->errorInfo()));		
	$req->bindValue(':cdnom', $cdnom);
	$req->execute();
	$result = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $result;
}

if(isset($_POST['cdnom'])) 
{
	$cdnom = $_POST['cdnom'];
	$habitat = habitat($cdnom);
	
	$liste = null;
	$total = 0;
	$liste .= '<table class="table table-striped"><thead><tr>';
	$liste .= '<th>Nb</th><th>Eunis</th><th>Habitat</th><th>Lien</th>';
	$liste .= '</tr></thead><tbody>';
	foreach($habitat as $n)
	{
		$liste .= '<tr>';
		$liste .= '<td>'.$n['nb'].'</td><td>'.$n['lbcode'].'</td><td><i class="fa fa-info-circle text-info curseurlien infohab" title="Description" id="'.$n['cdhab'].'"></i> '.$n['lbhabitat'].'</td><td><a href="https://inpn.mnhn.fr/habitat/cd_hab/'.$n['cdhab'].'"><img src="../dist/img/inpn.png" width="50" height="18" alt="logo INPN"/></a></td>';
		$liste .= '</tr>';
		$total += $n['nb'];		
	}
	$liste .= '</tbody></table>';
	
	$retour['table'] = $liste;
	$retour['total'] = $total;
	$retour['statut'] = 'Oui';	
		
}
else
{
	$retour['statut'] = 'Non';
}	
echo json_encode($retour);
