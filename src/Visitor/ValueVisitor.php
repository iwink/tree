<?php

namespace Iwink\Tree\Visitor;

use Iwink\Tree\Node\NodeInterface;

/**
 * A visitor that returns an array containing the values of each node.
 * @since $ver$
 */
class ValueVisitor extends ArrayVisitor
{
    /**
     * Converter for the node's value.
     * @since $ver$
     * @var callable|null
     */
    private $converter;

    /**
     * Creates a new visitor.
     * @since $ver$
     * @param callable|null $converter Optional converter for the node's value.
     */
    public function __construct(?callable $converter = null)
    {
        $this->converter = $converter;
    }

    /**
     * @inheritDoc
     * @since $ver$
     */
    protected function doVisit(NodeInterface $node)
    {
        $value = $node->getValue();
        if (null !== $this->converter) {
            $value = call_user_func($this->converter, $value);
        }

        return $value;
    }
}
