<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DomainUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $domain;
    public $tenant;
    public $domainUrl;  // Add this new property

    public function __construct($domain, $tenant)
{
    $this->domain = $domain;
    $this->tenant = $tenant;
    
    tenancy()->initialize($tenant);
    $this->adminUser = \App\Models\User::oldest()->first(); // Changed line
    tenancy()->end();
    
    $this->domainUrl = (app()->isLocal() ? 'http://' : 'https://') 
                     . $domain;
}

    public function build()
    {
        return $this->subject("New Domain Added: {$this->domain}")
                    ->view('emails.domain_updated');
    }
}