<?php

namespace components;

use PDO;

class DBConnection {

    public $dsn = '';
    public $username = '';
    public $password = '';
    public $charset = 'utf8';

    public function __construct($params) {
        foreach ($params as $key => $val) {
            if (isset($this->$key)) {
                $this->$key = $val;
            }
        }
    }

    public function tryConnect() {
        try {
            $conn = new PDO($this->dsn, $this->username, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully";
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

}
