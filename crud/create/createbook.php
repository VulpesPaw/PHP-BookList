<?php

session_start();

if($_SESSION['loggedIn'] === true){  
require_once("../crud.php");

$page = "<head> <title>Add book to database</title> </head>";

$page = $page.file_get_contents("../../html/header.html");

$page = $page.file_get_contents("createbook.html");

$page = $page.file_get_contents("../../html/endtag.html");


$page = str_replace("{{cssPath}}", "../..//db/layouts/homepage/homepage.css" , $page);

include("../../db/layouts/nav/nav.php");

$page = str_replace("{{navigation}}", $navHTML, $page);

$page = str_replace("<h1>My Book List</h1>", "<h1>Add Book To Database</h1>", $page);

echo $page;

}else{
  header("Location:../../");
exit();
}
?>