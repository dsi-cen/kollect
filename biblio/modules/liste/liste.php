<?php 
$scripthaut = '<script type="text/javascript" src="../dist/js/jquery.js"></script>';
$script = '<script src="../dist/js/bootstrap.min.js" defer></script>
<script type="text/javascript" src="../dist/js/jquery.dataTables.min.js" defer></script>
<script type="text/javascript" src="../dist/js/datatables/dataTables.buttons.min.js" defer></script>
<script type="text/javascript" src="../dist/js/datatables/jszip.min.js" defer></script>
<script type="text/javascript" src="../dist/js/datatables/buttons.html5.min.js" defer></script>';
$css = '<link rel="stylesheet" type="text/css" href="../dist/css/dataTables.bootstrap4.css">
<link rel="stylesheet" type="text/css" href="../dist/css/buttons.bootstrap4.min.css">';

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
				$auteur = $n['nom'].' ('.$n['prenomab'].') et '.$plusauteur[0]['nom'].' ('.$plusauteur[0]['prenomab'].')';
			}
			else
			{
				$tabaut[] = ['nom'=>$n['nom'],'prenom'=>$n['prenomab']];
				foreach($plusauteur as $a)
				{
					$tabaut[] = ['nom'=>$a['nom'],'prenom'=>$a['prenomab']];
				}
				$nbauteur = count($tabaut);
				$et = $nbauteur - 1;
				$auteur = null;
				for($i = 0; $i < $nbauteur; $i++) 
				{
					if($i == 0)
					{
						$auteur .= $tabaut[$i]['nom'].' ('.$tabaut[$i]['prenom'].')';
					}
					if($i == $et)
					{
						$auteur .= ' et '.$tabaut[$i]['nom'].' ('.$tabaut[$i]['prenom'].')';
					}
					if($i != 0 && $i < $et)
					{
						$auteur .= ', '.$tabaut[$i]['nom'].' ('.$tabaut[$i]['prenom'].')';
					}
				}
				$tabaut = null;
			}			
		}
		else
		{
			$auteur = $n['nom'].' ('.$n['prenomab'].')';
		}
		$vol = (empty($n['fascicule'])) ? $n['tome'] : $n['tome'].'('.$n['fascicule'].')';
		$bib[] = ['idbiblio'=>$n['idbiblio'],'auteur'=>$auteur,'titre'=>$n['titre'],'annee'=>$n['annee'],'tome'=>$vol,'fascicule'=>$n['fascicule'],'publi'=>$n['publi']];		
	}
	return $bib;	
}

if(isset($_GET['choix']) && isset($_GET['id'])) 
{
	include CHEMIN_MODELE.'liste.php';
	
	$choix = htmlspecialchars($_GET['choix']);
	$id = htmlspecialchars($_GET['id']);
	
	if($choix == 'observa')
	{
		foreach($rjson_site['observatoire'] as $n)
		{
			if($n['nomvar'] == $id)
			{
				$nom = $n['nom'];
			}
		}		
		$labbib = 'observatoire';
		$bread = '<li class="breadcrumb-item"><a href="index.php?module=observa&amp;action=observa">Par observatoire</a></li>';
		$titre = 'Bibliographie '.$nom;
		$description = 'Bibliographie des '.$nom;
		$liste = recherche_observa($id);
		$bib = ($liste != false) ? preparation($liste) : null;
	}
	if($choix == 'aut')
	{
		$labbib = 'auteur';
		$bread = '<li class="breadcrumb-item"><a href="index.php?module=auteur&amp;action=auteur">Par auteur</a></li>';
		$auteur = auteur($id);
		$nom = $auteur['nom'].' '.$auteur['prenom'];
		$titre = 'Bibliographie de '.$nom;
		$description = 'Bibliographie de '.$nom;
		$liste = recherche_auteur($id);
		$bib = ($liste != false) ? preparation($liste) : null;
	}
	if($choix == 'mot')
	{
		$labbib = 'mot-clé';
		$bread = '<li class="breadcrumb-item"><a href="index.php?module=motcle&amp;action=motcle">Par mot-clés</a></li>';
		$mot = motcle($id);
		$nom = $mot['mot'];
		$titre = 'Bibliographie '.$nom;
		$description = 'Bibliographie pour le mot-clé '.$nom;
		$liste = recherche_motcle($id);
		$bib = ($liste != false) ? preparation($liste) : null;
	}	
	if($choix == 'com')
	{
		$labbib = 'commune';
		$bread = '<li class="breadcrumb-item"><a href="index.php?module=commune&amp;action=commune">Par commune</a></li>';
		$com = commune($id);
		$nom = $com['commune'];
		$titre = 'Bibliographie '.$nom;
		$description = 'Bibliographie pour la commune '.$nom;
		$liste = recherche_commune($id);
		$bib = ($liste != false) ? preparation($liste) : null;
	}
	if($choix == 'publi')
	{
		$labbib = 'publication';
		$bread = '<li class="breadcrumb-item"><a href="index.php?module=publi&amp;action=publi">Par publication</a></li>';
		$nom = $id;
		$titre = 'Bibliographie '.$nom;
		$description = 'Bibliographie pour la publication '.$nom;
		$liste = recherche_publi($id);
		$bib = ($liste != false) ? preparation($liste) : null;
	}
	if($choix == 'taxon')
	{
		$labbib = 'espèce';
		$bread = '<li class="breadcrumb-item"><a href="index.php?module=taxon&amp;action=taxon">Par espèce</a></li>';
		$taxon = taxon($id);
		$nom = $taxon['nom'];
		$titre = 'Bibliographie '.$nom;
		$description = 'Bibliographie pour l\'espèce '.$nom;
		$liste = recherche_taxon($id);
		$bib = ($liste != false) ? preparation($liste) : null;
	}	
	
	include CHEMIN_VUE.'liste.php';
}