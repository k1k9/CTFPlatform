<?php
/**
 * AbstractModel
 * @author k1k9
 */
namespace app\models;
use mysqli;
use Exception;

class AbstractModel
{
    protected function connectMysql(){
        /**
         * Connects with MySQL
         * @return mysqli
         */
        try {
            $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            $mysqli->set_charset('utf8mb4');
            if ($mysqli->connect_errno) return false;
            return $mysqli;
        } catch (Exception $e){
            return false;
        }
        return false;
    }
}