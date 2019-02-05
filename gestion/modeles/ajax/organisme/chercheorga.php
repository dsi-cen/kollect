<?php 
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';
function rechercher($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idorg, organisme, descri FROM referentiel.organisme WHERE idorg = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id);
	$req->execute();
	$obser = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $obser;
}

function etudeslist($id)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->prepare("SELECT referentiel.etude.idetude, referentiel.etude_organisme.idorg, referentiel.etude.etude 
                                    FROM referentiel.etude
                                    LEFT OUTER JOIN referentiel.etude_organisme on referentiel.etude_organisme.idetude = referentiel.etude.idetude
                                    AND referentiel.etude_organisme.idorg = :id WHERE referentiel.etude.idetude !=0 ") or die(print_r($bdd->errorInfo()));
    $req->bindParam(":id",$id);
    $req->execute();
    $rows = $req->fetchAll(PDO::FETCH_ASSOC);
    $list = "<select id='etudelist' multiple='multiple'>";
    foreach ($rows as $row => $ligne) {
        $list .= '<option value="'. $ligne['idetude'] . '" ';
        if ($ligne['idorg']){
            $list .= 'selected >';
        } else {$list .= '>';}
        $list .= $ligne['etude']. '</option>';
    }
    $list .= '</select>';
    $req->closeCursor();
    return ($list);
}

if(isset($_POST['id']))
{
	$id = $_POST['id'];
	$orga = rechercher($id);
	
	if($orga['idorg'] == $id)
	{	
		$retour['statut'] = 'Ok';
		$retour['info'] = $orga;
        $retour['etudelist'] = etudeslist($id);
	}
	else
	{
		$retour['statut'] = 'Impossible de récupérer les info de cet organisme';
	}	
	echo json_encode($retour);
}