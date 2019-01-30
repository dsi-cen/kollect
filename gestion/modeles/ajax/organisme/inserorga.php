<?php 
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function insere_orga($orga,$descri,$etude)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO referentiel.organisme (organisme, descri) VALUES (:orga, :descri) ");
	$req->bindValue(':orga', $orga);
	$req->bindValue(':descri', $descri);
	$vali = ($req->execute()) ? 'oui' : 'non';
	if ($vali == 'oui') {
        $last_id = $bdd->lastInsertId();
        $etudelist = json_decode($etude, true);
        foreach ($etudelist as $etude => $value) {
            $req = $bdd->prepare("INSERT INTO referentiel.etude_organisme (idetude, idorg) VALUES(:idetude, :idorg) ");
            $req->bindValue(':idorg', $last_id);
            $req->bindValue(':idetude', $value);
        }
    }
	$req->closeCursor();
	return $vali;	
}

if(isset($_POST['orga']) AND isset($_POST['descri']))
{
	$orga = $_POST['orga'];
	$descri = $_POST['descri'];
    $etude = $_POST['etude'];
	
	$vali = insere_orga($orga,$descri,$etude);
	
	$retour['statut'] = ($vali == 'oui') ? 'Ok' : 'Erreur ! Problème lors insertion observateur';
}
else 
{ $retour['statut'] = 'Tous les champs ne sont pas parvenus'; }
echo json_encode($retour);