<?php

$lat = 25.034153;
$lng = 121.568509;

$geocode = @file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lng&sensor=false");

$geodata = json_decode($geocode);

/*
if ( $geodata->results == [] ) echo "false";
else echo "true";

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

file_put_contents("Tmpfile.gz", fopen("http://data.taipei/youbike", 'r'));
$fileName = "Tmpfile.gz";
$distName = "bike";

uncompress($fileName, $distName);
 */
$command = escapeshellcmd('python2 getbike.py');
$output = shell_exec($command);

$distName = 'data.json';
$bikecode = file_get_contents($distName);
$bikedata = json_decode($bikecode);
echo $bikedata->results[0]->sno;

/*
//$data = $bikedata->result->results;
foreach ($bikedata->retVal as $key->$value)
{
	echo $key->sno.'<br>';
}
 */

//echo $output->results[0]->address_components[1]->long_name;

//if (stripos("hihi hello", "taipei") == NULL) echo "hello";

?>
