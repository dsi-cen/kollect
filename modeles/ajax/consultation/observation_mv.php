<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
include 'export_functions.php';

session_start();


function pagination($nbpage,$pageaffiche)
{
    $prec = $pageaffiche - 1;
    $suiv = $pageaffiche + 1;
    $avdern = $nbpage - 1;
    $adj = 2;
    $listp = '';
    if($nbpage > 1)
    {
        $listp .= '<ul class="pagination">';
        if($pageaffiche == 2)
        {
            $listp .= '<li id="pp1" class="page-item"><span class="page-link curseurlien">&laquo;</span></li>';
        }
        elseif($pageaffiche > 2)
        {
            $listp .= '<li id="pp'.$prec.'" class="page-item"><span class="page-link curseurlien">&laquo;</span></li>';
        }
        if($nbpage < 7 + ($adj * 2))
        {
            $listp .= ($pageaffiche == 1) ? '<li id="p1" class="page-item active"><a class="page-link">1</a></li>' : '<li id="p1" class="page-item"><a class="page-link curseurlien">1</a></li>';
            for($i=2; $i<=$nbpage; $i++)
            {
                $listp .= ($i == $pageaffiche) ? '<li id="p'.$i.'" class="page-item active"><a class="page-link">'.$i.'</a></li>' : '<li id="p'.$i.'" class="page-item"><a class="page-link curseurlien">'.$i.'</a></li>';
            }
        }
        else
        {
            if($pageaffiche < 2 + ($adj * 2))
            {
                $listp .= ($pageaffiche == 1) ? '<li id="p1" class="page-item active"><a class="page-link">1</a></li>' : '<li id="p1" class="page-item"><a class="page-link curseurlien">1</a></li>';
                for($i=2; $i <= 4 + ($adj * 2); $i++)
                {
                    $listp .= ($i == $pageaffiche) ? '<li id="p'.$i.'" class="page-item active"><a class="page-link">'.$i.'</a></li>' : '<li id="p'.$i.'" class="page-item"><a class="page-link curseurlien">'.$i.'</a></li>';
                }
                $listp .= '<li class="page-item"><span class="page-link">&hellip;</span></li>';
                $listp .= '<li id="p'.$avdern.'" class="page-item"><a class="page-link curseurlien">'.$avdern.'</a></li>';
                $listp .= '<li id="p'.$nbpage.'" class="page-item"><a class="page-link curseurlien">'.$nbpage.'</a></li>';
            }
            elseif((($adj * 2) + 1 < $pageaffiche) && ($pageaffiche < $nbpage - ($adj * 2)))
            {
                $listp .= '<li id="p1" class="page-item"><a class="page-link curseurlien">1</a></li>';
                $listp .= '<li id="p2" class="page-item"><a class="page-link curseurlien">2</a></li>';
                $listp .= '<li class="page-item"><span class="page-link">&hellip;</span></li>';
                for($i = $pageaffiche - $adj; $i <= $pageaffiche + $adj; $i++)
                {
                    $listp .= ($i == $pageaffiche) ? '<li id="p'.$i.'" class="page-item active"><a class="page-link">'.$i.'</a></li>' : '<li id="p'.$i.'" class="page-item"><a class="page-link curseurlien">'.$i.'</a></li>';
                }
                $listp .= '<li class="page-item"><span class="page-link">&hellip;</span></li>';
                $listp .= '<li id="p'.$avdern.'" class="page-item"><a class="page-link curseurlien">'.$avdern.'</a></li>';
                $listp .= '<li id="p'.$nbpage.'" class="page-item"><a class="page-link curseurlien">'.$nbpage.'</a></li>';
            }
            else
            {
                $listp .= '<li id="p1" class="page-item"><a class="page-link curseurlien">1</a></li>';
                $listp .= '<li id="p2" class="page-item"><a class="page-link curseurlien">2</a></li>';
                $listp .= '<li class="page-item"><span class="page-link">&hellip;</span></li>';
                for($i = $nbpage - (2 + ($adj * 2)); $i <= $nbpage; $i++)
                {
                    $listp .= ($i == $pageaffiche) ? '<li id="p'.$i.'" class="page-item active"><a class="page-link">'.$i.'</a></li>' : '<li id="p'.$i.'" class="page-item"><a class="page-link curseurlien">'.$i.'</a></li>';
                }
            }
        }
        if($pageaffiche != $nbpage)
        {
            $listp .= '<li id="pp'.$suiv.'" class="page-item"><span class="page-link curseurlien">&raquo;</span></li>';
        }
        $listp .= '</ul>';
    }
    return $listp;
}

