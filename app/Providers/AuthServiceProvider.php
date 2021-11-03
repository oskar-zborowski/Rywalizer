<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        VerifyEmail::toMailUsing(function ($notificable, $url) {
            
            $spaUrl = 'https://spa.test?email_verify_url=' . $url;

            return (new MailMessage)
                ->subject('Potwierdzenie adresu e-mail')
                ->line('Kliknij w poniższy przycisk, aby zweryfikować adres e-mail')
                ->action('Potwierdź', $spaUrl)
                ->line('Dziękujemy za korzystanie z naszego serwisu!');;
        });
    }
}
