<?php

require 'function.php';

if( $_GET['lat'] && $_GET['lng'] )
{
	$lat = $_GET['lat'];
	$lng = $_GET['lng'];

	$flag = getAddr( $lat, $lng);
	$myObj = new stdClass;
	if ($flag == -2)
	{
		$myObj->code = -2;
		$myObj->result = [];
	}
	else if ($flag == -1)
	{
		$myObj->code = -1;
		$myObj->result = [];
	}
	else if ($flag == 0)
	{
		$myObj->code = 0;
		$myObj->result = [];

		$target = find( $lat, $lng);

		if ($target == -1)
		{
			$myObj->code = 1;
		}
		else
		{

			$good1 = new stdClass;
			$good1->station = $target[0]->sna;
			$good1->num_bike = $target[0]->sbi;
			$good2 = new stdClass;
			$good2->station = $target[1]->sna;
			$good2->num_bike = $target[1]->sbi;
			array_push($myObj->result, $good1);
			array_push($myObj->result, $good2);
		}
	}
	else
	{
		$myObj->code = -3;
		$myObj->result = [];
	}

	header('Content-type: application/json');
	$output = json_encode($myObj, JSON_UNESCAPED_UNICODE);
	echo $output;
}
else 
{
	$myObj = new stdClass;
	$myObj->code = -1;
	$myObj->result = [];

	header('Content-type: application/json');
	$output = json_encode($myObj, JSON_UNESCAPED_UNICODE);
	echo $output;

}

