<?php
namespace core;

class User{
  private static $_instance = null;
  static $table = 'user';
  static $user;

  public static function getInstance ()
  {
      if (self::$_instance === null) {
          self::$_instance = new self;
      }

      return self::$_instance;
  }

  public static function findById($connection, $id){
    $stmt = $connection->prepare("
      SELECT * FROM user WHERE id=:id AND status=1 LIMIT 1
    "); 
    //$stmt->bindParam(':userTable', self::$table, \PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
    $stmt->execute();
    static::$user = $stmt->fetchObject();
    return static::getInstance();
  }

  public static function findByUsername($connection, $username){
    $stmt = $connection->prepare("
      SELECT * FROM user WHERE username=:username AND status=1 LIMIT 1
    "); 
    //$stmt->bindParam(':userTable', self::$table, \PDO::PARAM_STR);
    $stmt->bindParam(':username', $username, \PDO::PARAM_STR, 25);
    $stmt->execute();
    static::$user = $stmt->fetchObject();
    return static::getInstance();
  }

  public function verifyPassword($password){
    $verify = false;
    if(static::$user){
      $verify = password_verify($password, static::$user->password);
    }
    return $verify;
  }

  public function check(){
    return !empty(static::$user);
  }

  public static function get(){
    if(!static::$user){
      if(!empty($_SESSION['user'])) static::$user = $_SESSION['user'];
    }
    return static::$user;
  }

  public function set($user=null){
    $_SESSION['user'] = $user;
    static::$user = $user;
  }

  public static function hashPassword($password){
    return password_hash($password, PASSWORD_DEFAULT);
  }
}