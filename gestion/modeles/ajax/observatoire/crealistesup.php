<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';
function creerrang($disc)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");	
	$req = $bdd->query("CREATE TABLE $disc.rang (idrang smallint NOT NULL,rang character varying(5),CONSTRAINT rang_pkey PRIMARY KEY (idrang))") or die(print_r($bdd->errorInfo()));
	$req->closeCursor();
}	
function creersousgenre($disc)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");	
	$req = $bdd->query("CREATE TABLE $disc.sousgenre (cdnom integer NOT NULL,genre integer,sousgenre text,auteur text,locale character(3),CONSTRAINT sousgenre_pkey PRIMARY KEY (cdnom))") or die(print_r($bdd->errorInfo()));
	$req->closeCursor();
}	
function creergenre($disc)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");	
	$req = $bdd->query("CREATE TABLE $disc.genre (cdnom integer NOT NULL,cdsup integer,cdtaxsup integer,genre text,auteur text,locale character(3),CONSTRAINT genre_pkey PRIMARY KEY (cdnom))") or die(print_r($bdd->errorInfo()));
	$req->closeCursor();
}
function creersoustribu($disc)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");	
	$req = $bdd->query("CREATE TABLE $disc.soustribu (cdnom integer NOT NULL,cdsup integer,cdtaxsup integer,soustribu text,auteur text,locale character(3),CONSTRAINT soustribu_pkey PRIMARY KEY (cdnom))") or die(print_r($bdd->errorInfo()));
	$req->closeCursor();
}
function creertribu($disc)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");	
	$req = $bdd->query("CREATE TABLE $disc.tribu (cdnom integer NOT NULL,cdsup integer,cdtaxsup integer,tribu text,auteur text,locale character(3),CONSTRAINT tribu_pkey PRIMARY KEY (cdnom))") or die(print_r($bdd->errorInfo()));
	$req->closeCursor();
}
function creersousfamille($disc)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");	
	$req = $bdd->query("CREATE TABLE $disc.sousfamille (cdnom integer NOT NULL,cdsup integer,sousfamille text,auteur text,nomvern text,locale character(3),CONSTRAINT sousfamille_pkey PRIMARY KEY (cdnom))") or die(print_r($bdd->errorInfo()));
	$req->closeCursor();
}
function creerfamille($disc)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");	
	$req = $bdd->query("CREATE TABLE $disc.famille (cdnom integer NOT NULL,cdsup integer,famille character varying(40),auteur text,ordre character varying(40),classe character varying(30),nomvern text,locale character(3),CONSTRAINT famille_pkey PRIMARY KEY (cdnom))") or die(print_r($bdd->errorInfo()));
	$req->closeCursor();
}
function creerliste($disc)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");	
	$req = $bdd->query("CREATE TABLE $disc.liste (
						cdnom integer NOT NULL,
						cdref integer,
						cdsup integer,
						cdtaxsup integer,
						nom text,
						genre text,
						espece text,
						auteur text,
						rang character varying(5),
						famille integer,
						nomvern text,
						locale character (3),
						commentaire text,
						CONSTRAINT liste_pkey PRIMARY KEY (cdnom))") or die(print_r($bdd->errorInfo()));
	$req->closeCursor();
}
function table()
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT table_name FROM information_schema.tables WHERE table_schema='referentiel' AND table_name='liste'") or die(print_r($bdd->errorInfo()));
	$table = $req->rowCount();
	$req->closeCursor();
	return $table;		
}
function creerlisteref()
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");	
	$req = $bdd->query("CREATE TABLE referentiel.liste (
						cdnom integer NOT NULL,
						nom text,
						auteur text,
						nomvern text,
						observatoire character varying(10),
						rang character varying(5),
						CONSTRAINT liste_pkey PRIMARY KEY (cdnom))") or die(print_r($bdd->errorInfo()));
	$req->closeCursor();
}
function recherche_rang($valchoix,$choix)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->prepare("SELECT DISTINCT rang FROM referentiel.taxref WHERE $choix = :choix ORDER BY rang DESC") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':choix', $valchoix);
	$req->execute();
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}
function recherche_rangor($disc,$idrang)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->prepare("SELECT idrang FROM $disc.rang WHERE idrang = :idrang ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idrang', $idrang);
	$req->execute();
	$liste = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}
