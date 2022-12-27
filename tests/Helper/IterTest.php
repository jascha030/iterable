<?php

declare(strict_types=1);

namespace Jascha030\Iterable\Helper;

use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;

/**
 * @covers \Jascha030\Iterable\Helper\Iter
 *
 * @internal
 */
final class IterTest extends TestCase
{
    /**
     * @throws \ReflectionException
     */
    public function testReduceWithKeyParameter(): void
    {
        $withKeys = Iter::reduce(
            static fn(int $current, int $value, int $key): int => $current + $value + $key,
            [1, 2, 3],
            1
        );

        assertEquals(
            1          // Initial
            + 1 + 2 + 3 // Values
            + 1 + 2,    // Keys
            $withKeys
        );
    }

    /**
     * @throws \ReflectionException
     */
    public function testReduceWithoutKeyParameter(): void
    {
        $withKeys = Iter::reduce(
            static fn(int $current, int $value): int => $current + $value,
            [1, 2, 3],
            1
        );

        assertEquals(
            1  // Initial
            + 1 + 2 + 3, // Values
            $withKeys
        );
    }
}
