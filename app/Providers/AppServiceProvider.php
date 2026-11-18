<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // OAuth endpoints are only needed if the MCP server uses Passport
        if( class_exists( \Laravel\Passport\Passport::class ) && config( 'app.shop_mcp_auth' ) !== 'passport' ) {
            \Laravel\Passport\Passport::ignoreRoutes();
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Illuminate\Support\Facades\Gate::define('admin', function($user, $class, $roles) {
            if( isset( $user->superuser ) && $user->superuser ) {
                return true;
            }
            return app( '\Aimeos\Shop\Base\Support' )->checkUserGroup( $user, $roles );
        });

        // APP_URL instead of the request host because the host can be forged to steal the token
        \Illuminate\Auth\Notifications\ResetPassword::createUrlUsing( function( $notifiable, $token ) {
            return rtrim( config( 'app.url' ), '/' ) . route( 'password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false );
        });

        // OAuth login and consent for MCP clients
        if( config( 'app.shop_mcp_auth' ) === 'passport' ) {
            \Laravel\Passport\Passport::authorizationView( 'auth.oauth-authorize' );
            \Illuminate\Auth\AuthenticationException::redirectUsing( fn() => route( 'login' ) );
        }
    }
}
