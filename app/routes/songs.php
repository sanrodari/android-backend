<?php

$app->get('/songs/', function () use ($app, $db, $accept) {
  if(!authorizeRequest($app, $db)) return;

  if($accept == 'application/json') {
    $all = $db->all('SELECT * FROM songs');
    echo json_encode($all);
  }  
  else {
    # TODO servir la página
    echo "servir la página";
  }

});

$app->get('/songs/:id', function ($id) use ($app, $db, $accept) {
  if(!$user = authorizeRequest($app, $db)) return;

  if($accept == 'application/json') {
    $one = $db->one('SELECT * FROM songs WHERE id = :id',
      array('id' => $id)
    );

    if($one){
      echo json_encode($one);
    }
    else {
      echo json_encode(array('error' => 'No se encontró registro'));
    }
  }  
  else {
    # TODO servir la página
    echo "servir la página";
  }

});

$app->post('/songs/', function () use ($app, $db, $accept) {
  if(!$user = authorizeRequest($app, $db)) return;

  if($accept == 'application/json') {
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

$app->put('/songs/:id', function ($id) use ($app, $db, $accept) {
  if(!$user = authorizeRequest($app, $db)) return;

  if($accept == 'application/json') {
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

$app->delete('/songs/:id', function ($id) use ($app, $db, $accept) {
  if(!$user = authorizeRequest($app, $db)) return;

  if($accept == 'application/json') {
    $req = $app->request();
    $response = array();
    $rows = $db->execute('DELETE FROM `songs` WHERE `id` = :id AND `user` = :idUser', 
      array(
        'id'     => $id,
        'idUser' => $user->id
      )
    );

    $response['rowsAffected'] = $rows;
    echo json_encode($response);
  }  
  else {
    # TODO servir la página
    echo "servir la página";
  }
});