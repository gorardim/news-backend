<?php declare(strict_types=1);

namespace Nuwave\Lighthouse\Schema;

class RootType
{
    public const QUERY = 'Query';

    public const MUTATION = 'Mutation';

    public const SUBSCRIPTION = 'Subscription';

    public static function isRootType(string $typeName): bool
    {
        return in_array(
            $typeName,
            [
                static::QUERY,
                static::MUTATION,
                static::SUBSCRIPTION,
            ],
        );
    }
}
