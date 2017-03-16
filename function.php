<?php

function inTaipei( $lat, $lng)
{
	$geocode=file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lng&sensor=false");

	$geodata= json_decode($geocode);

	if ($geodata->results == [])
		return -1;
	else
	{
	$address = $geodata->results[0]->formatted_address;

	if (stripos($address, "Taipei") != NULL) 
		return 0;
	else 
		return -2;
	}

}



?>
