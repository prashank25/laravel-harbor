<?php

declare(strict_types=1);

/**
 * This file is part of Laravel Harbor.
 *
 * (c) Mehran Rasulian <mehran.rasulian@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace App\Services\Forge\Pipeline;

use App\Services\Forge\ForgeService;
use App\Traits\Outputifier;
use Closure;

class CloneExistingSslCertificate
{
    use Outputifier;

    public function __invoke(ForgeService $service, Closure $next)
    {
        if (! $service->setting->sslCloneCert || ! $service->siteNewlyMade) {
            return $next($service);
        }

        $this->information('Cloning existing SSL certificate.');
        $cloned = $service->forge->createCertificate(
            $service->server->id,
            $service->site->id,
            [
                'type' => 'clone',
                'certificate_id' => $service->setting->sslCloneCert,
            ],
        );

        $this->information('Activating cloned SSL certificate.');
        $service->forge->activateCertificate(
            $service->server->id,
            $service->site->id,
            $cloned->id
        );

        return $next($service);
    }
}