function nbobs()
{

    $strQuery = 'SELECT COUNT(DISTINCT idobs) AS nb FROM obs.synthese_obs_nflou ';
    $strQuery .= query($where='non');
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");

    $req = $bdd->query($strQuery);

    $req->execute();
    $nbobs = $req->fetchColumn();
    $req->closeCursor();
    return $nbobs;
}


function listeobs() {

    $fields = 'idfiche, idobs, idobservateur, idmainobser, observateur, floutage_sensible, nom_station, commune, observatoire, photo, son, rang, cdref, code_validation, nomlatin, nomvern, date_debut_obs, nb_tot';

    $idmembre = $_SESSION['idmembre'];
    $droits = $_SESSION['droits'];
    $idobservateur = $_POST['idobser'];
    $observateurmembre = rechercheobservateurid($idmembre);

    if ($droits > 3 || $observateurmembre == $idobservateur) {
        $mv = "SELECT " . $fields . " FROM obs.synthese_obs_nflou " . query($where = "non") . " ORDER BY idfiche,idobs,idligne";
    } else if ($droits == 3 || $droits == 2) {
        if ($observateurmembre == $idobservateur) {
            $mv = "SELECT " . $fields . " FROM obs.synthese_obs_nflou " . query($where = "non") . " ORDER BY idfiche,idobs,idligne";
        } else {
            // echo json_encode(get_observatoire_validateur($idmembre));
            $observatoires = implode(",", get_observatoire_validateur($idmembre));
            $observatoires = "('" . str_replace(",", "','", rtrim(trim($observatoires), ",")) . "')";
            $mv1 = "SELECT " . $fields . " FROM obs.synthese_obs_nflou " . "WHERE (((idmainobser = " . $observateurmembre . " OR (idobservateur LIKE '" . $observateurmembre . ",%' OR idobservateur LIKE '%, " . $observateurmembre . "' OR idobservateur LIKE '%, " . $observateurmembre . ",%')) " . query($where = 'oui') . ") OR (( observatoire IN " . $observatoires . " OR (floutage_kollect = 'Pas de dégradation' and taxon_sensible = 'non')) " . query($where = 'oui') . ")) ";
            $mv2 = "SELECT " . $fields . " FROM obs.synthese_obs_flou " . "WHERE (idmainobser != " . $observateurmembre . ") AND (idobservateur NOT LIKE '" . $observateurmembre . ",%' AND idobservateur NOT LIKE '%, " . $observateurmembre . "' AND idobservateur NOT LIKE '%, " . $observateurmembre . ",%') AND observatoire NOT IN " . $observatoires . " " . query($where = 'oui');
            $mv = "((" . $mv1 . ") UNION (" . $mv2 . "))";
        }
    } else if ($droits == 1) {
        if ($observateurmembre == $idobservateur) {
            $mv = "SELECT " . $fields . " FROM obs.synthese_obs_nflou " . query($where = "non") . " ORDER BY idfiche,idobs,idligne";
        } else {
            $mv1 = "SELECT " . $fields . " FROM obs.synthese_obs_nflou " . "WHERE (((idmainobser = " . $observateurmembre . " OR (idobservateur LIKE '" . $observateurmembre . ",%' OR idobservateur LIKE '%, " . $observateurmembre . "' OR idobservateur LIKE '%, " . $observateurmembre . ",%')) " . query($where = 'oui') . ") OR (( (floutage_kollect = 'Pas de dégradation' and taxon_sensible = 'non')) " . query($where = 'oui') . ")) ";
            $mv2 = "SELECT " . $fields . " FROM obs.synthese_obs_flou " . "WHERE (idmainobser != " . $observateurmembre . ") AND (idobservateur NOT LIKE '" . $observateurmembre . ",%' AND idobservateur NOT LIKE '%, " . $observateurmembre . "' AND idobservateur NOT LIKE '%, " . $observateurmembre . ",%') " . query($where = 'oui');
            $mv = "((" . $mv1 . ") UNION (" . $mv2 . "))";
        }
    }

    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->query($mv);
    $req->execute();
    $resultat = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $resultat;
}

