<?php 
$titre = $rjson_obser['titre'];
$description = 'Site sur les '.$rjson_obser['nom'].' '.$rjson_site['ad2'].' '.$rjson_site['lieu'];
$script = '<script src="../dist/js/jquery.js" defer></script>
<script src="../dist/js/bootstrap.min.js" defer></script>';
$css = '';

include CHEMIN_MODELE.'accueil.php';

$nbobs = nbobs($nomvar);
$nbsp = nbespece($nomvar);
$nbphoto = nbphoto($nomvar);
$nbespecep = nbespecep($nomvar);
$nbobser = nbobservateur($nomvar);
/*$listeobserva = nbobservateur($nomvar);
if(count($listeobserva) > 0)
{
	$listeobserva1 = nbobservateur1($nomvar);
	if (count($listeobserva1) > 0)
	{
		foreach ($listeobserva as $n)
		{
			$obs1[] = $n['idobser'];
		}
		foreach ($listeobserva1 as $n)
		{
			$obs2[] = $n['idobser'];
		}
		$obs3 = array_merge($obs1, $obs2);		
		$nbobser = count(array_unique($obs3));	
	}
	else
	{
		$nbobser = count($listeobserva);
	}	
}
else
{
	$nbobser = 0;
}*/
//article
$type = 'ac'.$nomvar;
$article = article($type);
//actu
if ($rjson_site['actu'] == 'oui')
{
	$nbactu = $rjson_site['nbactu'];
	$listeactu = listeactu($nbactu,$nomvar);
	$nblisteactu = count($listeactu);
}
//affichage latin ou pas
$latin = (isset($_SESSION['latin'])) ? $_SESSION['latin'] : '';
if($rjson_obser['latin'] == 'oui' && $latin == 'oui') { $nomlatin = 'oui'; }
elseif($rjson_obser['latin'] == 'oui' && ($latin == 'defaut' || $latin == '')) { $nomlatin = 'oui'; }
elseif($rjson_obser['latin'] == 'non' && $latin == 'oui') { $nomlatin = 'oui'; }
elseif($rjson_obser['latin'] == 'non' || $latin == 'non') { $nomlatin = 'non'; }
//photo
$photo = photo($nomvar);
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
$listedecade = decade($nomvar,$decade);
//dernières obs
$datej = new DateTime();
$datej->sub(new DateInterval('P30D'));
$dater = $datej->format('Y-m-d');		
$listeobs = listeobs($nomvar,$dater);

$json_emprise = file_get_contents('../emprise/emprise.json');
$emprise = json_decode($json_emprise, true);

//Mise à jour des indices
if(isset($rjson_obser['indice']))
{
	$json_date = file_get_contents('../json/indice.json');
	$rdate = json_decode($json_date, true);
	
	$maintenant = new DateTime();
	$ancien = (isset($rdate['date'][$nomvar])) ? new DateTime($rdate['date'][$nomvar]) : new DateTime('2017-12-05 12:00:00');
	$inter = new DateInterval('PT30M');
	$datep = $maintenant->format('Y-m-d H:i:s');
	
	if($maintenant->sub($inter) > $ancien)
	{
		if(isset($_SERVER['HTTPS']))
		{
			$prefix = $_SERVER['HTTPS'] ? 'https://' : 'http://';
		}
		else
		{
			$prefix = 'http://';
		}
		$url = $prefix.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$urlval = str_replace('index.php?d='.$nomvar.'', 'modeles/calcindice', $url);
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $urlval.'.php');
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, 'observa='.$nomvar.'');
		curl_setopt($curl, CURLOPT_TIMEOUT_MS, 1000);
	 
		curl_exec($curl);
		curl_close($curl);
		
		if(isset($rdate['date']))
		{
			$an = $rdate['date'];
			$tmp = [$nomvar => $datep];
			$datejson['date'] = array_merge($an,$tmp);
		}
		else
		{
			$datejson['date'] = [$nomvar => $datep];
		}
		$adjson = json_encode($datejson);
		if($fp = @fopen('../json/indice.json', 'w+')) 
		{
			fwrite($fp, $adjson);
			fclose($fp);
		}
	}	
}

include 'modules/accueil/vues/accueil.php';