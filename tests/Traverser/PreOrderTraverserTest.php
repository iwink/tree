<?php

namespace Iwink\Tree\Tests\Traverser;

use Iwink\Tree\Visitor\ValueVisitor;

/**
 * Unit tests for {@see PreOrderTraverser}.
 * @since 1.0.0
 */
class PreOrderTraverserTest extends TraverserTest
{
    /**
     * Test case for {@see PreOrderTraverser::traverse()}.
     * @since 1.0.0
     */
    public function testTraverse(): void
    {
        $tree = $this->getTree();
        $visitor = new ValueVisitor();

        $expected = ['F', 'B', 'A', 'D', 'C', 'E', 'H', 'G', 'I', 'J'];

        $tree->visitPreOrder($visitor);
        $this->assertSame($expected, iterator_to_array($visitor->getResult()));
    }
}
