<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';	
function table($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT table_name FROM information_schema.tables WHERE table_schema='$nomvar' AND table_name='liste'") or die(print_r($bdd->errorInfo()));
	$table = $req->rowCount();
	$req->closeCursor();
	return $table;		
}
function recherche_tax($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT liste.cdnom, nom, genre.genre, famille.famille, liste.nomvern, liste.locale FROM $nomvar.liste 
						LEFT JOIN $nomvar.genre ON genre.cdnom = liste.cdtaxsup
						INNER JOIN $nomvar.famille ON famille.cdnom = liste.famille
						WHERE cdref = liste.cdnom AND (rang = 'ES' OR rang = 'SSES')
						ORDER BY nom") or die(print_r($bdd->errorInfo()));
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;	
}
function verifliste($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->prepare("SELECT $nomvar.liste.cdnom FROM $nomvar.liste
						INNER JOIN referentiel.liste ON referentiel.liste.cdnom = $nomvar.liste.cdnom
						WHERE observatoire != :observa ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$liste = $req->rowCount();
	$req->closeCursor();
	return $liste;		
}
function observadble($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT DISTINCT observatoire FROM $nomvar.liste
						INNER JOIN referentiel.liste ON referentiel.liste.cdnom = $nomvar.liste.cdnom ") or die(print_r($bdd->errorInfo()));
	$liste = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}

if(isset($_POST['sel']))
{
	$nomvar = $_POST['sel'];
	$table = table($nomvar);
	if($table > 0)	
	{
		$verif = verifliste($nomvar);
		if($verif > 0)
		{
			$dbl = observadble($nomvar);
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert">Votre observatoire comprends des taxons ('.$verif.') déjà présent dans un autre observatoire : <b>'.$dbl['observatoire'].'</b> ! Vous devez les supprimer ou les décocher de l\'observatoire ayant comme identifiant : <b>'.$dbl['observatoire'].'</b>.</div>';
		}
		else
		{
			$taxon = recherche_tax($nomvar);
			
			if($taxon != false)
			{
				$sup = '<i class="fa fa-trash curseurlien text-danger"></i>';
				
				$l = '<button type="button" class="btn btn-warning" id="Bttnon">Mettre tous comme non présent</button>';
				$l .= '<table id="tblliste" class="table table-sm table-striped" cellspacing="0" width="100%">';
				$l .= '<thead><tr><th>Famille</th><th>Genre</th><th>Nom</th><th>Nom Français</th><th></th><th></th></tr></thead>';
				$l .= '</table>';
				foreach($taxon as $n)
				{
					if($n['locale'] == 'oui')
					{
						$s = '<input type="checkbox" title="locale : oui" value="oui" class="sel curseurlien" checked>';											
					}
					else
					{
						$s = '<input type="checkbox" title="locale : non" value="oui" class="sel curseurlien">';
					}
					$nomvern = '<input type="text" value="'.$n['nomvern'].'" class="nomvern">';	
					//$data[] = [$n['famille'],$n['genre'],$n['nom'],$n['nomvern'],$sup,$s,'DT_RowId'=>$n['cdnom']];
					$data[] = [$n['famille'],$n['genre'],$n['nom'],$nomvern,$sup,$s,'DT_RowId'=>$n['cdnom']];
				}
				$retour['liste'] = $l;
				$retour['data'] = $data;
				$retour['statut'] = 'Oui';
			}	
		}
	}
	else
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert">Vous devez au péalable choisir <a href="index.php?module=observatoire&amp;action=espece">la liste</a></div>';
	}	
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert">Aucun observatoire de choisit.</div>';
}
echo json_encode($retour);