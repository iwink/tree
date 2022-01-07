<?php

namespace Iwink\Tree\Traverser;

use Iwink\Tree\Node\NodeInterface;

/**
 * A tree traverser {@link https://en.wikipedia.org/wiki/Tree_traversal}.
 * @since 1.0.0
 */
interface TraverserInterface
{
    /**
     * Traverses all descendent nodes.
     * @since 1.0.0
     * @param NodeInterface $node The node to start from.
     * @return NodeInterface[] The nodes.
     */
    public function traverse(NodeInterface $node): iterable;
}
