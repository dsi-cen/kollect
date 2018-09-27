<?php
$scripthaut = '<script src="../dist/js/jquery.js"></script>';
$script = '<script src="../dist/js/bootstrap.min.js" defer></script>';
$css = '';
$titre = 'Nouvelles espèces - '.$nomd;
$description = 'Liste des nouvelles espèces de'.$nomd.' '.$rjson_site['ad1'].' '.$rjson_site['lieu'].'';

include CHEMIN_MODELE.'nouveau.php';

$liste = nouveau($nomvar);
$listeannee = nouveauannee($nomvar);
$famille = nouveaufamille($nomvar);

$choixlatin = (isset($_SESSION['latin'])) ? $_SESSION['latin'] : '';
if($rjson_obser['latin'] == 'oui' && $choixlatin == 'oui')
{
	$latin = 'nom';
}
elseif($rjson_obser['latin'] == 'oui' && ($choixlatin == 'defaut' || $choixlatin == ''))
{
	$latin = 'nom';
}
elseif($rjson_obser['latin'] == 'non' && $choixlatin == 'oui')
{
	$latin = 'nom';
}
elseif($rjson_obser['latin'] == 'non' || $choixlatin == 'non') 
{
	$latin = 'nomvern';
}
elseif($rjson_obser['latin'] == 'oui' && $choixlatin == 'non') 
{
	$latin = 'nomvern';
}

foreach($liste as $n)
{
	$annee[] = $n['annee'];
	if($n['plusobser'] == 'oui')
	{
		$obs2[] = '<a href="../index.php?module=infoobser&amp;action=info&amp;idobser='.$n['idobser'].'">'.$n['prenom'].' '.$n['nomobser'].'</a>';
		$obsplus = cherche_observateur($n['idfiche']);
		foreach($obsplus as $o)
		{
			$obs2[] = '<a href="../index.php?module=infoobser&amp;action=info&amp;idobser='.$o['idobser'].'">'.$o['prenom'].' '.$o['nom'].'</a>'; 
		}
		$observateur = implode(', ', $obs2);
		$obs2 = null;
	}
	else
	{
		$observateur = '<a href="../index.php?module=infoobser&amp;action=info&amp;idobser='.$n['idobser'].'">'.$n['prenom'].' '.$n['nomobser'].'</a>';
	}
	if($latin == 'nom')
	{
		$taxon = '<i>'.$n['nom'].'</i>';
	}
	else
	{
		$taxon = ($n['nomvern'] != '') ? $n['nomvern'].' <i>('.$n['nom'].')</i>' : '<i>'.$n['nom'].'</i>';
	}
	if($n['iddet'] == $n['idobser'] || $n['iddet'] == '')
	{
		$det = $n['prenom'].' '.$n['nomobser'];
	}
	else
	{
		$tmpdet = determinateur($n['iddet']);
		$det = $tmpdet['prenom'].' '.$tmpdet['nom'];
	}
	$new[] = ['annee'=>$n['annee'], 'famille'=>$n['famille'], 'cdref'=>$n['cdref'], 'nom'=>$taxon, 'datefr'=>$n['datefr'], 'observateur'=>$observateur, 'det'=>$det, 'idobs'=>$n['idobs']];
}
$annee = array_flip($annee);


include CHEMIN_VUE.'nouveau.php';