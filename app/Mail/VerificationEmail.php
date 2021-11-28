<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $url;

    /**
     * Create a new message instance.
     * 
     * @param string $url
     */
    public function __construct(string $url) {
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        $html = "Kliknij w poniższy link, aby zweryfikować adres e-mail:<br>
                $this->url";

        return $this->subject('Potwierdzenie adresu e-mail')
                    ->html($html);
    }
}