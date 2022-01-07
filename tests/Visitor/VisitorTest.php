<?php

namespace Iwink\Tree\Tests\Visitor;

use Iwink\Tree\Tests\TestCase;
use Iwink\Tree\Visitor\ArrayVisitor;

/**
 * Base test case for a visitor.
 * @since 1.0.0
 */
abstract class VisitorTest extends TestCase
{
    /**
     * Returns a single serialized node.
     * @since 1.0.0
     * @return array The serialized node.
     */
    protected function getSerializedNode(): array
    {
        return [['id' => 1, 'value' => '1', 'children' => []]];
    }

    /**
     * Returns a multiple serialized nodes.
     * @since 1.0.0
     * @return array The serialized nodes.
     */
    protected function getSerializedNodes(): array
    {
        $nodes = $this->getSerializedNode();
        $nodes[0]['children'][] = 2;

        return array_merge($nodes, [['id' => 2, 'value' => '2']]);
    }

    /**
     * Helper method to assert instances of {@see ArrayVisitor}.
     * @since 1.0.0
     * @param array $expected The expected result.
     * @param ArrayVisitor $visitor The visitor.
     */
    protected function assertArrayVisitor(array $expected, ArrayVisitor $visitor): void
    {
        $result = $visitor->getResult();
        $this->assertInstanceOf(\Traversable::class, $result);
        $this->assertSame($expected, iterator_to_array($result));
    }
}
