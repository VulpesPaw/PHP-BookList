<?php

session_start();

if($_SESSION['loggedIn'] === true){  
  header("Location:../../");
  exit();

}else{

  include_once("register.html");
  include_once('../html/endtag.html');
  if($_GET){
    
   if($_GET['mes'] == 'regFail'){
   echo "<hr><h3>REGISTRATION FAILED</h3><hr>";
  }if ($_GET['mes'] == 'noMatchPwd'){
    echo "<hr><h3>PASSWORD AND CONFIRM PASSWORD DOESN NOT MATCH</h3><hr>";
  }if($_GET['res'] == 'uEx'){
    echo"<hr><h3>User already exist</h3><hr>";
  }
  if($_GET['res'] == 'unEx'){
    echo"<hr><h3>Username is already in use</h3><hr>";
  }
 }  
}




?>