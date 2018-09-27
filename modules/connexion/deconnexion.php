<?php
$url = $_SERVER['HTTP_REFERER'];
$_SESSION = array();
session_destroy();	
setcookie('idp', '');
setcookie('idn', '');
setcookie('idd', '');
setcookie('idm', '');
header('location:'.$url.'');