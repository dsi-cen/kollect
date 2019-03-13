<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
session_start();

function infomare($idmare)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");

        $sql = "SELECT site.idsite, site.site, site.typestation, site.commentaire, 
                TO_CHAR(infosmare.datedescription, 'DD/MM/YYYY') AS datedescription, infosmare.idtypemare, infosmare.idenvironnement, 
                infosmare.receaulibre, infosmare.idvegaquatique, infosmare.idvegsemiaquatique, infosmare.idvegrivulaire, 
                infosmare.idtypeexutoire, infosmare.idtaillemare, infosmare.idcouleureau, infosmare.idnaturefond, 
                infosmare.idrecberge, infosmare.idprofondeureau, infosmare.commentaire AS commentairemare
                FROM obs.site
                left join station.infosmare on site.idsite = infosmare.idstation
                WHERE site.idsite = :idmare
                ORDER by infosmare.datedescription DESC";
    $req = $bdd->prepare($sql) or die(print_r($bdd->errorInfo()));
    $req->bindValue(':idmare', $idmare);
    $req->execute();
    $info = $req->fetch(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $info;
}

function infostation($id)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");

    $sql = "SELECT site.idsite, site.site, site.typestation, site.commentaire              
            FROM obs.site
            WHERE site.idsite = :id";
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

    $info = infostation($id);

    echo json_encode($info);
}