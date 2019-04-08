<?php

include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
session_start();

function details_station($idstation)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $sql = "SELECT site, idtypestation, libtypestation, libidstatusstation, commentaire, site.membre.nom, site.membre.prenom, site.membre.idmembre
            FROM obs.site
            left join site.membre on site.membre.idmembre = obs.site.idmembre 
            left join referentiel_station.typestation on referentiel_station.typestation.idtypestation = obs.site.typestation
            left join referentiel_station.statusstation on referentiel_station.statusstation.idstatusstation = obs.site.idstatus
            where obs.site.idsite = :idstation ";
    $req = $bdd->prepare($sql) or die(print_r($bdd->errorInfo()));
    $req->bindValue(':idstation', $idstation);
    $req->execute();
    $detail = $req->fetch(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $detail;
}

function descriptionsmares_station($idstation)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $sql = "SELECT
    referentiel.observateur.observateur, referentiel.observateur.idm,
    datedescription, idinfosmare
FROM station.infosmare
left join referentiel.observateur on referentiel.observateur.idobser = station.infosmare.idobser
WHERE idstation = :idstation
ORDER BY datedescription DESC";
    $req = $bdd->prepare($sql) or die(print_r($bdd->errorInfo()));
    $req->bindValue(':idstation', $idstation);
    $req->execute();
    $detail = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $detail;
}

function description_detail_mare($idinfosmare)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $sql = "with select_alimeau as (
select infosmare.idinfosmare, string_agg(alimeau.libalimeau, ', ') as alimeau
FROM station.infosmare
left join station.infosmare_alimeau on infosmare_alimeau.idinfosmare =  infosmare.idinfosmare
left join referentiel_station.alimeau on alimeau.idalimeau = infosmare_alimeau.idalimeau
group by infosmare.idinfosmare
), 
select_menaces as (
select infosmare.idinfosmare, string_agg(menaces.libmenaces, ', ') as menaces
FROM station.infosmare
left join station.infosmare_menaces on infosmare_menaces.idinfosmare =  infosmare.idinfosmare
left join referentiel_station.menaces on menaces.idmenaces = infosmare_menaces.idmenaces
group by infosmare.idinfosmare
)
SELECT
    referentiel.observateur.observateur,
    datedescription,
    commentaire,
    plusobser,
    receaulibre,
    couleureau.libcouleureau,
    environnement.libenvironnement,
    naturefond.libnaturefond,
    profondeureau.libprofondeureau,
    recberge.librecberge,
    taillemare.libtaillemare,
    typeexutoire.libtypeexutoire,
    typemare.libtypemare,
    vegaquatique.libvegaquatique,
    vegrivulaire.libvegrivulaire,
    vegsemiaquatique.libvegsemiaquatique,
    select_alimeau.alimeau,
    select_menaces.menaces
FROM station.infosmare
left join referentiel.observateur on referentiel.observateur.idobser = station.infosmare.idobser
left join referentiel_station.couleureau on couleureau.idcouleureau =  infosmare.idcouleureau
left join referentiel_station.environnement on environnement.idenvironnement =  infosmare.idenvironnement
left join referentiel_station.naturefond on naturefond.idnaturefond =  infosmare.idnaturefond
left join referentiel_station.profondeureau on profondeureau.idprofondeureau =  infosmare.idprofondeureau
left join referentiel_station.recberge on recberge.idrecberge =  infosmare.idrecberge
left join referentiel_station.taillemare on taillemare.idtaillemare =  infosmare.idtaillemare
left join referentiel_station.typeexutoire on typeexutoire.idtypeexutoire =  infosmare.idtypeexutoire
left join referentiel_station.typemare on typemare.idtypemare =  infosmare.idtypemare
left join referentiel_station.vegaquatique on vegaquatique.idvegaquatique =  infosmare.idvegaquatique
left join referentiel_station.vegrivulaire on vegrivulaire.idvegrivulaire =  infosmare.idvegrivulaire
left join referentiel_station.vegsemiaquatique on vegsemiaquatique.idvegsemiaquatique =  infosmare.idvegsemiaquatique
left join select_alimeau on select_alimeau.idinfosmare = infosmare.idinfosmare
left join select_menaces on select_menaces.idinfosmare = infosmare.idinfosmare
WHERE infosmare.idinfosmare = :idinfosmare";
    $req = $bdd->prepare($sql) or die(print_r($bdd->errorInfo()));
    $req->bindValue(':idinfosmare', $idinfosmare);
    $req->execute();
    $detail = $req->fetch(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $detail;
}

