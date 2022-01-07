<?php

namespace Iwink\Tree\Tests\Visitor;

use Iwink\Tree\Tree;
use Iwink\Tree\Visitor\SerializerVisitor;

/**
 * Unit tests for {@see SerializerVisitor}.
 * @since $ver$
 */
class SerializerVisitorTest extends VisitorTest
{
    /**
     * Test case for {@see SerializerVisitor}.
     * @since $ver$
     */
    public function testVisitor(): void
    {
        $tree = Tree::fromSerialized($this->getSerializedNodes());

        $root = $tree->getRoot();
        $expected = [['id' => spl_object_hash($root), 'value' => '1', 'children' => []]];
        foreach ($root->getChildren() as $child) {
            $expected[0]['children'][] = spl_object_hash($child);
            $expected[] = ['id' => spl_object_hash($child), 'value' => '2', 'children' => []];
        }

        $visitor = new SerializerVisitor();
        $tree->visitPreOrder($visitor);

        $this->assertArrayVisitor($expected, $visitor);
    }
}
