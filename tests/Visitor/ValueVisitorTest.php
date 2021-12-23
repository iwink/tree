<?php

namespace Iwink\Tree\Tests\Visitor;

use Iwink\Tree\Tree;
use Iwink\Tree\Visitor\ValueVisitor;

/**
 * Unit tests for {@see ValueVisitor}.
 * @since $ver$
 */
class ValueVisitorTest extends VisitorTest {
	/**
	 * Data provider for {@see ValueVisitor}.
	 * @since $ver$
	 * @return mixed[] The data set.
	 */
	public function visitorDataProvider(): array {
		return [
			'Default' => [null, 'visitPreOrder', ['1', '2']],
			'With converter' => ['intval', 'visitPostOrder', [2, 1]],
		];
	}

	/**
	 * Test case for {@see ValueVisitor}.
	 * @since $ver$
	 * @param callable|null $converter Optional converter.
	 * @param string $method The method to traverse the tree with.
	 * @param mixed[] $expected The expected result.
	 * @dataProvider visitorDataProvider The data provider.
	 */
	public function testVisitor(?callable $converter, string $method, array $expected): void {
		$tree = Tree::fromSerialized($this->getSerializedNodes());

		$visitor = new ValueVisitor($converter);
		$tree->{$method}($visitor);
		$this->assertArrayVisitor($expected, $visitor);
	}
}
