<?php

class Mysql
{

    private $_servername;
    private $_username;
    private $_password;
    private $_dbname;

    protected $_conn;

    public function __construct()
    {
        $this->_servername = "localhost";
        $this->_username = "root";
        $this->_password = "";
        $this->_dbname = "autoresponder";

        $this->_conn = new mysqli($this->_servername, $this->_username, $this->_password, $this->_dbname);
        $this->_conn -> query ('SET NAMES utf8');
        $this->_conn -> query ('SET CHARACTER_SET utf8_unicode_ci');
        
        if ( $this->_conn->connect_error) {
            die("Connection failed: " .  $this->_conn->connect_error);
        }
    }

}


  
?>