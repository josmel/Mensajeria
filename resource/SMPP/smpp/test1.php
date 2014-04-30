<?php

echo "Start <br>";

include 'class.smpp.php';

echo "imported <br>";

$src  = "993434610"; // or text 
$dst  = "993434610";
$message = "Developer Test SMPP Message";

$s = new smpp();

echo "object created <br>";

$s->debug=1;

// $host,$port,$system_id,$password


echo "pre-open : ";
echo date("Y-m-d H:i:s",time());
echo "<br>";

$s->open("67.228.249.34", 8888, "fcardenas", "85470066");

//echo($s);

//echo "after";
//echo date("Y-m-d H:i:s",time());
echo "<br>";


// $source_addr,$destintation_addr,$short_message,$utf=0,$flash=0
$s->send_long($src, $dst, $message);

/* To send unicode 
$utf = true;
$message = iconv('Windows-1256','UTF-16BE',$message);
$s->send_long($src, $dst, $message, $utf);
*/

$s->close();

echo "<br>";
echo "after <br>";
echo date("Y-m-d H:i:s",time());


?>
