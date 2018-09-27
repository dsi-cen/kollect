<?php 
$script = '<script src="../dist/js/jquery.js" defer></script>
<script src="../dist/js/bootstrap.min.js" defer></script>';
$css = '';

if(isset($_GET['id'])) 
{
	include CHEMIN_MODELE.'biblio.php';
	
	$id = htmlspecialchars($_GET['id']);
	
	$biblio = recherche($id);
	
	$titre = strip_tags($biblio['titre']);
	$description = 'Information sur la référence '.$titre;
	
	if($biblio['plusauteur'] == 'oui')
	{
		$plusauteur = recherche_auteur($id);
		$nbauteur = count($plusauteur);
		if($nbauteur == 1)
		{
			$auteur = '<a href="index.php?module=liste&amp;action=liste&amp;choix=aut&amp;id='.$biblio['idauteur'].'">'.$biblio['nom'].' ('.$biblio['prenom'].')</a> et <a href="index.php?module=liste&amp;action=liste&amp;choix=aut&amp;id='.$plusauteur[0]['idauteur'].'">'.$plusauteur[0]['nom'].' ('.$plusauteur[0]['prenom'].')</a>';
		}
		else
		{
			$tabaut[] = ['idauteur'=>$biblio['idauteur'],'nom'=>$biblio['nom'],'prenom'=>$biblio['prenom']];
			foreach($plusauteur as $a)
			{
				$tabaut[] = ['idauteur'=>$a['idauteur'],'nom'=>$a['nom'],'prenom'=>$a['prenom']];
			}
			$nbauteur = count($tabaut);
			$et = $nbauteur - 1;
			$auteur = null;
			for($i = 0; $i < $nbauteur; $i++) 
			{
				if($i == 0)
				{
					$auteur .= '<a href="index.php?module=liste&amp;action=liste&amp;choix=aut&amp;id='.$tabaut[$i]['idauteur'].'">'.$tabaut[$i]['nom'].' ('.$tabaut[$i]['prenom'].')</a>';
				}
				if($i == $et)
				{
					$auteur .= ' et <a href="index.php?module=liste&amp;action=liste&amp;choix=aut&amp;id='.$tabaut[$i]['idauteur'].'">'.$tabaut[$i]['nom'].' ('.$tabaut[$i]['prenom'].')</a>';
				}
				if($i != 0 && $i < $et)
				{
					$auteur .= ', <a href="index.php?module=liste&amp;action=liste&amp;choix=aut&amp;id='.$tabaut[$i]['idauteur'].'">'.$tabaut[$i]['nom'].' ('.$tabaut[$i]['prenom'].')</a>';
				}
			}
		}
		$libaut = 'Auteurs';
	}
	else
	{
		$auteur = '<a href="index.php?module=liste&amp;action=liste&amp;choix=aut&amp;id='.$biblio['idauteur'].'">'.$biblio['nom'].' ('.$biblio['prenom'].')</a>';
		$libaut = 'Auteur';
	}
	foreach($rjson_site['observatoire'] as $n)
	{
		$tabobserva[$n['nomvar']] = $n['nom'];
		$tablatin[$n['nomvar']] = $n['latin'];
	}
	//isbn
	if(!empty($biblio['isbn']))
	{
		$isbn = urlencode('isbn '.$biblio['isbn']);
	}
	//observatoire
	if(!empty($biblio['observa']))
	{
		$observa = $tabobserva[$biblio['observa']];
	}
	//mot cle
	$mot = motcle($id);
	if($mot != false)
	{
		foreach($mot as $n)
		{
			$tabmot[] = ['idmc'=>$n['idmc'],'mot'=>$n['mot']];
		}		
	}
	//commune
	$com = commune($id);
	if($com != false)
	{
		foreach($com as $n)
		{
			$tabcom[] = ['codecom'=>$n['codecom'],'commune'=>$n['commune']];
		}		
	}
	//taxon	
	$taxon = taxon($id);
	if($taxon != false)
	{
		$latin = (isset($_SESSION['latin'])) ? $_SESSION['latin'] : '';
		foreach($taxon as $n)
		{
			$latobserva = $tablatin[$n['observatoire']];
			if($latin == 'oui')
			{
				$tabtaxon[] = ['cdnom'=>$n['cdnom'],'observa'=>$n['observatoire'],'nom'=>'<i>'.$n['nom'].'</i>'];
			}
			elseif($latin == 'non')
			{
				$tabtaxon[] = (!empty($n['nomvern'])) ? ['cdnom'=>$n['cdnom'],'observa'=>$n['observatoire'],'nom'=>$n['nomvern']] : ['cdnom'=>$n['cdnom'],'observa'=>$n['observatoire'],'nom'=>'<i>'.$n['nom'].'</i>'];
			}
			else
			{
				if($latobserva == 'oui')
				{
					$tabtaxon[] = ['cdnom'=>$n['cdnom'],'observa'=>$n['observatoire'],'nom'=>'<i>'.$n['nom'].'</i>'];
				}
				else
				{
					$tabtaxon[] = (!empty($n['nomvern'])) ? ['cdnom'=>$n['cdnom'],'observa'=>$n['observatoire'],'nom'=>$n['nomvern']] : ['cdnom'=>$n['cdnom'],'observa'=>$n['observatoire'],'nom'=>'<i>'.$n['nom'].'</i>'];
				}
			}
		}
		$nbtaxon = count($tabtaxon);
		if($nbtaxon > 20)
		{
			for($i = 0; $i < 20; $i++) 
			{
				$tabtaxon1[] = ['cdnom'=>$tabtaxon[$i]['cdnom'],'observa'=>$tabtaxon[$i]['observa'],'nom'=>$tabtaxon[$i]['nom']];
			}
			for($i = 20; $i < $nbtaxon; $i++) 
			{
				$tabtaxon2[] = ['cdnom'=>$tabtaxon[$i]['cdnom'],'observa'=>$tabtaxon[$i]['observa'],'nom'=>$tabtaxon[$i]['nom']];
			}
		}
		else
		{
			$tabtaxon1 = $tabtaxon;
		}
	}
	$url = 'http://'.$_SERVER['HTTP_HOST'].''.$_SERVER['REQUEST_URI'];
	
	include CHEMIN_VUE.'biblio.php';
}