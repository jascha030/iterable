<?php

declare(strict_types=1);

namespace Jascha030\Iterable\Rewindable;

use Iterator;
use Jascha030\Iterable\Helper\Iter;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use function ob_get_clean;
use function PHPUnit\Framework\assertEquals;

/**
 * @covers \Jascha030\Iterable\Helper\Iter
 * @covers \Jascha030\Iterable\Rewindable\RewindableGenerator
 *
 * @internal
 */
final class RewindableGeneratorTest extends TestCase
{
    public function testCurrent(): void
    {
        assertEquals(1, $this->getIterator()->current());
    }

    public function testFrom(): void
    {
        self::assertInstanceOf(Iterator::class, RewindableGenerator::from(function (): Iterator {
            yield 0 => 1;
            yield 1 => 2;
            yield 2 => 3;
        })());
    }

    public function testKey(): void
    {
        self::assertIsInt($this->getIterator()->key());
    }

    public function testNext(): void
    {
        $iterable = $this->getIterator();
        $curr     = $iterable->current();
        $iterable->next();

        self::assertNotEquals($curr, $iterable->current());
    }

    public function testRewind(): void
    {
        $test = $this->getIterator();

        $t1 = 0;
        $t2 = 0;

        foreach ($test as $i) {
            $t1 += $i;
        }

        foreach ($test as $i) {
            $t2 += $i;
        }

        assertEquals($t1, $t2);
    }

    public function testSend(): void
    {
        $iterator = Iter::makeRewindable(static function () {
            $data = yield;

            echo $data;
        });

        ob_start();
        $iterator()->send('ewa');

        assertEquals('ewa', ob_get_clean());
    }

    public function testThrow(): void
    {
        $this->expectException(RuntimeException::class);

        $iterator = $this->getIterator();
        $iterator->throw(new RuntimeException());
        $iterator->next();
    }

    public function testValid(): void
    {
        self::assertIsBool($this->getIterator()->valid());
    }

    private function getIterator(): RewindableGenerator
    {
        return Iter::makeRewindable(function (): Iterator {
            yield 0 => 1;
            yield 1 => 2;
            yield 2 => 3;
        })();
    }
}
