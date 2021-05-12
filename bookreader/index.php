<?php

session_start();

require_once ("../crud/crud.php");

if ($_GET['logout'] == 'suc') {
    echo '<script> alert("You have succesfully logged out"); </script>';
}

//css link is from html which is one step lower than serie/title/ (autoloading index.php)
$title = $_GET['title']; //gets series and title to fetch json document
if ($_GET['serie']) {
    $serie = $_GET['serie'];
    $tserie = strtolower($_GET['serie'] . "/");
} else {
    $serie = "";
    $tserie = "";
}

//checks if book file is found, ie if book exists, else sends to error.html
if (!json_decode(file_get_contents("../db/books/" . $tserie . $title . "/book.json"))) {
    header("Location:error.html?title=" . $_GET['title'] . "&serie=" . $serie);
    exit();
}

// gets book-file and html templates and replaces tags with content
$json = json_decode(file_get_contents("../db/books/" . $tserie . $title . "/book.json"));
$page = file_get_contents("../html/header.html");
$page = $page . file_get_contents("../db/layouts/book/template.html");
$page = $page . file_get_contents("../html/endtag.html");
$Title = $json->title;
$Serie = $json->serie;
if ($json->serie != null) {
    $tserie = " - " . $json->serie;
} else {
    $tserie = "";
}

$page = str_replace("<title>My Book List</title>", "<title>" . $Title . $tserie . "- My Book List</title>", $page);
$page = str_replace('<link rel="stylesheet" href="style.css">', '<link rel="stylesheet" href="../db/layouts/book/style.css">', $page);
include ("../db/layouts/nav/nav.php");
$page = str_replace("{{navigation}}", $navHTML, $page);

if ($serie != null) { //if book has serie, replace serietag and tiltetag with the corresponding of said book
    $temp = str_replace("{{serie}}", $Serie, file_get_contents("../html/title.html"));
    $temp = str_replace("{{title}}", $Title, $temp);
    $page = str_replace("{{title}}", $temp, $page);
    $page = str_replace("{{updatePath}}", "../crud/update/update.php?title=" . $title . "&serie=" . $serie, $page);
    $page = str_replace("{{deletePath}}", "../crud/delete/delete.php?title=" . $title . "&serie=" . $serie, $page);
} else { //replace titletag
    $page = str_replace("{{title}}", "<h2>" . $Title . "</h2><hr>", $page);
    $page = str_replace("{{updatePath}}", "../crud/update/update.php?title=" . $title, $page);
    $page = str_replace("{{deletePath}}", "../crud/delete/delete.php?title=" . $title, $page);
}
// replace authortag with book author
$page = str_replace("{{author}}", "Author: " . $json->author, $page);
$page = str_replace("{{release}}", "Release year: " . $json->release, $page);
if ($json->pages != null) { //check if book has an amout of pages
    $page = str_replace("{{pages}}", "Amount of Pages: " . $json->pages, $page);
} else {
    $page = str_replace("{{pages}}", "", $page);
}
// replace synopsis with book's synopsis
$page = str_replace("{{synopsis}}", $json->synopsis, $page);
if ($json->serie != null) {
    $tserie = strtolower("&serie=" . $json->serie);
} else {
    $tserie = "";
}
$page = str_replace("{{crReviewPath}}", "../crud/review/createreview/createreview.php?title=" . $title . $tserie, $page);

if ($json->reviews) { // checks if there are reviews, loops through them and adds them to page
    $reviews = "";
    $temp = "";
    foreach ($json->reviews as $value => $item) {//gets reviewtemplate and replaces tags with content
        $temp = str_replace("{{username}}", uidToUsername($item->uid), file_get_contents("../html/review.html"));
        if ($item->uid == $_SESSION['uid']) {
            $delBtn = '<button onclick="location.href=' . '{{deletePath}}' . '">Delete Review</button>';
            $delBtn = str_replace("{{deletePath}}", "'../crud/review/deletereview/deletereview.php?revID=" . $item->revID . "&title=" . $title . "&serie=" . $serie . "'", $delBtn);
        } else {
            $delBtn = "";
        }
        $temp = str_replace("{{deleteReview}}", $delBtn, $temp);
        $reviews = $reviews . str_replace("{{review}}", $item->review, $temp);
    }
    $page = str_replace("{{reviews}}", $reviews, $page);
} else {
    $page = str_replace("{{reviews}}", "", $page);
}

$page = str_replace("{{serie}}", $Serie, $page);
if ($serie != null) { //checks if there are other books in current series, if there are they're printed out on page
    $bookIndex = [];
    $books = scandir("../db/books/" . $serie);
    $books = array_diff($books, array('..', '.'));
    foreach ($books as $book) {
        if (file_exists("../db/books/" . $serie . "/" . $book . "/book.json") && $book != $title) {
            $bookIndex[] = "../db/books/" . $serie . "/" . $book . "/book.json";
        }
    }
    $bookList = "";
    foreach ($bookIndex as $book) {
        $jsonBook = json_decode(file_get_contents($book));
        $bookList = $bookList . file_get_contents("../html/linkedbook.html");
        $bookList = str_replace("{{bookTitle}}", $jsonBook->title, $bookList);
        $bookList = str_replace("{{bookAuthor}}", $jsonBook->author, $bookList);
        //$tserie = strtolower($_GET['serie']);
        $bookList = str_replace("{{bookPath}}", "?title=" . strtolower($jsonBook->title) . "&serie=" . $_GET['serie'], $bookList);
    }
    $page = str_replace("{{otherBooks}}", $bookList, $page);
    $page = str_replace("{{tserie}}", $jsonBook->serie, $page);
} else {
    $page = str_replace("{{tserie}}", "this Series", $page);
    $page = str_replace("{{otherBooks}}", "", $page);
}

function uidToUsername($uid) {//gets username from their uid, works because userfiles are based on thier uids'
    $user = (array)json_decode(file_get_contents("../db/users/" . $uid . ".json"));
    $username = $user['username'];
    return $username;
}
$page = str_replace("{{creator}}", "Created by: " . uidToUsername($json->create), $page);// adds creator of page
foreach ($json->edit as $editor) { // if there are editors, thier names gets added to page
    if ($editor == $json->edit[0]) {
        $editors = "Edited by: " . uidToUsername($editor);
    } else if ($editor == end($json->edit)) {
        $editors = $editors . " & " . uidToUsername($editor);
    } else {
        $editors = $editors . ", " . uidToUsername($editor);
    }
}
$page = str_replace("{{editor}}", $editors, $page);
$page = str_replace("{{footer}}", file_get_contents("../html/footer.html"), $page);

echo $page;//renders page
?>