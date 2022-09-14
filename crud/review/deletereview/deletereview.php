<?php

session_start();

if($_SESSION['loggedIn'] === true && $_GET){

require_once("../../crud.php");
$info = $_GET;

if($info['serie']!=null){
$tserie= $info['serie']."/";
}else{
  $tserie = "";
}

$data = (array)json_decode(file_get_contents("../../../db/books/".$tserie.$info['title']."/book.json")); //json format
$newArr = [];

foreach($data['reviews'] as $key => $val){

  if($val->uid != $_SESSION['uid'] || $val->revID != $info['revID']){
    $newArr[] =$val ;
  }
}
$data['reviews'] = $newArr;

$data = json_encode($data, JSON_PRETTY_PRINT);

saveData("../../../db/books/".$tserie.$info['title'], "/book.json", $data);

if(true){
  header("Location:../../../bookreader/?title=".$info['title']."&serie=".$info['serie']);
exit();
}
}else{
  header("Location:../../../");
exit();
}
