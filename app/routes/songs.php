<?php

$app->get('/songs/', function () use ($app, $db, $accept) {
  if(!$user = authorizeRequest($app, $db)) return;

  $all = $db->all('SELECT * FROM songs s WHERE `s`.`user` = :user', array(
    "user" => $user->id
  ));

  if($accept == 'application/json') {
    echo json_encode($all);
  }  
  else {
    $app->render('songs/index.html', array(
      'songs'   => $all,
      'title'   => 'Canciones',
      'scripts' => array('index_songs.js')
    ));
  }
})->name('songs');

$app->get('/songs/:id', function ($id) use ($app, $db, $accept) {
  if(!$user = authorizeRequest($app, $db)) return;

  $one = $db->one('SELECT * FROM songs WHERE id = :id AND `user` = :idUser', array(
    'id'     => $id,
    'idUser' => $user->id
  ));

  if($accept == 'application/json') {
    if($one){
      echo json_encode($one);
    }
    else {
      echo json_encode(array('error' => 'No se encontró registro'));
    }
  }  
  else {
    $app->render('songs/show.html', array(
      'title' => "Detalle de canción {$one->name}",
      'song'  => $one
    ));
  }

})->name('song')
// TODO no funcionó esta regex (?!new)
->conditions(array('id' => '\\d+'));

$app->post('/songs/', function () use ($app, $db, $accept) {
  if(!$user = authorizeRequest($app, $db)) return;

  $req = $app->request();
  $response = array();
  if($req->post('name') && $req->post('url')) {
    $id = $db->execute('INSERT INTO songs (`name`, `url`, `user`) VALUES(:name, :url, :user)', 
      array(
        'name' => $req->post('name'),
        'url'  => $req->post('url'),
        'user' => $user->id
      )
    );

    $response['id'] = $id;
  }
  else {
    $response['error'] = 'Faltan parámetros';
  }

  if($accept == 'application/json') {
    echo json_encode($response);
  }  
  else {
    $app->redirect($app->urlFor('song', array("id" => $id)));
  }

});

$app->put('/songs/:id', function ($id) use ($app, $db, $accept) {
  if(!$user = authorizeRequest($app, $db)) return;

  $req = $app->request();
  $response = array();
  if($req->put('name') && $req->put('url')) {
    $rows = $db->execute('UPDATE `songs` s SET `name` = :name, `url` = :url WHERE s.id = :id AND `user` = :idUser', 
      array(
        'name'   => $req->put('name'),
        'url'    => $req->put('url'),
        'id'     => $id,
        'idUser' => $user->id
      )
    );

    $response['id']           = $id;
    $response['name']         = $req->put('name');
    $response['url']          = $req->put('url');
    $response['rowsAffected'] = $rows;
  }
  else {
    $response['error'] = 'Faltan parámetros';
  }

  if($accept == 'application/json') {
    echo json_encode($response);
  }  
  else {
    $app->redirect($app->urlFor('song', array("id" => $id)));
  }
});

$app->delete('/songs/:id', function ($id) use ($app, $db, $accept) {
  if(!$user = authorizeRequest($app, $db)) return;

  $req = $app->request();
  $response = array();
  $rows = $db->execute('DELETE FROM `songs` WHERE `id` = :id AND `user` = :idUser', 
    array(
      'id'     => $id,
      'idUser' => $user->id
    )
  );

  $response['rowsAffected'] = $rows;
  if($accept == 'application/json') {
    echo json_encode($response);
  }  
  else {
    $app->redirect($app->urlFor('songs'));
  }
});

// Rutas específicas para representación HTML

$app->get('/songs/:id/edit/', function ($id) use ($app, $db, $accept) {
  if(!$user = authorizeRequest($app, $db)) return;

  $one = $db->one('SELECT * FROM songs WHERE id = :id AND `user` = :idUser', array(
    'id'     => $id,
    'idUser' => $user->id
  ));

  if($one) {
    $app->render('songs/edit.html', array(
      'title' => "Editar {$one->name}",
      'song'  => $one
    ));
  }
  else {
    echo 'No existe el recurso solicitado.';
    $app->response()->status(404);
  }
})->name('editSong');

$app->get('/songs/new/', function () use ($app, $db, $accept) {
  if(!$user = authorizeRequest($app, $db)) return;

  $app->render('songs/new.html', array(
    'title' => "Crear canción"
  ));
})->name('newSong');