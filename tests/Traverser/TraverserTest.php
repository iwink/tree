<?php

namespace Iwink\Tree\Tests\Traverser;

use Iwink\Tree\Tests\TestCase;
use Iwink\Tree\Node\Node;
use Iwink\Tree\Node\NodeInterface;
use Iwink\Tree\Tree;
use Iwink\Tree\TreeInterface;

/**
 * Base test case.
 * @since 1.0.0
 */
abstract class TraverserTest extends TestCase
{
    /**
     * Returns nodes for a tree.
     * @since 1.0.0
     * @return NodeInterface[] The nodes indexed on value.
     */
    protected function getNodes(): array
    {
        $nodes = [];
        foreach (range('A', 'J') as $value) {
            $nodes[$value] = new Node($value);
        }

        return $nodes;
    }

    /**
     * Returns a tree to be traversed.
     *
     * The tree has the following structure:
     *
     *         F
     *     /      \
     *    B        H
     *  /  \     / | \
     * A    D   G  I  J
     *    /  \
     *  C     E
     *
     * @since 1.0.0
     * @return TreeInterface The tree.
     */
    protected function getTree(): TreeInterface
    {
        $nodes = $this->getNodes();

        $nodes['D']->addChild($nodes['C'], $nodes['E']);
        $nodes['H']->addChild($nodes['G'], $nodes['I'], $nodes['J']);
        $nodes['B']->addChild($nodes['A'], $nodes['D']);
        $nodes['F']->addChild($nodes['B'], $nodes['H']);

        return new Tree($nodes['F']);
    }
}
