<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
session_start();

function observateur2($term)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->prepare("SELECT observateur, idobser FROM referentiel.observateur WHERE observateur ILIKE :recherche") or die(print_r($bdd->errorInfo()));
    $req->bindValue(':recherche', ''.$term.'%');
    $req->execute();
    $resultat = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $resultat;
}

function insere_obser($listobser, $idinfosmare)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    foreach($listobser as $idobser){
        $req = $bdd->prepare("INSERT INTO station.infosmare_plusobser (idinfosmare, idobser) 
                                        VALUES (:idinfosmare, :idobser) ") or die(print_r($bdd->errorInfo()));
        $req->bindValue(':idinfosmare', $idinfosmare);
        $req->bindValue(':idobser', $idobser);
        $req->execute();
        $req->closeCursor();
    }
}

function insere_coordonnee($x,$y,$alt,$lat,$lng,$l93,$utm,$utm1,$l935)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->prepare("INSERT INTO obs.coordonnee (x, y, altitude, lat, lng, codel93, utm, utm1, codel935) VALUES(:x, :y, :alt, :lat, :lng, :l93, :utm, :utm1, :l935) ") or die(print_r($bdd->errorInfo()));
    $req->bindValue(':x', $x);
    $req->bindValue(':y', $y);
    $req->bindValue(':alt', $alt);
    $req->bindValue(':lat', $lat);
    $req->bindValue(':lng', $lng);
    $req->bindValue(':l93', $l93);
    $req->bindValue(':utm', $utm);
    $req->bindValue(':utm1', $utm1);
    $req->bindValue(':l935', $l935);
    if ($req->execute())
    {
        $idcoord = $bdd->lastInsertId('obs.coordonnee_idcoord_seq');
    }
    $req->closeCursor();
    return $idcoord;
}

function update_coordonnee($codesite, $x,$y,$alt,$lat,$lng,$l93,$utm,$utm1,$l935)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->prepare("SELECT idcoord
                                    FROM obs.site
                                    where idsite = :codesite") or die(print_r($bdd->errorInfo()));
    $req->bindValue(':codesite', $codesite);
    $req->execute();
    $idcoord = $req->fetch(PDO::FETCH_ASSOC);

    $req = $bdd->prepare("UPDATE obs.coordonnee SET x = :x, 
                                                              y = :y, 
                                                              altitude = :alt, 
                                                              lat = :lat, 
                                                              lng = :lng, 
                                                              codel93 = :l93, 
                                                              utm = :utm, 
                                                              utm1 = :utm1, 
                                                              codel935 = :l935
                                                              WHERE idcoord = :idcoord ") or die(print_r($bdd->errorInfo()));
    $req->bindValue(':x', $x);
    $req->bindValue(':y', $y);
    $req->bindValue(':alt', $alt);
    $req->bindValue(':lat', $lat);
    $req->bindValue(':lng', $lng);
    $req->bindValue(':l93', $l93);
    $req->bindValue(':utm', $utm);
    $req->bindValue(':utm1', $utm1);
    $req->bindValue(':l935', $l935);
    $req->bindValue(':idcoord', $idcoord['idcoord']);
    $req->execute();
    $req->closeCursor();
    return $idcoord['idcoord'];
}

function inser_coordgeo($idcoord,$geo,$poly)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->prepare("INSERT INTO obs.coordgeo (idcoord, geo, poly) VALUES(:idcoord, :geo, :poly) ") or die(print_r($bdd->errorInfo()));
    $req->bindValue(':idcoord', $idcoord);
    $req->bindValue(':geo', $geo);
    $req->bindValue(':poly', $poly);
    $req->execute();
    $req->closeCursor();
}

