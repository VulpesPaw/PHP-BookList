<?php

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
  $tryLogin['email'] = strtolower($tryLogin['email']);
  $uid = md5($_POST['email']);
  $tryUser = json_decode(file_get_contents('../db/users/'.$uid.'.json'));

  if($currentUser = doesUserExist( $tryLogin, $tryUser)){//!throws me out

      //when we've found a user
      $pwdCheck = password_verify($tryLogin['password'], $currentUser['password']);

      if($pwdCheck){       
        session_start();
        $_SESSION['loggedIn'] = true;
        $_SESSION['username'] = $currentUser['username'];
        $_SESSION['uid'] = $currentUser['uid'];
        header('Location:../?mes=loggedIn');
        exit();
      }else{        
  header('Location:login.php?mes=loginFail'); // if no post, sends away
  exit();
  }
  }else{
    header('Location:login.php?mes=loginFail');
    exit();
  }
}else{
    header('Location:login.php?mes=loginFail');
    exit();
  }
}else{
     header('Location:login.php?');
     exit();
  }
?>