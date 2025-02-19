<?php

declare(strict_types=1);

namespace App\Services\Forge\Pipeline;

use App\Services\Forge\ForgeService;
use App\Traits\Outputifier;
use Closure;
use Illuminate\Support\Str;

class RemoveTaskScheduler
{
    use Outputifier;

    public function __invoke(ForgeService $service, Closure $next)
    {
        foreach ($service->forge->jobs($service->setting->server) as $job) {
            if ($service->setting->siteIsolationRequired && $job->user === $service->site->username) {
                $this->information("Removing scheduled command for user: {$job->command}");

                $job->delete();

                continue;
            }

            if (Str::contains($job->command, $service->siteDirectory())) {
                $this->information("Removing scheduled command for directory: {$job->command}");

                $job->delete();
            }
        }

        return $next($service);
    }
}
