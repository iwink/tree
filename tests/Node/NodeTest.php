<?php

namespace Iwink\Tree\Tests\Node;

use Iwink\Tree\Tests\TestCase;
use Iwink\Tree\Node\Node;
use Iwink\Tree\Node\NodeInterface;
use Iwink\Tree\Visitor\VisitorInterface;

/**
 * Unit tests for {@see Node}.
 * @since $ver$
 */
class NodeTest extends TestCase {
	/**
	 * Test case for {@see Node::__construct()}.
	 * @since $ver$
	 */
	public function testConstructor(): void {
		$parent = new Node('Parent');
		$node = new Node('value', $parent);

		$this->assertEquals('value', $node->getValue());
		$this->assertSame($parent, $node->getParent());
	}

	/**
	 * Test case for {@see Node::setValue()} and {@see Node::getValue()}.
	 * @since $ver$
	 */
	public function testSetValue(): void {
		$node = new Node('value');

		$this->assertSame($node, $node->setValue('new value'));
		$this->assertEquals('new value', $node->getValue());
	}

	/**
	 * Test case for {@see Node::getChildren()}.
	 * @since $ver$
	 */
	public function testGetChildren(): void {
		$parent = new Node('Parent');

		$child1 = new Node('Child-1', $parent);
		$child2 = new Node('Child-2', $parent);

		$this->assertSame([$child1, $child2], iterator_to_array($parent->getChildren()));
	}

	/**
	 * Test case for {@see Node::addChild}.
	 * @since $ver$
	 */
	public function testAddChild(): void {
		$parent = new Node('Parent');

		$child1 = new Node('Child-1');
		$child2 = new Node('Child-2');

		$this->assertSame($parent, $parent->addChild($child1));
		$this->assertSame($parent, $parent->addChild($child2));

		$children = iterator_to_array($parent->getChildren());
		$this->assertSame([$child1, $child2], $children);

		$this->assertSame($parent, $child1->getParent());
		$this->assertSame($parent, $child2->getParent());

		// Test adding self as child
		$this->assertSame($children, iterator_to_array($parent->addChild($parent)->getChildren()));
	}

	/**
	 * Test case for {@see Node::setChild()}.
	 * @since $ver$
	 */
	public function testSetChild(): void {
		$parent = new Node('Parent');

		$child1 = new Node('Child-1', $parent);
		$child2 = new Node('Child-2');

		$this->assertSame($parent, $parent->setChild(0, $child2));

		$this->assertSame([$child2, $child1], iterator_to_array($parent->getChildren()));
		$this->assertSame($parent, $child2->getParent());
	}

	/**
	 * Test case for {@see Node::removeChild()}.
	 * @since $ver$
	 */
	public function testRemoveChild(): void {
		$parent = new Node('Parent');

		$child1 = new Node('Child-1', $parent);
		$child2 = new Node('Child-2', $parent);
		$child3 = new Node('Child-3', $parent);

		$this->assertSame($parent, $parent->removeChild($child1, $child3));

		$this->assertNull($child1->getParent());
		$this->assertSame($parent, $child2->getParent());
		$this->assertNull($child3->getParent());

		// Make sure both directions are cut.
		$this->assertEquals([$child2], iterator_to_array($parent->getChildren()));

		// Test removal of nodes that aren't a child of the node
		$other_parent = new Node('Other parent');
		$child4 = new Node('Child-4', $other_parent);

		$this->assertSame($parent, $parent->removeChild($child4));
		$this->assertSame($other_parent, $child4->getParent());
	}

	/**
	 * Test case for {@see Node::deleteChild()}.
	 * @since $ver$
	 */
	public function testDeleteChild(): void {
		$parent = new Node('Parent');

		$child1 = new Node('Child-1', $parent);
		$child2 = new Node('Child-2', $parent);
		$child3 = new Node('Child-3', $parent);

		$this->assertSame($parent, $parent->deleteChild(1));

		$this->assertNull($child2->getParent());
		$this->assertSame([$child1, $child3], iterator_to_array($parent->getChildren()));
	}

	/**
	 * Test case for {@see Node::setParent()} where the node already had a parent.
	 * @since $ver$
	 */
	public function testSetParentWithOldParent(): void {
		$parent = new Node('Parent');
		$new_parent = new Node('New Parent');

		$child = new Node('Child-1', $parent);

		$this->assertSame($child, $child->setParent($new_parent));

		$this->assertSame($new_parent, $child->getParent());
		$this->assertEquals([], iterator_to_array($parent->getChildren()));
	}

