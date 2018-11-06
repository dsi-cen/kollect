<?php
/*
En cas de modification, il faut également vérifier :
valobservatoir.php
catinser.php
importliste.php
indice.php
site/mobser.php
*/
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function insere_statut($id,$type,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO statut.statutsite (cdprotect, observa, type) VALUES (:id, :observa, :type) ");
	$req->bindValue(':id', $id);
	$req->bindValue(':observa', $nomvar);
	$req->bindValue(':type', $type);
	$req->execute();
	$req->closeCursor();
}
function sup_statut($id,$nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->prepare("DELETE FROM statut.statutsite WHERE cdprotect = :id AND observa = :observa ");
	$req->bindValue(':id', $id);
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$req->closeCursor();
}

if (isset($_POST['id']) and isset($_POST['sel']))
{
	$nomvar = $_POST['sel'];
	$id = $_POST['id'];
	$type = $_POST['type'];
	$coche = $_POST['coche'];
	
	$json = file_get_contents('../../../../json/'.$nomvar.'.json');
	$rjson = json_decode($json, true);
	
	if($coche == 'oui')
	{
		if(isset($rjson['statut']))
		{
			if(isset($rjson['statut'][$type]))
			{
				$tabid[] = $id;
				foreach($rjson['statut'] as $cle => $n)
				{
					if($cle == $type)
					{
						foreach($n as $a)
						{
							$tabid[] = $a;
						}					
					}
					else
					{
						$ancien[$cle] = $n;
					}
				}
				$nouv = array($type=>$tabid);
				$statut = (isset($ancien)) ? array_merge($nouv, $ancien) : $nouv;			
			}
			else
			{
				$tabid[] = $id;
				$nouv = array($type=>$tabid);
				$statut = array_merge($nouv, $rjson['statut']);
			}
		}
		else
		{
			$tabid[] = $id;
			$statut = array($type=>$tabid);		
		}
		insere_statut($id,$type,$nomvar);
	}
	elseif($coche == 'non')
	{
		foreach($rjson['statut'] as $cle => $n)
		{
			if($cle == $type)
			{
				foreach($n as $a)
				{
					if($a != $id)
					{
						$tabid[$cle] = array($a);
					}					
				}					
			}
			else
			{
				$ancien[$cle] = $n;
			}
		}
		if(isset($tabid))
		{
			$statut = (isset($ancien)) ? array_merge($tabid, $ancien) : $tabid;
		}
		else
		{
			$statut = (isset($ancien)) ? $ancien : null;
		}
		sup_statut($id,$nomvar);
	}
	
	$filename = '../../../../json/'.$nomvar.'.json';
	$datajson = array();
	$datajson['titre'] = $rjson['titre'];
	$datajson['metakey'] = $rjson['metakey'];
	$datajson['icon'] = $rjson['icon'];
	$datajson['couleur'] = $rjson['couleur'];
	$datajson['nomvar'] = $rjson['nomvar'];
	$datajson['nom'] = $rjson['nom'];
	$datajson['nomdeux'] = $rjson['nomdeux'];
	$datajson['latin'] = $rjson['latin'];
	if(isset($rjson['indice'])) { $datajson['indice'] = $rjson['indice']; }
	if(isset($rjson['saisie'])) { $datajson["saisie"] = $rjson['saisie']; }
	if(isset($rjson['categorie']))	{ $datajson["categorie"] = $rjson['categorie']; }
	if(isset($rjson['systematique'])) { $datajson["systematique"] = $rjson['systematique']; }
	if(isset($rjson['gen1'])) { $datajson["gen1"] = $rjson['gen1']; }
	if(isset($rjson['gen2'])) { $datajson["gen2"] = $rjson['gen2']; }
	if(isset($statut)) { $datajson["statut"] = $statut; }
	$datajson['description'] = $rjson['description'];
	$ajson = json_encode($datajson);
	if (!$fp = @fopen($filename, 'w+')) 
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Impossible de créer ou d\'écrire le fichier '.$nomvar.'.json dans le répertoire json. Assurez vous d\'avoir les droits nécessaires (CHMOD).</p></div>';
		echo json_encode($retour);	
		exit;
	} 
	else 
	{
		fwrite($fp, $ajson);
		fclose($fp);
		$retour['statut'] = 'Oui';
	}
}
else
{
	$retour['statut'] = 'Non';
}
echo json_encode($retour);