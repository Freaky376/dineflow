<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TenantCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $userEmail;
    public $randomPassword;
    public $domain;
    public $subscriptionPlan;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userName, $userEmail, $randomPassword, $domain, $subscriptionPlan)
    {
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->randomPassword = $randomPassword;
        $this->domain = $domain;
        $this->subscriptionPlan = $subscriptionPlan;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.tenant_created')
                    ->with([
                        'userName' => $this->userName,
                        'userEmail' => $this->userEmail,
                        'randomPassword' => $this->randomPassword,
                        'domain' => $this->domain,
                        'subscriptionPlan' => $this->subscriptionPlan,
                    ]);
    }
}
