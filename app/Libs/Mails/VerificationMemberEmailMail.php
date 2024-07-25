<?php
namespace App\Libs\Mails;

use App\Libs\Mails\WevelopeMail;
use App\Libs\Repository\Config;

class VerificationMemberEmailMail extends WevelopeMail
{
    private $resetToken;
    private $person;

    public function __construct($person)
    {
        $this->person = $person;
    }

    public function setResetToken($resetToken)
    {
        $this->resetToken = $resetToken;
    }

    public function getData()
    {
        $messages = null;

        $data = Config::get('body_email');

        $baseUrl = env('APP_URL');

        $url = sprintf("%s/admin#/member/verification/%s", $baseUrl, $this->resetToken);
        $link = sprintf("<a href='%s'>%s</a>", $url, $url);

        $replace_array = [
            '{link}' => $link,
            "\n" => '<br>'
        ];

        $messages = str_replace(array_keys($replace_array),array_values($replace_array), $data);

        return [
            'messages' => $messages,
        ];
    }

    public function getEmail()
    {
        return $this->person->email;
    }

    public function getView()
    {
        return 'email.verified-email';
    }

    public function getSubject()
    {
        return '[Verified] Permintaan Verifikasi Email';
    }

    public function build()
    {
        return $this->view($this->getView())
                    ->with($this->getData())
                    ->subject($this->getSubject());
    }

    public function updateModel(){}
}
