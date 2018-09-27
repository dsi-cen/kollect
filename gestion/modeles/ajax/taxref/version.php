<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function vidertable()
{
	$bdd = PDO2::getInstance();		
	$bdd->exec("DELETE FROM taxref.histo ");
}
function insere_histo()
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->exec("INSERT INTO taxref.histo (cdnom,nom,nomvern,valfinal,rang) 
						SELECT cdnom, nom, nomvern, valfinal::int, rang FROM taxref.change
						INNER JOIN referentiel.liste USING(cdnom)
						WHERE (cdnom = valinit::int) AND champ = 'CD_REF' ");
	return $req;		
}

vidertable();
$liste = insere_histo();
if($liste != false)
{
	$retour['statut'] = 'Oui';
	$retour['nb'] = $liste;
}	
else
{
	$retour['statut'] = 'Non';
}
		
echo json_encode($retour);