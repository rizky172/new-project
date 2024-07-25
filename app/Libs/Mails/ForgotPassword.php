<?php
namespace App\Libs\Mails;

use App\Libs\Mails\WevelopeMail;

// Forgot password confirmation to member
class ForgotPassword extends WevelopeMail
{
    private $resetToken;
    private $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function setResetToken($resetToken)
    {
        $this->resetToken = $resetToken;
    }

    public function getData() {
        return [
            'user' => $this->user,
            'hash' => $this->resetToken,
        ];
    }

    public function getEmail() {
        return $this->user->email;
    }

    public function getView() {
        return 'email.forgot-password';
    }

    public function getSubject()
    {
        return '[Reset] Permintaan reset Password';
    }

    public function build()
    {
        return $this->view($this->getView())
                    ->with($this->getData())
                    ->subject($this->getSubject());
    }

    public function updateModel(){}
}
