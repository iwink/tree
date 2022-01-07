<?php

namespace Iwink\Tree\Traverser;

use Iwink\Tree\Node\NodeInterface;

/**
 * A level order tree traverser {@link https://en.wikipedia.org/wiki/Tree_traversal#Breadth-first_search_/_level_order}.
 * @since 1.0.0
 */
final class LevelOrderTraverser implements TraverserInterface
{
    /**
     * @inheritDoc
     * @since 1.0.0
     */
    public function traverse(NodeInterface $node): iterable
    {
        $queue = new \SplQueue();
        $queue->enqueue($node);

        yield $node;

        while (0 < $queue->count()) {
            foreach ($queue->dequeue()->getChildren() as $child) {
                $queue->enqueue($child);

                yield $child;
            }
        }
    }
}