function description_obser_principal_mare($idinfosmare)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $sql = "SELECT
    referentiel.observateur.idm,
    referentiel.observateur.prenom,
    referentiel.observateur.nom
FROM station.infosmare
left join referentiel.observateur on referentiel.observateur.idobser = station.infosmare.idobser
WHERE idinfosmare = :idinfosmare";
    $req = $bdd->prepare($sql) or die(print_r($bdd->errorInfo()));
    $req->bindValue(':idinfosmare', $idinfosmare);
    $req->execute();
    $detail = $req->fetch(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $detail;
}

function description_obser_autres_mare($idinfosmare)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $sql = "SELECT
    referentiel.observateur.idm,
    referentiel.observateur.prenom,
    referentiel.observateur.nom
FROM station.infosmare_plusobser
left join referentiel.observateur on referentiel.observateur.idobser = station.infosmare_plusobser.idobser
WHERE idinfosmare = :idinfosmare";
    $req = $bdd->prepare($sql) or die(print_r($bdd->errorInfo()));
    $req->bindValue(':idinfosmare', $idinfosmare);
    $req->execute();
    $detail = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $detail;
}

function photos_mare($idstation)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $sql = "SELECT idobser, datephoto, nomphoto
            FROM station.photo
            WHERE idstation = :idstation ";
    $req = $bdd->prepare($sql) or die(print_r($bdd->errorInfo()));
    $req->bindValue(':idstation', $idstation);
    $req->execute();
    $photo = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $photo;
}


if(isset($_POST['idstation'])){

    $idstation = $_POST['idstation'];
    $liste['detail'] = details_station($idstation);
    $liste['detailidm'] = $_SESSION['idmembre'];
    $descriptions = descriptionsmares_station($idstation);
    $liste['descriptions'] = "<ul>";
    // Simple liste pour panneau de gauche
    foreach($descriptions as $d){
        $liste['descriptions'] .= "<li>" . $d['datedescription'] . " par " . $d['observateur'] . ' <i onclick="minitable(' . $d['idinfosmare'] . ')" class="fa fa-eye text-info minitable"></i>';
        $_SESSION['idmembre'] == $d['idm'] ? $liste['descriptions'] .= '<a href="index.php?module=stations&amp;action=saisie&amp;moddescription=' . $d['idinfosmare'] . ' "> <i class="fa fa-pencil text-warning"></i> </a>' : null  ;
        $_SESSION['idmembre'] == $d['idm'] ? $liste['descriptions'] .= '<i onclick="deldescription(' . $d['idinfosmare'] . ')" class="fa fa-trash text-danger"></i> ' : null ;  ;
        $liste['descriptions'] .= "</li>" ;
    }
    $liste['descriptions'] .= "</ul>";

    // Photo de la station
    $photos = photos_mare($idstation);
    if(count($photos) != 0){
        $gallery = "";
        foreach($photos as $photo){
            $gallery .= '<a href="photo/P800/stations/' . $photo['nomphoto'] . '.jpg"><img src="photo/P200/stations/' . $photo['nomphoto'] . '.jpg" class="img-thumbnail mr-3"></a>';
            $gallery .= $photo['datephoto'];
        }
    }

    $liste['gallery'] = $gallery ;

    echo json_encode($liste);
}

