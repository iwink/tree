<?php

namespace Iwink\Tree\Visitor;

/**
 * Base implementation of a visitor.
 * @since 1.0.0
 */
abstract class Visitor implements VisitorInterface
{
    /**
     * @inheritDoc
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function beforeVisiting(): void
    {
    }

    /**
     * @inheritDoc
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function afterVisiting(): void
    {
    }
}
