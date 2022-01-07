<?php

namespace Iwink\Tree\Traverser;

use Iwink\Tree\Node\NodeInterface;

/**
 * Base implementation for a depth first tree traversal {@link https://en.wikipedia.org/wiki/Tree_traversal#Depth-first_search_of_binary_tree}.
 * @since $ver$
 */
abstract class DepthFirstTraverser implements TraverserInterface
{
    /**
     * The current traversal index.
     * @since $ver$
     * @var int
     */
    protected int $index = 0;

    /**
     * @inheritDoc
     * @since $ver$
     */
    final public function traverse(NodeInterface $node): iterable
    {
        $this->index = 0;

        return $this->doTraverse($node);
    }

    /**
     * Traverses a node.
     * @since $ver$
     * @param NodeInterface $node The node to traverse.
     * @return NodeInterface[] The nodes.
     */
    abstract protected function doTraverse(NodeInterface $node): iterable;
}
