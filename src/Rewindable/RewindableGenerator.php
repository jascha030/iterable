<?php

declare(strict_types=1);

namespace Jascha030\Iterable\Rewindable;

use Closure;
use Generator;
use Iterator;

/**
 * @internal
 */
final class RewindableGenerator implements Iterator
{
    private Closure $func;

    private ?Generator $generator;

    private function __construct(callable|Closure $fn, private array $args)
    {
        $this->func      = $fn;
        $this->generator = null;
    }

    public function rewind(): void
    {
        $function = $this->func;

        $this->generator = $function(...$this->args);
    }

    public function next(): void
    {
        null !== $this->generator || $this->rewind();

        $this->generator->next();
    }

    public function valid(): bool
    {
        null !== $this->generator || $this->rewind();

        return $this->generator->valid();
    }

    public function key(): mixed
    {
        null !== $this->generator || $this->rewind();

        return $this->generator->key();
    }

    public function current(): mixed
    {
        null !== $this->generator || $this->rewind();

        return $this->generator->current();
    }

    public function send($value = null)
    {
        null !== $this->generator || $this->rewind();

        return $this->generator->send($value);
    }

    public function throw($exception)
    {
        null !== $this->generator || $this->rewind();

        return $this->generator->throw($exception);
    }

    public static function from(callable|Closure $generator): Closure
    {
        return static fn(...$args) => new self($generator, $args);
    }
}
