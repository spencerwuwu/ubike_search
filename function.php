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
	public $sna;
	public $sbi;
}

function find( $lat1, $lng1)
{

	// Pull ubike data with python
	$command = escapeshellcmd('python2 getbike.py');
	$output = shell_exec($command);

	$distName = 'data.json';
	$bikecode = file_get_contents($distName);
	$bikedata = json_decode($bikecode);
	$bikeObject = $bikedata->results;
	$max = sizeof($bikeObject);

	// Init output array
	$targetSets = array();
	$targetSets[0] = new targetItem();
	$targetSets[1] = new targetItem();
	$target = new targetItem();
	$count = 0;
	for ($i = 0; $i < $max; $i++)
	{
		if ($bikeObject[$i]->sbi != 0)
		{
			$target->id = $i;
			$target->dist = dist( $lat1, $lng1, $bikeObject[$i]->lat, $bikeObject[$i]->lng);
			$target->sna = $bikeObject[$i]->sna;
			$target->sbi = $bikeObject[$i]->sbi;

			$targetSets[$count] = $target;
			$count++;
		}
		if ($count == 2)
			break;

	}


	for ($i = 0; $i < $max; $i++)
	{
		if ($bikeObject[$i]->sbi != 0)
		{
			$tmpDist = dist($lat1, $lng1, $bikeObject[$i]->lat, $bikeObject[$i]->lng);
			if ($tmpDist < $targetSets[0]->dist)
			{
				$targetSets[0]->id = $i;
				$targetSets[0]->dist = $tmpDist;
				$targetSets[0]->sna = $bikeObject[$i]->sna;
				$targetSets[0]->sbi = $bikeObject[$i]->sbi;
			}
			else 
			{
				if ($tmpDist < $targetSets[1]->dist)
				{
					$targetSets[1]->id = $i;
					$targetSets[1]->dist = $tmpDist;
					$targetSets[1]->sna = $bikeObject[$i]->sna;
					$targetSets[1]->sbi = $bikeObject[$i]->sbi;
				}
			}
		}
	}

	return $targetSets;


}

?>
