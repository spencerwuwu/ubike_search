<?php

require 'function.php';

/*
if (true)
{
	$lat = 25.034145;
	$lng = 121.568559;

 */
if( $_GET["lat"] && $_GET["lng"] )
{
	$lat = $GET["lat"];
	$lng = $GET["lng"];

	$flag = getAddr( $lat, $lng);
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

        $myObj->result[0]->station = $target[0]->sna;
        $myObj->result[0]->num_bike = $target[0]->sbi;
        $myObj->result[1]->station = $target[1]->sna;
        $myObj->result[1]->num_bike = $target[1]->sbi;
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


