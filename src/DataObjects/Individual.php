<?php

declare(strict_types=1);

namespace CraigPotter\Fca\DataObjects;

class Individual
{
    /**
     * Constructor
     *
     * @param string $irn
     * @param string $name
     * @param string $status
     */
    public function __construct(
        public readonly string $irn,
        public readonly string $name,
        public readonly string $status,
    ) {
        //
    }

    /**
     * Create a new instance from a response
     *
     * @param Response $response
     * @return static
     */
    public static function fromArray(array $data): static
    {
        return new static(
            $data['IRN'],
            $data['Name'],
            $data['Status'],
        );
    }
}
