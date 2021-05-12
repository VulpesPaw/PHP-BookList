<?php
session_start();

if($_SESSION['loggedIn'] === true){  
  header("Location:../../");
  exit();
}else{

include_once('login.html');

  if($_GET){
   if($_GET['mes'] == 'loginFail'){
   echo "<hr><h3>Login Failed, make sure to write correct email and password!</h3><hr>";
  }
  if($_GET['res'] == 'fieldNull'){
    echo '<h2>A field in the form was not filled propertly</h2>';
  }
}
  include_once('../html/endtag.html');
}