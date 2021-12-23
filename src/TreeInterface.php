<?php

namespace Iwink\Tree;

use Iwink\Tree\Node\Node;
use Iwink\Tree\Node\NodeInterface;
use Iwink\Tree\Visitor\SerializerVisitor;
use Iwink\Tree\Visitor\VisitorInterface;

/**
 * A tree data structure.
 * @since $ver$
 */
interface TreeInterface {
	/**
	 * Returns the root node of the tree.
	 * @since $ver$
	 * @return NodeInterface The root node.
	 */
	public function getRoot(): NodeInterface;

	/**
	 * Returns a clone of the tree.
	 * @since $ver$
	 * @return TreeInterface The clone.
	 */
	public function clone(): TreeInterface;

	/**
	 * Does a pre-order traversal of the root node and applies 1 or more visitors to each node.
	 * @since $ver$
	 * @param VisitorInterface ...$visitors The visitors.
	 */
	public function visitPreOrder(VisitorInterface ...$visitors): void;

	/**
	 * Does an in-order traversal of the root node and applies 1 or more visitors to each node.
	 * @since $ver$
	 * @param VisitorInterface ...$visitors The visitors.
	 */
	public function visitInOrder(VisitorInterface ...$visitors): void;

	/**
	 * Does a post-order traversal of the root node and applies 1 or more visitors to each node.
	 * @since $ver$
	 * @param VisitorInterface ...$visitors The visitors.
	 */
	public function visitPostOrder(VisitorInterface ...$visitors): void;

	/**
	 * Does a level-order traversal of the root node and applies 1 or more visitors to each node.
	 * @since $ver$
	 * @param VisitorInterface ...$visitors The visitors.
	 */
	public function visitLevelOrder(VisitorInterface ...$visitors): void;

	/**
	 * Creates a {@see TreeInterface} from a serialized array. If the serialized array is empty, a tree with single,
	 * empty node is returned.
	 *
	 * The array should have the following structure:
	 * [
	 *      ['id' => <ID>, 'value' => <VALUE>, 'children' => [<CHILD_ID>, <CHILD_ID>, ...]],
	 *      ['id' => <ID>, 'value' => <VALUE>, 'children' => [<CHILD_ID>, <CHILD_ID>, ...]],
	 * ]
	 *
	 * The `children` index is optional but when it's present, it should consist out of the <ID>-s of other nodes in the
	 * serialized array.
	 *
	 * @since $ver$
	 * @param array $serialized The serialized array.
	 * @param callable|null $converter Optional converter for the value.
	 * @param string $node_class The class of the nodes to construct.
	 * @return TreeInterface The tree.
	 */
	public static function fromSerialized(
		array $serialized,
		?callable $converter = null,
		string $node_class = Node::class
	): TreeInterface;

	/**
	 * Serializes a tree by converting each node to {@see SerializerVisitor::doVisit()} in a pre-order traversal.
	 * @since $ver$
	 * @param callable|null $converter Optional converter for the value.
	 * @return array The serialized Tree.
	 */
	public function serialize(?callable $converter = null): array;
}
