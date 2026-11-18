<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

class TrustHosts extends Middleware
{
    /**
     * Get the host patterns that should be trusted.
     *
     * @return array<int, string|null>
     */
    public function hosts()
    {
        $hosts = array_filter(array_map('trim', explode(',', (string) config('app.trusted_hosts', ''))));

        return array_merge(
            [$this->allSubdomainsOfApplicationUrl(), '^10\.\d+\.\d+\.\d+$'], // private IPs for Kubernetes probe checks
            array_map(fn($host) => '^' . preg_quote(strtolower($host)) . '$', $hosts)
        );
    }
}
