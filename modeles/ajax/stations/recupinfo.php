<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
session_start();

function infomare($id)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");

        $sql = "SELECT site.idsite, site.site, site.idtypestation, site.commentaire, 
                TO_CHAR(infosmare.datedescription, 'DD/MM/YYYY') AS datedescription, infosmare.idtypemare, infosmare.idmenaces, infosmare.idenvironnement, 
                infosmare.receaulibre, infosmare.idvegaquatique, infosmare.idvegsemiaquatique, infosmare.idvegrivulaire, 
                infosmare.idtypeexutoire, infosmare.idtaillemare, infosmare.idcouleureau, infosmare.idnaturefond, 
                infosmare.idrecberge, infosmare.idprofondeureau, infosmare.idalimeau, infosmare.commentaire AS commentairemare
                FROM obs.site
                left join station.infosmare on site.idsite = infosmare.idstation
                WHERE site.idsite = :id
                ORDER by infosmare.datedescription DESC";
    $req = $bdd->prepare($sql) or die(print_r($bdd->errorInfo()));
    $req->bindValue(':id', $id);
    $req->execute();
    $info = $req->fetch(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $info;
}

if(isset($_POST['id'])) {
    $idm = (isset($_SESSION['idmorigin'])) ? $_SESSION['idmorigin'] : $_SESSION['idmembre'];
    $id = $_POST['id'];

    $info = infomare($id);

    echo json_encode($info);
}