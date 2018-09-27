<?php 
$titre = $rjson_biblio['titre'];
$description = $rjson_biblio['description'];
$script = '<script src="../dist/js/jquery.js" defer></script>
<script src="../dist/js/bootstrap.min.js" defer></script>';
$css = '';

include CHEMIN_MODELE.'accueil.php';

$derniereref = derniere_ref();
foreach($derniereref as $n)
{
	$fasc = (!empty ($n['fascicule'])) ? '('.$n['fascicule'].'), ' : ', ' ;
	$lib = $n['titre'].'. '.$n['publi'].', '.$n['tome'].''.$fasc.''.$n['annee'].': '.$n['page'];
	if($n['plusauteur'] == 'oui')
	{
		$plusauteur = recherche_auteur($n['idbiblio']);
		$nbauteur = count($plusauteur);
		if($nbauteur == 1)
		{
			$auteur = $n['nom'].' ('.$n['prenom'].') et '.$plusauteur[0]['nom'].' ('.$plusauteur[0]['prenom'].')';
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
		$auteur = $n['nom'].' ('.$n['prenom'].')';
	}
	if(!empty($n['codecom']))
	{
		$codecom = "'".$n['codecom']."'";
		$listecom = cherche_commune($codecom);
		if(count($listecom > 1))
		{
			foreach($listecom as $c)
			{
				$com[] = ['codecom'=>$c['codecom'], 'commune'=>$c['commune']];
			}
		}
		else
		{
			$com[] = ['codecom'=>$listecom[0]['codecom'], 'commune'=>$listecom[0]['commune']];
		}
	}
	else
	{ $com = null; }
	$ref[] = ['idbiblio'=>$n['idbiblio'],'ref'=>$lib,'date'=>$n['datefr'],'auteur'=>$auteur,'commune'=>$com];
	$com = null;
}
//dernier taxon
$taxon = dernier_taxon();

//nombre
$nbref = nbref();
$nbauteur = nbauteur();
$nbtaxon = nbtaxon();

include 'modules/accueil/vues/accueil.php';