function insere_sgenre($valchoix,$choix,$disc)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->prepare("INSERT INTO $disc.sousgenre (cdnom,genre,sousgenre,auteur,locale) 
						SELECT cdnom, cdsup, nom, auteur, 'oui' AS locale FROM referentiel.taxref
						WHERE $choix = :choix AND rang = 'SSGN' AND cdnom = cdref") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':choix', $valchoix);
	$req->execute();
	$liste = $req->rowCount();
	$req->closeCursor();
	return $liste;		
}
function insere_genre($valchoix,$choix,$disc)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->prepare("INSERT INTO $disc.genre (cdnom,cdsup,cdtaxsup,genre,auteur,locale) 
						SELECT cdnom, cdsup, cdtaxsup, nom, auteur, 'oui' AS locale FROM referentiel.taxref
						WHERE $choix = :choix AND rang = 'GN' AND cdnom = cdref") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':choix', $valchoix);
	$req->execute();
	$liste = $req->rowCount();
	$req->closeCursor();
	return $liste;		
}
function insere_stribu($valchoix,$choix,$disc)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->prepare("INSERT INTO $disc.soustribu (cdnom,cdsup,cdtaxsup,soustribu,auteur,locale) 
						SELECT cdnom, cdsup, cdtaxsup, nom, auteur, 'oui' AS locale FROM referentiel.taxref
						WHERE $choix = :choix AND rang = 'SSTR' AND cdnom = cdref") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':choix', $valchoix);
	$req->execute();
	$liste = $req->rowCount();
	$req->closeCursor();
	return $liste;		
}
function insere_tribu($valchoix,$choix,$disc)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->prepare("INSERT INTO $disc.tribu (cdnom,cdsup,cdtaxsup,tribu,auteur,locale) 
						SELECT cdnom, cdsup, cdtaxsup, nom, auteur, 'oui' AS locale FROM referentiel.taxref
						WHERE $choix = :choix AND rang = 'TR' AND cdnom = cdref") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':choix', $valchoix);
	$req->execute();
	$liste = $req->rowCount();
	$req->closeCursor();
	return $liste;		
}
function insere_sfamille($valchoix,$choix,$disc)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->prepare("INSERT INTO $disc.sousfamille (cdnom,cdsup,sousfamille,auteur,nomvern,locale) 
						SELECT cdnom, cdsup, nom, auteur, nomvern, 'oui' AS locale FROM referentiel.taxref
						WHERE $choix = :choix AND rang = 'SBFM' AND cdnom = cdref") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':choix', $valchoix);
	$req->execute();
	$liste = $req->rowCount();
	$req->closeCursor();
	return $liste;		
}
function insere_famille($valchoix,$choix,$disc)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->prepare("INSERT INTO $disc.famille (cdnom,cdsup,famille,auteur,ordre,classe,nomvern,locale) 
						SELECT cdnom, cdsup, nom, auteur, ordre, classe, nomvern, 'oui' AS locale FROM referentiel.taxref
						WHERE $choix = :choix AND rang = 'FM' AND cdnom = cdref") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':choix', $valchoix);
	$req->execute();
	$liste = $req->rowCount();
	$req->closeCursor();
	return $liste;		
}
function classe($disc)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT DISTINCT classe FROM $disc.famille") or die(print_r($bdd->errorInfo()));
	$classe = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $classe;
}
//liste animalia
function insere_liste($valchoix,$choix,$disc)
{
	set_time_limit(0);
	$bdd = PDO2::getInstance();		
	$req = $bdd->prepare("SELECT taxref.cdnom, taxref.cdref, taxref.cdsup, taxref.cdtaxsup, nom, taxref.auteur, rang, famille.cdnom AS famille, taxref.nomvern FROM referentiel.taxref
						INNER JOIN $disc.famille ON famille.famille = taxref.famille
						WHERE taxref.$choix = :choix AND (rang = 'GN' OR rang = 'ES' OR rang = 'SSES') ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':choix', $valchoix);
	$req->execute();
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	$req = $bdd->prepare("INSERT INTO $disc.liste (cdnom,cdref,cdsup,cdtaxsup,nom,genre,espece,auteur,rang,famille,nomvern,locale) VALUES(:cdnom, :cdref, :cdsup, :cdtaxsup, :nom, :genre, :espece, :auteur, :rang, :famille, :nomvern, :locale) ") or die(print_r($bdd->errorInfo()));
	$nb = 0;
	foreach ($liste as $n)
	{
		$tabnv = explode(',',$n['nomvern']);
		$nomvern = preg_replace('/\((.*)\)/', '', $tabnv[0]);		
		if ($n['cdnom'] == $n['cdref'] and ($n['rang'] == 'ES' || $n['rang'] == 'SSES')){$tab = explode(' ',$n['nom']);$genre = $tab[0];$espece = $tab[1];}
		else{$genre = null;$espece = null;}
		$req->execute(array('cdnom'=>$n['cdnom'], 'cdref'=>$n['cdref'], 'cdsup'=>$n['cdsup'], 'cdtaxsup'=>$n['cdtaxsup'], 'nom'=>$n['nom'], 'genre'=>$genre, 'espece'=>$espece, 'auteur'=>$n['auteur'], 'rang'=>$n['rang'], 'famille'=>$n['famille'], 'nomvern'=>$nomvern, 'locale'=>'oui'));
		$nb++;
	}
	$req->closeCursor();
	return $nb;		
}
//liste autre (bota)
function insere_listebo($valchoix,$choix,$disc)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->prepare("INSERT INTO $disc.liste (cdnom,cdref,cdsup,cdtaxsup,nom,genre,espece,auteur,rang,famille,nomvern,locale)
						SELECT taxref.cdnom, taxref.cdref, taxref.cdsup, taxref.cdtaxsup, nom, '' AS genre, '' AS espece, taxref.auteur, rang, famille.cdnom AS famille, taxref.nomvern, 'oui' AS locale FROM referentiel.taxref
						INNER JOIN $disc.famille ON famille.famille = taxref.famille
						WHERE taxref.$choix = :choix AND (rang = 'GN' OR rang = 'ES' OR rang = 'SSES') ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':choix', $valchoix);
	$req->execute();
	$nb = $req->rowCount();
	$req->closeCursor();
	return $nb;	
}
function verifliste($disc)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query ("SELECT $disc.liste.cdnom FROM $disc.liste
						INNER JOIN referentiel.liste ON referentiel.liste.cdnom = $disc.liste.cdnom") or die(print_r($bdd->errorInfo()));
	$liste = $req->rowCount();
	$req->closeCursor();
	return $liste;		
}
function nombredebut($disc)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query ("SELECT COUNT(cdnom) FROM $disc.liste
						WHERE cdnom = cdref AND (rang = 'GN' OR rang = 'ES' OR rang = 'SSES') ") or die(print_r($bdd->errorInfo()));
	$liste = $req->fetchColumn();
	$req->closeCursor();
	return $liste;		
}
function supligne($disc)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("DELETE FROM referentiel.liste WHERE observatoire = :sel ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':sel', $disc);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;
}
function insere_listeref($disc)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query ("INSERT INTO referentiel.liste (cdnom,nom,auteur,nomvern,observatoire,rang) 
						SELECT cdnom, nom, auteur, nomvern, '$disc' AS observatoire, rang FROM $disc.liste
						WHERE cdnom = cdref ") or die(print_r($bdd->errorInfo()));
	$liste = $req->rowCount();
	$req->closeCursor();
	return $liste;		
}
function insere_rang($idrang,$lrang,$disc)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->prepare("INSERT INTO $disc.rang (idrang,rang) VALUES(:id, :rang) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $idrang);
	$req->bindValue(':rang', $lrang);
	$req->execute();
	$req->closeCursor();	
}

