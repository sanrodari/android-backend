<?php

function _generateToken() {
  return uniqid(TRUE);
}

$app->post('/users/', function () use ($app, $db, $accept) {

  if($accept == 'application/json') {
    $req = $app->request();
    $response = array();

    if($req->post('username') && $req->post('password')) {
      try {
        $token = _generateToken();
        $id = $db->execute('INSERT INTO users (`username`, `password`, `token`) VALUES(:username, :password, :token)', 
          array(
            'username' => $req->post('username'),
            'password' => $req->post('password'),
            'token' => $token
          )
        );
      } catch (Exception $e) {
        $response['error'] = "El nombre de usuario {$req->post('username')} ya ha sido tomado.";
        echo json_encode($response);
        return;
      }

      $response['id'] = $id;
      $response['token'] = $token;
      echo json_encode($response);
    }
    else {
      $response['error'] = 'Faltan parámetros';
      echo json_encode($response);
    }
  }
  else {
    // TODO Crear por formulario de página
    echo 'Crear por formulario de página';
  }
});

$app->get('/users/:id', function ($id) use ($app, $db, $accept) {
  if(!$user = authorizeRequest($app, $db)) return;

  if($accept == 'application/json') {

    // Si el usuario es el mismo se obtiene toda la información
    if($user->id == $id) {
      $sql = 'SELECT * FROM `users` u WHERE u.id = :id';
    }
    // Sino solo se retorna la información básica
    else {
      $sql = 'SELECT u.id, u.username FROM `users` u WHERE u.id = :id';
    }

    $retrivedUser = $db->one($sql, array(
      'id' => $id
    ));

    if ($retrivedUser) {
      echo json_encode($retrivedUser);
    }
    else {
      $app->response()->status(404);
      echo json_encode(array("error" => "No se encuentra el usuario de id $id."));
    }
  }  
  else {
    # TODO servir la página
    echo "servir la página";
  }

});

$app->put('/users/:id', function ($id) use ($app, $db, $accept) {
  if(!$user = authorizeRequest($app, $db)) return;

  if($accept == 'application/json') {
    $req = $app->request();
    $response = array();
    if($req->put('password')) {
      $token = _generateToken();
      $rows = $db->execute(
        'UPDATE `users` u SET `password` = :password, `token` = :token WHERE u.id = :id AND id = :idUser', 
        array(
          'password' => $req->put('password'),
          'token'    => $token,
          'id'       => $id,
          'idUser'   => $user->id
        )
      );

      $response['id']           = $id;
      $response['token']        = $token;
      $response['rowsAffected'] = $rows;
      echo json_encode($response);
    }
    else {
      $response['error'] = 'Faltan parámetros';
      echo json_encode($response);
    }
  }  
  else {
    # TODO servir la página
    echo "servir la página";
  }
});