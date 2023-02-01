<?php

declare(strict_types=1);

namespace Jascha030\Iterable\Rewindable;

use Closure;
use Generator;
use Iterator;
use PhpOption\None;
use PhpOption\Option;
use PhpOption\Some;
use Throwable;

/**
 * @implements Iterator<mixed, mixed>
 */
final class RewindableGenerator implements Iterator
{
    private Closure $func;

    private ?Generator $generator;

    /**
     * @param mixed[] $args
     */
    private function __construct(
        callable|Closure $fn,
        private readonly array $args
    ) {
        $this->func = $fn(...);

        $this->generator = null;
    }

    public function rewind(): void
    {
        $function = $this->func;

        $this->generator = $function(...$this->args);
    }

    public function next(): void
    {
        $this->getInner()->next();
    }

    public function valid(): bool
    {
        return $this->getInner()->valid();
    }

    public function key(): mixed
    {
        return $this->getInner()->key();
    }

    public function current(): mixed
    {
        return $this->getInner()->current();
    }

    public function send(mixed $value = null): mixed
    {
        return $this->getInner()->send($value);
    }

    public function throw(Throwable $exception): mixed
    {
        return $this->getInner()->throw($exception);
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

    /**
     * @return Option<null|Generator>
     */
    private function getGenerator(): Option
    {
        if (null !== $this->generator) {
            return new Some($this->generator);
        }

        return None::create();
    }

    private function getInner(): Generator
    {
        return $this->getGenerator()->getOrCall($this->factory(...));
    }
}
