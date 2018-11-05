<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
function etudebyorg($organisme)
{
    $bdd = PDO2::getInstance();
    $bdd->query('SET NAMES "utf8"');
    $req = $bdd->prepare("SELECT idetude, etude FROM referentiel.etude
                                        left join referentiel.etude_organisme using (idetude)
                                        where etude_organisme.idorg = :idorg
                                        and masquer = 'oui' 
                                        ORDER BY etude ");
    $req->bindValue(':idorg', $organisme);
    $req->execute();
    $resultats = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $resultats;
}
$res = etudebyorg($_POST['organisme']);
echo json_encode($res);
?>