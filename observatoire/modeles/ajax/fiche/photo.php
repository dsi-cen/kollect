<?php 
include '../../../../global/configbase.php';
include '../../../lib/pdo2.php';

function photo($cdnom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idobser, datephoto, sexe, stade, nomphoto, prenom, nom, to_char(datephoto, 'DD/MM/YYYY') AS datefr FROM site.photo
						INNER JOIN obs.obs USING(idobs)
						INNER JOIN referentiel.observateur USING(idobser)
						WHERE photo.cdnom = :cdnom AND (validation = 1 OR validation = 2)");
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
	
	$photo = photo($cdnom);
	
	$liste = null;
	$liste .= '<div class="grid-sizer col-sm-6 col-md-4 col-xl-3"></div>';
	foreach($photo as $n)
	{
		$liste .= '<div class="grid-item col-sm-6 col-md-4 col-xl-3 no-padding s'.$n['stade'].' '.$n['sexe'].'" data-auteur="'.$n['nom'].'" data-datep="'.$n['datephoto'].'">';
		$liste .= '<a href="../photo/P800/'.$nomvar.'/'.$n['nomphoto'].'.jpg" title="'.$n['prenom'].' '.$n['nom'].' - '.$n['datefr'].'"><img alt="'.$_POST['nom'].'" src="../photo/P400/'.$nomvar.'/'.$n['nomphoto'].'.jpg" class="img-fluid img-thumbnail"></a>';
		$liste .= '</div>';
		$sexe[$n['sexe']] = $n['sexe'];
		$stade[$n['stade']] = $n['stade'];	
	}
		
	$but = null;
	$but .= '<span class="listefiltre">';
	if(count($sexe) > 1)
	{
		$but .= '<button data-filter="*" type="button" class="btn btn-success" data-filter-group="sexe">Tous</button>';
		foreach($sexe as $n)
		{
			if($n == 'M')
			{
				$but .= ' <button data-filter=".'.$n.'" type="button" class="btn btn-secondary" data-filter-group="sexe">Mâle</button>';
			}
			if($n == 'F')
			{
				$but .= ' <button data-filter=".'.$n.'" type="button" class="btn btn-secondary" data-filter-group="sexe">Femelle</button>';
			}
			if($n == 'C')
			{
				$but .= ' <button data-filter=".'.$n.'" type="button" class="btn btn-secondary" data-filter-group="sexe">Couple</button>';
			}
		}
	}	
	if(isset($rjson['saisie']['stade']))
	{
		if(count($stade) > 1)
		{
			$but .= '&nbsp;<button data-filter="*" type="button" class="btn btn-success" data-filter-group="stade">Tous les stades</button>';
			foreach($rjson['saisie']['stade'] as $cle => $n)
			{
				foreach($stade as $s)
				{
					if($s == $n)
					{
						$but .= ' <button data-filter=".s'.$n.'" type="button" class="btn btn-secondary" data-filter-group="stade">'.$cle.'</button>';
					}
				}			
			}			
		}		
	}
	$but .= '</span>';
	if(count($photo) > 1 )
	{
		$but .= '&nbsp;tri par :';
		$but .= '<span class="listetri">';
		$but .= ' <button data-sort-by="datep" type="button" class="btn btn-secondary">Date</button>';
		$but .= ' <button data-sort-by="auteur" type="button" class="btn btn-secondary">Auteur</button>';
		$but .= '</span>';
	}
	
	$retour['but'] = $but;
	$retour['liste'] = $liste;
	
	$retour['statut'] = 'Oui';		
}
else
{
	$retour['statut'] = 'Non';
}	
echo json_encode($retour);
