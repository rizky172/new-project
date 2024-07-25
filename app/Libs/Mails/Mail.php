<?php
namespace App\Libs\Mails;

use Illuminate\Support\Facades\Mail as LaravelMail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

abstract class Mail extends Mailable
{
    use Queueable, SerializesModels;

    private $myTo = [];
    private $myCc = [];
    // Because using $bcc conflict iwht Mailer->bcc
    private $myBcc = [];

    abstract public function getData();

    abstract public function getView();

    abstract public function getSubject();
    //for attachment
    // abstract public function getAttachment();

    public function addTo($email)
    {
        $this->myTo[] = $email;
    }

    public function addCcc($email)
    {
        $this->myCc[] = $email;
    }

    public function addBcc($email)
    {
        $this->myBcc[] = $email;
    }

    public function getEmail()
    {
        return $this->myTo;
    }

    public function getCc()
    {
        return $this->myCc;
    }

    public function getBcc()
    {
        return $this->myBcc;
    }

    //for optional update model
    public function updateModel() {}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view($this->getView())
            ->with($this->getData())
            ->subject($this->getSubject());
            // ->attach($this->getAttachment());
    }

    public function sendMail()
    {
        $mailer = LaravelMail::to($this->getEmail());

        $cc = $this->getCc();
        if(!empty($cc))
            $mailer->cc($cc);

        $bcc = $this->getBcc();
        if(!empty($bcc))
            $mailer->bcc($$bcc);

        $mailer->send($this);

        $this->updateModel();
    }
}
