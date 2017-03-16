<?php

$lat = 25.034153;
$lng = 121.568509;

$google = "http://maps.googleapis.com/maps/api/geocode/json?latlng=" + "$lat" + "," + "$lng" + "&sensor=false";
$geocode = @file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lng&sensor=false");

$geodata = json_decode($geocode);
if ( $geodata->results == [] ) echo "false";
else echo "true";

/*
if (stripos($address, "Taipei") != NULL)
{
	$myObj->code = -2;
	$myObj->result = [];
	$output = json_encode($myObj);
	echo $output;
}
else echo "hohoho";
 */

/*
$bikecode = file_get_contents("http://data.taipei/opendata/datalist/apiAccess?scope=resourceAquire&rid=ddb80380-f1b3-4f8e-8016-7ed9cba571d5");
$bikedata = json_decode($bikecode);

echo $bikedata->result->results[0]->ar;
 */

//echo $output->results[0]->address_components[1]->long_name;

//if (stripos("hihi hello", "taipei") == NULL) echo "hello";

?>
