<?php
/**
 * Created Database
 */

class Database {

    private $host = "";
    private $dbname = "";
    private $username = "";
    private $password = "";
    private $port = "";
    private $connect = null;

    public function __construct(){
        $config = $GLOBALS['Config']['ENV'];
        $this->host = $config['host'];
        $this->dbname = $config['dbname'];
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->port = $config['port'];

        try{
            $this->connect = new PDO("pgsql:host = $this->host ;dbname= $this->dbname ; port= $this->port ", $this->username , $this->password );
            return $this->connect;
        }catch( PDOException $e ){
            $this->connect = null;
        }
    }

    public function query_sql( $query ){
        try{
            $result = [];
            $stmt = $this->connect->query( $query );
            if( $stmt ){
                while ( $row = $stmt->fetch(\PDO::FETCH_ASSOC) ){
                    $result[] = $row;
                }
            }
            $this->close_conn();
            return $result;
        }catch(Exception $e){
            return [];
        }
    }

    private function close_conn(){
        $this->connect = null;
    }
}

/**
 * sql create table
 * CREATE TABLE IF NOT EXISTS DOCTOR (
id INT AUTO_INCREMENT,
title VARCHAR(255) NOT NULL,
start_date DATE,
due_date DATE,
status TINYINT NOT NULL,
priority TINYINT NOT NULL,
description TEXT,
PRIMARY KEY (id)
)  ENGINE=INNODB;
 */