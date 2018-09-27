<?php
function schema()
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT schema_name FROM information_schema.schemata WHERE schema_name = 'install' ");
	$sch = $req->rowCount();
	$req->closeCursor();
	return $sch;		
}
function sup()
{
	$bdd = PDO2::getInstance();
	$req = $bdd->query("DROP SCHEMA install CASCADE ");
	$req->closeCursor();		
}