if(isset($_POST['choixtax']) && isset($_POST['choixloca']))
{

    $json_site = file_get_contents('../../../json/site.json');
    $rjson_site = json_decode($json_site, true);

    $demandeur = rechercheobservateurid($_SESSION['idmembre']);

    // TODO : droits
    $droit = ((isset($_SESSION['droits']) && $_SESSION['droits'] >= 1) || $_POST['d'] == 'oui' || $demandeur['idobser'] == $idobser) ? 'oui' : 'non';

    $nbobs = nbobs();
    $retour['nbobs'] = $nbobs;
    if($nbobs > 0)
    {
        $latin = (isset($_SESSION['latin'])) ? $_SESSION['latin'] : '';

        $nbpage = ceil($nbobs[0]/100);
        if($nbpage > 1)
        {
            $page = intval($_POST['page']);
            $pageaffiche = ($page > $nbpage) ? $nbpage : $page;
            $debut = ($pageaffiche * 100 - 100);
            $retour['pagination'] = pagination($nbpage,$pageaffiche);
        }
        else
        {
            $debut = 0;
        }

        $listeobs = listeobs();

        foreach($listeobs as $n)
        {
            $tabfichetmp[] = $n['idfiche'];

            if($droit == 'non')
            {
                if($n['floutage_sensible'] >= 2) { $tabsensible[] = $n['idfiche']; }
                if($n['floutage_sensible'] == 1) { $tabsensible1[] = $n['idfiche']; }
            }
        }

        $listefiche = array_unique($tabfichetmp);
        $listefiche = implode(',', $listefiche);
        $listephoto = listephoto($listefiche);

        if(count($listephoto) > 0)
        {
            foreach($listephoto as $n)
            {
                if($n['photo'] != '')
                {
                    $photoobs[] = $n['photo']; $okphoto = 'oui';
                }
                if($n['son'] != '')
                {
                    $sonobs[] = $n['son']; $okson = 'oui';
                }
            }
            if(isset($okphoto)) { $photoobs = array_flip($photoobs); }
            if(isset($okson)) { $sonobs = array_flip($sonobs); }
        }
        $tabfiche = array_count_values($tabfichetmp);
        $tabsensible = (isset($tabsensible)) ? array_flip($tabsensible) : '';
        $tabsensible1 = (isset($tabsensible1)) ? array_flip($tabsensible1) : '';

        foreach($listeobs as $n)
        {
            $plusfiche = (isset($tabfiche[$n['idfiche']]) && ($tabfiche[$n['idfiche']] > 1)) ? $n['idfiche'] : 'non';
            $ouiphoto = (isset($photoobs) && isset($photoobs[$n['idobs']])) ? 'oui' : 'non';
            $ouison = (isset($sonobs) && isset($sonobs[$n['idobs']])) ? 'oui' : 'non';
            if($n['idobservateur'])
            {
                $obs2[] = '<a href="index.php?module=infoobser&amp;action=info&amp;idobser='.$n['idmainobser'].'">'.$n['observateur'].'</a>';
                /*$obsplus = cherche_observateur($n['idfiche']);
                foreach($obsplus as $o)
                {
                    $obs2[] = '<a href="index.php?module=infoobser&amp;action=info&amp;idobser='.$o['idobser'].'">'.$o['prenom'].' '.$o['nom'].'</a>';
                }*/
                $obs = implode(', ', $obs2);
                $obs2 = null;
            }

            // TODO, nombre de commentaire sur l'obs ?
            $comobs = ($n['rqobs'] != "") ? 1 : 0;

            // TODO droits
            $affichagelocalisation = (!empty($n['nom_station'])) ? $n['commune'].', '.$n['nom_station'] : $n['commune'];

            foreach($rjson_site['observatoire'] as $d)
            {
                if($d['nomvar'] == $n['observatoire'])
                {
                    if($d['latin'] == 'oui' && $latin == 'oui') { $afflatin = 'oui'; }
                    elseif($d['latin'] == 'oui' && ($latin == 'defaut' || $latin == '')) { $afflatin = 'oui'; }
                    elseif($d['latin'] == 'non' && $latin == 'oui') { $afflatin = 'oui'; }
                    elseif($d['latin'] == 'non' || $latin == 'non') { $afflatin = 'non'; }
                    if ($afflatin == 'oui')
                    {
                        if($n['rang'] != 'GN')
                        {
                            $afflatintab = '<a href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d='.$d['nomvar'].'&amp;id='.$n['cdref'].'"><i>'.$n['nomlatin'].'</i></a>';
                        }
                        else
                        {
                            $afflatintab = '<a href="observatoire/index.php?module=fiche&amp;action=ficheg&amp;d='.$d['nomvar'].'&amp;id='.$n['cdref'].'"><i>'.$n['nomlatin'].' sp.</i></a>';
                        }
                    }
                    else
                    {
                        if($n['nomvern'] != '')
                        {
                            $afflatintab = '<a href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d='.$d['nomvar'].'&amp;id='.$n['cdref'].'" class="tbleu" data-toggle="tooltip" data-placement="top" title="'.$n['nomlatin'].'">'.$n['nomvern'].'</a>';
                        }
                        else
                        {
                            if($n['rang'] != 'GN')
                            {
                                $afflatintab = '<a href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d='.$d['nomvar'].'&amp;id='.$n['cdref'].'"><i>'.$n['nomlatin'].'</i></a>';
                            }
                            else
                            {
                                $afflatintab = '<a href="observatoire/index.php?module=fiche&amp;action=ficheg&amp;d='.$d['nomvar'].'&amp;id='.$n['cdref'].'"><i>'.$n['nomlatin'].' sp.</i></a>';
                            }
                        }
                    }
                    //validation
                    switch($n['code_validation'])
                    {
                        case 1:$clvali = 'val1'; $tolvali = 'Donnée certaine / très probable.'; break;
                        case 2:$clvali = 'val2'; $tolvali = 'Donnée probable'; break;
                        case 3:$clvali = 'val3'; $tolvali = 'Donnée douteuse'; break;
                        case 4:$clvali = 'val4'; $tolvali = 'Donnée invalide'; break;
                        case 5:$clvali = 'val5'; $tolvali = 'Validation non réalisable'; break;
                        case 6:$clvali = ''; $tolvali = 'En attente de validation'; break;
                        case 7:$clvali = ''; $tolvali = 'En attente de validation'; break;
                    }
                    $tabobs[] = [
                        'latin'=>$afflatin,
                        'taxon'=>$afflatintab,
                        'vali'=>$clvali,
                        'tvali'=>$tolvali,
                        'datefr'=>$n['date_debut_obs'],
                        'nomlat'=>$n['nomlatin'],
                        'nomfr'=>$n['nomvern'],
                        'nb'=>$n['nb_tot'],
                        'icon'=>$d['icon'],
                        'afloca'=>$affichagelocalisation,
                        'obs'=>$obs,
                        'idobs'=>$n['idobs'],
                        'idfiche'=>$n['idfiche'],
                        'com'=>$comobs,
                        'photo'=>$ouiphoto,
                        'son'=>$ouison,
                        'idmainobser'=>$n['idmainobser'],
                        'plusfiche'=>$plusfiche,
                        'idobser'=>$n['idmainobser']
                    ];
                }
            }
        }
        $liste = null;
        $liste .= '<table id="querytable" class="table table-hover table-sm tblobs">';
        $liste .= '<thead><tr><th>Nombre</th><th>Date</th><th>Nom latin</th><th>Localisation</th><th>Observateur(s)</th><th></th></tr></thead><tbody>';

        foreach($tabobs as $n)
        {
            $liste .= '<tr>';
            $liste .= (empty($observa) && empty($cdnom)) ? '<td><i class="'.$n['icon'].' fa-15x"></i>' : '<td>';
            $liste .= '&nbsp;<i class="fa fa-check-circle '.$n['vali'].'" data-toggle="tooltip" data-placement="top" title="'.$n['tvali'].'"></i>';
            $liste .= '&nbsp;'.$n['nb'];
            $liste .= '</td>';
            $liste .= '<td>'.$n['datefr'].'</td>';
            $liste .= '<td>'.$n['taxon'].'</td>';
            $liste .= '<td>'.$n['afloca'].'</td>';
            $liste .= '<td>'.$n['obs'].'</td>';
            $liste .= '<td>';
            $liste .= '<i class="fa fa-info-circle text-info curseurlien" data-toggle="modal" data-target="#obs" data-nomfr="'.$n['nomfr'].'" data-nomlat="'.$n['nomlat'].'" data-idobs="'.$n['idobs'].'" data-latin="'.$n['latin'].'" data-photo="'.$n['photo'].'" data-idmor="'.$n['idm'].'"></i>';
            $liste .= '&nbsp;<a href="index.php?module=observation&amp;action=detail&amp;idobs='.$n['idobs'].'" target="_blank"><i class="fa fa-eye text-info"></i></a>';
            if($n['plusfiche'] != 'non')
            {
                $liste .= '&nbsp;<i class="fa fa-list-ol color1 curseurlien" data-toggle="modal" data-target="#fiche" data-idfiche="'.$n['plusfiche'].'"></i>';
            }
            if($n['photo'] == 'oui')
            {
                $liste .= '&nbsp;<i class="fa fa-camera"></i>';
            }
            if($n['son'] == 'oui')
            {
                $liste .= '&nbsp;<i class="fa fa-volume-off"></i>';
            }
            if((isset($_SESSION['idmembre']) && $n['idmainobser'] == $demandeur) || (isset($_SESSION['virtobs']) && $n['idobser'] == $demandeur))
            {
                $liste .= '&nbsp;<i class="fa fa-pencil curseurlien text-warning" onclick="modfiche('.$n['idfiche'].')"></i>';
            }
            if($n['com'] == 1)
            {
                $liste .= '&nbsp;<i class="fa fa-comment-o" data-toggle="tooltip" data-placement="top" title="1 commentaire"></i>';
            }
            elseif($n['com'] > 1)
            {
                $liste .= '&nbsp;<i class="fa fa-comments-o" data-toggle="tooltip" data-placement="top" title="Plusieurs commentaires"></i>';
            }
            $liste .= '</td>';
            $liste .= '</tr>';
        }
        $liste .= '</tbody></table>';
        unset($tabobs);
    }
    else
    {
        $liste = 'Aucune observation pour ces critères';
    }

    $retour['listeobs'] = $liste;
    $retour['d'] = ($droit == 'oui') ? 'oui' : 'non';
    $retour['statut'] = 'Oui';
    echo json_encode($retour);
}
