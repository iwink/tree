<?php

namespace Iwink\Tree\Node;

use Iwink\Tree\Node\Storage\Storage;
use Iwink\Tree\Node\Storage\StorageInterface;
use Iwink\Tree\Visitor\VisitorInterface;

/**
 * A node in a tree.
 * @since $ver$
 */
final class Node implements NodeInterface {
	/**
	 * Stores information regarding about the structure.
	 * @since $ver$
	 * @var StorageInterface
	 */
	protected StorageInterface $storage;

	/**
	 * The value of this node.
	 * @since $ver$
	 * @var mixed
	 */
	private $value;

	/**
	 * Creates a new node.
	 * @since $ver$
	 * @param mixed $value Optional value of the node.
	 * @param null|NodeInterface $parent Optional parent of the node.
	 */
	public function __construct($value = null, ?NodeInterface $parent = null) {
		$this->storage = new Storage();

		$this->setValue($value);
		$this->setParent($parent);
	}

	/**
	 * @inheritDoc
	 * @since $ver$
	 */
	public function setValue($value): self {
		$this->value = $value;

		return $this;
	}

	/**
	 * @inheritDoc
	 * @since $ver$
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @inheritDoc
	 * @since $ver$
	 * @return \Generator
	 */
	public function getChildren(): iterable {
		yield from $this->storage->all();
	}

	/**
	 * @inheritDoc
	 * @since $ver$
	 */
	public function addChild(NodeInterface ...$nodes): self {
		$children = array_map(function (NodeInterface $node): NodeInterface {
			return $node->setParent($this);
		}, array_filter($nodes, function (NodeInterface $node): bool {
			return $this !== $node;
		}));

		$this->storage->push(...$children);

		return $this;
	}

	/**
	 * @inheritDoc
	 * @since $ver$
	 */
	public function setChild(int $position, NodeInterface $node): self {
		$this->storage->set($position, $node->setParent($this));

		return $this;
	}

	/**
	 * @inheritDoc
	 * @since $ver$
	 */
	public function removeChild(NodeInterface ...$nodes): self {
		$current = iterator_to_array($this->storage->all());
		$children = array_map(static function (NodeInterface $node): NodeInterface {
			return $node->setParent(null);
		}, array_uintersect($nodes, $current, static function (NodeInterface $a, NodeInterface $b): int {
			return strcmp(spl_object_hash($a), spl_object_hash($b));
		}));

		$this->storage->remove(...$children);

		return $this;
	}

	/**
	 * @inheritDoc
	 * @since $ver$
	 */
	public function deleteChild(int $position): self {
		$child = $this->storage->get($position);

		return $this->removeChild(...array_filter([$child]));
	}

	/**
	 * @inheritDoc
	 * @since $ver$
	 */
	public function setParent(?NodeInterface $parent): self {
		// Check & keep reference to current parent to prevent endless loops
		if ($parent !== $this->storage->getParent()) {
			$old_parent = $this->storage->getParent();

			$this->storage->setParent($parent);
			if ($old_parent instanceof NodeInterface) {
				$old_parent->removeChild($this);
			}

			if ($parent instanceof NodeInterface) {
				$parent->addChild($this);
			}
		}

		return $this;
	}

	/**
	 * @inheritDoc
	 * @since $ver$
	 */
	public function getParent(): ?NodeInterface {
		return $this->storage->getParent();
	}

	/**
	 * @inheritDoc
	 * @since $ver$
	 * @return \Generator
	 */
	public function getAncestors(): iterable {
		$node = $this;
		while ($node = $node->getParent()) {
			yield $node;
		}
	}

	/**
	 * @inheritDoc
	 * @since $ver$
	 * @return \Generator
	 */
	public function getSiblings(): iterable {
		$parent = $this->getParent();
		if (null === $parent) {
			return [];
		}

		foreach ($parent->getChildren() as $child) {
			if ($child === $this) {
				continue;
			}

			yield $child;
		}
	}

	/**
	 * @inheritDoc
	 * @since $ver$
	 */
	public function isRoot(): bool {
		return null === $this->getParent();
	}

	/**
	 * @inheritDoc
	 * @since $ver$
	 */
	public function isLeaf(): bool {
		return 0 === $this->storage->count();
	}

	/**
	 * @inheritDoc
	 * @since $ver$
	 */
	public function getDepth(): int {
		return iterator_count($this->getAncestors());
	}

	/**
	 * @inheritDoc
	 * @since $ver$
	 */
	public function getHeight(): int {
		$height = $this->getDepth();
		foreach ($this->getChildren() as $child) {
			$height = max($height, $child->getHeight());
		}

		return $height;
	}

	/**
	 * @inheritDoc
	 * @since $ver$
	 */
	public function accept(VisitorInterface $visitor): void {
		$visitor->visit($this);
	}

	/**
	 * @inheritDoc
	 * @since $ver$
	 */
	public function count(): int {
		return $this->storage->count();
	}

	/**
	 * Clones the node.
	 * @since $ver$
	 */
	public function __clone() {
		$this->storage = clone $this->storage;
		foreach ($this->storage->all() as $child) {
			$child->setParent($this);
		}
	}
}
