<?php

class db
{

    public static function getConnect()
    {
        /*
         * Database connection
         */
        if(file_exists(PATH.'/config/configDb.php'))
        {
            $configDb = require(PATH.'/config/configDb.php');
            $db = new PDO('mysql:host='.$configDb['host'].';db_name='.$configDb['db_name'].';', $configDb['user'], $configDb['password']);
            return $db;
        } else {
            throw new Exception("Config file for connecting database not found");
        }

    }

}

?>
