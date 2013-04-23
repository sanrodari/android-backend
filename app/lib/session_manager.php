<?php

require_once '../app/lib/database.php';
$db = Database::getInstance();

function authorizeRequest($app, $db){  
  $reqHeaders = getallheaders();

  $authorization =
    isset($reqHeaders['Authorization']) ?
      $reqHeaders['Authorization'] : $reqHeaders['authorization'];

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
    $app->response()->status(401);
    $req = $app->request();
    $accept = $req->headers('Accept');
    if($accept == 'application/json') {
      echo json_encode(array('error' => 'No tiene una sesión activa.'));
    }
    else {
      # TODO servir la página
      echo "No tiene una sesión activa.";
    }

    return FALSE;
  }
};