function update_coordgeo($codesite,$geo,$poly)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");

    $req = $bdd->prepare("SELECT idcoord
                                    FROM obs.site
                                    where idsite = :codesite") or die(print_r($bdd->errorInfo()));
    $req->bindValue(':codesite', $codesite);
    $req->execute();
    $idcoord = $req->fetch(PDO::FETCH_ASSOC);
    $req = $bdd->prepare("SELECT idcoord
                                    FROM obs.coordgeo
                                    where idcoord = :idcoord") or die(print_r($bdd->errorInfo()));
    $req->bindValue(':idcoord', $idcoord['idcoord']);
    $req->execute();
    $test = $req->fetch(PDO::FETCH_ASSOC);

    if ( empty($test['idcoord']) && !empty($geo) ) { // Si on passe du point à un polygone, on crée la geométrie
        $req = $bdd->prepare("INSERT INTO obs.coordgeo (idcoord, geo, poly) VALUES(:idcoord, :geo, :poly) ") or die(print_r($bdd->errorInfo()));
        $req->bindValue(':idcoord', $idcoord['idcoord']);
        $req->bindValue(':geo', $geo);
        $req->bindValue(':poly', $poly);
        $req->execute();
    }

    elseif ( !empty($test['idcoord']) && !empty($geo) ) { // Si on modifie un polygone, on update la ligne
        $req = $bdd->prepare("UPDATE obs.coordgeo SET geo = :geo, poly = :poly WHERE idcoord = :idcoord") or die(print_r($bdd->errorInfo()));
        $req->bindValue(':idcoord', $idcoord['idcoord']);
        $req->bindValue(':geo', $geo);
        $req->bindValue(':poly', $poly);
        $req->execute();
    }

    elseif ( !empty($test['idcoord']) && empty($geo) ) { // Si on passe d'un polygone à un point, on supprime la géométrie
        $req = $bdd->prepare("DELETE FROM obs.coordgeo WHERE idcoord = :idcoord") or die(print_r($bdd->errorInfo()));
        $req->bindValue(':idcoord', $idcoord['idcoord']);
        $req->execute();
    }
    $req->closeCursor();
}

function insere_site($codecom, $idcoord, $rqsite, $site, $typestation, $commentaire, $idm, $idparent, $idstatus)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->prepare("INSERT INTO obs.site (idcoord, codecom, site, rqsite, typestation, commentaire, idmembre, wsite, idparent, idstatus) VALUES(:idcoord, :codecom, :site, :rqsite, :typestation, :commentaire, :idm, :wsite, :idparent, :idstatus) ");
    $req->bindValue(':codecom', $codecom);
    $req->bindValue(':idcoord', $idcoord);
    $req->bindValue(':rqsite', $rqsite);
    $req->bindValue(':site', $site);
    $req->bindValue(':typestation', $typestation);
    $req->bindValue(':commentaire', $commentaire);
    $req->bindValue(':idm', $idm);
    $req->bindValue(':wsite', "oui");
    $req->bindValue(':idparent', $idparent == 0 ? $idparent = NULL : $idparent );
    $req->bindValue(':idstatus', $idstatus );
    if ($req->execute())
    {
        $rowidstation = $bdd->lastInsertId('obs.site_idsite_seq');
    }
    $req->closeCursor();
    return $rowidstation ;
}

function desactiver_site_parent($codesite)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->prepare("UPDATE obs.site SET wsite = :wsite
                                                        WHERE idsite = :idsite");
    $req->bindValue(':idsite', $codesite);
    $req->bindValue(':wsite', "non");
    $req->execute();
    $req->closeCursor();
}

function update_site($codesite, $codecom, $site, $commentaire, $idstatus)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->prepare("UPDATE obs.site SET codecom = :codecom, 
                                                        site = :site, 
                                                        rqsite = :rqsite, 
                                                        commentaire = :commentaire,
                                                        idstatus = :idstatus
                                                        WHERE idsite = :idsite");
    $req->bindValue(':codecom', $codecom);
    $req->bindValue(':rqsite', "MAJ de la station");
    $req->bindValue(':site', $site);
    $req->bindValue(':commentaire', $commentaire);
    $req->bindValue(':idsite', $codesite);
    $req->bindValue(':idstatus', $idstatus);
    $req->execute();
    $req->closeCursor();
}

