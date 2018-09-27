<?php

error_reporting(0); // Set E_ALL for debuging
require './autoload.php';

elFinder::$netDrivers['ftp'] = 'FTP';

function access($attr, $path, $data, $volume) {
	return strpos(basename($path), '.') === 0       
		? !($attr == 'read' || $attr == 'write')   
		:  null;                                
}

$dir = dirname(__FILE__);
$dirval = str_replace('/gestion/lib/elfinder', '/photo/', $dir);
//$dirval = str_replace('\gestion\lib\elfinder', '\photo', $dir);
$url = 'http://'.dirname($_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
$urlval = str_replace('/gestion/lib/elfinder', '/photo/', $url);
$opts = array(
	'roots' => array(
		array(
			'driver'        => 'LocalFileSystem', 
			'path'          => $dirval,
			'URL'           => $urlval,
			'uploadDeny'    => array('all'),                
			'uploadAllow'   => array('image', 'text/plain'),
			'uploadOrder'   => array('deny', 'allow'),     
			'accessControl' => 'access'                     
		)
	)
);
$connector = new elFinderConnector(new elFinder($opts));
$connector->run();