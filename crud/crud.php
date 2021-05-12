<?php

session_start();

/** mkdir - how to use
 mkdir - makes directory
 mkdir("directory to create", code (0777 = standard), bool:(if directory are a maptree(otherwise just specific map)))
 */
function createBook($data) { //creates book and creates maptree for said book
   
    $dataSyn = $data['synopsis'];
    $data = str_repAQ($data);//sanitizes data with exception for synopsis
    $data['synopsis'] = htmlentities($dataSyn); //encodes special chars for synopsis
    $data['synopsis'] = nl2br(filter_var($data['synopsis'], FILTER_SANITIZE_STRING));

    $title = strtolower($data['title']);
    $serie = strtolower($data['serie']);

    //adds extra data to bookfile
    $data['create'] = $_SESSION['uid']; //creator of page
    $data['edit'] = []; //editors of page
    $data['reviews'] = []; // future array for reviews
    
    //creates directory pathway if needed
    $dir = "willBeFilled";
    if ($serie != null) { //if dir doesn't exist and a series is set, create a new directory
        if (!file_exists("../../db/books/" . $serie . "/" . $title)) {       
            $dir = "../../db/books/" . $serie . "/" . $title;
            mkdir($dir, 0777, true); 
            
        } else { //else; book already exist, sends back because it should not be created again
            header("location:create/createbook.php?error=bookExist"); 
            exit();
        }
    } else if ($serie == null) {//if dir doesn't exist and series isn't set, create a new directory
        if (!file_exists("../../db/books/" . $title)) {
            $dir = "../../db/books/" . $title;
            mkdir($dir, 0777, true); 
            
        } else { //else; book already exist, sends back because it should not be created again
            header("location:create/createbook.php?error=bookExist"); 
            exit();
        }
    }
    $data = json_encode($data, JSON_PRETTY_PRINT);// encodes data
    saveData($dir . "/", "book.json", $data); // saves data in potential new directory    
}

function saveData($dir, $filename, $file) {// saves a $file to the $dir:ectory with a $filename    
    file_put_contents($dir . $filename, $file);
}

function updateBook($data) {// uppdates book, note: name and series cannot be changed
    $dataSyn = $data['synopsis'];
    $data = str_repAQ($data); //sanitizes data with exception for synopsis
    $data['synopsis'] = htmlentities($dataSyn); // synopsis are encoded instead of sanitized
    $data['synopsis'] = nl2br(filter_var($data['synopsis'], FILTER_SANITIZE_STRING));
    $title = strtolower($data['title']);
    //checks if series is set (done autonaticly if book got a series)
    if (strtolower($data['serie'] != "{{serie}}" && $data['serie'] != null)) {
        $serie = strtolower($data['serie']);
    } else {
        $serie = "";
    }
    if ($serie != null) {//checks if file is found and gets directory
        if (file_exists("../../db/books/" . $serie . "/" . $title)) {
            $dir = "../../db/books/" . $serie . "/" . $title;
        }
    } else {// If book got no serie, get directory;
        $dir = "../../db/books/" . $title;
    }

    //gets data and converts it into an array
    $jsondata = (array)json_decode(file_get_contents($dir . "/book.json"));
    
    // replace data in json-file
    foreach ($jsondata as $key => $val) {
        if ($val != $data[$key] && $data[$key] != null) {
            $jsondata[$key] = $data[$key];
        }
    };

    //adds uid to editors if uid aren't already there
    if (!in_array($_SESSION['uid'], $jsondata['edit'])) {
        $jsondata['edit'][] = $_SESSION['uid'];
    }
    $json = json_encode($jsondata, JSON_PRETTY_PRINT);
   
    saveData($dir . "/", "book.json", $json);//overwrites old data
}
function createReview($data) {// creates review
    $data['review'] = htmlentities($data['review']);//encodes review
    $title = strtolower($data['title']);

    //checks if book got a serie
    if (strtolower($data['serie'] != "{{serie}}" && $data['serie'] != null)) {
        $serie = strtolower($data['serie']);
    } else {
        $serie = "";
    }
    if ($serie != null) {//checks if file is found and gets directory
        if (file_exists("../../../db/books/" . $serie . "/" . $title)) {
            $dir = "../../../db/books/" . $serie . "/" . $title;
        }
    } else {// If book got no serie, the title is used like a serie;
        $dir = "../../../db/books/" . $title;
    }
    
    //gets data
    $json = json_decode(file_get_contents($dir . "/book.json"));
    $reviewArr = [
      "uid" => $_SESSION['uid'],
      "review" => nl2br($data['review']),
      "revID" => uniqid("", true) ];
    $json->reviews[] = $reviewArr;// adds review to array of reviews
    $json = json_encode($json, JSON_PRETTY_PRINT);
    saveData($dir . "/", "book.json", $json); // saves review       
}

function deleteBook($title, $serie = null) {// deletes book from database
    $title = strtolower($title);
    if ($serie != null) {
        $serie = strtolower($serie);
    }
    $dir = "../../db/books/";
    /* 
    Checks if serires got multiple books in them, if it. 
    If true, serie-map should be kept. 
    If count are nor equal or less than 3 are because current( . ) and upper( .. ) direcroty are counted as well
    */
    if (count(scandir($dir . $serie)) <= 3 && $serie != null) {
        rrmdir($dir . $serie);
    } else {
        rrmdir($dir . $title);
    }
}
function rrmdir($dir) { // removes direcroty-tree and everything inside it
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $object) && !is_link($dir . "/" . $object)) rrmdir($dir . DIRECTORY_SEPARATOR . $object);
                else unlink($dir . DIRECTORY_SEPARATOR . $object);
            }
        }
        rmdir($dir);
    }
}

function sanitizeData($data) {//sanitize function
    $review = $data['reviews'];
    $synopsis = $data['synopsis'];
    foreach ($data as $sanitize) {
        $temp = str_repAQ($sanitize);
        $temp = filter_var($temp, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_LOW);
        $temp = filter_var($temp, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH);
        $sanitize = htmlspecialchars($temp);
    }
    $review = htmlspecialchars($review);
    $synopsis = htmlspecialchars($synopsis);
    $data['review'] = $review;
    $data['synopsis'] = $synopsis;
    return $data;
}

function str_repAQ($data) {//custom sanitize/replace-function
    $data = str_replace("&", "And", $data);
    $data = str_replace("?", "Q", $data);
    $data = str_replace("#", "-charp", $data);
    $data = str_replace("'", "", $data);
    $data = str_replace('"', "", $data);   
    $data = str_replace("/","-slach-", $data);   
    $data = str_repTag($data);
    if($data == null){//in case all $data is erased
      $data = "nullified";
    }    
    return $data;
}
function str_repTag($data) {// removes tags
    $data = str_replace("<", "", $data);
    $data = str_replace(">", "", $data);
    return $data;
}
function debug_to_console($output) {// usefull debug function
    if (is_array($output)) $output = implode(',', $output);
    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}
?>