function insere_mare($idstation, $datedescription, $idtypemare, $idenvironnement, $receaulibre, $idvegaquatique, $idvegsemiaquatique, $idvegrivulaire, $idtypeexutoire, $idtaillemare, $idcouleureau, $idnaturefond, $idrecberge, $idprofondeureau, $commentairemare, $idmembre, $idobser, $plusobser)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->prepare("INSERT INTO station.infosmare (idstation, datedescription, idtypemare, idenvironnement, receaulibre, idvegaquatique, idvegsemiaquatique, idvegrivulaire, idtypeexutoire, idtaillemare, idcouleureau, idnaturefond, idrecberge, idprofondeureau, commentaire, idmembre, idobser, plusobser)
                                    VALUES(:idstation, :datedescription, :idtypemare,
                                    :idenvironnement, :receaulibre, :idvegaquatique, :idvegsemiaquatique, 
                                    :idvegrivulaire, :idtypeexutoire, :idtaillemare, :idcouleureau, 
                                    :idnaturefond, :idrecberge, :idprofondeureau, :commentairemare,
                                    :idmembre, :idobser, :plusobser)");
    $req->bindValue(':idstation', $idstation);
    $req->bindValue(':datedescription', $datedescription);
    $req->bindValue(':idtypemare', $idtypemare);
    $req->bindValue(':idenvironnement', $idenvironnement);
    $req->bindValue(':receaulibre', $receaulibre == "" ? $receaulibre = NULL : $receaulibre );
    $req->bindValue(':idvegaquatique', $idvegaquatique);
    $req->bindValue(':idvegsemiaquatique', $idvegsemiaquatique);
    $req->bindValue(':idvegrivulaire', $idvegrivulaire);
    $req->bindValue(':idtypeexutoire', $idtypeexutoire);
    $req->bindValue(':idtaillemare', $idtaillemare);
    $req->bindValue(':idcouleureau', $idcouleureau);
    $req->bindValue(':idnaturefond', $idnaturefond);
    $req->bindValue(':idrecberge', $idrecberge);
    $req->bindValue(':idprofondeureau', $idprofondeureau);
    $req->bindValue(':commentairemare', $commentairemare);
    $req->bindValue(':idmembre', $idmembre);
    $req->bindValue(':idobser', $idobser);
    $req->bindValue(':plusobser', $plusobser);
    if ($req->execute())
    {
        $idinfosmare = $bdd->lastInsertId('station.infosmare_idinfosmare_seq');
    }
    $req->closeCursor();
    return $idinfosmare ;
}

function insere_menaces($idinfosmare, $idmenaces)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    foreach ($idmenaces as $menace){
        $req = $bdd->prepare("INSERT INTO station.infosmare_menaces (idinfosmare, idmenaces)
                                        VALUES (:idinfosmare, :idmenaces) ");
        $req->bindValue(':idinfosmare', $idinfosmare);
        $req->bindValue(':idmenaces', $menace);
        $req->execute();
    }
    $req->closeCursor();
}

function insere_alimeau($idinfosmare, $idalimeau)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    foreach ($idalimeau as $alimeau){
        $req = $bdd->prepare("INSERT INTO station.infosmare_alimeau (idinfosmare, idalimeau)
                                        VALUES (:idinfosmare, :alimeau) ");
        $req->bindValue(':idinfosmare', $idinfosmare);
        $req->bindValue(':alimeau', $alimeau);
        $req->execute();
    }
    $req->closeCursor();
}

function insere_biogeo($x,$y,$idcoord)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->prepare("SELECT idbiogeo FROM referentiel.refbiogeo WHERE poly @> :recherche ") or die(print_r($bdd->errorInfo()));
    $req->bindValue(':recherche', '('.$x.','.$y.')');
    $req->execute();
    $idbiogeo = $req->fetchColumn();
    $req->closeCursor();
    $req = $bdd->prepare("INSERT INTO obs.biogeo (idcoord, idbiogeo) VALUES(:idcoord, :idbiogeo) ") or die(print_r($bdd->errorInfo()));
    $req->bindValue(':idcoord', $idcoord);
    $req->bindValue(':idbiogeo', $idbiogeo);
    $req->execute();
    $req->closeCursor();
}

function insere_photo($idstation, $idobser, $datephoto, $codecom, $nomphoto, $datesaisie, $ordre)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->prepare("INSERT INTO station.photo (idstation, idobser, datephoto, codecom, nomphoto, datesaisie, ordre) VALUES(:idstation, :idobser, :datephoto, :codecom, :nomphoto, :datesaisie, :ordre) ") or die(print_r($bdd->errorInfo()));
    $req->bindValue(':idstation', $idstation);
    $req->bindValue(':idobser', $idobser);
    $req->bindValue(':datephoto', $datephoto);
    $req->bindValue(':codecom', $codecom);
    $req->bindValue(':nomphoto', $nomphoto);
    $req->bindValue(':datesaisie', $datesaisie);
    $req->bindValue(':ordre', $ordre);
    $req->execute();
    $req->closeCursor();
}

