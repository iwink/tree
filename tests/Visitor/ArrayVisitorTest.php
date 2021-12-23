<?php

namespace Iwink\Tree\Tests\Visitor;

use Iwink\Tree\Node\NodeInterface;
use Iwink\Tree\Tree;
use Iwink\Tree\Visitor\ArrayVisitor;

/**
 * Unit tests for {@see ArrayVisitor}.
 * @since $ver$
 */
class ArrayVisitorTest extends VisitorTest {
	/**
	 * Test case for {@see ArrayVisitor}.
	 * @since $ver$
	 */
	public function testVisitor(): void {
		$tree = Tree::fromSerialized($this->getSerializedNodes());

		$visitor = new ConcreteArrayVisitor();

		$tree->visitPreOrder($visitor);
		$this->assertArrayVisitor(['1', '2'], $visitor);
		$this->assertSame(['1', '2'], $visitor->asArray());

		// Visiting the tree for a 2nd time should clear the first result
		$tree->visitPostOrder($visitor);
		$this->assertArrayVisitor(['2', '1'], $visitor);
		$this->assertSame(['2', '1'], $visitor->asArray());
	}
}

/**
 * Concrete implementation of an {@see ArrayVisitor}.
 * @since $ver$
 */
class ConcreteArrayVisitor extends ArrayVisitor {
	/**
	 * @inheritDoc
	 * @since $ver$
	 */
	protected function doVisit(NodeInterface $node) {
		return $node->getValue();
	}
}
