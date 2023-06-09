<?php declare(strict_types=1);

namespace Nuwave\Lighthouse\Schema\Directives;

use Nuwave\Lighthouse\Support\Contracts\ArgTransformerDirective;
use Nuwave\Lighthouse\Support\Contracts\Directive;

class TransformArgsDirective extends ArgTraversalDirective
{
    public static function definition(): string
    {
        return /** @lang GraphQL */ <<<'GRAPHQL'
"""
Transform the arguments of a field.
"""
directive @transformArgs on FIELD_DEFINITION
GRAPHQL;
    }

    protected function applyDirective(Directive $directive, mixed $value): mixed
    {
        if ($directive instanceof ArgTransformerDirective) {
            return $directive->transform($value);
        }

        return $value;
    }
}
