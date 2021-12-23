<?php

namespace Iwink\Tree\Tests\Traverser;

use Iwink\Tree\Visitor\ValueVisitor;

/**
 * Unit tests for {@see PostOrderTraverser}.
 * @since $ver$
 */
class PostOrderTraverserTest extends TraverserTest {
	/**
	 * Test case for {@see PostOrderTraverser::traverse()}.
	 * @since $ver$
	 */
	public function testTraverse(): void {
		$tree = $this->getTree();
		$visitor = new ValueVisitor();

		$expected = ['A', 'C', 'E', 'D', 'B', 'G', 'I', 'J', 'H', 'F'];

		$tree->visitPostOrder($visitor);
		$this->assertSame($expected, iterator_to_array($visitor->getResult()));
	}
}
