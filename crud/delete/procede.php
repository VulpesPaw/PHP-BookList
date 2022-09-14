<?php

session_start();

if($_SESSION['loggedIn'] === true && $_POST){  

require_once("../crud.php");

deleteBook($_POST['title'], $_POST['serie']); 

$title = "?title=".strtolower($_POST['title']);

if($_POST['serie']!=null){
  $serie = "&serie=".strtolower($_POST['serie']);
}else{
  $serie = "";
}

if($title){
  header('Location:../../'.$title.$serie);
exit();
}

}else{
header("Location:../../login=signin");
exit();
}

