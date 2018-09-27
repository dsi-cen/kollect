<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function verif_validateur($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM site.validateur WHERE discipline ILIKE :recherche ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':recherche', '%'.$nomvar.'%');
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;
}

if(isset($_POST['sel']))
{
	$nomvar = $_POST['sel'];
	
	$validateur = verif_validateur($nomvar);
	$retour['validateur'] = ($validateur == 0) ? 'non' : 'oui';	
		
	$json = file_get_contents('../../../json/'.$nomvar.'.json');
	$rjson = json_decode($json, true);
	if(isset($rjson['saisie']['stade']))
	{
		$retour['locale'] = (isset($rjson['saisie']['locale'])) ? $rjson['saisie']['locale'] : 'non';
		$retour['stade'] = $rjson['saisie']['stade'];
		$retour['methode'] = (isset($rjson['saisie']['methode'])) ? $rjson['saisie']['methode'] : '';
		$retour['collecte'] = (isset($rjson['saisie']['collecte'])) ? $rjson['saisie']['collecte'] : '';
		$retour['statutbio'] = (isset($rjson['saisie']['statutbio'])) ? $rjson['saisie']['statutbio'] : '';
		$retour['mort'] = (isset($rjson['saisie']['mort']) && !empty($rjson['saisie']['mort'])) ? $rjson['saisie']['mort'] : '';
		$retour['obdenom'] = (isset($rjson['saisie']['denom'])) ? $rjson['saisie']['denom'] : ['Nombre d\'individus observés'=>'IND'];
		$retour['protocole'] = (isset($rjson['saisie']['protocole'])) ? $rjson['saisie']['protocole'] : array('aucun'=>'0');
		$retour['stbio'] = (isset($rjson['saisie']['stbio'])) ? $rjson['saisie']['stbio'] : 'vivant';
		$retour['mf'] = (isset($rjson['saisie']['mf']) && $rjson['saisie']['mf'] == 'oui') ? $rjson['saisie']['mf'] : '';
		$retour['plteh'] = (isset($rjson['saisie']['plteh'])) ? $rjson['saisie']['plteh'] : 'non';	
		$retour['plteb'] = (isset($rjson['saisie']['plteb'])) ? $rjson['saisie']['plteb'] : 'non';	
		$retour['aves'] = (isset($rjson['saisie']['aves'])) ? $rjson['saisie']['aves'] : 'non';
		$retour['col'] = (isset($rjson['saisie']['col'])) ? $rjson['saisie']['col'] : 'non';
		$retour['bota'] = (isset($rjson['saisie']['bota'])) ? $rjson['saisie']['bota'] : 'non';
		$retour['aff'] = ($rjson['latin'] == 'oui') ? 'latin' : 'nomf';	
		if(isset($rjson['saisie']['aves']) && $rjson['saisie']['aves'] == 'oui')
		{
			$tmp = null;
			$tmp .= '<option value="0"></option>';
			$tmp .= '<option value="2">2 - Présence dans son habitat durant sa période de reproduction</option>';
			$tmp .= '<option value="3" title="Mâle chanteur présent en période de nidification, cris nuptiaux ou tambourinages entendus, mâle vu en parade. Si ce comportement est observé de manière répétée, voir code 5">3 - Mâle chanteur présent en période de nidification</option>';
			$tmp .= '<option value="4" title="Observation d\'un couple sans comportement particulier, sinon, voir indices 5 et 6">4 - Couple présent dans son habitat durant sa période de nidifcation</option>';
			$tmp .= '<option value="5" title="(chant, chants simultanés de plusieurs individus, querelles avec des voisins,...)">5 - Comportement territorial observé sur un même territoire, 2 journées différentes à 7 jours ou plus d\'intervalle</option>';
			$tmp .= '<option value="6">6 - Comportement nuptial : parades, vols nuptiaux, copulation ou échange de nourriture entre adultes.</option>';
			$tmp .= '<option value="7" title="Visite de nichoir, cavité, falaise,... (Voir également code 10)">7 - Visite d\'un site de nidification probable, distinct d\'un site de repos</option>';
			$tmp .= '<option value="8">8 - Cri d\'alarme ou tout autre comportement agité indiquant la présence d\'un nid ou de jeunes aux alentours</option>';
			$tmp .= '<option value="9" title="La capture d\'espèces protégées est interdite. Uniquement pour les personnes autorisées.">9 - Preuve physiologique : plaque incubatrice très vascularisée ou oeuf présent dans l\'oviducte (observation sur un oiseau en main)</option>';
			$tmp .= '<option value="10" title="Oiseau transportant des brindilles, herbes, mousses, boue,... ou forant une cavité dans un tronc (pics) ou dans le sol (guépiers,...)">10 - Transport de matériel ou construction d\'un nid, forage d\'une cavité</option>';
			$tmp .= '<option value="11" title="Oiseau simulant une aile brisée ou ayant un comportement agressif lors de l\'approche du nid">11 - Oiseau simulant une blessure ou détournant l\'attention, tels les canards, gallinacés, oiseaux de rivage,...</option>';
			$tmp .= '<option value="12">12 - Nid vide ayant été utilisé la présente saison</option>';
			$tmp .= '<option value="13">13 - Jeunes en duvet ou jeunes venant de quitter le nid et incapables de soutenir le vol sur de longues distances</option>';
			$tmp .= '<option value="14">14 - Adulte gagnant, occupant ou quittant le site d\'un nid, comportement révélateur d\'un nid occupé dont le contenu ne peut être vérifié (trop haut, trop loin, dans une cavité...)</option>';
			$tmp .= '<option value="15">15 - Adulte transportant un sac fécal.</option>';
			$tmp .= '<option value="16">16 - Adulte transportant de la nourriture pour les jeunes durant sa période de nidifcation</option>';
			$tmp .= '<option value="17">17 - Coquilles d\'oeufs éclos</option>';
			$tmp .= '<option value="18">18 - Nid vu avec un adulte couvant</option>';
			$tmp .= '<option value="19">19 - Nid contenant des oeufs ou des jeunes (vus ou entendus)</option>';
			$retour['avesindice'] = $tmp;
		}
		$retour['statut'] = 'Oui';
	}
	else
	{
		$retour['stade'] = '';
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert">Aucun stade de définit pour l\'observatoire <b>'.$rjson['nom'].'</b> ! Contacter un administrateur du site</div>';
	}
	$retour['icon'] = substr($rjson['icon'],3);
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert">Aucun observatoire de choisit.</div>';
}
echo json_encode($retour);