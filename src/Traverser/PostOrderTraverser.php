<?php

namespace Iwink\Tree\Traverser;

use Iwink\Tree\Node\NodeInterface;

/**
 * A post-order tree traverser {@link https://en.wikipedia.org/wiki/Tree_traversal#Post-order_(LRN)}.
 * @since 1.0.0
 */
final class PostOrderTraverser extends DepthFirstTraverser
{
    /**
     * @inheritDoc
     * @since 1.0.0
     * @return \Generator
     */
    protected function doTraverse(NodeInterface $node): iterable
    {
        foreach ($node->getChildren() as $child) {
            yield from $this->doTraverse($child);
            $this->index++;
        }

        yield $this->index => $node;
    }
}
