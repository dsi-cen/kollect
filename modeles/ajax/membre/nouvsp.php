<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
session_start();

function nouveau($idobser)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("WITH sel AS (
							SELECT MIN(date1) AS prem, cdref FROM obs.fiche
							INNER JOIN obs.obs USING(idfiche)
							GROUP BY cdref
						), sel1 AS (
							SELECT idobs FROM sel
							INNER JOIN obs.fiche ON fiche.date1 = sel.prem
							INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche AND obs.cdnom = sel.cdref
							LEFT JOIN obs.plusobser ON plusobser.idfiche = fiche.idfiche
							WHERE fiche.idobser = :id OR plusobser.idobser = :id
						)
						SELECT DISTINCT sel1.idobs, to_char(date1, 'DD/MM/YYYY') AS datefr, cdref, liste.nom, liste.nomvern, EXTRACT(YEAR FROM date1) AS annee, plusobser, fiche.idobser, observateur.nom AS nomobser, prenom, iddet, observa, fiche.idfiche FROM sel1
						INNER JOIN obs.obs ON obs.idobs = sel1.idobs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser
						WHERE rang = 'ES' OR rang = 'SSES' 
						ORDER BY annee DESC, nom ");
	$req->bindValue(':id', $idobser, PDO::PARAM_INT);
	$req->execute();
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}
function cherche_observateur($idfiche)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT nom, prenom, observateur.idobser FROM obs.plusobser
						INNER JOIN referentiel.observateur ON observateur.idobser = plusobser.idobser
						WHERE idfiche = :idfiche
						ORDER BY idplus ");
	$req->bindValue(':idfiche', $idfiche, PDO::PARAM_INT);
	$req->execute();
	$obsplus = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $obsplus;
}
function determinateur($iddet)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT nom, prenom FROM referentiel.observateur WHERE idobser = :iddet");
	$req->bindValue(':iddet', $iddet);
	$req->execute();
	$det = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $det;
}

if(isset($_POST['idobser'])) 
{
	$idobser = htmlspecialchars($_POST['idobser']);	
	
	$liste = nouveau($idobser);
	
	if($liste != false)
	{
		$choixlatin = (isset($_SESSION['latin'])) ? $_SESSION['latin'] : '';
		$json_site = file_get_contents('../../../json/site.json');
		$rjson = json_decode($json_site, true);
		foreach($rjson['observatoire'] as $n)
		{
			$tabobserva[$n['nomvar']] = $n['nom'];
			$tablatin[$n['nomvar']] = $n['latin'];
		}	
		$nbsp = count($liste);
		
		foreach($liste as $n)
		{
			$observa = $tabobserva[$n['observa']];
			$tmpob[] = $observa;
			sort($tmpob);
			$tabannee[] = ['observa'=>$observa,'annee'=>$n['annee']];
			
			if($tablatin[$n['observa']] == 'oui' && $choixlatin == 'oui') { $taxon = '<i>'.$n['nom'].'</i>'; }
			elseif($tablatin[$n['observa']] == 'oui' && ($choixlatin == 'defaut' || $choixlatin == '')) { $taxon = '<i>'.$n['nom'].'</i>'; }
			elseif($tablatin[$n['observa']] == 'non' && $choixlatin == 'oui') { $taxon = '<i>'.$n['nom'].'</i>'; }
			elseif($tablatin[$n['observa']] == 'non' || $choixlatin == 'non') { $taxon = ($n['nomvern'] != '') ? $n['nomvern'].' <i>('.$n['nom'].')</i>' : '<i>'.$n['nom'].'</i>'; }
			elseif($tablatin[$n['observa']] == 'oui' && $choixlatin == 'non') { $taxon = ($n['nomvern'] != '') ? $n['nomvern'].' <i>('.$n['nom'].')</i>' : '<i>'.$n['nom'].'</i>'; } 
			
			if($n['plusobser'] == 'oui')
			{
				$obs2[] = '<a href="index.php?module=infoobser&amp;action=info&amp;idobser='.$n['idobser'].'">'.$n['prenom'].' '.$n['nomobser'].'</a>';
				$obsplus = cherche_observateur($n['idfiche']);
				foreach($obsplus as $o)
				{
					$obs2[] = '<a href="index.php?module=infoobser&amp;action=info&amp;idobser='.$o['idobser'].'">'.$o['prenom'].' '.$o['nom'].'</a>'; 
				}
				$observateur = implode(', ', $obs2);
				$obs2 = null;
			}
			else
			{
				$observateur = '<a href="index.php?module=infoobser&amp;action=info&amp;idobser='.$n['idobser'].'">'.$n['prenom'].' '.$n['nomobser'].'</a>';
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
			
			$new[] = ['observa'=>$observa, 'nomvar'=>$n['observa'], 'annee'=>$n['annee'], 'cdref'=>$n['cdref'], 'nom'=>$taxon, 'datefr'=>$n['datefr'], 'observateur'=>$observateur, 'det'=>$det, 'idobs'=>$n['idobs']];
		}
		$observatab = array_count_values($tmpob);
		$tabtmp = array_map( 'serialize' , $tabannee );
		$tabtmp = array_unique($tabtmp);
		$tabannee = array_map( 'unserialize' , $tabtmp );		
		
		$l = ($nbsp > 1) ? '<h3 class="h5">'.$nbsp.' nouvelles espèces</h3>' : '<h3 class="h5">'.$nbsp.' nouvelle espèce</h3>';
		$l .= '<p>Liste établie à partir des données contenu dans la base</p>';
		$l .= '<div class="mb-3"><button type="button" id="voir" class="btn btn-outline-secondary">Tout afficher</button><button type="button" id="pasvoir" class="btn btn-outline-secondary ml-2">Tout cacher</button></div>';
		foreach($observatab as $cle => $e)
		{
			$l .= '<div class="listefamille">';
			$l .= '<div><h4 class="h5"><button id="'.$cle.'" class="btn btn-sm color1_bg idfam" type="button"><span class="fa fa-plus blanc"></span></button>'.$cle.' ('.$e.')</h4></div>';
			$l .= '<ul id="f'.$cle.'" class="collapse min">';
			foreach($tabannee as $a)
			{
				if($a['observa'] == $cle)
				{
					$l .= '<li><span class="font-weight-bold">'.$a['annee'].'</span><ul>';
					foreach($new as $n)
					{
						if($n['observa'] == $a['observa'] && $n['annee'] == $a['annee'])
						{
							$l .= '<li><span class="font-weight-bold"><a href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d='.$n['nomvar'].'&amp;id='.$n['cdref'].'">'.$n['nom'].'</a></span>, '.$n['datefr'].' - '.$n['observateur'].' Det. '.$n['det'].' - <a href="index.php?module=observation&action=detail&amp;idobs='.$n['idobs'].'"><i class="fa fa-eye" title="Voir l\'observation"></i></a></li>';
						}
					}
					$l .= '</ul></li>';
				}
			}
			$l .= '</ul>';
			$l .= '</div>';
		}
	}
	else
	{
		$l = 'Aucune nouvelle espèce';
	}
	$retour['listenew'] = $l;	
	
	$retour['statut'] = 'Oui';
}
else
{
	$retour['statut'] = 'Non';
}
echo json_encode($retour);
?>