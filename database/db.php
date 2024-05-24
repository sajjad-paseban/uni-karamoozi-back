<?php
include "../helper/function.php";

class DB{
    private $host;
    private $database;
    private $username;
    private $password;
    private $db;

    public function __construct()
    {
        $this->host = env('DB_HOST');
        $this->database = env('DB_DATABASE');
        $this->username = env('DB_USERNAME');
        $this->password = env('DB_PASSWORD');

        $this->db = new mysqli(
            $this->host,
            $this->username, 
            $this->password, 
            $this->database
        );
     
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
    }

    public function get($table, $columns = [], $condition = null){
        $c = count($columns) > 0 ? implode(',', $columns) : '*';

        return $this->db->query("SELECT $c FROM $table" . (($condition) ? " where $condition" : ""));
    }

    public function query($query){
        return $this->db->query($query);
    }

    public function command(){
        return $this->db;
    }

    public function __destruct(){
        $this->db->close();
    }
}