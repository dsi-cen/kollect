<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function recherche($cdnom)
{
	$bdd = PDO2::getInstance();		
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idphoto, ordre FROM site.photo WHERE cdnom = :cdnom ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':cdnom', $cdnom);
	$req->execute();
	$nbresultats = $req->rowCount();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return array($nbresultats, $resultat);	
}
function modordre($mordre,$id)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("UPDATE site.photo SET ordre = :ordre WHERE idphoto = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id);
	$req->bindValue(':ordre', $mordre);
	$req->execute();
	$req->closeCursor();
}

if(isset($_POST['cdnom']) && isset($_POST['ordre']))
{	
	$cdnom = $_POST['cdnom'];
	$ordre = $_POST['ordre'];
	$idphoto = $_POST['idphoto'];
	$ini = $_POST['ini'];
	
	$liste = recherche($cdnom);
	
	foreach($liste[1] as $n)
	{
		if($ini > $ordre)
		{	
			if($n['ordre'] >= $ordre && $n['idphoto'] != $idphoto && $n['ordre'] != $liste[0])
			{
				$mordre = $n['ordre'] + 1;
				modordre($mordre,$n['idphoto']);
			}
		}
		elseif($ini < $ordre)
		{
			if($n['ordre'] <= $ordre && $n['idphoto'] != $idphoto && $n['ordre'] != 1)
			{
				$mordre = $n['ordre'] - 1;
				modordre($mordre,$n['idphoto']);
			}			
		}
	}
	modordre($ordre,$idphoto);
		
	$retour['statut'] = 'Oui';
}
else
{
	$retour['statut'] = 'Non';
}
echo json_encode($retour);	
?>