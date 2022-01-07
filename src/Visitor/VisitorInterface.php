<?php

namespace Iwink\Tree\Visitor;

use Iwink\Tree\Node\NodeInterface;

/**
 * A visitor for a node following the visitor pattern {@link https://en.wikipedia.org/wiki/Visitor_pattern}.
 * @since 1.0.0
 */
interface VisitorInterface
{
    /**
     * Visits a node and performs an operation.
     * @since 1.0.0
     * @param NodeInterface $node The node to visit.
     */
    public function visit(NodeInterface $node): void;

    /**
     * Executed before the visitor starts visiting nodes.
     * @since 1.0.0
     */
    public function beforeVisiting(): void;

    /**
     * Executed after the visitor visited all nodes.
     * @since 1.0.0
     */
    public function afterVisiting(): void;
}
