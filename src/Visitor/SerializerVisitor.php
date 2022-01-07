<?php

namespace Iwink\Tree\Visitor;

use Iwink\Tree\Node\NodeInterface;
use Iwink\Tree\TreeInterface;

/**
 * Visitor that creates a serializable array which preserves hierarchy.
 *
 * The result of this visitor can be used to reconstruct a tree using {@see TreeInterface::fromSerialized()}.
 *
 * @since 1.0.0
 */
class SerializerVisitor extends ValueVisitor
{
    /**
     * The output of each node is: ['id' => <ID>, 'value' => <VALUE>, 'children' => [<CHILD_ID>, <CHILD_ID>, ...]]
     * @inheritDoc
     * @since 1.0.0
     */
    protected function doVisit(NodeInterface $node)
    {
        return [
            'id' => spl_object_hash($node),
            'value' => parent::doVisit($node),
            'children' => array_map('spl_object_hash', iterator_to_array($node->getChildren())),
        ];
    }
}
