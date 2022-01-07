<?php

namespace Iwink\Tree\Node\Storage;

use Iwink\Tree\Node\NodeInterface;

/**
 * A storage for nodes.
 * @since 1.0.0
 */
interface StorageInterface extends \Countable
{
    /**
     * Returns all children.
     * @since 1.0.0
     * @return NodeInterface[] The children.
     */
    public function all(): iterable;

    /**
     * Pushes 1 or more children to the storage.
     * @since 1.0.0
     * @param NodeInterface ...$children The children.
     * @return $this The storage.
     */
    public function push(NodeInterface ...$children): self;

    /**
     * Stores a child at a position. If position is negative, it's calculated from the end. Higher ordered children
     * (including the one at position) are moved to the right.
     * @since 1.0.0
     * @param int $position The position.
     * @param NodeInterface $child The child.
     * @return $this The storage.
     */
    public function set(int $position, NodeInterface $child): self;

    /**
     * Returns the child at the position. If position is negative, it's calculated from the end.
     * @since 1.0.0
     * @param int $position The position.
     * @return NodeInterface|null The child.
     */
    public function get(int $position): ?NodeInterface;

    /**
     * Deletes a child at a position. If position is negative, it's calculated from the end. Higher ordered children
     * are moved to the left.
     * @since 1.0.0
     * @param int $position The position.
     * @return $this The storage.
     */
    public function delete(int $position): self;

    /**
     * Removes 1 or more children.
     * @since 1.0.0
     * @param NodeInterface ...$children The children.
     * @return $this The storage.
     */
    public function remove(NodeInterface ...$children): self;

    /**
     * Sets the parent.
     * @since 1.0.0
     * @param NodeInterface|null $parent The parent.
     * @return $this The node.
     */
    public function setParent(?NodeInterface $parent): self;

    /**
     * Returns the parent.
     * @since 1.0.0
     * @return NodeInterface|null The parent.
     */
    public function getParent(): ?NodeInterface;
}
