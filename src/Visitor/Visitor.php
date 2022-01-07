<?php

namespace Iwink\Tree\Visitor;

/**
 * Base implementation of a visitor.
 * @since $ver$
 */
abstract class Visitor implements VisitorInterface
{
    /**
     * @inheritDoc
     * @since $ver$
     * @codeCoverageIgnore
     */
    public function beforeVisiting(): void
    {
    }

    /**
     * @inheritDoc
     * @since $ver$
     * @codeCoverageIgnore
     */
    public function afterVisiting(): void
    {
    }
}
