<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function recherche($cdnom)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idphoto, to_char(datephoto, 'DD/MM/YYYY') AS datefr, nomphoto, ordre, idobs, observatoire, stade.stade, sexe, observateur, idobser FROM site.photo
						INNER JOIN referentiel.observateur USING(idobser)
						INNER JOIN referentiel.stade ON stade.idstade = photo.stade
						WHERE cdnom = :cdnom
						ORDER BY ordre ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':cdnom', $cdnom);
	$req->execute();
	$nbresultats = $req->rowCount();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return array($nbresultats, $resultat);	
}
if(isset($_POST['cdnom']))
{	
	$cdnom = $_POST['cdnom'];
	if(isset($_POST['observa']))
	{
		$observa = $_POST['observa'];
		
		$json = file_get_contents('../../../../json/'.$observa.'.json');
		$rjson = json_decode($json, true);
		if(isset($rjson['saisie']['stade']))
		{
			$retour['stade'] = $rjson['saisie']['stade'];
		}
	}
	$listephoto = recherche($cdnom);
	if($listephoto[0] >= 1)
	{
		$nb = $listephoto[0];
		$p = '<div class="d-flex flex-row">';
		$l = '<table class="table table-hover table-sm">';
		$l .= '<thead><tr class="table-active">';
		$l .= '<th></th><th>Nomphoto</th><th>ordre</th><th>stade</th><th>sexe</th><th>observatoire</th><th>date</th><th>auteur</th><th>idobs</th>';
		$l .= '</tr></thead>';
		$l .= '<tbody class="">';
		foreach($listephoto[1] as $n)
		{
			$l .= '<tr id="'.$n['idphoto'].'">';
			$l .= '<td><i class="fa fa-trash curseurlien text-danger supphoto" title="Supprimer la photo"></i>&nbsp;&nbsp;<i class="fa fa-pencil curseurlien text-warning modphoto" title="Modifier les informations"></i></td>';
			$l .= '<td>'.$n['nomphoto'].'</td>';
			$l .= '<td><select class="ordre"><option value="'.$n['ordre'].'">'.$n['ordre'].'</option>';
			for($i = 1; $i <= $nb; $i++) 
			{
				if ($n['ordre'] == $i) { $select = "selected"; }
				else { $select = ''; $l .= '<option value="'.$i.'" '.$select.'>'.$i.'</option>'; }						
			}
			$l .= '</select></td>';
			$l .= '<td class="stade">'.$n['stade'].'</td><td class="sexe">'.$n['sexe'].'</td><td class="observa">'.$n['observatoire'].'</td><td>'.$n['datefr'].'</td><td id="id-'.$n['idobser'].'" class="idobser">'.$n['observateur'].'</td>';
			$l .= '<td><a href="../index.php?module=observation&amp;action=detail&amp;idobs='.$n['idobs'].'">'.$n['idobs'].'</a></td>';
			$l .= '</tr>';
			$p .= '<div class="p-2">';
			$p .= '<p class="mb-0">'.$n['nomphoto'].'</p>';
			$p .= '<a class="agrand" href="../photo/P800/'.$n['observatoire'].'/'.$n['nomphoto'].'.jpg" title="'.$n['observateur'].' - '.$n['datefr'].'"><img class="img-thumbnail" alt="" src="../photo/P200/'.$n['observatoire'].'/'.$n['nomphoto'].'.jpg"></a>';
			$p .= '<p>'.$n['stade'].'</p>';
			$p .= '</div>';
		}
		$l .= '</tbody></table>';
		$p .= '</div>';
		$retour['tbl'] = $l;
		$retour['photo'] = $p;
	}
	$retour['nb'] = $listephoto[0];
	if(isset($_POST['observa']))
	{
		$retour['fiche'] = '<a href="../observatoire/index.php?module=fiche&amp;action=fiche&amp;d='.$observa.'&amp;id='.$cdnom.'">Fiche de l\'espèce</a>'; 
	}
	
	$retour['statut'] = 'Oui';
}
else
{
	$retour['statut'] = 'Non';
}
echo json_encode($retour);	
?>