<?php
if ($_FILES['file']['name']) {
	if (!$_FILES['file']['error']) {
		//$name = md5(rand(100, 200));
		//$ext = explode('.', $_FILES['file']['name']);
		//$filename = $name . '.' . $ext[1];
		$name = $_FILES['file']['name'];
		$filename = $name;
		$destination = '../../../photo/article/' . $filename; 
		$location = $_FILES["file"]["tmp_name"];
		move_uploaded_file($location, $destination);
		$url = 'http://'.dirname($_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
		$urlval = str_replace('/gestion/modeles/ajax', '/photo/article/', $url);
		echo $urlval.$filename;
	}
	else
	{
		echo  $message = 'Erreur!  Erreur lors du téléchargement :  '.$_FILES['file']['error'];
	}
}
?>