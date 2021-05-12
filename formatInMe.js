/*<? php

echo '<script>alert("Welcome to Geeks for Geeks")</script>';

if ($_POST) {

    require_once("userChecker.php");

    $user = $_POST;
    $user["password"] = password_hash($user['password'], PASSWORD_DEFAULT, ["cost"=> 14]); //hardbracets bc it's not an array

    // Get existing users from file
    $users = json_decode(file_get_contents("../DB/users.json"));

    // Check if email is already registerd
    if (!doesUserExist($user, $users)) {
        $user[] = $user; // pushes new user into userlist

        file_put_contents("../DB/users.json", json_encode($users, JSON_PRETTY_PRINT));

        header("login.php?mes=registerd"); //redirects you to the main site

    }
    else {
        echo('alert("Login failed, user already exists!");');
    }

}


/* TODO:
if (logged){

  require_once myCode.html
}