<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';
session_start();

function recup_obs($observa, $checked)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->prepare("SELECT idobs, cdref, idcom FROM obs.obs 
						LEFT JOIN vali.comvali USING(idobs) 
						WHERE validation = 6 AND observa = :observa AND idobs IN ($checked)") or die(print_r($bdd->errorInfo()));
    $req->bindValue(':observa', $observa);
    // $req->bindValue(':checked', $checked);
    $req->execute();
    $resultat = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $resultat;
}
function mod_vali($idobs,$choix)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->prepare("UPDATE obs.obs SET validation = :vali WHERE idobs = :idobs ") or die(print_r($bdd->errorInfo()));
    $req->bindValue(':idobs', $idobs, PDO::PARAM_INT);
    $req->bindValue(':vali', $choix);
    $vali = ($req->execute()) ? 'oui' : '';
    $req->closeCursor();
    return $vali;
}
function inser_histovali($idobs,$cdnom,$dates,$choix,$dec,$idm)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->prepare("INSERT INTO vali.histovali (idobs, cdnom, dateval, vali, decision, idm, typevali) VALUES(:idobs, :cdnom, :dateval, :vali, :dec, :idm, 2) ") or die(print_r($bdd->errorInfo()));
    $req->bindValue(':idobs', $idobs, PDO::PARAM_INT);
    $req->bindValue(':cdnom', $cdnom, PDO::PARAM_INT);
    $req->bindValue(':dateval', $dates);
    $req->bindValue(':vali', $choix);
    $req->bindValue(':dec', $dec);
    $req->bindValue(':idm', $idm);
    $req->execute();
    $req->closeCursor();
}

if(isset($_POST['observa']) && !empty($_POST['observa']))
{
    $checked = str_replace('"', "", $_POST['checked']); // Liste des obs à valider
    $observa = $_POST['observa']; // Observatoire concerné
    $idm = $_SESSION['idmembre'];

    $liste = recup_obs($observa, $checked);

    $dates = date("Y-m-d");
    $datefr = date('d/m/Y à H:i');
    $dec = 'Validation manuelle par sélection du '.$datefr.' par '.$_SESSION['prenom'].' '.$_SESSION['nom'];
    $choix = 1;

    $nb = 0;
    foreach($liste as $n)
    {
        if(empty($n['idcom']))
        {
            $vali = mod_vali($n['idobs'],$choix);
            if($vali == 'oui')
            {
                inser_histovali($n['idobs'],$n['cdref'],$dates,$choix,$dec,$idm);
                $nb++;
            }
        }
    }
    $retour['liste'] = $liste;
    $retour['nb'] = $nb;
    $retour['statut'] = 'Oui';
}
else
{
    $retour['statut'] = 'Non';
    $retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur ! observatoire non récupéré</div>';
}
echo json_encode($retour);
?>