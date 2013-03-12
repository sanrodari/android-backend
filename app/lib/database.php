<?php
// Abstract class providing singleton behaviour
// will be used by handlers and database classes
abstract class Singleton {
    protected static $instances = array();
    protected function __construct(){}
    private function __clone(){}
    private function __wakeup(){}

    public static function getInstance() {
        $class = get_called_class();
        if(!isset(self::$instances[$class])) {
            self::$instances[$class] = new $class();
        }
        return self::$instances[$class];
    }
}

// Simple PDO database wrapper
// it is not needed by this example and only
// used for prettier request handlers code examples
class Database extends Singleton {
    protected static $dbh;
    protected function __construct() {
        self::$dbh = new PDO('mysql:host=' . $_ENV['DB1_HOST'] . ';port=' . $_ENV['DB1_PORT'] . ';dbname=' . $_ENV['DB1_NAME'],
          $_ENV['DB1_USER'], $_ENV['DB1_PASS']);
        self::$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    public function all($sql, $params = array()) {
        $stmt = self::$dbh->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function one($sql, $params = array()) {
        $stmt = self::$dbh->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    public function execute($sql, $params = array()) {
        $stmt = self::$dbh->prepare($sql);
        $stmt->execute($params);
        if(preg_match('/insert/i', $sql)) return self::$dbh->lastInsertId();
        else return $stmt->rowCount();
    }
}