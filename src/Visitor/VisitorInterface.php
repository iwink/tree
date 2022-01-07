<?php

namespace Iwink\Tree\Visitor;

use Iwink\Tree\Node\NodeInterface;

/**
 * A visitor for a node following the visitor pattern {@link https://en.wikipedia.org/wiki/Visitor_pattern}.
 * @since $ver$
 */
interface VisitorInterface
{
    /**
     * Visits a node and performs an operation.
     * @since $ver$
     * @param NodeInterface $node The node to visit.
     */
    public function visit(NodeInterface $node): void;

    /**
     * Executed before the visitor starts visiting nodes.
     * @since $ver$
     */
    public function beforeVisiting(): void;

    /**
     * Executed after the visitor visited all nodes.
     * @since $ver$
     */
    public function afterVisiting(): void;
}
