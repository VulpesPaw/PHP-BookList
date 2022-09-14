<?php

session_start();

if($_SESSION['loggedIn']){  
  
session_destroy();
  header("Location:".$_SERVER['HTTP_REFERER']."?&logout=suc");
exit();
}
session_destroy();
if(true){
  header("location:/");
}
exit();
