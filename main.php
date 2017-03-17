<?php

require 'function.php';

if( $GET["lat"] && $GET["lng"] )
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
	}
	else
	{
		$myObj->code = -3;
		$myObj->result = [];
	}

	$output = json_encode($myObj);
	echo $output;
}


