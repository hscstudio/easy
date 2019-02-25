<?php
namespace Core;

class User
{
    private static $_instance = null;
    static $table = 'user';
    static $user = null;

    public static function findByUsername($connection, $username)
    {
        $stmt = $connection->prepare("
          SELECT * FROM " . self::$table . " WHERE username=:username AND status=1 LIMIT 1
        ");
        $stmt->bindParam(':username', $username, \PDO::PARAM_STR, 25);
        $stmt->execute();
        static::$user = $stmt->fetchObject();
    }

    public static function verifyPassword($password)
    {
        $verify = false;
        if (!empty(static::$user)) {
            $verify = password_verify($password, static::$user->password_hash);
        }
        return $verify;
    }

    public static function set()
    {
        $_SESSION['user'] = static::$user;
    }

    public static function login($connection, $username, $password)
    {   
        $object = new \stdClass();
        $object->status = false;
        static::findByUsername($connection, $username);
        if(!empty(static::$user)){
            $verify = static::verifyPassword($password);
            if($verify){
                static::set();
                $object->status = true;
                $object->message = 'login success';
            }
            else{
                $object->message = 'password not match';
            }
        }
        else{
            $object->message = 'username not found';
        }
        return $object;
    }

    public static function get()
    {
        $user = null;
        if (!empty($_SESSION['user'])) {
            $user = $_SESSION['user'];
        }
        return $user;
    }

    public static function logout()
    {
        unset($_SESSION['user']);
    }
}
