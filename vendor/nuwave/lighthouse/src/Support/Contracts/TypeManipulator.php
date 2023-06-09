<?php declare(strict_types=1);

namespace Nuwave\Lighthouse\Support\Contracts;

use GraphQL\Language\AST\TypeDefinitionNode;
use Nuwave\Lighthouse\Schema\AST\DocumentAST;

interface TypeManipulator extends Directive
{
    /**
     * Apply manipulations from a type definition node.
     *
     * @param  \GraphQL\Language\AST\TypeDefinitionNode&\GraphQL\Language\AST\Node  $typeDefinition
     */
    public function manipulateTypeDefinition(DocumentAST &$documentAST, TypeDefinitionNode &$typeDefinition): void;
}
