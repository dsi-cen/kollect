<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';



if(isset($_POST['choixtax']) && isset($_POST['choixloca']))
{
    $idobser = $_POST['idobser'];
    $choixtax = $_POST['choixtax'];
    $choixloca = $_POST['choixloca'];
    $photo = (isset($_POST['photo'])) ? 'oui' : 'non';
    $son = (isset($_POST['son'])) ? 'oui' : 'non';
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $etude = $_POST['etude'];
    $orga = $_POST['orga'];
    $typedon = $_POST['typedon'];
    $flou = $_POST['flou'];
    $pr = $_POST['pr'];
    $habitat = $_POST['habitat'];

    if(!empty($choixtax))
    {
        $observa = ($choixtax == 'observa') ? $_POST['rchoixtax'] : null;
        $cdnom = ($choixtax == 'espece') ? $_POST['rchoixtax'] : null;
    }
    else
    {
        $observa = null; $cdnom = null;
    }
    if(!empty($choixloca))
    {
        $codecom = ($choixloca == 'commune') ? $_POST['rchoixloca'] : null;
        $idsite = ($choixloca == 'site') ? $_POST['rchoixloca'] : null;
        $site = ($choixloca == 'sitee') ? $_POST['sitee'] : null;
        $poly = ($choixloca == 'poly') ? $_POST['poly'] : null;
        $dist = ($choixloca == 'cercle') ? $_POST['rayon'] : null;
    }
    else
    {
        $codecom = null; $idsite = null; $site = null; $poly = null; $dist = null;
    }
    $date1 = null; $date2 = null; $typedate = null;
    if(isset($_POST['date']) && !empty($_POST['date']))
    {
        $typedate = 'obs';
        $date1 = DateTime::createFromFormat('d/m/Y', $_POST['date']);
        $date1 = $date1->format('Y-m-d');
        $date2 = DateTime::createFromFormat('d/m/Y', $_POST['date2']);
        $date2 = $date2->format('Y-m-d');
    }
    if(isset($_POST['dates']) && !empty($_POST['dates']))
    {
        $typedate = 'saisie';
        $date1 = DateTime::createFromFormat('d/m/Y', $_POST['dates']);
        $date1 = $date1->format('Y-m-d');
        $date2 = DateTime::createFromFormat('d/m/Y', $_POST['dates2']);
        $date2 = $date2->format('Y-m-d');
    }
    $decade = ($_POST['decade'] != 'NR') ? $_POST['decade'] : null;
    $vali = ($_POST['vali'] != 'NR') ? $_POST['vali'] : null;
    $indice = (!empty($_POST['rindice'])) ? $_POST['rindice'] : null;

    if(!empty($_POST['rstatut']))
    {
        if(empty($_POST['rlrr']) && empty($_POST['rlre']) && empty($_POST['rlrf']))
        {
            $statut = 'type IN('.$_POST['rstatut'].')';
        }
        else
        {
            $tmp = explode(',', $_POST['rstatut']);
            $statut = null; $con = 'non';
            foreach($tmp as $n)
            {
                if($n == "'LRR'" && !empty($_POST['rlrr']))
                {
                    $statut = ($con == 'non') ? 'type = '.$n.' AND lr IN('.$_POST['rlrr'].')' : $statut.' OR (type = '.$n.' AND lr IN('.$_POST['rlrr'].'))';
                    $con = 'oui';
                }
                if($n == "'LRE'" && !empty($_POST['rlre']))
                {
                    $statut = ($con == 'non') ? 'type = '.$n.' AND lr IN('.$_POST['rlre'].')' : $statut.' OR (type = '.$n.' AND lr IN('.$_POST['rlre'].'))';
                    $con = 'oui';
                }
                if($n == "'LRF'" && !empty($_POST['rlrf']))
                {
                    $statut = ($con == 'non') ? 'type = '.$n.' AND lr IN('.$_POST['rlrf'].')' : $statut.' OR (type = '.$n.' AND lr IN('.$_POST['rlrf'].'))';
                    $con = 'oui';
                }
            }
        }
    }
    else
    {
        $statut = null;
    }


    $retour['statut'] = 'Oui';
    echo json_encode($retour);
}	