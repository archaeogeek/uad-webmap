<?php
$refno = htmlspecialchars( $_GET["refno"]);
echo $refno."<br>";
$table = htmlspecialchars( $_GET["table"]);
echo $table."<br>";
$URL = "http://lancasteruad.oxfordarchaeology.com/rest/index.php/" . $table . "/UADReferenceNumber/" . $refno;
echo $URL."<br>";
$json = file_get_contents($URL);
$jsonIterator = new RecursiveIteratorIterator(
    new RecursiveArrayIterator(json_decode($json, TRUE)),
    RecursiveIteratorIterator::SELF_FIRST);

foreach ($jsonIterator as $key => $val) {
    if(is_array($val)) {
        echo "$key:"."<br>";
    } else {
        echo "$key => $val"."<br>";
    }
}
?>
