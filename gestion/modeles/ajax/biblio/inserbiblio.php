<?php 
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';
session_start();

function cherche_membre($idm)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT nom, prenom FROM site.membre WHERE idmembre = :idm ");
	$req->bindValue(':idm', $idm, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function insere_biblio($idauteur,$titre,$type,$publi,$annee,$tome,$fas,$page,$resume,$plusauteur,$dates,$isbn)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO biblio.biblio (titre, idauteur, typep, publi, annee, tome, fascicule, page, resume, plusauteur, datesaisie, isbn) VALUES(:titre, :idauteur, :type, :publi, :annee, :tome, :fas, :page, :resume, :plus, :date, :isbn) ");
	$req->bindValue(':idauteur', $idauteur);
	$req->bindValue(':titre', $titre);
	$req->bindValue(':publi', $publi);
	$req->bindValue(':annee', $annee);
	$req->bindValue(':tome', $tome);
	$req->bindValue(':fas', $fas);
	$req->bindValue(':page', $page);
	$req->bindValue(':type', $type);
	$req->bindValue(':resume', $resume);
	$req->bindValue(':plus', $plusauteur);
	$req->bindValue(':date', $dates);
	$req->bindValue(':isbn', $isbn);
	if ($req->execute())
	{
		$idbiblio = $bdd->lastInsertId('biblio.biblio_idbiblio_seq');
	}
	$req->closeCursor();
	return $idbiblio;	
}
function insere_plusauteur($idbiblio,$idauteur)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO biblio.plusauteur (idbiblio, idauteur) VALUES(:idbiblio, :idauteur) ");
	$req->bindValue(':idbiblio', $idbiblio);
	$req->bindValue(':idauteur', $idauteur);
	$req->execute();
	$req->closeCursor();
}
function insere_suivi($idbiblio,$idm,$nom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO biblio.suivisaisie (idbiblio, idm, nom) VALUES(:idbiblio, :idm, :nom) ");
	$req->bindValue(':idbiblio', $idbiblio);
	$req->bindValue(':idm', $idm);
	$req->bindValue(':nom', $nom);
	$req->execute();
	$req->closeCursor();
}
function insere_lienexterne($idbiblio,$url)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO biblio.lienexterne (idbiblio, url) VALUES(:id, :url) ");
	$req->bindValue(':id', $idbiblio);
	$req->bindValue(':url', $url);
	$req->execute();
	$req->closeCursor();
}
function insere_observa($idbiblio,$observa)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO biblio.biblioobserva (idbiblio, observa) VALUES(:id, :observa) ");
	$req->bindValue(':id', $idbiblio);
	$req->bindValue(':observa', $observa);
	$req->execute();
	$req->closeCursor();
}
function insere_taxon($idbiblio,$cdnom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO biblio.bibliotaxon (idbiblio, cdnom) VALUES(:idbiblio, :cdnom) ");
	$req->bindValue(':idbiblio', $idbiblio);
	$req->bindValue(':cdnom', $cdnom);
	$req->execute();
	$req->closeCursor();
}
function insere_motcle($idbiblio,$mc)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO biblio.bibliomc (idbiblio, idmc) VALUES(:idbiblio, :mc) ");
	$req->bindValue(':idbiblio', $idbiblio);
	$req->bindValue(':mc', $mc);
	$req->execute();
	$req->closeCursor();
}
function insere_codecom($idbiblio,$codecom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO biblio.bibliocom (idbiblio, codecom) VALUES(:idbiblio, :code) ");
	$req->bindValue(':idbiblio', $idbiblio);
	$req->bindValue(':code', $codecom);
	$req->execute();
	$req->closeCursor();
}
function info_biblio($idbiblio)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT CONCAT(biblio.idauteur, ', ', string_agg(DISTINCT plusauteur.idauteur::text, ', ')) AS idauteur, observa, plusauteur, CONCAT(string_agg(DISTINCT codecom::text, ',')) AS codecom, CONCAT(string_agg(DISTINCT cdnom::text, ',')) AS cdnom, CONCAT(string_agg(DISTINCT idmc::text, ',')) AS idmc, url FROM biblio.biblio
						LEFT JOIN biblio.plusauteur USING(idbiblio)
						LEFT JOIN biblio.biblioobserva USING(idbiblio)
						LEFT JOIN biblio.bibliocom USING(idbiblio)
						LEFT JOIN biblio.bibliotaxon USING(idbiblio)
						LEFT JOIN biblio.bibliomc USING(idbiblio)
						LEFT JOIN biblio.lienexterne USING(idbiblio)
						WHERE idbiblio = :idbiblio
						GROUP BY biblio.idauteur, observa, plusauteur, url ");
	$req->bindValue(':idbiblio', $idbiblio);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function modif_biblio($idauteur,$titre,$type,$publi,$annee,$tome,$fas,$page,$resume,$plusauteur,$idbiblio,$isbn)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE biblio.biblio SET titre = :titre, idauteur = :idauteur, typep = :type, publi = :publi, annee = :annee, tome = :tome, fascicule = :fas, page = :page, resume = :resume, plusauteur = :plus, isbn = :isbn WHERE idbiblio = :idbiblio ");
	$req->bindValue(':idauteur', $idauteur);
	$req->bindValue(':titre', $titre);
	$req->bindValue(':publi', $publi);
	$req->bindValue(':annee', $annee);
	$req->bindValue(':tome', $tome);
	$req->bindValue(':fas', $fas);
	$req->bindValue(':page', $page);
	$req->bindValue(':type', $type);
	$req->bindValue(':resume', $resume);
	$req->bindValue(':plus', $plusauteur);
	$req->bindValue(':idbiblio', $idbiblio);
	$req->bindValue(':isbn', $isbn);
	$ok = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $ok;
}
function modif_observa($idbiblio,$observa)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE biblio.biblioobserva SET observa = :observa WHERE idbiblio = :idbiblio ");
	$req->bindValue(':observa', $observa);
	$req->bindValue(':idbiblio', $idbiblio);
	$req->closeCursor();
}
function modif_lien($idbiblio,$url)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE biblio.lienexterne SET url = :url WHERE idbiblio = :idbiblio ");
	$req->bindValue(':url', $url);
	$req->bindValue(':idbiblio', $idbiblio);
	$req->closeCursor();
}
function sup_plusauteur($idbiblio)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("DELETE FROM biblio.plusauteur WHERE idbiblio = :idbiblio ");
	$req->bindValue(':idbiblio', $idbiblio);
	$req->execute();
	$req->closeCursor();
} 
function sup_mot($idbiblio)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("DELETE FROM biblio.bibliomc WHERE idbiblio = :idbiblio ");
	$req->bindValue(':idbiblio', $idbiblio);
	$req->execute();
	$req->closeCursor();
}
function sup_taxon($idbiblio)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("DELETE FROM biblio.bibliotaxon WHERE idbiblio = :idbiblio ");
	$req->bindValue(':idbiblio', $idbiblio);
	$req->execute();
	$req->closeCursor();
}
function sup_commune($idbiblio)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("DELETE FROM biblio.bibliocom WHERE idbiblio = :idbiblio ");
	$req->bindValue(':idbiblio', $idbiblio);
	$req->execute();
	$req->closeCursor();
}
function sup_observa($idbiblio)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("DELETE FROM biblio.biblioobserva WHERE idbiblio = :idbiblio ");
	$req->bindValue(':idbiblio', $idbiblio);
	$req->execute();
	$req->closeCursor();
}
function sup_lien($idbiblio)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("DELETE FROM biblio.lienexterne WHERE idbiblio = :idbiblio ");
	$req->bindValue(':idbiblio', $idbiblio);
	$req->execute();
	$req->closeCursor();
} 

