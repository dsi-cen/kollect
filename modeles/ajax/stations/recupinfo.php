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

    $sql = "SELECT site.idsite, site.site, site.typestation, site.commentaire, codecom              
            FROM obs.site
            WHERE site.idsite = :id";
    $req = $bdd->prepare($sql) or die(print_r($bdd->errorInfo()));
    $req->bindValue(':id', $id);
    $req->execute();
    $info = $req->fetch(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $info;
}

function listephoto($id)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");

    $sql = "SELECT idphoto, idstation, photo.idobser, observateur, datephoto, photo.codecom, nomphoto, datesaisie, ordre
            FROM station.photo
            left join obs.site on station.photo.idstation = obs.site.idsite
            left join referentiel.observateur on station.photo.idobser = referentiel.observateur.idobser
            WHERE site.idsite = :id
            order by datephoto DESC";
    $req = $bdd->prepare($sql) or die(print_r($bdd->errorInfo()));
    $req->bindValue(':id', $id);
    $req->execute();
    $liste = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();
    $table = '<ul class="list-unstyled font12 mt-1">';
    foreach($liste as $photo) {
        $table .= '<li><a href="photo/P800/stations/' . $photo['nomphoto'] . '.jpg"><img src="photo/P200/stations/' . $photo['nomphoto'] . '.jpg" class="img-thumbnail mr-3"></a>';
        $table .= 'Date : ' . $photo['datephoto'] . ', par ' . $photo['observateur'];
        $table .= '<i class="ml-2 text-danger fa fa-trash curseurlien" title="Supprimer la photo" onclick="supphoto(' . $photo['idphoto'] . ')"></i></li>';
    }
    $table .= '</ul>';
    return $table;
}


if(isset($_POST['id'])) {
    $idm = (isset($_SESSION['idmorigin'])) ? $_SESSION['idmorigin'] : $_SESSION['idmembre'];
    $id = $_POST['id'];
    $info = [];
    $info = infostation($id);
    $info['photo'] = listephoto($id);

    echo json_encode($info);
}