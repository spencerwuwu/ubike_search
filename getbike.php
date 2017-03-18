<?php


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

$bikecode = file_get_contents($distName);
echo $bikecode;
$bikedata = json_decode($bikecode);
