<?php

namespace Iwink\Tree\Tests;

use Iwink\Tree\Node\Node;
use Iwink\Tree\Node\NodeInterface;
use Iwink\Tree\Tree;
use Iwink\Tree\Visitor\SerializerVisitor;
use Iwink\Tree\Visitor\ValueVisitor;
use Iwink\Tree\Visitor\VisitorInterface;

/**
 * Unit tests for {@see Tree}.
 * @since 1.0.0
 */
class TreeTest extends TestCase
{
    /**
     * A Root node.
     * @since 1.0.0
     * @var Node
     */
    private Node $root;

    /**
     * The class under test.
     * @since 1.0.0
     * @var Tree
     */
    private Tree $tree;

    /**
     * @inheritDoc
     * @since 1.0.0
     */
    protected function setUp(): void
    {
        $this->root = new Node('root_node');
        $this->tree = new Tree($this->root);
    }

    /**
     * Test case for {@see Tree::getRoot()}.
     * @since 1.0.0
     */
    public function testGetRoot(): void
    {
        $this->assertSame($this->root, $this->tree->getRoot());
    }

    /**
     * Test case for {@see Tree::clone()} and {@see Tree::__clone()}.
     * @since 1.0.0
     */
    public function testClone(): void
    {
        $new_tree = $this->tree->clone();
        $another_tree = clone $new_tree;
        $this->assertNotSame($new_tree->getRoot(), $this->root);
        $this->assertNotSame($another_tree->getRoot(), $new_tree->getRoot());
    }

    /**
     * Test case for {@see Tree::doVisit}.
     * @since 1.0.0
     */
    public function testDoVisit(): void
    {
        $visitor_1 = $this->createMock(VisitorInterface::class);
        $visitor_1
            ->expects($this->exactly(3))
            ->method('visit')
            ->with($this->root);
        $visitor_1
            ->expects($this->exactly(3))
            ->method('beforeVisiting');
        $visitor_1
            ->expects($this->exactly(3))
            ->method('afterVisiting');

        $visitor_2 = $this->createMock(VisitorInterface::class);
        $visitor_2
            ->expects($this->exactly(3))
            ->method('visit')
            ->with($this->root);
        $visitor_2
            ->expects($this->exactly(3))
            ->method('beforeVisiting');
        $visitor_2
            ->expects($this->exactly(3))
            ->method('afterVisiting');

        $this->assertNull($this->tree->visitPreOrder());
        $this->assertNull($this->tree->visitPreOrder($visitor_1, $visitor_2));
        $this->assertNull($this->tree->visitPostOrder($visitor_1, $visitor_2));
        $this->assertNull($this->tree->visitLevelOrder($visitor_1, $visitor_2));
    }

    /**
     * Test case for {@see Tree::fromSerialized()} with an invalid node class.
     * @since 1.0.0
     */
    public function testFromSerializedInvalidNode(): void
    {
        $this->expectExceptionObject(
            new \InvalidArgumentException(
                sprintf('Invalid node class "Test". The class should implement "%s".', NodeInterface::class)
            )
        );

        Tree::fromSerialized([], null, 'Test');
    }

    /**
     * Data provider for {@see Tree::fromSerialized()}.
     * @since 1.0.0
     * @return array The data set.
     */
    public function fromSerializedDataProvider(): array
    {
        return [
            'Empty' => [[], null, [null]],
            'Single node' => [
                [
                    ['id' => 1, 'value' => 'root'],
                ],
                null,
                ['root'],
            ],
            'Multiple nodes' => [
                [
                    ['id' => 1, 'value' => 'root', 'children' => [2]],
                    ['id' => 2, 'value' => 'child', 'children' => []],
                ],
                null,
                ['root', 'child'],
            ],
            'With converter' => [
                [
                    ['id' => 1, 'value' => '1'],
                ],
                'boolval',
                [true],
            ],
        ];
    }

    /**
     * Test case for {@see Tree::fromSerialized()}.
     * @since 1.0.0
     * @param array $serialized The serialized tree.
     * @param null|callable $converter Optional converter.
     * @param array $expected The expected values.
     * @dataProvider fromSerializedDataProvider The data provider.
     */
    public function testFromSerialized(array $serialized, ?callable $converter, array $expected): void
    {
        $tree = Tree::fromSerialized($serialized, $converter);

        $visitor = new ValueVisitor();
        $tree->visitPreOrder($visitor);
        $this->assertSame($expected, iterator_to_array($visitor->getResult()));
    }

    /**
     * Data provider for {@see Tree::serialize()}.
     * @since 1.0.0
     * @return array The data set.
     */
    public function serializeDataProvider(): array
    {
        return [
            'Single node' => [
                [
                    ['id' => 1, 'value' => 'root'],
                ],
                null,
            ],
            'Multiples nodes' => [
                [
                    ['id' => 1, 'value' => 'root', 'children' => [2]],
                    ['id' => 2, 'value' => 'child', 'children' => []],
                ],
                null,
            ],
            'With converter' => [
                [
                    ['id' => 1, 'value' => '1'],
                ],
                'boolval',
            ],
        ];
    }

    /**
     * Test case for {@see Tree::serialize()}.
     * @since 1.0.0
     * @param array $serialized The serialized tree.
     * @param null|callable $converter Optional converter.
     * @dataProvider serializeDataProvider The data provider.
     */
    public function testSerialize(array $serialized, ?callable $converter): void
    {
        $tree = Tree::fromSerialized($serialized);

        $visitor = new SerializerVisitor($converter);
        $tree->visitPreOrder($visitor);

        $this->assertSame(iterator_to_array($visitor->getResult()), $tree->serialize($converter));
    }
}
