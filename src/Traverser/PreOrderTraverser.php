<?php

namespace Iwink\Tree\Traverser;

use Iwink\Tree\Node\NodeInterface;

/**
 * A pre-order tree traverser {@link https://en.wikipedia.org/wiki/Tree_traversal#Pre-order_(NLR)}.
 * @since 1.0.0
 */
final class PreOrderTraverser extends DepthFirstTraverser
{
    /**
     * @inheritDoc
     * @since 1.0.0
     * @return \Generator
     */
    protected function doTraverse(NodeInterface $node): iterable
    {
        yield $this->index => $node;

        foreach ($node->getChildren() as $child) {
            $this->index++;
            yield from $this->doTraverse($child);
        }
    }
}
