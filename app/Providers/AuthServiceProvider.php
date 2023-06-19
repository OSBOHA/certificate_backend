<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
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

        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->from('no-replay@osboha180.com', 'Osboha 180')
                ->subject('تأكيد البريد الالكتروني')
                ->line('لطفا، قم بالضغط على الزر أدناه، وذلك لتأكيد تسجيلك في موقع توثيق القراءة - أصبوحة 180.')
                ->action('تأكيد البريد الالكتروني', $url);
        });

        $this->registerPolicies();

        Passport::routes();
    }
}
