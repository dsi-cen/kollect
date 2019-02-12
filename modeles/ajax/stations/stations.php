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

function insere_site($codecom, $idcoord, $rqsite, $site)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->prepare("INSERT INTO obs.site (idcoord, codecom, site, rqsite) VALUES(:idcoord, :codecom, :site, :rqsite) ");
    $req->bindValue(':codecom', $codecom);
    $req->bindValue(':idcoord', $idcoord);
    $req->bindValue(':rqsite', $rqsite);
    $req->bindValue(':site', $site);
    if ($req->execute())
    {
        $idsite = $bdd->lastInsertId('obs.site_idsite_seq');
    }
    $req->closeCursor();
    return $idsite;
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

function insere_photo($idobser,$datep,$codecom,$nomphoto,$dates,$obser)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->prepare("INSERT INTO site.photo (cdnom, idobser, datephoto, codecom, stade, nomphoto, datesaisie, sexe, observatoire, idobs, ordre) VALUES(:cdnom, :idobser, :datep, :codecom, :stade, :nom, :dates, :sexe, :obser, :idobs, :ordre) ") or die(print_r($bdd->errorInfo()));
    $req->bindValue(':cdnom', NULL);
    $req->bindValue(':idobser', $idobser);
    $req->bindValue(':datep', $datep);
    $req->bindValue(':codecom', $codecom);
    $req->bindValue(':stade', NULL);
    $req->bindValue(':nom', $nomphoto);
    $req->bindValue(':dates', $dates);
    $req->bindValue(':sexe', NULL);
    $req->bindValue(':obser', $obser);
    $req->bindValue(':idobs', NULL);
    $req->bindValue(':ordre', NULL);
    $req->execute();
    $req->closeCursor();
}

if(isset($_POST['codesite'])) {
    $idm = (isset($_SESSION['idmorigin'])) ? $_SESSION['idmorigin'] : $_SESSION['idmembre'];
    //précision, coordonnées, site
    if ($_POST['codesite'] == 'Nouv') { //Nouveau site.
            $codecom = $_POST['codecom']; // Code commune
            $nomstation = $_POST['nomstation']; // Nom de la station
            //Insertion coordonnée
            $x = $_POST['x'];
            $y = $_POST['y'];
            $alt = (!empty($_POST['alt'])) ? $_POST['alt'] : null;
            $lat = $_POST['lat'];
            $lng = $_POST['lng'];
            $l93 = $_POST['l93'];
            $l935 = $_POST['l935'];
            $utm = $_POST['utm'];
            $utm1 = $_POST['utm1'];

            $idcoord = insere_coordonnee($x, $y, $alt, $lat, $lng, $l93, $utm, $utm1, $l935);

            // Si un objet est dessiné
            $geo = $_POST['typepoly'];
            if (!empty($geo)) {
                inser_coordgeo($idcoord, $geo);
            }

            // Pour utilisation biogeo
            // if($_POST['biogeo'] == 'oui') { insere_biogeo($x,$y,$idcoord); }
            //Insertion site

            $site = htmlspecialchars($_POST['nomstation']);
            if ($site != '') {
                $rqsite = 'Insertion via gestion des stations. idm - ' . $idm;
                $idsite = insere_site($codecom, $idcoord, $rqsite, $site);
            }
    }
            // Si présence de photo
            $photo = $_POST['aphoto'];
            if(isset($photo))
            {
                if($photo == 'oui')
                {
                    if (!file_exists('../../../photo/P800/stations')) {
                        mkdir('../../../photo/P800/stations', 0777, true);
                        mkdir('../../../photo/P400/stations', 0777, true);
                        mkdir('../../../photo/P200/stations', 0777, true);
                    }

                    $dossier_destination1 = '../../../photo/P800/stations/';
                    $dossier_destination2 = '../../../photo/P400/stations/';
                    $dossier_destination3 = '../../../photo/P200/stations/';
                    $nomphoto = $codecom . time();
                    $nomfichier = $nomphoto.'.jpg';
                    $img = $_POST['image-data'];
                    $exp = explode(',', $img);
                    $data = base64_decode($exp[1]);
                    $file = $dossier_destination1 . $nomfichier;
                    if(file_put_contents($file, $data) !== false)
                    {
                        require '../../../lib/RedimImageJpg.php';
                        $orien = $_POST['orien'];
                        $repSource = $dossier_destination1;
                        $repDest = $dossier_destination2;
                        $redim = ($orien == 'paysage') ? fctredimimage(400,266,$repDest,'',$repSource,$nomfichier) : fctredimimage(200,300,$repDest,'',$repSource,$nomfichier);
                        $repDest = $dossier_destination3;
                        $redim = ($orien == 'paysage') ? fctredimimage(200,133,$repDest,'',$repSource,$nomfichier) : fctredimimage(100,150,$repDest,'',$repSource,$nomfichier);
                        if ($redim == true)
                        {
                            $dates = date("Y-m-d H:i:s");
                            // $ordre = ($nbphoto == 0) ? 1 : $nbphoto + 1 ;
                            insere_photo($idobserp,$pfiche['date1'],$pfiche['codecom'],$nomphoto,$dates,$sexe,$nomvar);
                        }
                    }
                }
            }
        }
echo json_encode($retour);


