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
						ir character varying(2),
						vali smallint,
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
		if($n['cdnom'] == $n['cdref'] and ($n['rang'] == 'ES' || $n['rang'] == 'SSES')){ $tab = explode(' ',$n['nom']); $genre = $tab[0]; $espece = $tab[1]; }
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
function insere_listeref($disc)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query ("INSERT INTO referentiel.liste (cdnom,nom,auteur,nomvern,observatoire,rang,vali) 
						SELECT cdnom, nom, auteur, nomvern, '$disc' AS observatoire, rang, 0 AS vali FROM $disc.liste
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
	creerrang($disc);
	foreach ($rang as $n)
	{
		if ($n['rang'] == 'SSGN')
		{
			file_put_contents('progression.txt', 'Creation table et insertion sous genre');
			creersousgenre($disc);
			$nbsgenre = insere_sgenre($valchoix,$choix,$disc);
			$idrang = 3;
			$lrang = $n['rang'];
			insere_rang($idrang,$lrang,$disc);
			if ($nbsgenre > 0)	
			{
				$mes[] = '<div class="alert alert-success" role="alert"><p>Creation table <b>sousgenre</b> dans schema '.$disc.'. '.$nbsgenre.' lignes insérées</p></div>';
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
			file_put_contents('progression.txt', 'Creation table et insertion des genres');
			creergenre($disc);
			$nbgenre = insere_genre($valchoix,$choix,$disc);
			$idrang = 4;
			$lrang = $n['rang'];
			insere_rang($idrang,$lrang,$disc);
			if ($nbgenre > 0)	
			{
				$mes[] = '<div class="alert alert-success" role="alert"><p>Creation table <b>genre</b> dans schema '.$disc.'. '.$nbgenre.' lignes insérées</p></div>';
			}
			else
			{
				$retour['statut'] = 'Non';
				$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Erreur ! creation, insertion table genre.</p></div>';
				echo json_encode($retour);	
				exit;
			}
		}
		if ($n['rang'] == 'SSTR')
		{
			file_put_contents('progression.txt', 'Creation table et insertion sous tribu');
			creersoustribu($disc);
			$nbstribu = insere_stribu($valchoix,$choix,$disc);
			$idrang = 5;
			$lrang = $n['rang'];
			insere_rang($idrang,$lrang,$disc);
			if ($nbstribu > 0)	
			{
				$mes[] = '<div class="alert alert-success" role="alert"><p>Creation table <b>soustribu</b> dans schema '.$disc.'. '.$nbstribu.' lignes insérées</p></div>';
			}
			else
			{
				$retour['statut'] = 'Non';
				$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Erreur ! creation, insertion table soustribu.</p></div>';
				echo json_encode($retour);	
				exit;
			}
		}
		if ($n['rang'] == 'TR')
		{
			file_put_contents('progression.txt', 'Creation table et insertion des tribu');
			creertribu($disc);
			$nbtribu = insere_tribu($valchoix,$choix,$disc);
			$idrang = 6;
			$lrang = $n['rang'];
			insere_rang($idrang,$lrang,$disc);
			if ($nbtribu > 0)	
			{
				$mes[] = '<div class="alert alert-success" role="alert"><p>Creation table <b>tribu</b> dans schema '.$disc.'. '.$nbtribu.' lignes insérées</p></div>';
			}
			else
			{
				$retour['statut'] = 'Non';
				$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Erreur ! creation, insertion table tribu.</p></div>';
				echo json_encode($retour);	
				exit;
			}
		}
		if ($n['rang'] == 'SBFM')
		{
			file_put_contents('progression.txt', 'Creation table et insertion sous famille');
			creersousfamille($disc);
			$nbsfamille = insere_sfamille($valchoix,$choix,$disc);
			$idrang = 7;
			$lrang = $n['rang'];
			insere_rang($idrang,$lrang,$disc);
			if ($nbsfamille > 0)	
			{
				$mes[] = '<div class="alert alert-success" role="alert"><p>Creation table <b>sousfamille</b> dans schema '.$disc.'. '.$nbsfamille.' lignes insérées</p></div>';
			}
			else
			{
				$retour['statut'] = 'Non';
				$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Erreur ! creation, insertion table sousfamille.</p></div>';
				echo json_encode($retour);	
				exit;
			}
		}
		if ($n['rang'] == 'FM')
		{
			file_put_contents('progression.txt', 'Creation table et insertion des familles');
			creerfamille($disc);
			$nbfamille = insere_famille($valchoix,$choix,$disc);
			$idrang = 8;
			$lrang = $n['rang'];
			insere_rang($idrang,$lrang,$disc);
			if ($nbfamille > 0)	
			{
				$mes[] = '<div class="alert alert-success" role="alert"><p>Creation table <b>famille</b> dans schema '.$disc.'. '.$nbfamille.' lignes insérées</p></div>';
			}
			else
			{
				$retour['statut'] = 'Non';
				$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Erreur ! creation, insertion table famille.</p></div>';
				echo json_encode($retour);	
				exit;
			}
		}
		if ($n['rang'] == 'ES')
		{
			file_put_contents('progression.txt', 'Creation table et insertion espèces');
			$classe = classe($disc);
			creerliste($disc);
			$table = table();
			if ($table == 0)	
			{
				creerlisteref();
			}
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
			$idrang = 2;
			$lrang = $n['rang'];
			insere_rang($idrang,$lrang,$disc);
			if ($nbliste > 0)	
			{
				$verif = verifliste($disc);
				if($verif > 0)
				{
					$retour['verif'] = 'Non';
					$mes[] = '<div class="alert alert-success" role="alert"><p>Creation Table <b>liste</b> dans schema '.$disc.', '.$nbliste.' lignes insérées</p></div>';
					$mes[] = '<div class="alert alert-danger" role="alert">Votre observatoire comprends des taxons déjà présent dans un autre observatoire ! Vous devrez les supprimer (gestion des taxons).</div>';
				}
				else
				{
					$mes[] = '<div class="alert alert-success" role="alert"><p>Creation Table <b>liste</b> dans schema '.$disc.', '.$nbliste.' lignes insérées</p></div>';
					$nblisteref = insere_listeref($disc);
					if ($nblisteref > 0)	
					{
						$retour['verif'] = 'Oui';
						$mes[] = '<div class="alert alert-success" role="alert"><p>'.$nblisteref.' lignes insérées dans table <b>liste</b> du schema referentiel</p></div>';
					}
					else
					{
						$retour['statut'] = 'Non';
						$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Erreur ! creation, insertion table liste schema referentiel.</p></div>';
						echo json_encode($retour);	
						exit;
					}
				}				
			}
			else
			{
				$retour['statut'] = 'Non';
				$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Erreur ! creation, insertion table liste.</p></div>';
				echo json_encode($retour);	
				exit;
			}
		}
		if ($n['rang'] == 'SSES')
		{
			file_put_contents('progression.txt', 'Creation table et insertion sous espèces');
			$idrang = 1;
			$lrang = $n['rang'];
			insere_rang($idrang,$lrang,$disc);
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