<?php

require_once '../app/lib/database.php';
$db = Database::getInstance();

$app->get('/songs', function () use ($app, $db) {
  $all = $db->all('SELECT * FROM songs');
  echo json_encode($all);
});

$app->get('/songs/:id', function ($id) use ($app, $db) {
  $one = $db->one('SELECT * FROM songs WHERE id = :id',
    array('id' => $id)
  );

  if($one){
    echo json_encode($one);
  }
  else {
    echo json_encode(array('error' => 'No se encontró registro'));
  }
});

$app->post('/songs', function () use ($app, $db) {

  $req = $app->request();
  $response = array();
  if($req->post('name') && $req->post('url')) {
    $id = $db->execute('INSERT INTO songs (`name`, `url`) VALUES(:name, :url)', 
      array(
        'name' => $req->post('name'),
        'url' => $req->post('url')
      )
    );

    $response['id'] = $id;
    echo json_encode($response);
  }
  else {
    $response['error'] = 'Faltan parámetros';
    echo json_encode($response);
  }

});