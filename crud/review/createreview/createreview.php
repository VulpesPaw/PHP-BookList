<?php

session_start();

if($_SESSION['loggedIn'] === true ){  

require_once("../../crud.php");

//Imports all parts of page
$page = "<head> <title>Update book in database</title> </head>";

$page = $page.file_get_contents("../../../html/header.html");

$page = str_replace("{{cssPath}}", "../../../db/layouts/homepage/homepage.css" , $page);

$page = $page.file_get_contents("createreview.html");


$page = $page.file_get_contents("../../../html/endtag.html");

//gets correct JSON file and reads database using $_GET,

$title = strtolower($_GET['title']);
if($_GET['serie']){
  $tserie = strtolower($_GET['serie']."/");
}else{
  $tserie = "";
}

if(!json_decode(file_get_contents("../../../db/books/".$tserie.$title."/book.json"))){
  header("Location:error.html?title=".$_GET['title']."&serie=".$tserie );  
exit();
}

//String replace to get dynamic content

$page = str_replace("{{title}}", $title, $page);
if(str_replace("{{serie}}", $_GET['serie'], $page)){
  $page = str_replace("{{serie}}", $_GET['serie'], $page);
}else{
  $page = str_replace("{{serie}}", "", $page);
}

include("../../../db/layouts/nav/nav.php");

$page = str_replace("{{navigation}}", $navHTML, $page);

$page = str_replace("<h1>My Book List</h1>", "<h1>Create review on ".$title."</h1>", $page);

echo $page;

}else{
header("Location:../../../?login=signin");
exit();
}


?>