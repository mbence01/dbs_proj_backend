<?php

require_once("EnvReader.php");

class DBConnector {
    private $conn = false;

    public function __construct($fname = null) {
        $env = null;

        if($fname == null)
            $env = new EnvReader();
        else
            $env = new EnvReader($fname);

        $this->conn = oci_connect($env->readValue("DB_USER"), $env->readValue("DB_PASS"), $env->readValue("DB_TNS"));
        return $this;
    }

    public function __destruct() {
        if($this->conn) {
            oci_close($this->conn);
            $this->conn = false;
        }
        return 0;
    }

    public function getConn() {
        return $this->conn;
    }

    public function closeConn() {
        return $this->__destruct();
    }
}

?>