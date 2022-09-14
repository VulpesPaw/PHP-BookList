<?php

session_start();


if($_SESSION['loggedIn'] === true){  

require_once("../crud.php");

//Imports all parts of page
$page = "<head> <title>Delete book in database</title> </head>";

$page = $page.file_get_contents("../../html/header.html");

$page = $page.file_get_contents("delete.html");

/*$page = $page.file_get_contents("deleteJs.html");*/

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
$page = str_replace("{{serie}}", $json->serie, $page);

include("../../db/layouts/nav/nav.php");

$page = str_replace("{{navigation}}", $navHTML, $page);

$page = str_replace("<h1>My Book List</h1>", "<h1>Delete Book In Database</h1>", $page);

$page = str_replace("{{cssPath}}", "../../db/layouts/homepage/homepage.css" , $page);

echo $page;

}else{
header("Location:../../");
exit();
}

?>

