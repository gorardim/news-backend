<?php declare(strict_types=1);

namespace Nuwave\Lighthouse\Schema\Types\Scalars;

use Illuminate\Support\Carbon;

/**
 * Only works with Carbon 2.
 */
class DateTimeUtc extends DateScalar
{
    protected function format(Carbon $carbon): string
    {
        return $carbon->toJSON();
    }

    protected function parse(mixed $value): Carbon
    {
        // @phpstan-ignore-next-line We know the format to be good, so this can never return `false`
        return Carbon::createFromIsoFormat('YYYY-MM-DDTHH:mm:ss.SSSSSSZ', $value);
    }
}
