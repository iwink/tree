<?php

namespace Iwink\Tree\Tests\Node\Storage;

use Iwink\Tree\Tests\TestCase;
use Iwink\Tree\Node\NodeInterface;
use Iwink\Tree\Node\Storage\Storage;

/**
 * Unit tests for {@see Storage}.
 * @since 1.0.0
 */
class StorageTest extends TestCase
{
    /**
     * The class under test.
     * @since 1.0.0
     * @var Storage
     */
    private $storage;

    /**
     * Children used for the test.
     * @since 1.0.0
     * @var NodeInterface[]
     */
    private $children = [];

    /**
     * @inheritDoc
     * @since 1.0.0
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->storage = new Storage();
        $this->setupChildren();
    }

    /**
     * Test case for {@see Storage::all()}.
     * @since 1.0.0
     */
    public function testAll(): void
    {
        $this->assertEquals($this->children, iterator_to_array($this->storage->all()));
    }

    /**
     * Test case for {@see Storage::push()}.
     * @since 1.0.0
     */
    public function testPush(): void
    {
        $this->assertSame($this->storage, $this->storage->push(...$this->children));
        $this->storage->push($this->createMock(NodeInterface::class));
        // Nodes only get added once.
        $this->assertCount(4, $this->storage->all());
    }

    /**
     * Data provider for {@see self::testSet()}.
     * @since 1.0.0
     * @return mixed[] The data.
     */
    public function dataProviderForTestSet(): array
    {
        $children = [];
        for ($i = 0; $i < 3; $i++) {
            $children[] = $this->createMock(NodeInterface::class);
        }

        $node = $this->createMock(NodeInterface::class);

        return [
            'new node' => [
                $children,
                1,
                $node,
                [
                    $children[0],
                    $node,
                    $children[1],
                    $children[2],
                ],
            ],
            'existing node' => [
                $children,
                1,
                $children[2],
                [
                    $children[0],
                    $children[2],
                    $children[1],
                ],
            ],
            'negative new node' => [
                $children,
                -3,
                $node,
                [
                    $node,
                    $children[0],
                    $children[1],
                    $children[2],
                ],
            ],
            'negative existing node' => [
                $children,
                -2,
                $children[1],
                [
                    $children[1],
                    $children[0],
                    $children[2],
                ],
            ],
        ];
    }

    /**
     * Test case for {@see Storage::set()}.
     * @since 1.0.0
     * @param NodeInterface[] $original Original children.
     * @param int $position The position to set the node.
     * @param NodeInterface $node The node to set.
     * @param NodeInterface[] $expected The expacted current state.
     * @dataProvider dataProviderForTestSet The data provider.
     */
    public function testSet(array $original, int $position, NodeInterface $node, array $expected): void
    {
        $storage = new Storage();
        $storage->push(...$original);

        $this->assertSame($storage, $storage->set($position, $node));
        $this->assertSame($expected, iterator_to_array($storage->all()));
    }

    /**
     * Test case for {@see Storage::get()}.
     * @since 1.0.0
     */
    public function testGet(): void
    {
        $this->assertSame($this->children[0], $this->storage->get(0));
        $this->assertSame($this->children[1], $this->storage->get(1));
        $this->assertSame($this->children[2], $this->storage->get(2));
        $this->assertNUll($this->storage->get(3));
    }

    /**
     * Test case for {@see Storage::delete()}.
     * @since 1.0.0
     */
    public function testDelete(): void
    {
        $this->assertSame($this->storage, $this->storage->delete(1));
        $this->assertCount(2, $this->storage->all());
        $this->assertSame([$this->children[0], $this->children[2]], iterator_to_array($this->storage->all()));
    }

    /**
     * Test case for {@see Storage::remove()}.
     * @since 1.0.0
     */
    public function testRemove(): void
    {
        $this->assertSame($this->storage, $this->storage->remove($this->children[1], $this->children[0]));
        $this->assertCount(1, $this->storage->all());
        $this->assertSame([$this->children[2]], iterator_to_array($this->storage->all()));
    }

    /**
     * Test case for {@see Storage::setParent()} and {@see Storage::getParent()}.
     * @since 1.0.0
     */
    public function testParent(): void
    {
        $node = $this->createMock(NodeInterface::class);
        $this->assertSame($this->storage, $this->storage->setParent($node));
        $this->assertSame($node, $this->storage->getParent());
        $this->assertSame($this->storage, $this->storage->setParent(null));
        $this->assertNull($this->storage->getParent());
    }

    /**
     * Test case for {@see Storage::__clone()}.
     * @since 1.0.0
     */
    public function testClone(): void
    {
        $clone = clone $this->storage;
        $this->assertCount(3, $clone->all());
        $this->assertNotSame(iterator_to_array($clone->all()), iterator_to_array($this->storage->all()));
    }

    /**
     * Helper method to set up a few nodes as children.
     * @since 1.0.0
     */
    private function setupChildren(): void
    {
        for ($i = 0; $i < 3; $i++) {
            $this->children[] = $this->createMock(NodeInterface::class);
        }

        $this->storage->push(...$this->children);
    }
}
