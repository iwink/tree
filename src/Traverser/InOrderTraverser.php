<?php

namespace Iwink\Tree\Traverser;

use Iwink\Tree\Node\NodeInterface;

/**
 * An in-order tree traverser {@link https://en.wikipedia.org/wiki/Tree_traversal#In-order,_LNR}.
 * @since 1.0.0
 */
final class InOrderTraverser extends DepthFirstTraverser
{
    /**
     * @inheritDoc
     * @since 1.0.0
     * @return \Generator
     */
    protected function doTraverse(NodeInterface $node): iterable
    {
        if ($node->isLeaf()) {
            yield $node;
        } else {
            $i = -1;
            $middle = (int) ($node->count() / 2);

            foreach ($node->getChildren() as $child) {
                if (++$i === $middle) {
                    yield $node;
                }

                yield from $this->doTraverse($child);
            }
        }
    }
}
