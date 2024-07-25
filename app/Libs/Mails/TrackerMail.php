<?php
namespace App\Libs\Mails;

/*
 * Send an email to dest and to tracker mail
 */
abstract class TrackerMail extends AbstractMail
{
    public function getTrackerEmail()
    {
        return 'tracker@mail.com';
    }

    public function sendMail()
    {
        parent::sendMail();

        $trackerMail = clone $this;
        $trackerMail->sendMail();
    }
}
