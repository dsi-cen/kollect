<?php
if (isset($_SESSION['prenom']) && isset($_SESSION['nom'])) {
    $json_m = file_get_contents('json/maintenance.json');
    $maintenance = json_decode($json_m, true);

    if ($maintenance['etat'] == 'Production') {

        $titre = 'Liste des stations';
        $description = 'Liste des stations';
        $script = '<script src="dist/js/jquery.js" defer></script>
                   <script src="dist/js/bootstrap.min.js" defer></script>
                   <script src="dist/js/leafletpj4.js"></script>
                   <script src="dist/js/jquery.dataTables.min.js" defer></script>
                   <script src="dist/js/popup-image.js" defer></script>
                   <script src="dist/js/listestations.js" defer></script>';

        $css = '<link rel="stylesheet" href="dist/css/leaflet.css" />
                <link rel="stylesheet" href="dist/css/dataTables.bootstrap4.css" />
                <link rel="stylesheet" href="dist/css/popup.css" />
                <link rel="stylesheet" href="dist/css/jquery-ui.css" />';

        $sansheader = 'oui';
        $pasdebdp = 'oui';
        $titrep = 'Liste des stations';


        include CHEMIN_MODELE . 'stations.php';
        $departements = liste_departements();
        $types = liste_type_stations();
        include CHEMIN_VUE . 'liste.php';


    } else {
        header('location:index.php?module=maintenance&action=maintenance');
    }
} else {
    header('location:index.php?module=connexion&action=connexion&s=l');
}
