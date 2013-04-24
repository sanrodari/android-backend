<?php

if (!function_exists('getallheaders')) { 
  function getallheaders()  { 
    $headers = ''; 
    foreach ($_SERVER as $name => $value) { 
      if (substr($name, 0, 5) == 'HTTP_') { 
        $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value; 
      } 
    } 
    return $headers; 
  } 
}

function authorizeRequest($app, $db, $respond = TRUE){  
  $req = $app->request();
  $accept = $req->headers('Accept');
  $reqHeaders = getallheaders();

  $authorization = '';
  if($accept == 'application/json') {
    if(isset($reqHeaders['Authorization'])) {
      $authorization = $reqHeaders['Authorization'];
    }
    elseif (isset($reqHeaders['authorization'])) {
      $authorization = $reqHeaders['authorization']; 
    }
  }
  else {
    $authorization = $app->getCookie('Authorization');
  }

  $user = NULL;
  if($authorization) {
    $user = $db->one('SELECT * FROM users u WHERE u.token = :token', 
      array(
        'token' => $authorization
      )
    );
  }

  if($user) {
    return $user;
  }
  else {

    if($respond) {
      // $app->response()->status(401);
      if($accept == 'application/json') {
        echo json_encode(array('error' => 'No tiene una sesión activa.'));
      }
      else {
        $app->redirect($app->urlFor('newSession'));
      }
    }

    return FALSE;
  }
};