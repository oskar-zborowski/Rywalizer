<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountRestoration extends Mailable
{
    use Queueable, SerializesModels;

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
        $html = "Kliknij w poniższy link, aby przywrócić konto:<br>
                $this->url";

        return $this->subject('Przywrócenie konta')
                    ->html($html);
    }
}
