<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ValidationActeMariageMailable extends Mailable
{
    use Queueable, SerializesModels;
    private $maire;
    private $code_declaration_mariage;
    private $code_otp;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($maire, $code_declaration_mariage, $code_otp)
    {
        $this->maire = $maire;
        $this->code_declaration_mariage = $code_declaration_mariage;
        $this->code_otp = $code_otp;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Validation acte de mariage')->from(env("MAIL_USERNAME"), env("SUBJECT"))->markdown("mail.validation_acte_mariage")->with([
            "maire"=> $this->maire,
            "code_declaration_mariage"=> $this->code_declaration_mariage,
            "code_otp"=>$this->code_otp
        ]);
    }
}
