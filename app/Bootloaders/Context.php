<?php

declare(strict_types=1);

namespace Application\Bootloaders;

use OutOfBoundsException;

/**
 * A generic immutable context object that holds typed key-value data.
 * Uses variadic template to track exact keys and their types throughout the application bootstrapping process.
 *
 * @template T of array<string, mixed> The shape of the data stored in the context (associative array with string keys)
 */
final readonly class Context
{
    /**
     * Internal storage for context data.
     *
     * @var T
     */
    private array $data;

    /**
     * Creates a new context instance.
     *
     * @param T $data Initial data to populate the context. Defaults to empty array.
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Retrieves a value from the context by key.
     * Throws if the key does not exist — this ensures type safety via PHPStan.
     *
     * @template TKey as key-of<T>
     *
     * @param TKey $key The key to retrieve
     *
     * @throws OutOfBoundsException If the key is not present in the context
     *
     * @return T[TKey] The value associated with the key
     */
    public function get(string $key): mixed
    {
        if (!array_key_exists($key, $this->data)) {
            throw new OutOfBoundsException("Key '$key' not found in context");
        }

        return $this->data[$key];
    }

    /**
     * Returns a new context that carries over every value already present in this
     * context, with the given values merged on top.
     *
     * This is the mechanism that makes the context "accumulate" across the bootloader
     * chain: a bootloader that only needs yesterday's value still hands it down to
     * whoever runs next, instead of a later bootloader having to know, by convention,
     * which of the previous keys it must repeat by hand.
     *
     * @template TExtra of array<string, mixed>
     *
     * @param TExtra $extra Additional (or overriding) data to merge into the context
     *
     * @return Context<T&TExtra> A new context containing both the old and the new data
     */
    public function with(array $extra): self
    {
        return new self([...$this->data, ...$extra]);
    }
}
