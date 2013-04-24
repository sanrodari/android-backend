<?php

// Raiz de la web app. Se usa el named route para servir los archivos estáticos.
$app->get('/', function() use ($app, $db) {
  if(!$user = authorizeRequest($app, $db)) return;
  if($user) {
    $app->redirect('songs');
  }
  else {
    $app->redirect('newSession'); 
  }
})->name('root');