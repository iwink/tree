<?php

namespace Iwink\Tree;

use Iwink\Tree\Node\Node;
use Iwink\Tree\Node\NodeInterface;
use Iwink\Tree\Traverser\InOrderTraverser;
use Iwink\Tree\Traverser\LevelOrderTraverser;
use Iwink\Tree\Traverser\PostOrderTraverser;
use Iwink\Tree\Traverser\PreOrderTraverser;
use Iwink\Tree\Traverser\TraverserInterface;
use Iwink\Tree\Visitor\SerializerVisitor;
use Iwink\Tree\Visitor\VisitorInterface;

/**
 * A traversable tree.
 * @since $ver$
 */
final class Tree implements TreeInterface
{
    /**
     * The root node of the tree.
     * @since $ver$
     * @var NodeInterface
     */
    private NodeInterface $root;

    /**
     * Creates a new tree.
     * @since $ver$
     * @param NodeInterface $root The root node of the tree.
     */
    public function __construct(NodeInterface $root)
    {
        $this->root = $root;
    }

    /**
     * @inheritDoc
     * @since $ver$
     */
    public function getRoot(): NodeInterface
    {
        return $this->root;
    }

    /**
     * @inheritDoc
     * @since $ver$
     */
    public function clone(): TreeInterface
    {
        return clone $this;
    }

    /**
     * Clones the tree.
     * @since $ver$
     */
    public function __clone()
    {
        $this->root = clone $this->root;
    }

    /**
     * @inheritDoc
     * @since $ver$
     */
    public function visitPreOrder(VisitorInterface ...$visitors): void
    {
        $this->doVisit(new PreOrderTraverser(), ...$visitors);
    }

    /**
     * @inheritDoc
     * @since $ver$
     */
    public function visitInOrder(VisitorInterface ...$visitors): void
    {
        $this->doVisit(new InOrderTraverser(), ...$visitors);
    }

    /**
     * @inheritDoc
     * @since $ver$
     */
    public function visitPostOrder(VisitorInterface ...$visitors): void
    {
        $this->doVisit(new PostOrderTraverser(), ...$visitors);
    }

    /**
     * @inheritDoc
     * @since $ver$
     */
    public function visitLevelOrder(VisitorInterface ...$visitors): void
    {
        $this->doVisit(new LevelOrderTraverser(), ...$visitors);
    }

    /**
     * Helper method to visit all nodes.
     * @since $ver$
     * @param TraverserInterface $traverser The traverser.
     * @param VisitorInterface ...$visitors The visitors.
     */
    private function doVisit(TraverserInterface $traverser, VisitorInterface ...$visitors): void
    {
        if (empty($visitors)) {
            return;
        }

        foreach ($visitors as $visitor) {
            $visitor->beforeVisiting();
            foreach ($traverser->traverse($this->root) as $node) {
                $node->accept($visitor);
            }
            $visitor->afterVisiting();
        }
    }

    /**
     * @inheritDoc
     * @since $ver$
     */
    public static function fromSerialized(
        array $serialized,
        ?callable $converter = null,
        string $node_class = Node::class
    ): TreeInterface {
        if (!is_subclass_of($node_class, NodeInterface::class)) {
            throw new \InvalidArgumentException(
                sprintf('Invalid node class "%s". The class should implement "%s".', $node_class, NodeInterface::class)
            );
        }

        // Shortcut empty source
        if (empty($serialized)) {
            return new self(new $node_class(null));
        }

        $nodes = [];
        foreach ($serialized as $node) {
            $value = $node['value'];
            if (null !== $converter) {
                $value = $converter($value);
            }

            $nodes[$node['id']] = new $node_class($value);
        }

        foreach ($serialized as $node) {
            foreach ($node['children'] ?? [] as $child) {
                $nodes[$node['id']]->addChild($nodes[$child]);
            }
        }

        return new self(reset($nodes));
    }

    /**
     * @inheritDoc
     * @since $ver$
     */
    public function serialize(?callable $converter = null): array
    {
        $visitor = new SerializerVisitor($converter);
        $this->visitPreOrder($visitor);

        return $visitor->asArray();
    }
}
