<?php

$app->post('/sessions/', function () use ($app, $db, $accept) {

  if($accept == 'application/json') {
    $req = $app->request();
    $response = array();
    if($req->post('username') && $req->post('password')) {
      $user = $db->one('SELECT * FROM users u WHERE u.username = :username AND u.password = :password', 
        array(
          'username' => $req->post('username'),
          'password' => $req->post('password')
        )
      );

      if($user) {
        $response['token'] = $user->token;
        $response['id']    = $user->id;
        echo json_encode($response);
      }
      else {
        $response['error'] = 'Credenciales incorrectas.';
        echo json_encode($response); 
      }
    }
    else {
      $response['error'] = 'Faltan parámetros';
      echo json_encode($response);
    }
  }
  else {
    // TODO Iniciar sesión por página
    echo 'Iniciar sesión por página';
  }

});