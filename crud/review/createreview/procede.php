<?php

session_start();

if($_SESSION['loggedIn'] === true && $_POST){  

require_once("../../crud.php");

$data = $_POST;

createReview($data); 

$title = "title=".strtolower($data['title']);

if($data['serie']!=null){
  $serie = "&serie=".strtolower($data['serie']);
}else{
  $serie = "";
}

if($title){
  header('Location:../../../bookreader/?'.$title.$serie);
exit();
}
}else{
header("Location:../../");
exit();
}

