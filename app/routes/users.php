<?php

function _generateToken() {
  return uniqid(TRUE);
}

$app->post('/users/', function () use ($app, $db, $accept) {

  $req = $app->request();
  $response = array();

  $token = '';
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
      // TODO Manejar la posibilidad del HTML
      echo json_encode($response);
      return;
    }

    $response['id'] = $id;
    $response['token'] = $token;
  }
  else {
    // TODO Manejar este error para HTML
    $response['error'] = 'Faltan parámetros';
  }
  
  if($accept == 'application/json') {
    echo json_encode($response);
  }
  else {
    $app->setCookie('Authorization', $token, '30 days');
    $app->redirect($app->urlFor('songs'));
  }
})->name('users');

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
})->name('user')
// TODO no funcionó esta regex (?!new)
->conditions(array('id' => '\\d+'));

$app->put('/users/:id', function ($id) use ($app, $db, $accept) {
  if(!$user = authorizeRequest($app, $db)) return;

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
  }
  else {
    $response['error'] = 'Faltan parámetros';
  }

  if($accept == 'application/json') {
    echo json_encode($response);
  }  
  else {
    $app->setCookie('Authorization', $token, '30 days');
    $app->redirect($app->urlFor('songs'));
  }
});

// Rutas específicas para representación HTML

$app->get('/users/:id/edit/', function ($id) use ($app, $db, $accept) {
  if(!$user = authorizeRequest($app, $db)) return;

  // Si el usuario es el mismo se obtiene toda la información
  if($user->id == $id) {
    $sql = 'SELECT * FROM `users` u WHERE u.id = :id';
  }

  $retrivedUser = $db->one($sql, array(
    'id' => $id
  ));

  if($retrivedUser) {
    $app->render('users/edit.html', array(
      'title'   => "Editar perfil",
      'user'    => $retrivedUser,
      'scripts' => array('edit_user.js')
    ));
  }
  else {
    echo 'No existe el recurso solicitado.';
    $app->response()->status(404);
  }
})->name('editUser');

$app->get('/users/new/', function () use ($app, $db, $accept) {
  $app->render('users/new.html', array(
    'title' => "Regístrate"
  ));
})->name('newUser');