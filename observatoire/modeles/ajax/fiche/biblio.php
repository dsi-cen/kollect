<?php 
include '../../../../global/configbase.php';
include '../../../lib/pdo2.php';

function plusauteur($idbiblio)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT nom, prenom, prenomab, idauteur FROM biblio.plusauteur
						INNER JOIN biblio.auteurs USING(idauteur)
						WHERE idbiblio = :idbiblio ");
	$req->bindValue(':idbiblio', $idbiblio);
	$req->execute();
	$auteur = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $auteur;
}
function sujet($cdnom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idbiblio, titre, nom, prenom, publi, annee, tome, fascicule, page, plusauteur FROM biblio.biblio
						INNER JOIN biblio.auteurs USING(idauteur)
						INNER JOIN biblio.bibliotaxon USING(idbiblio)
						WHERE cdnom = :cdnom
						ORDER BY annee ");		
	$req->bindValue(':cdnom', $cdnom);
	$req->execute();
	$result = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $result;
}
function taxon($cdnom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT DISTINCT idbiblio, titre, nom, prenom, publi, annee, tome, fascicule, page, plusauteur FROM biblio.biblio
						INNER JOIN biblio.auteurs USING(idauteur)
						INNER JOIN biblio.bibliofiche USING(idbiblio)
						INNER JOIN obs.obs USING(idfiche)
						WHERE cdnom = :cdnom 
						ORDER BY annee ");		
	$req->bindValue(':cdnom', $cdnom);
	$req->execute();
	$result = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $result;
}
function preparation($liste)
{
	foreach($liste as $n)
	{
		if($n['plusauteur'] == 'oui')
		{
			$plusauteur = plusauteur($n['idbiblio']);
			$nbauteur = count($plusauteur);
			if($nbauteur == 1)
			{
				$auteur = '<b>'.$n['nom'].' ('.$n['prenom'].')</b> et <b>'.$plusauteur[0]['nom'].' ('.$plusauteur[0]['prenom'].')</b>';
			}
			else
			{
				$tabaut[] = ['nom'=>$n['nom'],'prenom'=>$n['prenom']];
				foreach($plusauteur as $a)
				{
					$tabaut[] = ['nom'=>$a['nom'],'prenom'=>$a['prenom']];
				}
				$nbauteur = count($tabaut);
				$et = $nbauteur - 1;
				$auteur = null;
				for($i = 0; $i < $nbauteur; $i++) 
				{
					if($i == 0)
					{
						$auteur .= '<b>'.$tabaut[$i]['nom'].' ('.$tabaut[$i]['prenom'].')</b>';
					}
					if($i == $et)
					{
						$auteur .= ' et <b>'.$tabaut[$i]['nom'].' ('.$tabaut[$i]['prenom'].')</b>';
					}
					if($i != 0 && $i < $et)
					{
						$auteur .= ', <b>'.$tabaut[$i]['nom'].' ('.$tabaut[$i]['prenom'].')</b>';
					}
				}
				$tabaut = null;
			}			
		}
		else
		{
			$auteur = '<b>'.$n['nom'].' ('.$n['prenom'].')</b>';
		}
		$vol = (empty($n['fascicule'])) ? $n['tome'] : $n['tome'].'('.$n['fascicule'].')';
		$bib[] = ['idbiblio'=>$n['idbiblio'],'auteur'=>$auteur,'titre'=>$n['titre'],'annee'=>$n['annee'],'tome'=>$vol,'fascicule'=>$n['fascicule'],'publi'=>$n['publi'],'page'=>$n['page']];		
	}
	return $bib;	
}

if(isset($_POST['cdnom'])) 
{
	$cdnom = $_POST['cdnom'];
	$sujet = sujet($cdnom);
	
	$taxon = taxon($cdnom);
	
	$bib = null;
	if($sujet != false)
	{
		$liste = preparation($sujet);
		$bib .= '<h3 class="h5">Référence(s) traitant de l\'espèce</h3>';
		$bib .= '<p>';
		foreach($liste as $n)
		{
			$bib .= '<a href="../biblio/index.php?module=biblio&amp;action=biblio&amp;id='.$n['idbiblio'].'">'.$n['auteur'].', '.$n['annee'].'.- '.$n['titre'].'. <i>'.$n['publi'].'</i>, '.$n['tome'].' :'.$n['page'].'</a><br />';
		}		
		$bib .= '</p>';
	}
	if($taxon != false)
	{
		$liste = preparation($taxon);
		$bib .= '<h3 class="h5">Référence(s) dont des observations figurent sur le site</h3>';
		$bib .= '<p>';
		foreach($liste as $n)
		{
			$bib .= '<a href="../biblio/index.php?module=biblio&amp;action=biblio&amp;id='.$n['idbiblio'].'">'.$n['auteur'].', '.$n['annee'].'.- '.$n['titre'].'. <i>'.$n['publi'].'</i>, '.$n['tome'].' :'.$n['page'].'</a><br />';
		}		
		$bib .= '</p>';
	}
	
	$retour['biblio'] = $bib;
	$retour['statut'] = 'Oui';	
		
}
else
{
	$retour['statut'] = 'Non';
}	
echo json_encode($retour);
