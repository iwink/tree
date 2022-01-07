<?php

namespace Iwink\Tree\Tests\Traverser;

use Iwink\Tree\Traverser\InOrderTraverser;
use Iwink\Tree\Visitor\ValueVisitor;

/**
 * Unit tests for {@see InOrderTraverser}
 * @since 1.0.0
 */
class InOrderTraverserTest extends TraverserTest
{
    /**
     * Test case for {@see InOrderTraverser::traverse()}.
     * @since 1.0.0
     */
    public function testTraverse(): void
    {
        $tree = $this->getTree();
        $visitor = new ValueVisitor();

        $expected = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];

        $tree->visitInOrder($visitor);
        $this->assertSame($expected, iterator_to_array($visitor->getResult()));
    }
}
