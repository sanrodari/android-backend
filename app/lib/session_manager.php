<?php

function authorizeRequest($app, $db, $respond = TRUE){  
  $req = $app->request();
  $accept = $req->headers('Accept');
  $reqHeaders = getallheaders();

  if($accept == 'application/json') {
    $authorization =
      isset($reqHeaders['Authorization']) ?
        $reqHeaders['Authorization'] : 
          isset($reqHeaders['authorization']) ? $reqHeaders['authorization'] : '';
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
      $app->response()->status(401);
      $req = $app->request();
      $accept = $req->headers('Accept');
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