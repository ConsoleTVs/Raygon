<?php

declare(strict_types=1);

namespace Erik\Raygon\Service\Contracts;

interface Binding
{
    /**
     * Set the current binding to be a singleton.
     *
     * @param bool $value
     * @return static
     */
    public function singleton(bool $value = true): static;

    /**
     * Returns true if the binding is a singleton.
     *
     * @return bool
     */
    public function isSingleton(): bool;

    /**
     * Sets the binding container instance.
     *
     * @param Container $container
     * @param bool $ignoreIfExists
     * @return static
     */
    public function container(?Container $container, bool $ignoreIfExists = false): static;

    /**
     * Returns the currently used container if any.
     *
     * @return Container|null
     */
    public function getContainer(): ?Container;

    /**
     * Resolves the current binding from
     * the container.
     *
     * @return mixed
     */
    public function resolve(): mixed;
}
