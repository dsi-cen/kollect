<?php
$titre = 'Indice - '.$nomd;;
$description = 'Calcul des indices de rareté - '.$nomd.' '.$rjson_site['ad2'].' '.$rjson_site['lieu'];
$script = '<script src="../dist/js/jquery.js"></script>
<script src="../dist/js/bootstrap.min.js" defer></script>';
$css = '';

include CHEMIN_MODELE.'indice.php';
 
$json_emprise = file_get_contents('../emprise/emprise.json');
$emprise = json_decode($json_emprise, true);

$mt = ($rjson_obser['indice']['maillage'] == 'l93') ? $emprise['nbmaille'] : $emprise['nbmaille5'];
$libmaille = ($rjson_obser['indice']['maillage'] == 'l93') ? '10 x 10 (100 Km²)' : '5 x 5 (25 km²)';
$choixobs = ($rjson_obser['indice']['choix'] == 'obs') ? 'observations' : 'espèces' ;
$date = (isset($rjson_obser['indice']['date'])) ? $rjson_obser['indice']['date'] : null;
$m = calc_m($rjson_obser['indice']['choix'],$nomvar,$rjson_obser['indice']['valchoix'],$rjson_obser['indice']['maillage'],$date);
$M = round($m/$mt*100,1);
$ms = $rjson_obser['indice']['ms'];

$cr1b = round(100-($ms/$mt)*100,1);
$cr2b = $cr1b - 1; $cr3b = $cr2b - 2; $cr4b = $cr3b - 4; $cr5b = $cr4b - 8; $cr6b = $cr5b - 16; $cr7b = $cr6b - 32; $cr8b = 0;
$crp1b = round($cr1b+($M-($cr1b*$M/100)),1);
$crp2b = round($cr2b+($M-($cr2b*$M/100)),1);
$crp3b = round($cr3b+($M-($cr3b*$M/100)),1);
$crp4b = round($cr4b+($M-($cr4b*$M/100)),1);
$crp5b = round($cr5b+($M-($cr5b*$M/100)),1);
$crp6b = round($cr6b+($M-($cr6b*$M/100)),1);
$crp7b = round($cr7b+($M-($cr7b*$M/100)),1);
$crp8b = 0;
$m2b = ceil((100-$cr2b)/100*$mt); $m3b = ceil((100-$cr3b)/100*$mt); $m4b = ceil((100-$cr4b)/100*$mt); $m5b = ceil((100-$cr5b)/100*$mt); $m6b = ceil((100-$cr6b)/100*$mt); $m7b = ceil((100-$cr7b)/100*$mt); $m8b = ceil((100-$cr8b)/100*$mt);
$m2a = $ms; $m3a = $m2b; $m4a = $m3b; $m5a = $m4b; $m6a = $m5b; $m7a = $m6b; $m8a = $m7b;
$mp1b = ceil((100-$crp1b)/100*$mt);
$mp2b = ceil((100-$crp2b)/100*$mt);
$mp3b = ceil((100-$crp3b)/100*$mt);
$mp4b = ceil((100-$crp4b)/100*$mt);
$mp5b = ceil((100-$crp5b)/100*$mt);
$mp6b = ceil((100-$crp6b)/100*$mt);
$mp7b = ceil((100-$crp7b)/100*$mt);
$mp8b = $mt;
$mp2a = $mp1b; $mp3a = $mp2b; $mp4a = $mp3b; $mp5a = $mp4b; $mp6a = $mp5b; $mp7a = $mp6b; $mp8a = $mp7b;

$tab = null;
$tab .= '<tr><th scope="row" class="text-sm-left">Exceptionnelle (E)</th><td>100</td><td>'.$cr1b.'</td><td>1</td><td>'.$ms.'</td><td>100</td><td>'.$crp1b.'</td><td></td><td>'.$mp1b.'</td></tr>';
$tab .= '<tr><th scope="row" class="text-sm-left">Très rare (TR)</th><td>'.$cr1b.'</td><td>'.$cr2b.'</td><td>'.$m2a.'</td><td>'.$m2b.'</td><td>'.$crp1b.'</td><td>'.$crp2b.'</td><td>'.$mp2a.'</td><td>'.$mp2b.'</td></tr>';
$tab .= '<tr><th scope="row" class="text-sm-left">Rare (R)</th><td>'.$cr2b.'</td><td>'.$cr3b.'</td><td>'.$m3a.'</td><td>'.$m3b.'</td><td>'.$crp2b.'</td><td>'.$crp3b.'</td><td>'.$mp3a.'</td><td>'.$mp3b.'</td></tr>';
$tab .= '<tr><th scope="row" class="text-sm-left">Assez rare (AR) </th><td>'.$cr3b.'</td><td>'.$cr4b.'</td><td>'.$m4a.'</td><td>'.$m4b.'</td><td>'.$crp3b.'</td><td>'.$crp4b.'</td><td>'.$mp4a.'</td><td>'.$mp4b.'</td></tr>';
$tab .= '<tr><th scope="row" class="text-sm-left">Peu commune (PC)</th><td>'.$cr4b.'</td><td>'.$cr5b.'</td><td>'.$m5a.'</td><td>'.$m5b.'</td><td>'.$crp4b.'</td><td>'.$crp5b.'</td><td>'.$mp5a.'</td><td>'.$mp5b.'</td></tr>';
$tab .= '<tr><th scope="row" class="text-sm-left">Assez commune (AC)</th><td>'.$cr5b.'</td><td>'.$cr6b.'</td><td>'.$m6a.'</td><td>'.$m6b.'</td><td>'.$crp5b.'</td><td>'.$crp6b.'</td><td>'.$mp6a.'</td><td>'.$mp6b.'</td></tr>';
$tab .= '<tr><th scope="row" class="text-sm-left">Commune (C)</th><td>'.$cr6b.'</td><td>'.$cr7b.'</td><td>'.$m7a.'</td><td>'.$m7b.'</td><td>'.$crp6b.'</td><td>'.$crp7b.'</td><td>'.$mp7a.'</td><td>'.$mp7b.'</td></tr>';
$tab .= '<tr><th scope="row" class="text-sm-left">Très commune (CC)</th><td>'.$cr7b.'</td><td>0</td><td>'.$m8a.'</td><td>'.$m8b.'</td><td>'.$crp7b.'</td><td>'.$crp8b.'</td><td>'.$mp8a.'</td><td>'.$mp8b.'</td></tr>';

//exemple
$val1 = ceil($mt * 0.03);
$ir1 = round(100-($val1/$mt)*100,1);
$indice1 = calc($ir1,$cr1b,$cr2b,$cr3b,$cr4b,$cr5b,$cr6b,$cr7b,$crp1b,$crp2b,$crp3b,$crp4b,$crp5b,$crp6b,$crp7b);
$val2 = ceil($mt * 0.3);
$ir2 = round(100-($val2/$mt)*100,1);
$indice2 = calc($ir2,$cr1b,$cr2b,$cr3b,$cr4b,$cr5b,$cr6b,$cr7b,$crp1b,$crp2b,$crp3b,$crp4b,$crp5b,$crp6b,$crp7b);

if(!empty($date))
{
	$an = substr($rjson_obser['indice']['date'], 0, 4);
}

include CHEMIN_VUE.'indice.php';