	/**
	 * Test case for {@see Node::setParent()} where the parent is set to Null.
	 * @since $ver$
	 */
	public function testSetParentNull(): void {
		$parent = new Node('Parent');
		$child = new Node('Child-1', $parent);

		$this->assertSame($child, $child->setParent(null));
		// Make sure both directions are cut.
		$this->assertEquals([], iterator_to_array($parent->getChildren()));
		$this->assertNull($child->getParent());
	}

	/**
	 * Test case for {@see Node::getParent()}.
	 * @since $ver$
	 */
	public function testGetParent(): void {
		$parent = new Node('Parent');
		$child = new Node('Child-1', $parent);
		$child2 = new Node('Child-2');

		$this->assertSame($parent, $child->getParent());
		$this->assertNull($child2->getParent());
	}

	/**
	 * Test case for {@see Node::getAncestors()}.
	 * @since $ver$
	 */
	public function testGetAncestors(): void {
		$grandparent = new Node('Grandparent');
		$parent = new Node('Parent', $grandparent);
		$child = new Node('Child', $parent);

		$this->assertSame([$parent, $grandparent], iterator_to_array($child->getAncestors()));
	}

	/**
	 * Test case for {@see Node::getSiblings()}.
	 * @since $ver$
	 */
	public function testGetSiblings(): void {
		$parent = new Node('Parent');

		$child1 = new Node('Child-1', $parent);
		$child2 = new Node('Child-2', $parent);
		$child3 = new Node('Child-3', $parent);

		$node = new Node('Node');

		$this->assertEquals([$child1, $child3], iterator_to_array($child2->getSiblings()));
		$this->assertEquals([], iterator_to_array($node->getSiblings()));
	}

	/**
	 * Test case for {@see Node::isRoot()}.
	 * @since $ver$
	 */
	public function testIsRoot(): void {
		$parent = new Node('Parent');
		$child = new Node('Child', $parent);

		$this->assertTrue($parent->isRoot());
		$this->assertFalse($child->isRoot());
	}

	/**
	 * Test case for {@see Node::isLeaf()}.
	 * @since $ver$
	 */
	public function testIsLeaf(): void {
		$parent = new Node('Parent');
		$child = new Node('Child', $parent);

		$this->assertFalse($parent->isLeaf());
		$this->assertTrue($child->isLeaf());
	}

	/**
	 * Test case for {@see Node::getDepth()}.
	 * @since $ver$
	 */
	public function testGetDepth(): void {
		$ancestor = new Node('ancestor');
		$parent = new Node('parent', $ancestor);
		$child = new Node('child', $parent);

		$this->assertSame(1, $parent->getDepth());
		$this->assertSame(2, $child->getDepth());
	}

	/**
	 * Test case for {@see Node::getHeight()}.
	 * @since $ver$
	 */
	public function testGetHeight(): void {
		$ancestor = new Node('ancestor');
		$parent_1 = new Node('parent 1', $ancestor);
		$child_1 = new Node('child 1', $parent_1);

		$parent_2 = new Node('parent 2', $ancestor);
		$child_2 = new Node('child 2', $parent_2);
		$grandchild_2 = new Node('grandchild 2', $child_2);

		$this->assertSame(2, $parent_1->getHeight());
		$this->assertSame(2, $child_1->getHeight());
		$this->assertSame(3, $parent_2->getHeight());
		$this->assertSame(3, $grandchild_2->getHeight());
		$this->assertSame(3, $ancestor->getHeight());
	}

	/**
	 * Test case for {@see Node::accept()}.
	 * @since $ver$
	 */
	public function testAccept(): void {
		$node = new Node('Node');
		$visitor = $this->createMock(VisitorInterface::class);

		$visitor->expects($this->once())->method('visit')->with($node);
		$node->accept($visitor);
	}

	/**
	 * Test case for {@see Node::__clone()}.
	 * @since $ver$
	 */
	public function testClone(): void {
		$node = new Node('Node');
		$child = new Node('Child');

		$node->addChild($child);
		$clone = clone $node;

		// Test top level reference
		$this->assertSame($node->getParent(), $clone->getParent());

		// Test if the child node was cloned
		/** @var NodeInterface $clone_child */
		$clone_child = $clone->getChildren()->current();
		$this->assertNotSame($child, $clone_child);

		// Test if the child's parent is a reference to the cloned node
		$clone_parent = $clone_child->getParent();
		$this->assertInstanceOf(NodeInterface::class, $clone_parent);
		$this->assertNotSame($node, $clone_parent);
	}
}
