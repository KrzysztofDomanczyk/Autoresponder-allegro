<?php

// require_once "EmailGetter.php";
// require_once "EmailSender.php";
require_once "TimeChecker.php";

require_once "php-imap-client-master/ImapClient/ImapClientException.php";
require_once "php-imap-client-master/ImapClient/ImapConnect.php";
require_once "php-imap-client-master/ImapClient/ImapClient.php";
require_once "php-imap-client-master/ImapClient/IncomingMessage.php";
require_once "php-imap-client-master/ImapClient/TypeAttachments.php";
require_once "php-imap-client-master/ImapClient/SubtypeBody.php";
require_once "php-imap-client-master/ImapClient/TypeBody.php";
require_once "php-imap-client-master/ImapClient/Section.php";
require_once "php-imap-client-master/ImapClient/AdapterForOutgoingMessage.php";

use SSilence\ImapClient\ImapClientException;
use SSilence\ImapClient\ImapConnect;
use SSilence\ImapClient\ImapClient as Imap;

class EmailGetter
{
    private $_mailbox;
    private $_username;
    private $_password;
    private $_encryption;
    private $_imap;

    public function __construct()
    {
        $mailbox = 'xxxx';
        $username = 'xxxx';
        $password = 'xxxxx';
        $encryption = Imap::ENCRYPT_SSL; 
        
        try {
            $this->_imap = new Imap($mailbox, $username, $password, $encryption);
        } catch (ImapClientException $error){
            echo $error->getInfo();
        }
    }   

    public function areUnSeenMessage()
    {
        $ifAre = $this->_imap->countUnreadMessages();

        return $ifAre > 0 ? true : false ;
    }

    public function getEmailsUnSeenMessage()
    {
        try {
            $emails = $this->_imap->getUnreadMessages(false);
        } catch (ImapClientException $error){
            echo $error->getInfo();
        }

        $emailsInfos = [];

        foreach ($emails as $email) {
            $emailInfo = $this->getEmailInfo($email);   
            array_push($emailsInfos, $emailInfo);
        };

        return $emailsInfos;
    }

    private function getEmailInfo($email)
    {   
        $data = [
            "email" => $email->header->details->from[0]->mailbox .'@'. $email->header->details->from[0]->host,
            "reply_to" => $email->header->details->reply_to[0]->mailbox .'@'. $email->header->details->reply_to[0]->host,
            "date_sent" => $email->header->date,
            "subject" => $email->header->subject
        ];

        return $data;
    }

}
?>

