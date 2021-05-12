<?php

session_start();

if($_SESSION['loggedIn'] === true){  

require_once("../crud.php");

//Imports all parts of page
$page = "<head> <title>Update book in database</title> </head>";

$page = $page.file_get_contents("../../html/header.html");

$page = str_replace("{{cssPath}}", "../..//db/layouts/homepage/homepage.css" , $page);

$page = $page.file_get_contents("update.html");

$page = $page.file_get_contents("../../html/endtag.html");

//gets correct JSON file and reads database using $_GET,

$title = strtolower($_GET['title']);
if($_GET['serie']){
  $serie = strtolower($_GET['serie']."/");
}else{
  $serie = "";
}

if(!json_decode(file_get_contents("../../db/books/".$serie.$title."/book.json"))){
  header("Location:error.html?title=".$_GET['title']."&serie=".$serie );  
exit();
}

$json = json_decode(file_get_contents("../../db/books/".$serie.$title."/book.json"));

//String replace to get dynamic content

$page = str_replace("{{title}}", $json->title, $page);
if(str_replace("{{serie}}", $json->serie, $page)){
  $page = str_replace("{{serie}}", $json->serie, $page);
}else{
  $page = str_replace("{{serie}}", "", $page);
}
$page = str_replace("{{author}}", $json->author, $page);
$page = str_replace("{{pages}}", $json->pages, $page);
$page = str_replace("{{release}}", $json->release, $page);
$page = str_replace("{{synopsis}}", str_replace("<br />", "", $json->synopsis), $page);

include("../../db/layouts/nav/nav.php");

$page = str_replace("{{navigation}}", $navHTML, $page);

$page = str_replace("<h1>My Book List</h1>", "<h1>Update Book In Database</h1>", $page);

echo $page;

}else{
  header("Location:../../?login=signin");
exit();
}

?>