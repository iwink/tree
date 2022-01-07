<?php

namespace Iwink\Tree\Tests\Traverser;

use Iwink\Tree\Visitor\ValueVisitor;

/**
 * Unit tests for {@see LevelOrderTraverser}.
 * @since $ver$
 */
class LevelOrderTraverserTest extends TraverserTest
{
    /**
     * Test case for {@see LevelOrderTraverser::traverse()}.
     * @since $ver$
     */
    public function testTraverse(): void
    {
        $tree = $this->getTree();
        $visitor = new ValueVisitor();

        $expected = ['F', 'B', 'H', 'A', 'D', 'G', 'I', 'J', 'C', 'E'];

        $tree->visitLevelOrder($visitor);
        $this->assertSame($expected, iterator_to_array($visitor->getResult()));
    }
}
