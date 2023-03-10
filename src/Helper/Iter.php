<?php

declare(strict_types=1);

namespace Jascha030\Iterable\Helper;

use Closure;
use Generator;
use Jascha030\Iterable\Rewindable\RewindableGenerator;
use ReflectionException;
use ReflectionFunction;

final class Iter
{
    /**
     * @param array|mixed[] $iterable
     *
     * @throws ReflectionException
     *
     * @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection
     */
    public static function reduce(Closure $callback, iterable $iterable, mixed $startValue = null): mixed
    {
        $accumulator        = $startValue;
        $numberOfParameters = (new ReflectionFunction($callback))->getNumberOfParameters();

        foreach ($iterable as $key => $value) {
            $accumulator = $callback(
                ...(static fn (): Generator => match ($numberOfParameters) {
                    2       => yield from [$accumulator, $value],
                    3       => yield from [$accumulator, $value, $key],
                    default => throw new \ArgumentCountError('$callback should have 2 or 3 parameters.')
                })()
            );
        }

        return $accumulator;
    }

    public static function makeRewindable(callable|Closure $function): Closure
    {
        return RewindableGenerator::from($function);
    }
}
