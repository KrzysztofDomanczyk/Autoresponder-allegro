<?php
    require_once "TimeChecker.php";
    require_once "EmailGetter.php";
    require_once "EmailSender.php";
    require_once "EmailChecker.php";


class Autoresponder
{
    public function init() 
    {   
        if ($this->checkConditions()) {
            $emailGetter = new EmailGetter();
            $checker = new EmailChecker();
            $emails = $emailGetter->getEmailsUnSeenMessage();
            $emails = $checker->check($emails);
            $areEmailsToSend = $emails != null;
            if ($areEmailsToSend) {
                $emailSender = new EmailSender();
                $emailSender->respond($emails);
            } 
        }
    }   

    protected function checkConditions()
    {         
        $emailsDb = new Emails();
        $timeChecker = new TimeChecker();

        if ($timeChecker->isTimeToClearSentEmails()) {
            $emailsDb->ereasAllEmails();
        } 
     
        if ($timeChecker->isTimeToRespond()) {
            $emailGetter = new EmailGetter();
            return $emailGetter->areUnSeenMessage();
        } 
        return false;
    }
 
}
?>