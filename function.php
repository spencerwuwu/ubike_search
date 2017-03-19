<?php

// Check if the location is valid and is in Taipei
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

// Narrow down targets to both diff in lat and lng < 0.01 to skip some from runnging calcDistance
function narrowDist($lat1,$lng1, $lat2, $lng2)
{
	$dist1 = $lat1 - $lat2;
	$dist2 = $lng1 - $lng2;

	if (($dist1 < 0.01 && $dist1 > -0.01) && ($dist2 < 0.01 && $dist2 > -0.01))
		return true;
}

// Calculate the distance between current location and bike stations
function calcDistance($lat1,$lng1, $lat2, $lng2)
{
	$data = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?origins=$lat1,$lng1&destinations=$lat2,$lng2&mode=walking");

	$data = json_decode($data);
	return $data->rows[0]->elements[0]->distance->value;
}

class targetItem
{
	public $id;
	public $dist;
	public $sna;
	public $sbi;
}

// Download and extract gz
function uncompress($srcName, $dstName) {
	$sfp = gzopen($srcName, "rb");
	$fp = fopen($dstName, "w");

	while ($sfp && !gzeof($sfp)) {
		$string = gzread($sfp, 4096);
		fwrite($fp, $string, strlen($string));
	}
	gzclose($sfp);
	fclose($fp);
}

function find( $lat1, $lng1)
{

	// Pull ubike data

	file_put_contents("tmp/Tmpfile.gz", fopen("http://data.taipei/youbike", 'r'));
	$fileName = "tmp/Tmpfile.gz";
	$distName = "tmp/bike";

	uncompress($fileName, $distName);

	$bikecode = file_get_contents($distName);
	$bikedata = json_decode($bikecode);

	$bikeObject = array();

	foreach ( $bikedata->retVal as $key=>$value)
	{
		$data = new stdClass;
		$data->sno = $value->sno;
		$data->sbi = $value->sbi;
		$data->sna = $value->sna;
		$data->lat = $value->lat;
		$data->lng = $value->lng;
		array_push($bikeObject, $data);
	}

	$max = sizeof($bikeObject);

	// Init output array
	$targetSets = array();
	$targetSets[0] = new targetItem();
	$targetSets[1] = new targetItem();
	$target1 = new targetItem();
	$target2 = new targetItem();
	$count = 0;
	for ($i = 0; $i < $max; $i++)
	{
		if ($bikeObject[$i]->sbi != 0)
		{
			if ($count == 0)
			{
				$target1->id = $i;
				$target1->dist = calcDistance( $lat1, $lng1, $bikeObject[$i]->lat, $bikeObject[$i]->lng);
				$target1->sna = $bikeObject[$i]->sna;
				$target1->sbi = $bikeObject[$i]->sbi;
			}
			else
			{
				$target2->id = $i;
				$target2->dist = calcDistance( $lat1, $lng1, $bikeObject[$i]->lat, $bikeObject[$i]->lng);
				$target2->sna = $bikeObject[$i]->sna;
				$target2->sbi = $bikeObject[$i]->sbi;
			}
			$count++;
		}
		if ($count == 2)
			break;
		else if ($count == 0 && $i == $max-1)
			return -1;							// All sbi == 0
		else
		{
			$targetSets[0] = $target1;
			$targetSets[1] = $target1;
		}
	}


	for ($i = 0; $i < $max; $i++)
	{
		if ($bikeObject[$i]->sbi != 0)
		{
			if (narrowDist( $lat1, $lng1, $bikeObject[$i]->lat, $bikeObject[$i]->lng))
			{
				$tmpDist = calcDistance($lat1, $lng1, $bikeObject[$i]->lat, $bikeObject[$i]->lng);
				if ($tmpDist < $target1->dist)
				{
					if ($target1->dist < $target2->dist && $i != $target2->id)
					{
						$target2->id = $target1->id;
						$target2->dist = $target1->dist;
						$target2->sna = $target1->sna;
						$target2->sbi = $target1->sbi;
					}
					$target1->id = $i;
					$target1->dist = $tmpDist;
					$target1->sna = $bikeObject[$i]->sna;
					$target1->sbi = $bikeObject[$i]->sbi;
				}
				else 
				{
					if ($tmpDist < $target2->dist && $i != $target1->id)
					{
						$target2->id = $i;
						$target2->dist = $tmpDist;
						$target2->sna = $bikeObject[$i]->sna;
						$target2->sbi = $bikeObject[$i]->sbi;
					}
				}
			}
		}
	}

	$targetSets[0] = $target1;
	$targetSets[1] = $target2;
	return $targetSets;

}

?>
