<?php
$refno = htmlspecialchars( $_GET["refno"]);
echo $refno;
$table = htmlspecialchars( $_GET["table"]);
echo $table;
$URL = "http://lancasteruad.oxfordarchaeology.com/rest/index.php/" . $table . "/UADReferenceNumber/" . $refno;
echo $URL;
$json = file_get_contents($url);
$jsonIterator = new RecursiveIteratorIterator(
    new RecursiveArrayIterator(json_decode($json, TRUE)),
    RecursiveIteratorIterator::SELF_FIRST);

foreach ($jsonIterator as $key => $val) {
    if(is_array($val)) {
        echo "$key:\n";
    } else {
        echo "$key => $val\n";
    }
}
?>
