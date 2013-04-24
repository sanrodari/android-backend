<?php

$app->post('/sessions/', function () use ($app, $db, $accept) {

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
    }
  }
  else {
    $response['error'] = 'Faltan parámetros';
  }

  if($accept == 'application/json') {
    echo json_encode($response); 
  }
  else {
    // Si inició sesión correctamente.
    if($user) {
      $app->setCookie('Authorization', $user->token, '30 days');
      $app->redirect($app->urlFor('songs'));
    }
    else {
      $app->redirect($app->urlFor('newSession'));
    }
  }

})->name('sessions');

// Rutas específicas para representación HTML

$app->get('/sessions/new', function () use ($app, $db, $accept) {
  $app->render('sessions/new.html', array(
    'title' => 'Iniciar sesión'
  ));
})->name('newSession');

$app->delete('/sessions/', function () use ($app, $db, $accept) {
  $app->deleteCookie('Authorization');
});