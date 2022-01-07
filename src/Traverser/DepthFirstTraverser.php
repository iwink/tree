<?php

namespace Iwink\Tree\Traverser;

use Iwink\Tree\Node\NodeInterface;

/**
 * Base implementation for a depth first tree traversal {@link https://en.wikipedia.org/wiki/Tree_traversal#Depth-first_search_of_binary_tree}.
 * @since 1.0.0
 */
abstract class DepthFirstTraverser implements TraverserInterface
{
    /**
     * The current traversal index.
     * @since 1.0.0
     * @var int
     */
    protected int $index = 0;

    /**
     * @inheritDoc
     * @since 1.0.0
     */
    final public function traverse(NodeInterface $node): iterable
    {
        $this->index = 0;

        return $this->doTraverse($node);
    }

    /**
     * Traverses a node.
     * @since 1.0.0
     * @param NodeInterface $node The node to traverse.
     * @return NodeInterface[] The nodes.
     */
    abstract protected function doTraverse(NodeInterface $node): iterable;
}
