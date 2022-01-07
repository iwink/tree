<?php

namespace Iwink\Tree\Traverser;

use Iwink\Tree\Node\NodeInterface;

/**
 * A pre-order tree traverser {@link https://en.wikipedia.org/wiki/Tree_traversal#Pre-order_(NLR)}.
 * @since $ver$
 */
final class PreOrderTraverser extends DepthFirstTraverser
{
    /**
     * @inheritDoc
     * @since $ver$
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
