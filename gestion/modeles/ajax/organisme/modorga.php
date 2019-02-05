<?php
if(isset($_POST['id']))
{
	include '../../../../global/configbase.php';
	include '../../../../lib/pdo2.php';
	
	function mod($id,$orga,$descri)
	{
		$bdd = PDO2::getInstance();
		$bdd->query("SET NAMES 'UTF8'");
		$req = $bdd->prepare("UPDATE referentiel.organisme SET organisme = :orga, descri = :descri WHERE idorg = :id ") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':id', $id);
		$req->bindValue(':orga', $orga);
		$req->bindValue(':descri', $descri);
		$ok = ($req->execute()) ? 'oui' : 'non';
		$req->closeCursor();
		return $ok;
	}
    function mod_affectation_etude($id, $etude) // RLE : Update etudes
    {
        $bdd = PDO2::getInstance();
        $bdd->query("SET NAMES 'UTF8'");
        $req = $bdd->prepare("DELETE FROM referentiel.etude_organisme WHERE idorg = :id AND idetude != 0") or die(print_r($bdd->errorInfo()));
        $req->bindValue(':id', $id);
        $req->execute();
        $req->closeCursor();

        $orgaupdate = json_decode($etude, true);

        $bdd = PDO2::getInstance();
        $bdd->query("SET NAMES 'UTF8'");
        foreach ($orgaupdate as $org => $value) {
            $req = $bdd->prepare("INSERT INTO referentiel.etude_organisme (idetude, idorg) VALUES(:etude, :id);") or die(print_r($bdd->errorInfo()));
            $req->bindValue(':id', $id);
            $req->bindValue(':etude', $value);
            $req->execute();
        }
        $req->closeCursor();
    }
	
	$id = $_POST['id'];
	$orga = $_POST['orga'];
	$descri = $_POST['descri'];
    $etude = $_POST['etude'];
	
	$vali = mod($id,$orga,$descri);
    mod_affectation_etude($id, $etude);
	$retour['statut'] = ($vali == 'oui') ? 'Ok' : 'Erreur ! Problème lors de la modification';
	
}
else 
{$retour['statut'] = 'Tous les champs ne sont pas parvenus';}
echo json_encode($retour);