if(isset($_POST['codesite'])) {
    $idm = (isset($_SESSION['idmorigin'])) ? $_SESSION['idmorigin'] : $_SESSION['idmembre'];

    $idobseror = $_POST['idobseror'];
    $idobserorl[] = $_POST['idobseror']; // temp array
    $listobser = $_POST['idobser'];
    $listobser = explode(",",$listobser);
    $listobser = array_map('trim', $listobser);
    $listobser = array_unique($listobser);
    $listobser = array_diff( $listobser, $idobserorl); // Supp de l'observateur principal
    count($listobser) == 0 ? $plusobser = "non" : $plusobser = "oui" ;

    // Informations géographiques
    $codecom = $_POST['codecom']; // Code commune
    $x = $_POST['x'];
    $y = $_POST['y'];
    $alt = (!empty($_POST['alt'])) ? $_POST['alt'] : null;
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $l93 = $_POST['l93'];
    $l935 = $_POST['l935'];
    $utm = $_POST['utm'];
    $utm1 = $_POST['utm1'];
    $site = htmlspecialchars($_POST['lieub']);
    $idparent = $_POST['parent'] ;

    // Si un objet est dessiné
    $geo = $_POST['typepoly'];

    $typestation = $_POST['typestation'];
    $commentaire = $_POST['commentaire'];

    // Données mare
    $idtypemare = $_POST['typemare'];
    $idenvironnement = $_POST['environnement'];
    $receaulibre = $_POST['eaulibre'];
    $idvegaquatique = $_POST['vegaquatique'];
    $idvegsemiaquatique = $_POST['vegsemiaquatique'];
    $idvegrivulaire = $_POST['vegrivulaire'];
    $idtypeexutoire = $_POST['typeexutoire'];
    $idtaillemare = $_POST['taillemare'];
    $idcouleureau = $_POST['couleureau'];
    $idnaturefond = $_POST['naturefond'];
    $idrecberge = $_POST['recouvrberge'];
    $idprofondeureau = $_POST['profondeureau'];
    $commentairemare = $_POST['commentairemare'];
    $idmenaces = $_POST['menaces'];
    $idalimeau = $_POST['alimeau'];
    $idstatus = $_POST['libstatus'];

    // Date description
    if (!empty($_POST['date'])){
        $datedescription = DateTime::createFromFormat('d/m/Y', $_POST['date']);
        $datedescription = $datedescription->format('Y-m-d');
    }

    if ($_POST['codesite'] == 'Nouv') { //Nouveau site.

        $idcoord = insere_coordonnee($x, $y, $alt, $lat, $lng, $l93, $utm, $utm1, $l935);

        if (!empty($geo)) {
            inser_coordgeo($idcoord, $geo);
        }

        // Pour utilisation biogeo
        // if($_POST['biogeo'] == 'oui') { insere_biogeo($x,$y,$idcoord); }

        //Insertion site
        if ($site != '') { // Création du site
            $rqsite = 'Insertion via gestion des stations. idm - ' . $idm;
            $rowidstation = insere_site($codecom, $idcoord, $rqsite, $site, $typestation, $commentaire, $idm, $idparent, $idstatus);
            $idparent != 0 ? desactiver_site_parent($idparent) : null;
        }

        if ($typestation == 1) { // Si c'est du type 'mare'
            // Insérer les informations générales
            $idinfosmare = insere_mare($rowidstation, $datedescription, $idtypemare, $idenvironnement, $receaulibre, $idvegaquatique, $idvegsemiaquatique, $idvegrivulaire, $idtypeexutoire, $idtaillemare, $idcouleureau, $idnaturefond, $idrecberge, $idprofondeureau, $commentairemare, $idm, $idobseror, $plusobser);
            // Insérer les menaces
            insere_menaces($idinfosmare, $idmenaces);
            // Insérer les alimentations en eau
            insere_alimeau($idinfosmare, $idalimeau);
            // Insérer les observateurs si plusieurs "oui"
            if ($plusobser == "oui") {
                insere_obser($listobser, $idinfosmare);
            }
        }
        // Si présence de photo
        $photo = $_POST['aphoto'];
        if (isset($photo)) {
            if ($photo == 'oui') {
                if (!file_exists('../../../photo/P800/stations')) {
                    mkdir('../../../photo/P800/stations', 0764, true); // 777 temp
                    mkdir('../../../photo/P400/stations', 0764, true); // 777 temp
                    mkdir('../../../photo/P200/stations', 0764, true); // 777 temp
                }

                $dossier_destination1 = '../../../photo/P800/stations/';
                $dossier_destination2 = '../../../photo/P400/stations/';
                $dossier_destination3 = '../../../photo/P200/stations/';
                $nomphoto = $codecom . time();
                $nomfichier = $nomphoto . '.jpg';
                $img = $_POST['imagedata'];
                $exp = explode(',', $img);
                $data = base64_decode($exp[1]);
                $file = $dossier_destination1 . $nomfichier;
                if (file_put_contents($file, $data) !== false) {
                    require '../../../lib/RedimImageJpg.php';
                    $orien = $_POST['orien'];
                    $repSource = $dossier_destination1;
                    $repDest = $dossier_destination2;
                    $redim = ($orien == 'paysage') ? fctredimimage(400, 266, $repDest, '', $repSource, $nomfichier) : fctredimimage(200, 300, $repDest, '', $repSource, $nomfichier);
                    $repDest = $dossier_destination3;
                    $redim = ($orien == 'paysage') ? fctredimimage(200, 133, $repDest, '', $repSource, $nomfichier) : fctredimimage(100, 150, $repDest, '', $repSource, $nomfichier);
                    if ($redim == true) {
                        //$ordre = ($nbphoto == 0) ? 1 : $nbphoto + 1 ;
                        $ordre = 1; // Fonction à faire pour compter les images
                        $datesaisie = date("Y-m-d H:i:s");
                        $copyright = $_POST['copyright'];
                        insere_photo($rowidstation, $copyright, $datedescription, $codecom, $nomphoto, $datesaisie, $ordre);
                    }
                }
            }
        }
    }

    if ($_POST['codesite'] != 'Nouv') { // Modification de la station
        $codesite = $_POST['codesite'] ;
        // Si présence de photo
        $photo = $_POST['aphoto'];
        if (isset($photo)) {
            if ($photo == 'oui') {
                if (!file_exists('../../../photo/P800/stations')) {
                    mkdir('../../../photo/P800/stations', 0764, true); // 777 temp
                    mkdir('../../../photo/P400/stations', 0764, true); // 777 temp
                    mkdir('../../../photo/P200/stations', 0764, true); // 777 temp
                }

                $dossier_destination1 = '../../../photo/P800/stations/';
                $dossier_destination2 = '../../../photo/P400/stations/';
                $dossier_destination3 = '../../../photo/P200/stations/';
                $nomphoto = $codecom . time();
                $nomfichier = $nomphoto . '.jpg';
                $img = $_POST['imagedata'];
                $exp = explode(',', $img);
                $data = base64_decode($exp[1]);
                $file = $dossier_destination1 . $nomfichier;
                if (file_put_contents($file, $data) !== false) {
                    require '../../../lib/RedimImageJpg.php';
                    $orien = $_POST['orien'];
                    $repSource = $dossier_destination1;
                    $repDest = $dossier_destination2;
                    $redim = ($orien == 'paysage') ? fctredimimage(400, 266, $repDest, '', $repSource, $nomfichier) : fctredimimage(200, 300, $repDest, '', $repSource, $nomfichier);
                    $repDest = $dossier_destination3;
                    $redim = ($orien == 'paysage') ? fctredimimage(200, 133, $repDest, '', $repSource, $nomfichier) : fctredimimage(100, 150, $repDest, '', $repSource, $nomfichier);
                    if ($redim == true) {
                        //$ordre = ($nbphoto == 0) ? 1 : $nbphoto + 1 ;
                        $ordre = 1; // Fonction à faire pour compter les images
                        $datesaisie = date("Y-m-d H:i:s");
                        $copyright = $_POST['copyright'];

                        $dateprisedevue = DateTime::createFromFormat('d/m/Y', $_POST['dateprisedevue']);
                        $dateprisedevue= $dateprisedevue->format('Y-m-d');

                        insere_photo($codesite, $copyright, $dateprisedevue, $codecom, $nomphoto, $datesaisie, $ordre);
                    }
                }
            }
        }

        if( $_POST['adddescription'] == "oui" ){ // Ajout d'une description
            // Insert description
            $rowidstation = $_GET['addto'];
            if ($typestation == 1) { // Si c'est du type 'mare'
                // Insérer les informations générales
                $idinfosmare = insere_mare($codesite, $datedescription, $idtypemare, $idenvironnement, $receaulibre, $idvegaquatique, $idvegsemiaquatique, $idvegrivulaire, $idtypeexutoire, $idtaillemare, $idcouleureau, $idnaturefond, $idrecberge, $idprofondeureau, $commentairemare, $idm, $idobseror, $plusobser);
                // Insérer les menaces
                insere_menaces($idinfosmare, $idmenaces);
                // Insérer les alimentations en eau
                insere_alimeau($idinfosmare, $idalimeau);
                // Insérer les observateurs si plusieurs "oui"
                if ($plusobser == "oui") {
                    insere_obser($listobser, $idinfosmare);
                }
            }
        }
        else {
            // Update des géométrie, /!\ toutes les obs sont affectées
            update_coordonnee($codesite, $x,$y,$alt,$lat,$lng,$l93,$utm,$utm1,$l935);
            update_coordgeo($codesite,$geo,$poly);
            update_site($codesite, $codecom, $site, $commentaire, $idstatus);
        }
    }
}
echo json_encode($retour);