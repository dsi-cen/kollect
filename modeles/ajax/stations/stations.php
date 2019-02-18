<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
session_start();

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

function insere_site($codecom, $idcoord, $rqsite, $site, $typestation, $commentaire)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->prepare("INSERT INTO obs.site (idcoord, codecom, site, rqsite, typestation, commentaire) VALUES(:idcoord, :codecom, :site, :rqsite, :typestation, :commentaire) ");
    $req->bindValue(':codecom', $codecom);
    $req->bindValue(':idcoord', $idcoord);
    $req->bindValue(':rqsite', $rqsite);
    $req->bindValue(':site', $site);
    $req->bindValue(':typestation', $typestation);
    $req->bindValue(':commentaire', $commentaire);
    if ($req->execute())
    {
        $rowidstation = $bdd->lastInsertId('obs.site_idsite_seq');
    }
    $req->closeCursor();
    return $rowidstation ;
}

function insere_mare($idstation, $datedescription, $idtypemare, $idmenaces, $idenvironnement, $receaulibre, $idvegaquatique, $idvegsemiaquatique, $idvegrivulaire, $idtypeexutoire, $idtaillemare, $idcouleureau, $idnaturefond, $idrecberge, $idprofondeureau, $idalimeau, $commentairemare)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->prepare("INSERT INTO station.infosmare (idstation, datedescription, idtypemare, idmenaces, idenvironnement, receaulibre, idvegaquatique, idvegsemiaquatique, idvegrivulaire, idtypeexutoire, idtaillemare, idcouleureau, idnaturefond, idrecberge, idprofondeureau, idalimeau, commentaire)
                                    VALUES(:idstation, :datedescription, :idtypemare, :idmenaces,
                                    :idenvironnement, :receaulibre, :idvegaquatique, :idvegsemiaquatique, 
                                    :idvegrivulaire, :idtypeexutoire, :idtaillemare, :idcouleureau, 
                                    :idnaturefond, :idrecberge, :idprofondeureau, :idalimeau, :commentairemare)");
    $req->bindValue(':idstation', $idstation);
    $req->bindValue(':datedescription', $datedescription);
    $req->bindValue(':idtypemare', $idtypemare);
    $req->bindValue(':idmenaces', $idmenaces);
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
    $req->bindValue(':idalimeau', $idalimeau);
    $req->bindValue(':commentairemare', $commentairemare);
    $req->execute();
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

    // Si un objet est dessiné
    $geo = $_POST['typepoly'];

    $typestation = $_POST['typestation'];
    $commentaire = $_POST['commentaire'];

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
            $rowidstation = insere_site($codecom, $idcoord, $rqsite, $site, $typestation, $commentaire);
        }

        if ($typestation == 1) { // Si c'est du type 'mare'
            $datedescription = DateTime::createFromFormat('d/m/Y', $_POST['date']);
            $datedescription = $datedescription->format('Y-m-d');
            $idtypemare = $_POST['typemare'];
            $idenvironnement = $_POST['environnement'];
            $idmenaces = $_POST['menaces'];
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
            $idalimeau = $_POST['alimeau'];
            $commentairemare = $_POST['commentairemare'];
            insere_mare($rowidstation, $datedescription, $idtypemare, $idmenaces, $idenvironnement, $receaulibre, $idvegaquatique, $idvegsemiaquatique, $idvegrivulaire, $idtypeexutoire, $idtaillemare, $idcouleureau, $idnaturefond, $idrecberge, $idprofondeureau, $idalimeau, $commentairemare);
        }
        // Si présence de photo
        $photo = $_POST['aphoto'];
        if (isset($photo)) {
            if ($photo == 'oui') {
                if (!file_exists('../../../photo/P800/stations')) {
                    mkdir('../../../photo/P800/stations', 0777, true); // 777 temp
                    mkdir('../../../photo/P400/stations', 0777, true); // 777 temp
                    mkdir('../../../photo/P200/stations', 0777, true); // 777 temp
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
}
echo json_encode($retour);


