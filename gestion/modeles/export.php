<?php
function exgeo($data)
{
	header("Content-type: application/json");
	header("Content-disposition: attachment; filename=export.geojson");
	echo $data;
	unset($_SESSION['export']);	
	exit;
}
