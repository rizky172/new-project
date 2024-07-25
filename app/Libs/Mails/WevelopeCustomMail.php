<?php
namespace App\Libs\Mails;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Libs\Mails\WevelopeMail;

use App\Libs\Repository\AbstractRepository;

use App\Config;

// Set SMTP programatically
abstract class WevelopeCustomMail extends WevelopeMail
{
    use Queueable, SerializesModels;

    public function __construct()
    {
        $this->validate();
    }

    // Validate mail configuration
    private function validate()
    {
        $config = Config::all();
        $mailConfig = [];
        foreach ($config as $x) {
            if ($x->key == 'smtp_host') {
                $mailConfig['host'] = $x->value;
            } else if ($x->key == 'smtp_port'){
                $mailConfig['port'] = $x->value;
            } else if ($x->key == 'smtp_username'){
                $mailConfig['username'] = $x->value;
            } else if ($x->key == 'smtp_password'){
                $mailConfig['password'] = $x->value;
            } else if ($x->key == 'email_from'){
                $mailConfig['email'] = $x->value;
            } else if ($x->key == 'email_name'){
                $mailConfig['name'] = $x->value;
            }
        }

        $fields = $mailConfig;

        $rules = [
            'host' => 'required',
            'port' => 'required',
            'email' => 'required',
            'name' => 'required',
            'username' => 'required',
            'password' => 'required',
        ];

        // var_dump($fields, $rules); die;

        $validator = Validator::make($fields, $rules);
        AbstractRepository::validOrThrow($validator);
    }

    public function getData(){}

    public function getView(){}

    public function getEmail(){}

    public function getSubject(){}
    //for attachment
    public function getAttachment(){}
    //for optional update model
    abstract public function updateModel();

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view($this->getView())
            ->with($this->getData())
            ->subject($this->getSubject())
            ->attach($this->getAttachment());
    }

    public function sendMail()
    {
        Mail::to($this->getEmail())->send($this);
    }
}