if (isset($_POST['choix']) and isset($_POST['valchoix']) and isset($_POST['disc']))
{
	$disc = ($_POST['disc']);
	$choix = $_POST['choix'];
	$valchoix = $_POST['valchoix'];
	
	$rang = recherche_rang($valchoix,$choix);	
		
	foreach ($rang as $n)
	{
		if ($n['rang'] == 'SSGN')
		{
			$idrang = 3;
			$lrang = $n['rang'];
			$rangor = recherche_rangor($disc,$idrang);
			if($rangor['idrang'] != 3)
			{
				creersousgenre($disc);
				insere_rang($idrang,$lrang,$disc);				
			}
			file_put_contents('progression.txt', 'Insertion sous genre');		
			$nbsgenre = insere_sgenre($valchoix,$choix,$disc);			
			if ($nbsgenre > 0)	
			{
				$mes[] = '<div class="alert alert-success" role="alert"><b>Sousgenre</b> dans schema '.$disc.'. '.$nbsgenre.' lignes insérées</div>';
			}
			else
			{
				$retour['statut'] = 'Non';
				//$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Erreur ! creation, insertion table sousgenre.</p></div>';
				$mes[] = '<div class="alert alert-warning" role="alert"><p>Erreur ! creation, insertion table sousgenre.</p></div>';
				//echo json_encode($retour);	
				//exit;
			}
		}
		if ($n['rang'] == 'GN')
		{
			$idrang = 4;
			$lrang = $n['rang'];
			$rangor = recherche_rangor($disc,$idrang);
			if($rangor['idrang'] != 4)
			{
				creergenre($disc);
				insere_rang($idrang,$lrang,$disc);
			}
			file_put_contents('progression.txt', 'Insertion des genres');			
			$nbgenre = insere_genre($valchoix,$choix,$disc);			
			if ($nbgenre > 0)	
			{
				$mes[] = '<div class="alert alert-success" role="alert"><b>Genre</b> dans schema '.$disc.'. '.$nbgenre.' lignes insérées</div>';
			}
			else
			{
				$retour['statut'] = 'Non';
				$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur ! creation, insertion table genre.</div>';
				echo json_encode($retour);	
				exit;
			}
		}
		if ($n['rang'] == 'SSTR')
		{
			$idrang = 5;
			$lrang = $n['rang'];
			$rangor = recherche_rangor($disc,$idrang);
			if($rangor['idrang'] != 5)
			{
				creersoustribu($disc);
				insere_rang($idrang,$lrang,$disc);
			}
			file_put_contents('progression.txt', 'Insertion sous tribu');
			$nbstribu = insere_stribu($valchoix,$choix,$disc);
			if ($nbstribu > 0)	
			{
				$mes[] = '<div class="alert alert-success" role="alert"><b>Soustribu</b> dans schema '.$disc.'. '.$nbstribu.' lignes insérées</div>';
			}
			else
			{
				$retour['statut'] = 'Non';
				$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur ! creation, insertion table soustribu.</div>';
				echo json_encode($retour);	
				exit;
			}
		}
		if ($n['rang'] == 'TR')
		{
			$idrang = 6;
			$lrang = $n['rang'];
			$rangor = recherche_rangor($disc,$idrang);
			if($rangor['idrang'] != 6)
			{
				creertribu($disc);
				insere_rang($idrang,$lrang,$disc);
			}
			file_put_contents('progression.txt', 'Insertion des tribu');			
			$nbtribu = insere_tribu($valchoix,$choix,$disc);
			if ($nbtribu > 0)	
			{
				$mes[] = '<div class="alert alert-success" role="alert"><b>Tribu</b> dans schema '.$disc.'. '.$nbtribu.' lignes insérées</div>';
			}
			else
			{
				$retour['statut'] = 'Non';
				$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur ! creation, insertion table tribu.</div>';
				echo json_encode($retour);	
				exit;
			}
		}
		if ($n['rang'] == 'SBFM')
		{
			$idrang = 7;
			$lrang = $n['rang'];
			$rangor = recherche_rangor($disc,$idrang);
			if($rangor['idrang'] != 7)
			{
				creersousfamille($disc);
				insere_rang($idrang,$lrang,$disc);
			}			
			file_put_contents('progression.txt', 'Insertion sous famille');			
			$nbsfamille = insere_sfamille($valchoix,$choix,$disc);			
			if ($nbsfamille > 0)	
			{
				$mes[] = '<div class="alert alert-success" role="alert"><b>Sousfamille</b> dans schema '.$disc.'. '.$nbsfamille.' lignes insérées</div>';
			}
			else
			{
				$retour['statut'] = 'Non';
				$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur ! creation, insertion table sousfamille.</div>';
				echo json_encode($retour);	
				exit;
			}
		}
		if ($n['rang'] == 'FM')
		{
			$idrang = 8;
			$lrang = $n['rang'];
			$rangor = recherche_rangor($disc,$idrang);
			if($rangor['idrang'] != 8)
			{
				creerfamille($disc);
				insere_rang($idrang,$lrang,$disc);
			}	
			file_put_contents('progression.txt', 'Insertion des familles');			
			$nbfamille = insere_famille($valchoix,$choix,$disc);			
			if ($nbfamille > 0)	
			{
				$mes[] = '<div class="alert alert-success" role="alert"><b>Famille</b> dans schema '.$disc.'. '.$nbfamille.' lignes insérées</div>';
			}
			else
			{
				$retour['statut'] = 'Non';
				$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur ! creation, insertion table famille.</div>';
				echo json_encode($retour);	
				exit;
			}
		}
		if ($n['rang'] == 'ES')
		{
			file_put_contents('progression.txt', 'Insertion espèces');
			$classe = classe($disc);
			$nbliste = insere_liste($valchoix,$choix,$disc);
			//en attente, rajouter regne dans taxref			
			/*if ($classe['classe'] != 'Equisetopsida')
			{
				$nbliste = insere_liste($valchoix,$choix,$disc);
			}
			else
			{
				$nbliste = insere_listebo($valchoix,$choix,$disc);			
			}*/
			if ($nbliste > 0)	
			{
				$nbor = nombredebut($disc);				
				$verif = verifliste($disc);
				if ($verif > $nbor)
				{
					$retour['verif'] = 'Non';
					$mes[] = '<div class="alert alert-success" role="alert"><b>Liste</b> dans schema '.$disc.', '.$nbliste.' lignes insérées</div>';
				}
				else
				{
					$mes[] = '<div class="alert alert-success" role="alert"><b>Liste</b> dans schema '.$disc.', '.$nbliste.' lignes insérées</div>';
					$vali = supligne($disc);
					if ($vali == 'oui')
					{
						$nblisteref = insere_listeref($disc);
						if ($nblisteref > 0)	
						{
							$retour['verif'] = 'Oui';
							$mes[] = '<div class="alert alert-success" role="alert">'.$nblisteref.' lignes insérées dans table <b>liste</b> du schema referentiel</div>';
						}
						else
						{
							$retour['statut'] = 'Non';
							$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur ! creation, insertion table liste schema referentiel.</div>';
							echo json_encode($retour);	
							exit;
						}
					}
					else
					{
						$retour['statut'] = 'Non';
						$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur ! suppression des taxons de la table liste du schema referentiel.</div>';
						echo json_encode($retour);	
						exit;
					}
				}				
			}
			else
			{
				$retour['statut'] = 'Non';
				$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur ! creation, insertion table liste.</div>';
				echo json_encode($retour);	
				exit;
			}
		}
		if ($n['rang'] == 'SSES')
		{
			$idrang = 1;
			$lrang = $n['rang'];
			$rangor = recherche_rangor($disc,$idrang);
			if($rangor['idrang'] != 1)
			{
				insere_rang($idrang,$lrang,$disc);
			}			
		}
	}
	
	$retour['statut'] = 'Oui';
	$retour['mes'] = $mes;
	
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Aucun observatoire de choisit.</p></div>';
}
echo json_encode($retour);