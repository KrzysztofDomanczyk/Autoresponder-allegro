<?php

class EmailChecker
{
    private $_host;

    public function __construct()
    {
        $this->host = 'xxxx';
    }

    public function check($emails)
    {
        $emailsDb = new Emails();
        $timeChecker = new TimeChecker();

        $sentEmails = $emailsDb->getSentEmails();

        $filteredEmails           = $this->filterEmails($emails, $sentEmails, $timeChecker);
        $emailsAskAboutTheProduct = $this->filterEmailsAskAboutTheProduct($sentEmails, $emails);
        $emails                   = array_merge($filteredEmails, $emailsAskAboutTheProduct);
        return $emails;
    }

    private function filterEmailsAskAboutTheProduct($sentEmails, $emails)
    {
        $emails = array_merge($emails);//reindex
        $askAboutProductEmails = [];
    
        foreach ($emails as $email) {
            $isAppropriate = substr($email['subject'], 0, 19) == "Pytanie o przedmiot" && $email['email'] == 'powiadomienia@allegro.pl';
            if ($isAppropriate) {
                    array_push($askAboutProductEmails, $email);
            }
        }

        $askAboutProductEmails = $this->noRespondedEmails($sentEmails, $askAboutProductEmails);
        $askAboutProductEmails = $this->deleteEmailsFromPreviousCycle($askAboutProductEmails);

        return $askAboutProductEmails;
    }

    private function filterEmails($emails, $sentEmails) 
    {

        $emails = $this->deleteEmailsFromSpecificHost($emails);
        $emails = $this->noRespondedEmails($sentEmails, $emails);
        $emails = $this->deleteEmailsFromPreviousCycle($emails);

        return $emails;
    }

    private function noRespondedEmails($sentEmails, $emails)
    {
        $emails = array_merge($emails);//reindex
        $index = 0;
        foreach ($emails as $email) {
            foreach ($sentEmails as $sentEmail) {
                if ($email['reply_to'] === $sentEmail['reply_to']) {
                    unset($emails[$index]);
                }
            }
            $index++;
        }

        return $emails;
    }

    private function deleteEmailsFromSpecificHost($emails)
    {
        $emailsFromSpecificHost = [];

        foreach ($emails as $email) {
            $host = explode('@', $email['email']);
            if ($host[1] == 'allegromail.pl') {
                array_push($emailsFromSpecificHost, $email);
            }
        }

        return $emailsFromSpecificHost;
    }

    private function deleteEmailsFromPreviousCycle($emails)
    {
    
            $timeChecker = new TimeChecker();
            $emails = array_merge($emails);//reindex
            $index = 0;

        if ($timeChecker->_date->isWeekday()) {
            foreach ($emails as $email) {
                if ($timeChecker->isPreviousCycle($email['date_sent'])) {
                    unset($emails[$index]);
                }
                $index++;
            }
        }

        return $emails;
    }
}
?>


