<?php

function getAddr( $lat, $lng)
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

function dist( $lat1, $lng1, $lat2, $lng2)
{
	return pow(2, ($lat1 - $lat2))+pow(2, ($lng1 - $lng2));
}

class targetItem
{
	public $id;
	public $dist;
}

function find( $lat1, $lng1)
{

$bikecode = file_get_contents("http://data.taipei/opendata/datalist/apiAccess?scope=resourceAquire&rid=ddb80380-f1b3-4f8e-8016-7ed9cba571d5");
$bikedata = json_decode($bikecode);

$bikeObject = $bikedata->result->results;
$max = sizeof($bikeObject);

// Init output array
$targetSet = array();
$target = new targetItem();
$count = 0;
for ($i = 0; $i < $max; $i++)
{
	if ($bikeObject->sbi != 0)
	{
		$target->id = $i;
		$dist = dist($lat1, $lng1, $bikeObject->lat, $bikeObject->lng);

		$count++;
	}
	if ($count == 1)
		break;

}


for ($i = 0; $i < $max; $i++)
{

}
 
	
}

?>