if(!empty($_POST['titre']) && !empty($_POST['idauteur']) && !empty($_POST['publi']))
{
	$idm = (isset($_SESSION['idmorigin'])) ? $_SESSION['idmorigin'] : $_SESSION['idmembre'];
	
	$idbiblio = $_POST['idbiblio'];
	$titre = strip_tags($_POST['titre'], '<em><i>');
	$titre = rtrim($titre);
	$publi = $_POST['publi'];
	$annee = $_POST['annee'];
	$tome = $_POST['tome'];
	$fas = $_POST['fas'];
	$page = $_POST['page'];
	$type = $_POST['type'];
	$isbn = $_POST['isbn'];
	$resum = strip_tags($_POST['resum'], '<em><i><strong><b>');
	$resum = rtrim($resum);
	$url = $_POST['lienw'];
	$mc = $_POST['mc'];
	$cdnom = $_POST['cdnom'];
	$dep = (isset($_POST['dep'])) ? $_POST['dep'] : '';	
	$codecom = $_POST['codecom'];
	$observa = $_POST['observa'];
			
	$auteur = explode(", ", $_POST['idauteur']);
	$plusauteur = (count($auteur) > 1) ? 'oui' : 'non';
	
	if($idbiblio == 0)//Nouvelle ref.
	{
		$dates = date("Y-m-d");
		$idbiblio = insere_biblio($auteur[0],$titre,$type,$publi,$annee,$tome,$fas,$page,$resum,$plusauteur,$dates,$isbn);
		if(!empty($idbiblio))
		{
			if($plusauteur == 'oui')
			{
				$auteuror = $auteur[0]; 
				foreach($auteur as $n)
				{
					if($n != $auteuror)
					{
						insere_plusauteur($idbiblio,$n);
					}
				}
			}
			$membre = cherche_membre($idm);
			$nom = $membre['nom'].' '.$membre['prenom'];
			insere_suivi($idbiblio,$idm,$nom);
			if(!empty($url))
			{
				insere_lienexterne($idbiblio,$url);
			}
			if($observa != 'NR')
			{
				insere_observa($idbiblio,$observa);
			}
			if(!empty($cdnom))
			{
				$tabcdnom = explode(",", $cdnom);
				foreach($tabcdnom as $n)
				{
					insere_taxon($idbiblio,$n);					
				}
			}
			if(!empty($mc))
			{
				$tabmc = explode(",", $mc);
				foreach($tabmc as $n)
				{
					insere_motcle($idbiblio,$n);					
				}
			}
			if(!empty($codecom))
			{
				$tabcodecom = explode(",", $codecom);
				foreach($tabcodecom as $n)
				{
					insere_codecom($idbiblio,$n);					
				}
			}
			$retour['statut'] = 'Oui';
			$retour['mes'] = '<div class="alert alert-success mt-1" role="alert">La référence a été ajoutée.</div>';
		}
		else
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger mt-1" role="alert">Erreur ! lors de l\'enregistrement </div>';
		}
	}
	else
	{
		$info = info_biblio($idbiblio);
		$ok = modif_biblio($auteur[0],$titre,$type,$publi,$annee,$tome,$fas,$page,$resum,$plusauteur,$idbiblio,$isbn);
		if($ok == 'oui')
		{
			$unseul = ($info['plusauteur'] == 'non') ? substr($info['idauteur'], 0, -2) : $info['idauteur'];
			if($unseul != $_POST['idauteur'])
			{
				sup_plusauteur($idbiblio);
				if(count($auteur) > 1)
				{
					$auteuror = $auteur[0]; 
					foreach($auteur as $n)
					{
						if($n != $auteuror)
						{
							insere_plusauteur($idbiblio,$n);
						}
					}		
				}				
			}
			if($mc != $info['idmc'])
			{
				sup_mot($idbiblio);
				if(!empty($mc))
				{
					$tabmc = explode(",", $mc);
					foreach($tabmc as $n)
					{
						insere_motcle($idbiblio,$n);					
					}
				}
			}
			if($cdnom != $info['cdnom'])
			{
				sup_taxon($idbiblio);
				if(!empty($cdnom))
				{
					$tabcdnom = explode(",", $cdnom);
					foreach($tabcdnom as $n)
					{
						insere_taxon($idbiblio,$n);					
					}
				}
			}
			if($codecom != $info['codecom'])
			{
				sup_commune($idbiblio);
				if(!empty($codecom))
				{
					$tabcodecom = explode(",", $codecom);
					foreach($tabcodecom as $n)
					{
						insere_codecom($idbiblio,$n);					
					}
				}
			}
			if($observa != $info['observa'])
			{
				if($observa != 'NR')
				{
					if(empty($info['observa']))
					{
						insere_observa($idbiblio,$observa);
					}
					else
					{
						modif_observa($idbiblio,$observa);
					}
				}
				else
				{
					sup_observa($idbiblio);
				}
			}
			if($url != $info['url'])
			{
				if(!empty($url))
				{
					if(empty($info['url']))
					{
						insere_lienexterne($idbiblio,$url);
					}
					else
					{
						modif_lien($idbiblio,$url);
					}
				}
				else
				{
					sup_lien($idbiblio);
				}
			}
			
			$retour['statut'] = 'Oui';
			$retour['mes'] = '<div class="alert alert-success mt-1" role="alert">La référence a été modifiée.</div>';
		}
		else
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger mt-1" role="alert">Erreur ! lors de la modification</div>';
		}
	}
}
else
{
	$retour['statut'] = 'Non'; 
	$retour['mes'] = '<div class="alert alert-danger mt-1" role="alert">La référence est incomplete !</div>';
}
echo json_encode($retour);	