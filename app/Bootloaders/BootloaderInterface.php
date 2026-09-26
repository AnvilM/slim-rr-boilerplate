<?php

declare(strict_types=1);

namespace Application\Bootloaders;

/**
 * Interface for application bootloaders.
 * Each bootloader receives a context, may consume existing values, and must return a new context
 * enriched with additional values (e.g., services, configurations).
 *
 * @template TInput of array<string, mixed> The expected input data shape (keys that must exist)
 * @template TOutput of array<string, mixed> The resulting data shape after this bootloader runs
 */
interface BootloaderInterface
{
    /**
     * Executes the bootloader logic.
     * Consumes values from the input context and produces new ones in the output context.
     *
     * @param Context<TInput> $context The incoming context with required dependencies
     *
     * @return Context<TOutput> A new context enriched with new values provided by this bootloader
     */
    public static function boot(Context $context): Context;
}
