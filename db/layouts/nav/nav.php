<?php
require_once(__DIR__."/../../../crud/crud.php"); /*Completly forgot about __DIR__ until now, that's why I may have strange workarourds here and there*/

$navHTML = file_get_contents(__DIR__."/nav.html");

if($_SESSION['loggedIn'] === true){
  
  $navHTML = str_replace("{{logoutButton}}", 
  '<button onclick="location.href='."'".__DIR__."/../../../?logout=logout "."'".'">Logout</button>',$navHTML);
}else{
  $navHTML = str_replace('{{logoutButton}}','<button onclick="location.href='."'".__DIR__."/../../../?login=signin "."'".'">SignIn</button>',$navHTML); 
}

$navHTML = str_replace("{{homePath}}", __DIR__."/../../../",$navHTML);

?>

