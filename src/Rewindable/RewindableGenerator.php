<?php

declare(strict_types=1);

namespace Jascha030\Iterable\Rewindable;

use Closure;
use Generator;
use Iterator;
use PhpOption\LazyOption;
use PhpOption\Option;
use Throwable;

/**
 * @implements Iterator<mixed, mixed>
 */
final class RewindableGenerator implements Iterator
{
    private Closure $func;

    private Generator $generator;

    /**
     * @param callable|Closure $fn
     * @param mixed[]          $args
     */
    private function __construct(
        callable|Closure       $fn,
        private readonly array $args
    )
    {
        $this->func = $fn(...);
    }

    private function getGenerator(): Generator
    {
        return Option::fromValue($this->generator)
                     ->orElse(new LazyOption($this->factory(...)))
                     ->get();
    }

    public function rewind(): void
    {
        $function = $this->func;

        $this->generator = $function(...$this->args);
    }

    public function next(): void
    {
        $this->getGenerator()->next();
    }

    public function valid(): bool
    {
        return $this->getGenerator()->valid();
    }

    public function key(): mixed
    {
        return $this->getGenerator()->key();
    }

    public function current(): mixed
    {
        return $this->getGenerator()->current();
    }

    public function send(mixed $value = null): mixed
    {
        return $this->getGenerator()->send($value);
    }

    public function throw(Throwable $exception): mixed
    {
        return $this->getGenerator()->throw($exception);
    }

    private function factory(): Generator
    {
        $this->rewind();

        return $this->generator;
    }

    public static function from(callable|Closure $generator): Closure
    {
        return static fn (...$args) => new self($generator, $args);
    }
}
