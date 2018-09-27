<?php
if(is_file('progression.txt')) {
    $retour['mes'] = file_get_contents('progression.txt');
} else {
    $retour['mes'] = 'En attente de traitement';
}
echo json_encode($retour);