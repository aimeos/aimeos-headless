<?php

namespace Tests\Feature\Auth;

use App\Http\Middleware\TrustHosts;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TrustedHostTest extends TestCase
{
    use RefreshDatabase;


    protected function setUp(): void
    {
        parent::setUp();

        config(['app.url' => 'https://shop.test']);

        // trusted hosts are not checked in unit tests by default
        $this->app->bind(TrustHosts::class, fn($app) => new class($app) extends TrustHosts {
            protected function shouldSpecifyTrustedHosts()
            {
                return true;
            }
        });
    }


    protected function tearDown(): void
    {
        Request::setTrustedHosts([]);
        Request::setTrustedProxies([], -1);

        parent::tearDown();
    }


    protected function resetUrl(User $user): string
    {
        $url = null;

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user, &$url) {
            $url = $notification->toMail($user)->actionUrl;
            return true;
        });

        return $url;
    }


    public function test_forwarded_host_from_proxy_is_not_used_in_reset_link(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->withServerVariables(['REMOTE_ADDR' => '10.0.0.1'])
            ->withHeaders(['X-Forwarded-Host' => 'evil.example', 'X-Forwarded-Proto' => 'https'])
            ->post('https://shop.test/forgot-password', ['email' => $user->email]);

        $this->assertStringStartsWith('https://shop.test/reset-password/', $this->resetUrl($user));
    }

    public function test_reset_link_uses_app_url_instead_of_request_host(): void
    {
        Notification::fake();

        config(['app.trusted_hosts' => 'www.example.org']);
        $user = User::factory()->create();

        $this->post('http://www.example.org/forgot-password', ['email' => $user->email]);

        $this->assertStringStartsWith('https://shop.test/reset-password/', $this->resetUrl($user));
    }

    public function test_untrusted_host_is_rejected(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('http://evil.example/forgot-password', ['email' => $user->email]);
        $this->post('http://10.0.0.1.evil.example/forgot-password', ['email' => $user->email]);

        Notification::assertNothingSent();
    }

    public function test_configured_trusted_host_is_accepted(): void
    {
        Notification::fake();

        config(['app.trusted_hosts' => 'www.example.org']);
        $user = User::factory()->create();

        $this->post('http://www.example.org/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class);
    }
}
