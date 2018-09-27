<?php 
$titre = $rjson_site['titre'];
$stitre = $rjson_site['stitre'];
$description = $rjson_site['description'];
$script = '<script src="dist/js/jquery.js" defer></script>
<script src="dist/js/bootstrap.min.js" defer></script>';
$css = '';
if(!isset($rjson_site['config']))
{
	include CHEMIN_MODELE.'accueil.php';

	$nbobser = nbobservateur();
	$nbobs = nbobs();
	$nbsp = nbespece();
	$nbphoto = nbphoto();
	$nbetudes = nbetudes();
	$nbbiblio = nbbiblio();
	$nbdonneespub = nbdonneespub();
	$nbdonneespriv = nbdonneespriv();	
	
	//article
	$type = 'acsite';
	$article = article($type);
	//actu
	if($rjson_site['actu'] == 'oui' && isset($theme))
	{
		$nbactu = $rjson_site['nbactu'];
		$listeactu = listeactu($nbactu);
		$theme = array_flip($theme);
		$cejour = new DateTime();
		$huitjrs = new DateInterval('P8D');
		$newactu = 0;
		foreach($listeactu as $n)
		{
			$dactu = new DateTime($n['datecreation']);
			if($dactu->add($huitjrs) >= $cejour) { $newactu ++; } 	
			if($n['theme'] != 'NR')
			{
				if(isset($rjson_site['observatoire']))
				{
					if(isset($theme[$n['theme']]))
					{
						foreach ($rjson_site['observatoire'] as $a)
						{
							if ($n['theme'] == $a['nomvar'])
							{
								$iconactu = $a['icon'];
							}
						}					
					}
					else
					{
						$iconactu = 'NR';
					}
				}
				else
				{
					$iconactu = 'NR';
				}
			}
			else
			{
				$iconactu = 'NR';
			}
			$actu[] = array('titre'=>$n['titre'],'soustitre'=>$n['soustitre'],'idactu'=>$n['idactu'],'datefr'=>$n['datefr'],'icon'=>$iconactu);
		}		
	}
	//photo
	$photo = photo();
	//dernières obs
	$json_emprise = file_get_contents('emprise/emprise.json');
	$emprise = json_decode($json_emprise, true);
	$latin = (isset($_SESSION['latin'])) ? $_SESSION['latin'] : '';
	if(isset($rjson_site['observatoire']))
	{
		$datej = new DateTime();
		$datej->sub(new DateInterval('P30D'));
		$dater = $datej->format('Y-m-d');		
		$listeobs = listeobs($dater);
		if(count($listeobs) > 0)
		{
			foreach($listeobs as $n)
			{
				foreach($rjson_site['observatoire'] as $d)
				{
					if($d['nomvar'] == $n['observa'])
					{
						if($d['latin'] == 'oui' && $latin == 'oui')
						{
							$tabobs[] = array('latin'=>'oui', 'datefr'=>$n['datefr'], 'nomlat'=>$n['nom'], 'nomfr'=>$n['nomvern'], 'icon'=>$d['icon'], 'nomvar'=>$d['nomvar'], 'idobs'=>$n['idobs']);
						}
						elseif($d['latin'] == 'oui' && ($latin == 'defaut' || $latin == ''))
						{
							$tabobs[] = array('latin'=>'oui', 'datefr'=>$n['datefr'], 'nomlat'=>$n['nom'], 'nomfr'=>$n['nomvern'], 'icon'=>$d['icon'], 'nomvar'=>$d['nomvar'], 'idobs'=>$n['idobs']);
						}
						elseif($d['latin'] == 'non' && $latin == 'oui')
						{
							$tabobs[] = array('latin'=>'oui', 'datefr'=>$n['datefr'], 'nomlat'=>$n['nom'], 'nomfr'=>$n['nomvern'], 'icon'=>$d['icon'], 'nomvar'=>$d['nomvar'], 'idobs'=>$n['idobs']);
						}
						elseif($d['latin'] == 'non' || $latin == 'non') 
						{
							$tabobs[] = array('latin'=>'non', 'datefr'=>$n['datefr'], 'nomlat'=>$n['nom'], 'nomfr'=>$n['nomvern'], 'icon'=>$d['icon'], 'nomvar'=>$d['nomvar'], 'idobs'=>$n['idobs']);
						}			
					}
				}				
			}
		}
	}
	//récupération décade
	$date = date('d-m-Y');
	list($j,$m,$a) = explode('-',$date);
	switch ($m)
	{
		case 1:$DMois = 'Ja'; $CMois = 'Janvier'; break;
		case 2:$DMois = 'Fe'; $CMois = 'Février'; break;
		case 3:$DMois = 'Ma'; $CMois = 'Mars'; break;
		case 4:$DMois = 'Av'; $CMois = 'Avril'; break;
		case 5:$DMois = 'M'; $CMois = 'Mai'; break;
		case 6:$DMois = 'Ju'; $CMois = 'Juin'; break;
		case 7:$DMois = 'Jl'; $CMois = 'Juillet'; break;
		case 8:$DMois = 'A'; $CMois = 'Août'; break;
		case 9:$DMois = 'S'; $CMois = 'Septembre'; break;
		case 10:$DMois = 'O'; $CMois = 'Octobre'; break;
		case 11:$DMois = 'N'; $CMois = 'Novembre'; break;
		case 12:$DMois = 'D'; $CMois = 'Décembre'; break;
	}
	
	if($j >= 1 && $j <= 10) { $Djrs = '1'; $dec1 = 'Du 1er au 10 '.$CMois; }
	elseif($j >= 11 && $j <= 20) { $Djrs = '2'; $dec1 = 'Du 11 au 20 '.$CMois; }
	elseif($j >= 21 && $j <= 31)
	{ 
		$datetime1 = new DateTime($date);
		$dernierjrs = $datetime1->format('t');
		$Djrs = '3'; $dec1 = 'Du 21 au '.$dernierjrs.' '.$CMois; 
	}
	$decade = $DMois . $Djrs;
	$listedecade = decade($decade);
	if(count($listedecade) > 0)
	{
		foreach($listedecade as $n)
		{
			foreach($rjson_site['observatoire'] as $d)
			{
				if($d['nomvar'] == $n['observa'])
				{
					$couleurnomvar = (!empty($d['couleur'])) ? $d['couleur'] : '';
					if($d['latin'] == 'oui' && $latin == 'oui')
					{
						$tabdecade[] = ['latin'=>'oui', 'nom'=>$n['nom'], 'nomvern'=>$n['nomvern'], 'nomvar'=>$d['nomvar'], 'cdref'=>$n['cdref'], 'nb'=>$n['nb'], 'obsern'=>$n['obsern'], 'prenom'=>$n['prenom'], 'nomphoto'=>$n['nomphoto'], 'icon'=>$d['icon'], 'color'=>$couleurnomvar, 'disc'=>$d['nom']];
					}
					elseif($d['latin'] == 'oui' && ($latin == 'defaut' || $latin == ''))
					{
						$tabdecade[] = ['latin'=>'oui', 'nom'=>$n['nom'], 'nomvern'=>$n['nomvern'], 'nomvar'=>$d['nomvar'], 'cdref'=>$n['cdref'], 'nb'=>$n['nb'], 'obsern'=>$n['obsern'], 'prenom'=>$n['prenom'], 'nomphoto'=>$n['nomphoto'], 'icon'=>$d['icon'], 'color'=>$couleurnomvar, 'disc'=>$d['nom']];
					}
					elseif($d['latin'] == 'non' && $latin == 'oui')
					{
						$tabdecade[] = ['latin'=>'oui', 'nom'=>$n['nom'], 'nomvern'=>$n['nomvern'], 'nomvar'=>$d['nomvar'], 'cdref'=>$n['cdref'], 'nb'=>$n['nb'], 'obsern'=>$n['obsern'], 'prenom'=>$n['prenom'], 'nomphoto'=>$n['nomphoto'], 'icon'=>$d['icon'], 'color'=>$couleurnomvar, 'disc'=>$d['nom']];
					}
					elseif($d['latin'] == 'non' || $latin == 'non') 
					{
						$tabdecade[] = ['latin'=>'non', 'nom'=>$n['nom'], 'nomvern'=>$n['nomvern'], 'nomvar'=>$d['nomvar'], 'cdref'=>$n['cdref'], 'nb'=>$n['nb'], 'obsern'=>$n['obsern'], 'prenom'=>$n['prenom'], 'nomphoto'=>$n['nomphoto'], 'icon'=>$d['icon'], 'color'=>$couleurnomvar, 'disc'=>$d['nom']];
					}	
				}
			}
		}
	}	
	
	include 'modules/accueil/vues/accueil.php';
}
else
{
	include 'modules/accueil/vues/noaccueil.php';
}
?>