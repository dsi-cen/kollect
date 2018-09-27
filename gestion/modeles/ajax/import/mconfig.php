<?php
if (isset($_POST['sel']) && !empty($_POST['sel']))
{
	$nomvar = $_POST['sel'];
	
	$json = file_get_contents('../../../../json/'.$nomvar.'.json');
	$rjson = json_decode($json, true);
	
	$liste = null;
	if (isset($rjson['saisie']['stade']))
	{
		$liste .= '<h3 class="h6">Stade</h3>';
		$liste .= '<ul>';
		foreach($rjson['saisie']['stade'] as $cle => $n)
		{
			$liste .= '<li><b>'.$n.'</b> '.$cle.'</li>';
		}
		$liste .= '</ul>';		
	}
	else
	{
		$liste .= '<h3 class="h6">Stade - non définit !</h3>';
	}
	if (isset($rjson['saisie']['methode']))
	{
		$liste .= '<h3 class="h6">Méthode d\'observation</h3>';
		$liste .= '<ul>';
		foreach($rjson['saisie']['methode'] as $cle => $n)
		{
			$liste .= '<li><b>'.$n.'</b> '.$cle.'</li>';
		}
		$liste .= '</ul>';		
	}
	else
	{
		$liste .= '<h3 class="h6">Méthode d\'observation - non définit !</h3>';
	}
	if (isset($rjson['saisie']['collecte']))
	{
		$liste .= '<h3 class="h6">Méthode de collecte</h3>';
		$liste .= '<ul>';
		foreach($rjson['saisie']['collecte'] as $cle => $n)
		{
			$liste .= '<li><b>'.$n.'</b> '.$cle.'</li>';
		}
		$liste .= '</ul>';		
	}
	else
	{
		$liste .= '<h3 class="h6">Méthode de collecte - non définit !</h3>';
	}
	if (isset($rjson['saisie']['statutbio']))
	{
		$liste .= '<h3 class="h6">Statut biologique</h3>';
		$liste .= '<ul>';
		foreach($rjson['saisie']['statutbio'] as $cle => $n)
		{
			$liste .= '<li><b>'.$n.'</b> '.$cle.'</li>';
		}
		$liste .= '</ul>';		
	}
	else
	{
		$liste .= '<h3 class="h6">Statut biologique - non définit !</h3>';
	}
	if (isset($rjson['saisie']['protocole']))
	{
		$liste .= '<h3 class="h6">Protocole</h3>';
		$liste .= '<ul>';
		foreach($rjson['saisie']['protocole'] as $cle => $n)
		{
			$liste .= '<li><b>'.$n.'</b> '.$cle.'</li>';
		}
		$liste .= '</ul>';		
	}
	else
	{
		$liste .= '<h3 class="h6">Protocole - non définit !</h3>';
	}
	
	$retour['liste'] = $liste;
	$retour['statut'] = 'Oui';
	
}
else
{
	$retour['statut'] = 'Non';	
}
echo json_encode($retour);