<?php

require  "Mysql.php";

class Emails extends Mysql
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getSentEmails()
    {
        $sql = 'SELECT * FROM sentemails';
        $result = $this->_conn->query($sql);
        $sentEmails = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $email = [];
                $email["email"] = $row["email"];
                $email["reply_to"] = $row["reply_to"];
                $email["date_sent"] = $row["date_sent"];
                $email["subject"] = $row["subject"];
                array_push($sentEmails, $email);
            }
        }
        return $sentEmails;
    } 

    public function saveToDb($email)
    {
        $sql = "INSERT INTO sentemails (email, reply_to, date_sent, subject) VALUES ( '". $email['email'] ."', '". $email['reply_to'] ."', '". $email['date_sent'] ."', '". $email['subject'] ."')";
        $result = $this->_conn->query($sql);
    }

    public function ereasAllEmails()
    {
        $sql = "TRUNCATE TABLE sentemails";
        $result = $this->_conn->query($sql);
    }
}

?>