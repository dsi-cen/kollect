<?php$titre = 'Cartographie des taxons';$description = 'Cartographie des espèces '.$rjson_site['ad1'].' '.$rjson_site['lieu'];$script = '<script src="dist/js/jquery.js" defer></script><script src="dist/js/bootstrap.min.js" defer></script><script src="dist/js/leafletpj4.js" defer></script><script src="dist/js/carto.js" defer></script>';$css = '<link rel="stylesheet" href="dist/css/leaflet.css" />';$sansheader = 'oui';$pasdebdp = 'oui';$titrep = 'Cartographie des espèces '.$rjson_site['ad1'].' '.$rjson_site['lieu'];include CHEMIN_MODELE.'carto.php';$taxon = recherche_taxon();$latin = (isset($_SESSION['latin'])) ? $_SESSION['latin'] : '';$couche = (isset($_SESSION['couche'])) ? $_SESSION['couche'] : '';include CHEMIN_VUE.'carto.php';