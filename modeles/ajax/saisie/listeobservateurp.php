<?php 
if(isset($_POST['idobser']))
{
	$idobser = htmlspecialchars($_POST['idobser']);
	
	include '../../../global/configbase.php';
	include '../../../lib/pdo2.php';
	
	function liste_observateur($idobser)
	{
		$bdd = PDO2::getInstance();
		$bdd->query("SET NAMES 'UTF8'");
		$req = $bdd->query('SELECT observateur, idobser FROM referentiel.observateur WHERE idobser IN ('.$idobser.')') or die(print_r($bdd->errorInfo()));
		$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
		$req->closeCursor();
		return $resultat;
	}
	
	$liste = liste_observateur($idobser);
	$obs = explode(", ", $idobser);
	$l = null;
	$l .= '<div class="form-check"><label class="form-check-label"><input class="form-check-input" type="radio" name="opph" value="'.$obs[0].'" checked> Vous même</label></div>';
	foreach($liste as $n)
	{
		if($n['idobser'] != $obs[0])
		{
			$l .= '<div class="form-check"><label class="form-check-label"><input class="form-check-input" type="radio" name="opph" value="'.$n['idobser'].'"> '.$n['observateur'].'</label></div>';
		}
	}
	
	echo $l;		
}