elseif (isset($_POST['idinfosmare'])){

    $idinfosmare = $_POST['idinfosmare'];
    $description = description_detail_mare($idinfosmare);
    $idobseror = description_obser_principal_mare($idinfosmare);

    // Avatar de l'observateur principal
    $favatar = '../../../photo/avatar/'.$idobseror['prenom'].''.$idobseror['idm'].'.jpg';
    $liste['description'] = (file_exists($favatar)) ? '<img src="photo/avatar/'.$idobseror['prenom'].''.$idobseror['idm'].'.jpg" width=24 height=24 alt="" class="rounded-circle"/> '.$idobseror['prenom'].' '.$idobseror['nom'] . '<br/>' : '<img src="photo/avatar/usera.jpg" width=24 height=24 alt="" class="img-circle"/> '.$idobseror['prenom'].' '.$idobseror['nom'] . '<br/>';

    // Avatar des observateurs secondaires
    if($description['plusobser'] == "oui"){

        $autresobser = description_obser_autres_mare($idinfosmare);

        foreach($autresobser as $autre){
            $favatar = '../../../photo/avatar/'.$autre['prenom'].''.$autre['idm'].'.jpg';
            $liste['description'] .= (file_exists($favatar)) ? '<img src="photo/avatar/'.$autre['prenom'].''.$autre['idm'].'.jpg" width=24 height=24 alt="" class="rounded-circle"/> '.$autre['prenom'].' '.$autre['nom'] . '<br/>' : '<img src="photo/avatar/usera.jpg" width=24 height=24 alt="" class="img-circle"/> '.$autre['prenom'].' '.$autre['nom'] . '<br/>';
            $favatar = null;
        }
    }

    $liste['description'] .= '<table class="table table-striped">
                              <thead>
                                <tr>
                                  <th scope="col">Paramètre</th>
                                  <th scope="col">Observation</th>
                                </tr>
                              </thead>
                              <tbody>';

    // Tableau pour le panneau de droite

    $liste['description'] .= "<tr>
                                  <td>Couleur de l'eau</td>
                                  <td>" . $description['libcouleureau'] . '</td>
                                </tr>';

        $liste['description'] .= "<tr>
                                  <td>Recouvrement en eau libre</td>
                                  <td>" . $description['receaulibre'] . ' %</td>
                                </tr>';

            $liste['description'] .= "<tr>
                                  <td>Environnement</td>
                                  <td>" . $description['libenvironnement'] . '</td>
                                </tr>';
            $liste['description'] .= "<tr>
                                  <td>Nature du fond</td>
                                  <td>" . $description['libnaturefond'] . '</td>
                                </tr>';
            $liste['description'] .= "<tr>
                                  <td>Profondeur de l'eau maximale observée</td>
                                  <td>" . $description['libprofondeureau'] . '</td>
                                </tr>';
            $liste['description'] .= "<tr>
                                  <td>Recouvrement des berges en pente douce</td>
                                  <td>" . $description['librecberge'] . '</td>
                                </tr>';
            $liste['description'] .= "<tr>
                                  <td>Taille de la mare</td>
                                  <td>" . $description['libtaillemare'] . '</td>
                                </tr>';
            $liste['description'] .= "<tr>
                                  <td>Type d'exutoire</td>
                                  <td>" . $description['libtypeexutoire'] . '</td>
                                </tr>';
            $liste['description'] .= "<tr>
                                  <td>Végétation aquatique</td>
                                  <td>" . $description['libvegaquatique'] . '</td>
                                </tr>';
            $liste['description'] .= "<tr>
                                  <td>Végétation semi-aquatique</td>
                                  <td>" . $description['libvegsemiaquatique'] . '</td>
                                </tr>';
            $liste['description'] .= "<tr>
                                  <td>Végétation rivulaire</td>
                                  <td>" . $description['libvegrivulaire'] . '</td>
                                </tr>';
            $liste['description'] .= "<tr><td>Menaces</td>
                                              <td>" . $description['menaces'] . '</td>
                                            </tr>';
            $liste['description'] .= "<tr><td>Alimentation en eau</td>
                                              <td>" . $description['alimeau'] . '</td>
                                            </tr>';
    $liste['description'] .= '</tbody></table>';

    // $liste['description'] = $description;

    echo json_encode($liste);
}