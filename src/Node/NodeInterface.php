<?php

namespace Iwink\Tree\Node;

use Iwink\Tree\Visitor\VisitorInterface;

/**
 * Represents a node in a tree.
 * @since $ver$
 */
interface NodeInterface extends \Countable {
	/**
	 * Sets the value of this node.
	 * @since $ver$
	 * @param mixed $value The value.
	 * @return self The node.
	 */
	public function setValue($value): self;

	/**
	 * Returns the value of this node.
	 * @since $ver$
	 * @return mixed The value.
	 */
	public function getValue();

	/**
	 * Returns the children of this node.
	 * @since $ver$
	 * @return NodeInterface[] The children.
	 */
	public function getChildren(): iterable;

	/**
	 * Adds 1 or more nodes as children of this node.
	 * @since $ver$
	 * @param self ...$nodes The nodes.
	 * @return self This node.
	 */
	public function addChild(self ...$nodes): self;

	/**
	 * Sets a child at a specific position. If position is negative, it's calculated from the end. Higher ordered
	 * children (including the one at position) are moved to the right.
	 * @since $ver$
	 * @param int $position The position.
	 * @param NodeInterface $node The node.
	 * @return self This node.
	 */
	public function setChild(int $position, self $node): self;

	/**
	 * Removes 1 or more child nodes from this node.
	 * @since $ver$
	 * @param NodeInterface ...$nodes The nodes.
	 * @return self This node.
	 */
	public function removeChild(NodeInterface ...$nodes): self;

	/**
	 * Deletes a child at a position. If position is negative, it's calculated from the end. Higher ordered children are
	 * moved to the left.
	 * @since $ver$
	 * @param int $position The position.
	 * @return self This node.
	 */
	public function deleteChild(int $position): self;

	/**
	 * Sets the parent of this node.
	 * @since $ver$
	 * @param NodeInterface|null $parent The parent.
	 * @return self This node.
	 */
	public function setParent(?NodeInterface $parent): self;

	/**
	 * Returns the parent of this node.
	 * @since $ver$
	 * @return NodeInterface|null The parent.
	 */
	public function getParent(): ?NodeInterface;

	/**
	 * Returns the ancestors of this node.
	 * @since $ver$
	 * @return NodeInterface[] The ancestors.
	 */
	public function getAncestors(): iterable;

	/**
	 * Returns the siblings of this node.
	 * @since $ver$
	 * @return NodeInterface[] The siblings.
	 */
	public function getSiblings(): iterable;

	/**
	 * Checks if this node is a root.
	 * @since $ver$
	 * @return bool Whether this node is a root.
	 */
	public function isRoot(): bool;

	/**
	 * Checks if this node is a leaf.
	 * @since $ver$
	 * @return bool Whether this node is a leaf.
	 */
	public function isLeaf(): bool;

	/**
	 * Returns the depth of this node from the root.
	 * @since $ver$
	 * @return int The depth.
	 */
	public function getDepth(): int;

	/**
	 * Returns the height of the tree for this node.
	 * @since $ver$
	 * @return int The height.
	 */
	public function getHeight(): int;

	/**
	 * Accepts a visitor following the visitor pattern {@link https://en.wikipedia.org/wiki/Visitor_pattern}.
	 * @since $ver$
	 * @param VisitorInterface $visitor The visitor.
	 */
	public function accept(VisitorInterface $visitor): void;

	/**
	 * Returns the amount of children of this node.
	 * @since $ver$
	 * @return int The child count.
	 */
	public function count(): int;
}
