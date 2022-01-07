<?php

namespace Iwink\Tree\Node\Storage;

use Iwink\Tree\Node\NodeInterface;

/**
 * A storage for nodes.
 * @since 1.0.0
 */
final class Storage implements StorageInterface
{
    /**
     * The parent node.
     * @since 1.0.0
     * @var NodeInterface|null
     */
    private ?NodeInterface $parent = null;

    /**
     * The children indexed on their hashes.
     * @since 1.0.0
     * @var NodeInterface[]
     */
    private array $children = [];

    /**
     * The ordered child hashes.
     * @since 1.0.0
     * @var string[]
     */
    private array $order = [];

    /**
     * @inheritDoc
     * @since 1.0.0
     * @return \Generator
     */
    public function all(): iterable
    {
        foreach ($this->order as $hash) {
            yield $this->children[$hash];
        }
    }

    /**
     * @inheritDoc
     * @since 1.0.0
     */
    public function push(NodeInterface ...$children): StorageInterface
    {
        foreach ($children as $child) {
            if (!$this->has($child)) {
                $this->order[] = $this->hash($child);
            }

            $this->store($child);
        }

        return $this;
    }

    /**
     * @inheritDoc
     * @since 1.0.0
     */
    public function set(int $position, NodeInterface $child): StorageInterface
    {
        $this->store($child);
        $hash = $this->hash($child);

        // Sanitize the order & determine the lower & higher ordered nodes, merge them to recalculate order.
        $order = array_diff($this->order, [$hash]);
        $higher = array_slice($order, $position);
        $lower = array_diff($order, $higher);

        $this->order = array_merge($lower, [$hash], $higher);

        return $this;
    }

    /**
     * @inheritDoc
     * @since 1.0.0
     */
    public function get(int $position): ?NodeInterface
    {
        if (!isset($this->order[$position])) {
            return null;
        }

        return $this->children[$this->order[$position]];
    }

    /**
     * @inheritDoc
     * @since 1.0.0
     */
    public function delete(int $position): StorageInterface
    {
        $child = $this->get($position);

        return $this->remove($child);
    }

    /**
     * @inheritDoc
     * @since 1.0.0
     */
    public function remove(NodeInterface ...$children): StorageInterface
    {
        $hashes = array_map(\Closure::fromCallable([$this, 'hash']), $children);

        // Recalculate order
        $this->order = array_values(array_diff($this->order, $hashes));
        $this->children = array_intersect_key($this->children, array_flip($this->order));

        return $this;
    }

    /**
     * @inheritDoc
     * @since 1.0.0
     */
    public function setParent(?NodeInterface $parent): StorageInterface
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @inheritDoc
     * @since 1.0.0
     */
    public function getParent(): ?NodeInterface
    {
        return $this->parent;
    }

    /**
     * Creates a unique hash for a child.
     * @since 1.0.0
     * @param NodeInterface $child The child.
     * @return string The hash.
     */
    private function hash(NodeInterface $child): string
    {
        return spl_object_hash($child);
    }

    /**
     * Checks if the child exists in the storage.
     * @since 1.0.0
     * @param NodeInterface $child The child.
     * @return bool Whether the child already exists.
     */
    private function has(NodeInterface $child): bool
    {
        return isset($this->children[$this->hash($child)]);
    }

    /**
     * Stores a child.
     * @since 1.0.0
     * @param NodeInterface $child The child.
     */
    private function store(NodeInterface $child): void
    {
        $this->children[$this->hash($child)] = $child;
    }

    /**
     * Clones the storage.
     * @since 1.0.0
     */
    public function __clone()
    {
        foreach ($this->all() as $child) {
            $this->remove($child);
            $this->push(clone $child);
        }
    }

    /**
     * @inheritDoc
     * @since 1.0.0
     */
    public function count(): int
    {
        return iterator_count($this->all());
    }
}
