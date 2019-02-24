<?php
namespace Core;

class Database
{
    public static function connection($config, $debug)
    {
        try {
            return new \PDO($config['dsn'], $config['username'], $config['password']);
        } catch (\PDOException $e) {
            if ($debug) {
                echo "Error!: " . $e->getMessage() . "</div>";
            } else {
                echo 'Failed to connect to database';
            }

            die();
        }
    }
}
