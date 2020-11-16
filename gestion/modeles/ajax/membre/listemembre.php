<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';
//M
function liste_membre()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT membre.idmembre, membre.nom, membre.prenom, droits, derniereconnection, mail, discipline, gestionobs, actif, latin, prefmembre.typedon, floutage, string_agg(organisme.organisme, ', ') AS organisme FROM site.membre
                                    LEFT JOIN site.validateur USING(idmembre)
                                    LEFT JOIN site.prefmembre USING(idmembre)
                                    LEFT JOIN referentiel.observateur ON observateur.idm = membre.idmembre
                                    LEFT JOIN referentiel.observateur_organisme ON observateur_organisme.idobser = observateur.idobser
                                    LEFT JOIN referentiel.organisme ON organisme.idorg = observateur_organisme.idorg
                                    group by membre.idmembre, membre.nom, membre.prenom, droits, derniereconnection, mail, discipline, gestionobs, actif, latin, prefmembre.typedon, floutage
                                    ORDER BY nom");
	$req->execute();
	$nbresultats = $req->rowCount();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return array($nbresultats, $resultat);
}

//C
$liste = liste_membre();
$nb = $liste[0];
$listemembre = null;
session_start();
//V
foreach($liste[1] as $n)
{
	$actif = ($n['actif'] == 1) ? 'oui' : 'non';
	if($n['floutage'] == 0 || empty($n['foutage'])) { $flou = 'aucun'; }
	if($n['floutage'] == 1) { $flou = 'commune'; }
	if($n['floutage'] == 2) { $flou = 'maille10'; }
	if($n['floutage'] == 3) { $flou = 'département'; }
	if (isset($_SESSION['virtuel']))
	{
		$listemembre .= '<tr><td class="text-center"><i class="fa fa-eye curseurlien text-primary" title="Se connecter en tant que" onclick="virtuel('.$n['idmembre'].')"></i></td>
				<td class="text-center"></td>
				<td class="text-center"></td><td><b>'.$n['nom'].'</b> '.$n['prenom'].'</td><td>'.$n['idmembre'].'</td>
				<td class="text-center">'.$n['droits'].'</td><td>'.$n['organisme'].'</td><td>'.$n['discipline'].'</td><td>'.$n['gestionobs'].'</td><td>'.$flou.'</td><td>'.$n['typedon'].'</td><td>'.$n['latin'].'</td><td>'.$n['derniereconnection'].'</td><td>'.$actif.'</td><td>'.$n['mail'].'</td></tr>';
	}
	else
	{
		$listemembre .= '<tr><td class="text-center"><i class="fa fa-eye curseurlien text-primary" title="Se connecter en tant que" onclick="virtuel('.$n['idmembre'].')"></i></td>
				<td class="text-center"><i class="fa fa-pencil curseurlien text-warning" title="Modifier/corriger" onclick="modifier('.$n['idmembre'].')"></i></td>
				<td class="text-center"><i class="fa fa-trash curseurlien text-danger" title="Supprimer ce membre" onclick="supmembre(id='.$n['idmembre'].')"></i></td><td><b>'.$n['nom'].'</b> '.$n['prenom'].'</td><td>'.$n['idmembre'].'</td>
				<td class="text-center">'.$n['droits'].'</td><td>'.$n['organisme'].'</td><td>'.$n['discipline'].'</td><td>'.$n['gestionobs'].'</td><td>'.$flou.'</td><td>'.$n['typedon'].'</td><td>'.$n['latin'].'</td><td>'.$n['derniereconnection'].'</td><td>'.$actif.'</td><td>'.$n['mail'].'</td></tr>';
	}	
	
}
$retour['nb'] = $nb;
$retour['liste'] = $listemembre;
header('Content-Type: application/json');
echo json_encode($retour);
