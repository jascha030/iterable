<?php

declare(strict_types=1);

namespace Jascha030\Iterable\Helper;

use Generator;
use Iterator;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;

/**
 * @covers \Jascha030\Iterable\Helper\Iter
 * @covers \Jascha030\Iterable\Rewindable\RewindableGenerator
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
            1 // Initial
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

    public function testMakeRewindable(): void
    {
        $map = function (callable $function, iterable $iterable): Iterator {
            foreach ($iterable as $k => $v) {
                yield $k => $function($v);
            }
        };

        $map    = Iter::makeRewindable($map);
        $mapped = $map(static fn(int $v) => $v + 1, [1, 2, 3]);

        [$first, $second] = [0, 0];

        foreach ($mapped as $value) {
            $first += $value;
        }

        // Loop would throw: "Exception : Cannot traverse an already closed generator",
        // without calling `Iter::makeRewindable()` on `$map` first.
        foreach ($mapped as $value) {
            $second += $value;
        }

        assertEquals($first, $second);
    }
}
