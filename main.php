<?php

require 'function.php';

if( $GET["lat"] && $GET["lng"] )
{
	$lat = $GET["lat"];
	$lng = $GET["lng"];
	if (inTaipei( $lat, $lng) == 0)
	{
		$myObj->code = -2;
		$myObj->result = [];
	}
	else
	{
	}

	$output = json_encode($myObj);
	echo $output;
}


