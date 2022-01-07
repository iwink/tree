<?php

namespace Iwink\Tree\Traverser;

use Iwink\Tree\Node\NodeInterface;

/**
 * A tree traverser {@link https://en.wikipedia.org/wiki/Tree_traversal}.
 * @since $ver$
 */
interface TraverserInterface
{
    /**
     * Traverses all descendent nodes.
     * @since $ver$
     * @param NodeInterface $node The node to start from.
     * @return NodeInterface[] The nodes.
     */
    public function traverse(NodeInterface $node): iterable;
}
