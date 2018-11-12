    <?php
    include '../global/configbase.php';
    include '../lib/pdo2.php';

    // RLE : Création des clés
    function fkey($k)
    {
        $bdd = PDO2::getInstance();
        $req = null;
        $filename = $k.'.sql';
        $req = file_get_contents($filename);
        $bdd->exec($req);
        unset($req);
    }
    fkey('fkey');
    // RLE : end
