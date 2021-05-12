<?php
session_start();

function doesUserExist($user, $users){//checks if user exists

    if ($user['email'] == $users->email){    
      return (array)$users;
   
    }
  
  
  return false;
}
function doesUsernameExist($user, $users){//checks if user exists

  foreach($users as $current   => $key  ){

    if ($user['username'] == $key->username){
 
      return $user;
    } 
  }  
  return false;
}

function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);  
}