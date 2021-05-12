<?php

/// THIS FILE EXIST PURLEY FOR EXPERIMENTAL PURPOSES, FOLLOWING CODE ARE TESTED HERE AND MAY OR MAY NOT WORK!! ///
/// USE AT YOUR OWN RISK! ///

header('Content-type: text/html; charset=utf-8');
$user = $_GET;

$user["uid"] = md5("fox@paw.se");
//$user["uid2"] = password_hash($user['uid2'], PASSWORD_DEFAULT, ["cost"=> 4]);


var_dump($user);


echo '<hr>';

$str = '"<br><script> none åäö </script>this is us ?&¤#!23 [{]}[{€£$"';
/*
$bb = htmlentities($str);
echo htmlentities($str);
var_dump($bb);
echo '<hr>';
$aa =  htmlspecialchars($str);
echo $aa;
var_dump($aa);
*/
/*
echo utf8_encode($str );
echo "<hr>";
echo htmlentities($str );
echo "<hr>";
echo htmlspecialchars($str );
echo "<hr>";*//*
echo "&lt;sciprt&gt;";

$unique =  uniqid ("" ,  true);
$unique = password_hash($unique, PASSWORD_DEFAULT, ["cost"=> 4]);

echo "<hr>";
echo $unique;
echo "<hr>";
echo $unique2;
echo "<hr>";
echo $unique3;
echo "<hr>";
echo $unique.$unique2;


//var_dump(utf8_encode($str ));*/
/*
$url = 'https://my-book-list.richardte18it.repl.co/easyTest.php';
$data = array('key1' => 'value1', 'key2' => 'value2');

// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
if ($result === FALSE) { /* Handle error */ /*}

var_dump($result);

include('html/html-1.html');
exit();*/


//date_default_timezone_set ( "	Europe/London");
//$date = DateTime::format(date(),"Y-m-d_H:i:s:u" ) ;
var_dump(microtime (true) ) ;