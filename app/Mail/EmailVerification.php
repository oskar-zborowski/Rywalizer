<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    private $url;
    private $afterRegistration;

    /**
     * Create a new message instance.
     * 
     * @param string|null $url przygotowany link do zweryfikowania maila (w przypadku nieustawionej zmiennej $url, wiadomość zostanie wysłana bez linku weryfikującego - np. po rejestracji z wykorzystaniem zewnętrznego serwisu uwierzytelniającego)
     * @param bool $afterRegistration flaga z informacją czy wywołanie metody jest pochodną procesu rejestracji nowego użytkownika
     */
    public function __construct(?string $url = null, bool $afterRegistration = true) {
        $this->url = $url;
        $this->afterRegistration = $afterRegistration;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {

        $html = '';

        if ($this->afterRegistration) {
            $html .= 'Dziękujemy za założenie konta w naszym serwisie';
        }

        if ($this->url) {
            $html .= "<br><br>Kliknij w poniższy link, aby zweryfikować adres e-mail:<br>
            $this->url";
        }

        return $this->subject('Potwierdzenie adresu e-mail')
                    ->html($html);
    }
}
