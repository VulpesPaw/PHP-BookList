<?php

session_start();

//Redirections
if ($_GET['logout'] == 'logout') { // sends to logoutscreen
    header("Location:/pieces/002_phpbook/logout.php");
    exit();//exit is after every header to ensure it directs directly
} else if ($_GET['login'] == 'signin') {
    header("Location:https://portfolio.torparlyckan.se/pieces/002_phpbook/validation/login.php");
    exit();
} else if ($_GET['logout'] == 'suc') {
    echo '<script> alert("You have succesfully logged out"); </script>';
} else if ($_SERVER["PATH_INFO"] != null) { //fixes some problems with pathing
    header("Location:https://portfolio.torparlyckan.se/pieces/002_phpbook/");
    exit();
}

require_once ("crud/crud.php");

//Imports html and replaces tags to build up page
$page = "";
$page = $page . file_get_contents("html/header.html");
$page = $page . file_get_contents("db/layouts/homepage/homepage.html");
$page = $page . file_get_contents("html/endtag.html");
$temp = "";

include ("db/layouts/nav/nav.php");//includes and replaces tags in nav.html
$page = str_replace("{{cssPath}}", "./db/layouts/homepage/homepage.css", $page);
$page = str_replace("{{navigation}}", $navHTML, $page);

if (!$_SESSION['loggedIn'] === true || $_SESSION['loggedIn'] == null) { // if not logged in, buttonlinks are set to singin
    $temp = "<button onclick='location.href=" . '"./validation/login.php"' . "' type='button'>Log in</button><br>";
    $temp = $temp . "<button onclick='location.href=" . '"./validation/register.php"' . "' type='button'>Register</button>";
    $page = str_replace("{{addLibaryPath}}", "./validation/login.php", $page);
}
if ($_SESSION['loggedIn'] === true) { // if logged in, greeted with username
    $temp = "<br> Welcome dear " . $_SESSION['username'] . "!<hr>";
    $page = str_replace("{{addLibaryPath}}", "crud/create/createbook.php", $page);
}
$page = str_replace("{{welcome}}", $temp, $page);

$bookIndex = [];
if ($series = scandir("db/books/")) { //Gets directory to all avalible books
    $series = array_diff($series, array('..', '.')); //delets the dots in start of scandirs, representing upper and current directory (i think)
    foreach ($series as $serie) {
        if (!file_exists("db/books/" . $serie . "/book.json")) {
            $books = scandir("db/books/" . $serie);
            $books = array_diff($books, array('..', '.'));
            foreach ($books as $book) {
                if (file_exists("db/books/" . $serie . "/" . $book . "/book.json")) {
                    $bookIndex[] = "db/books/" . $serie . "/" . $book . "/book.json";
                }
            }
        } else {
            $bookIndex[] = "db/books/" . $serie . "/book.json"; // puts directories of the book in an array
        }
    }
}

$bookList = "";
foreach ($bookIndex as $book) {// gets all books and and writes them to page with title, series and author
    $json = json_decode(file_get_contents($book));
    $bookList = $bookList . file_get_contents("html/book.html");
    $bookList = str_replace("{{bookTitle}}", $json->title, $bookList);
    if ($json->serie) {
        $bookSerie = $json->serie . "; ";
    } else {
        $bookSerie = "";
    }
    $bookList = str_replace("{{bookSerie}}", $bookSerie, $bookList);
    $bookList = str_replace("{{bookAuthor}}", $json->author, $bookList);
    if ($json->serie) {
        $tSerie = strtolower("&serie=" . $json->serie);
    } else {
        $tSerie = "";
    }
    $bookList = str_replace("{{bookPath}}", "bookreader/?title=" . strtolower($json->title) . $tSerie, $bookList);
}

$page = str_replace("{{books}}", $bookList, $page);
$page = str_replace("{{footer}}", file_get_contents("html/footer.html"), $page);
echo $page;
?>
