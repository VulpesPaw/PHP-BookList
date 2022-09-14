<?php

/// /// THIS IS ONLY FOR DEVELOP BACKLOOKING, THIS IS NOT FUNCTIONING AND SHOULD NO BE IMPLEMENTED WITHOUT A FIX! FOR IMPLEMENTAION, REFER TO NEW VALIDATION FILES !/// ///

session_start();

if (count($_POST)){
 
    require_once("userChecker.php");

    $user = $_POST;   

    $user["password"] = password_hash($user['password'], PASSWORD_DEFAULT, ["cost"=> 14]);

    // Get existing userList from file
    $userList = json_decode(file_get_contents("../db/userList.json"));

    //checks if username is already in use
    if(doesUsernameExist($user, $userList)){
       header("Location:login.php?mes=regFail&res=unEx");
    }


    // Check if email is already registerd
    if (!doesUserExist($user, $userList)) {

      $user["uid"] = md5($user["email"]); // creates a uniqe id based on mail
        $userList[] = $user; // pushes new user into userlist

        file_put_contents("../db/userList.json", json_encode($userList, JSON_PRETTY_PRINT));

        header("Location:login.php?mes=regSucc"); //redirects you to the main site

    }
    else {
        header('Location:register.php?mes=regFail&res=uEx');
    }

}else{
  header('Location:register.php?mes=regFail'); // if no post, sends away
  //echo'<scipt>alert("Registration failed, try again later")';
}



/*---- TODO: ----
if (logged){

  require_once myCode.html
}*/