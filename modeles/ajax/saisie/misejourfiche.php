<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function liste_fiched($obs)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$bdd->query("SET lc_time = 'fr_FR.UTF8'");
	$req = $bdd->prepare("SELECT DISTINCT idfiche, departement, commune, site, to_char(date1, 'DD/MM/YYYY') AS date_fr, fiche.idsite, localisation FROM obs.fiche
						INNER JOIN referentiel.departement ON departement.iddep = fiche.iddep
						INNER JOIN obs.obs USING(idfiche)
						LEFT JOIN referentiel.commune ON commune.codecom = fiche.codecom
						LEFT JOIN obs.site ON site.idsite = fiche.idsite
						WHERE idobser = :obs
						ORDER BY idfiche DESC
						LIMIT 10 ");
	$req->bindValue(':obs', $obs);
	$req->execute();
	$nbresultats = $req->rowCount();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return array($nbresultats, $resultat);		
}
function liste_fichec($obs)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$bdd->query("SET lc_time = 'fr_FR.UTF8'");
	$req = $bdd->prepare("SELECT DISTINCT idfiche, commune, site, to_char(date1, 'DD/MM/YYYY') AS date_fr, fiche.idsite, localisation FROM obs.fiche
						INNER JOIN referentiel.commune ON commune.codecom = fiche.codecom
						INNER JOIN obs.obs USING(idfiche)
						LEFT JOIN obs.site ON site.idsite = fiche.idsite
						WHERE idobser = :obs
						ORDER BY idfiche DESC
						LIMIT 10 ");
	$req->bindValue(':obs', $obs);
	$req->execute();
	$nbresultats = $req->rowCount();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return array($nbresultats, $resultat);		
}
if(isset($_POST['idobser']))
{	
	$idobser = htmlspecialchars($_POST['idobser']);	
	$json_emprise = file_get_contents('../../../emprise/emprise.json');
	$rjson_emprise = json_decode($json_emprise, true);
	$liste = ($rjson_emprise['emprise'] == 'fr' || $rjson_emprise['contour2'] == 'oui') ? liste_fiched($idobser) : liste_fichec($idobser);	

	$nb = $liste[0];
	$listefiche = null;
	
	if($nb > 0)
	{
		$listefiche .= '<table class="table table-hover table-sm">';
		if ($rjson_emprise['emprise'] == 'fr' or $rjson_emprise['contour2'] == 'oui')
		{
			foreach($liste[1] as $n)
			{
				$listefiche .= '<tr id="'.$n['idfiche'].'"><td><i class="fa fa-eye curseurlien voirliste" title="Voir la liste des espèces"></i>
								&nbsp;<i class="fa fa-plus-circle curseurlien text-success ajoutesp" title="Ajouter une espèce"></i>
								&nbsp;<i class="fa fa-pencil curseurlien text-warning modfiche" title="Modifier la fiche"></i></td>
								<td>'.$n['idfiche'].'</td><td>'.$n['date_fr'].'</td><td>'.$n['departement'].'</td><td>'.$n['commune'].'</td><td>'.$n['site'].'</td></tr>';
			}	
		}
		else
		{
			foreach($liste[1] as $n)
			{
				$listefiche .= '<tr id="'.$n['idfiche'].'"><td><i class="fa fa-eye curseurlien voirliste" title="Voir la liste des espèces"></i>
								&nbsp;<i class="fa fa-plus-circle curseurlien text-success ajoutesp" title="Ajouter une espèce"></i>
								&nbsp;<i class="fa fa-pencil curseurlien text-warning modfiche" title="Modifier la fiche"></i></td>
								<td>'.$n['idfiche'].'</td><td>'.$n['date_fr'].'</td><td>'.$n['commune'].'</td>';
				if($n['localisation'] == 2)
				{
					$listefiche .= '<td>localisation à la commune</td></tr>';
				}
				elseif($n['localisation'] == 1 && $n['idsite'] == 0)
				{
					$listefiche .= '<td>Nom du site non renseigné</td></tr>';
				}
				else
				{
					$listefiche .= '<td>'.$n['site'].'</td></tr>';
				}								
			}		
		}
		$listefiche .= '</table>';
	}
	else
	{
		$listefiche .= '<p>Vous avez aucune donnée actuellement dans la base. Si ce n\'est pas le cas, demandé à un administrateur de rattaché votre compte à vos observations</p>';
	}
	echo $listefiche;
}	