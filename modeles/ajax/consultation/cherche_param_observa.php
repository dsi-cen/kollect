<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';


if (isset($_GET['observa'])) {


    $obser = htmlspecialchars($_GET['observa']);
    $json_obser = file_get_contents('../../../json/'.$obser.'.json');
    $rjson_obser = json_decode($json_obser, true);

    echo json_encode($rjson_obser);
}