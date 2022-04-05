<?php

namespace Iwink\Tree\Visitor;

use Iwink\Tree\Node\NodeInterface;
use Iwink\Tree\TreeInterface;

/**
 * Base implementation of an array visitor.
 *
 * An array visitor converts a {@see TreeInterface} to an array by performing an operation on each node and pushing the
 * result to an array.
 *
 * @since 1.0.0
 */
abstract class ArrayVisitor extends Visitor
{
    /**
     * The results of the visited nodes.
     * @since 1.0.0
     * @var array
     */
    protected array $result = [];

    /**
     * @inheritDoc
     * @since 1.0.0
     */
    public function visit(NodeInterface $node): void
    {
        $this->result[] = $this->doVisit($node);
    }

    /**
     * Performs an operation on the node and returns the result.
     * @since 1.0.0
     * @param NodeInterface $node The node.
     * @return mixed The result of the operation.
     */
    abstract protected function doVisit(NodeInterface $node);

    /**
     * Returns the result of the visited nodes.
     * @since 1.0.0
     * @return \Generator The result.
     */
    public function getResult(): iterable
    {
        yield from $this->result;
    }

    /**
     * Returns the result as an array.
     * @since 1.0.0
     * @return array The result.
     */
    public function asArray(): array
    {
        return $this->result;
    }

    /**
     * @inheritDoc
     *
     * Empties out the results array.
     *
     * @since 1.0.0
     */
    public function beforeVisiting(): void
    {
        $this->result = [];
    }
}
