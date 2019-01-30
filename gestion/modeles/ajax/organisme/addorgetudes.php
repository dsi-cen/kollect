<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function etudeslist()
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->prepare("SELECT referentiel.etude.idetude, referentiel.etude.etude FROM referentiel.etude WHERE idetude != 0;") or die(print_r($bdd->errorInfo()));
    $req->execute();
    $rows = $req->fetchAll(PDO::FETCH_ASSOC);
    $list = "<select id='etudelistadd' multiple='multiple'>";
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

if($_POST['list'] == 'listok')
{
     $retour['list'] = etudeslist();
}
echo json_encode($retour);