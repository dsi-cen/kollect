<?php 
if(isset($_POST['cdhab']))
{
	$cdhab = htmlspecialchars($_POST['cdhab']);
	include '../../../global/configbase.php';
	include '../../../lib/pdo2.php';
	
	function recupcode($cdhab)
	{
		$bdd = PDO2::getInstance();
		$bdd->query('SET NAMES "utf8"');
		$req = $bdd->prepare("SELECT lbcode FROM referentiel.eunis WHERE cdhab = :cdhab ") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':cdhab', $cdhab);
		$req->execute();
		$resultat = $req->fetch(PDO::FETCH_ASSOC);
		$req->closeCursor();
		return $resultat;		
	}	
	function habitat($code,$niv)
	{
		$bdd = PDO2::getInstance();
		$bdd->query('SET NAMES "utf8"');
		$req = $bdd->prepare("SELECT cdhab, lbcode, lbhabitat FROM referentiel.eunis WHERE locale = 'oui' AND lbcode LIKE :code AND niveau = :niv ORDER BY lbcode ") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':code', ''.$code.'%');
		$req->bindValue(':niv', $niv);
		$req->execute();
		$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
		$req->closeCursor();
		return $resultat;		
	}
	function habitat3($code)
	{
		$bdd = PDO2::getInstance();
		$bdd->query('SET NAMES "utf8"');
		$req = $bdd->prepare("SELECT cdhab, lbcode, lbhabitat FROM referentiel.eunis WHERE locale = 'oui' AND lbcode LIKE :code AND niveau != 2 ORDER BY lbcode ") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':code', ''.$code.'%');
		$req->execute();
		$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
		$req->closeCursor();
		return $resultat;		
	}	
	
	$code = recupcode($cdhab);
	$habitat = ($_POST['niv'] == 2) ? habitat($code['lbcode'],$_POST['niv']) : habitat3($code['lbcode']);
		
	$liste = null;
	if(count($habitat) > 0)
	{
		$liste .= '<option value="NR">-- Affiner au besoin --</option>';
		foreach($habitat as $n)
		{
			$liste .= '<option value="'.$n['cdhab'].'" >'.$n['lbcode'].' - '.$n['lbhabitat'].'</option>';		
		}
	}
	else
	{
		$liste .= '<option value="NR">-- Pas de niveau inférieur --</option>';
	}

	echo $liste;		
}