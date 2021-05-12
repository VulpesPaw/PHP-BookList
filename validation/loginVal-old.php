<?php

/// /// THIS IS ONLY FOR DEVELOP BACKLOOKING, THIS IS NOT FUNCTIONING AND SHOULD NO BE IMPLEMENTED WITHOUT A FIX! FOR IMPLEMENTAION, REFER TO NEW VALIDATION FILES! /// ///

session_start();

require_once('userChecker.php');

if($_POST){  
if (isset($_POST['email'])
&& isset($_POST['password'])
&& ($_POST['email']!=null)
&& ($_POST['password']!=null)
)
{
  $tryLogin = $_POST;
  debug_to_console($tryLogin);
debug_to_console("get users1");
  //gets exisitng users
  $users = json_decode(file_get_contents('../db/userList.json'));

  /* Possible Warning, it may be solved
  [Mon Nov 30 15:23:07 2020] PHP Recoverable fatal error:  Object of class stdClass could not be converted to string in /home/runner/My-Book-List/validation/userChecker.php on line 21
[Mon Nov 30 15:23:07 2020] 172.18.0.1:56328 [500]: /validation/loginVal.php  
  */


 //check if login attempt matches exisitng user
  if($currentUser = doesUserExist($tryLogin, $users)){//!throws me out
debug_to_console("Current user");
      //when we've found a user
      $pwdCheck = password_verify($tryLogin['password'], $currentUser['password']);

      if($pwdCheck){       
        session_start();
        $_SESSION['loggedIn'] = true;
        $_SESSION['username'] = $currentUser['username'];
        $_SESSION['uid'] = $currentUser['uid'];
        
        header('Location:../?mes=loggedIn');


      }else{
        debug_to_console("away");
  header('Location:login.php?mes=loginFail'); // if no post, sends away
 // echo'<scipt>alert("Login failed, try again later")';
  }
  }else{
     header('Location:login.php?mes=loginFail');
  }
}else{
     header('Location:login.php?mes=loginFail');
  }
}else{
     header('Location:login.php');
  }



?>