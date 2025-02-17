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

namespace App\Actions;

use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class LineBreaksToArray
{
    use AsAction;

    public function handle(?string $content): ?array
    {
        return str($content)
            ->explode("\n")
            ->map(Str::squish(...))
            ->filter()
            ->values()
            ->all();
    }
}
