<?php
if(is_file('progression.txt')) {
    $retour['mes'] = file_get_contents('progression.txt');
} else {
    $retour['mes'] = 'En attente de traitement';
}
if(is_file('progressionb.txt')) {
	$nb = file_get_contents('progressionb.txt');
	$max = 15;	
	$retour['b'] = round((100/$max)*$nb);
} else {
    $retour['b'] = 0;
}
echo json_encode($retour);