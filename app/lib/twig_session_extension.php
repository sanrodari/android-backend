<?php

class Session_Extension extends \Twig_Extension {
  public function getName() {
    return 'Session_Extension';
  }

  public function getFunctions() {
    return array(
      'sessionUser' => new \Twig_Function_Method($this, 'sessionUser'),
    );
  }

  public function sessionUser($params = array(), $appName = 'default') {
    $app = \Slim\Slim::getInstance($appName);
    $db = Database::getInstance();

    $user = authorizeRequest($app, $db, FALSE);
    return $user;
  }
}