<?php

session_start();

if (count($_POST)){
 
    require_once("userChecker.php");

    $user = $_POST;  
    if($user['email'] == null || $user['username'] == null || $user['password'] == null){
      header("Location:register.php?mes=regFail&res=incorrectInput");
 exit();
    }
    $user['email'] = strtolower($user['email']);

  foreach($userArr as $item ){ //replaces filename with file
    if($item == md5($user['email']).".json"){
       header("Location:register.php?mes=regFail&res=userExist");
       exit();
  } 
}


    $user["password"] = password_hash($user['password'], PASSWORD_DEFAULT, ["cost"=> 14]);

    // Get existing userList from file
$userArr = scandir("../db/users/");
$userArr =  array_diff($userArr, array('..','.')); 


$LOU = [];
foreach($userArr as $item ){ //replaces filename with file
  $LOU[] = json_decode(file_get_contents("../db/users/".$item));//list of users
}

    //checks if username is already in use
    if($name = doesUsernameExist($user, $LOU)){
       header("Location:register.php?mes=regFail&res=unEx");
       exit();
    }

    // Check if email is already registerd
    if (!$email = doesUserExist($user, $LOU)) {

      $user["uid"] = md5($user["email"]); // creates a unique id based on mail

      $userFile = $user;

        file_put_contents("../db/users/".$user["uid"].".json", json_encode($userFile, JSON_PRETTY_PRINT));

        header("Location:login.php?mes=regSucc"); //redirects you to the main site
        exit();

    }
    else {
        header('Location:register.php?mes=regFail&res=uEx');
        exit();
    }

}else{
  header('Location:register.php?mes=regFail'); // if no post, sends away  
  exit();
}

?>