<?php

declare(strict_types=1);

namespace Jascha030\Iterable\Helper;

use Closure;
use Generator;
use ReflectionFunction;

final class Iter
{
    /**
     * @throws \ReflectionException
     */
    public static function reduce(callable|Closure $callback, iterable $iterable, mixed $startValue = null): mixed
    {
        $accumulator = $startValue;
        $paramCount  = (new ReflectionFunction($callback))->getNumberOfParameters();

        foreach ($iterable as $key => $value) {
            $accumulator = $callback(
                ...(static fn(): Generator => match ($paramCount) {
                    2 => yield from [$accumulator, $value],
                    3 => yield from [$accumulator, $value, $key]
                })()
            );
        }

        return $accumulator;
    }
}
