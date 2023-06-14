<?php

declare(strict_types=1);

namespace CraigPotter\Fca\DataObjects;

class Address
{
    /**
     * Constructor
     *
     * @param string $website
     * @param string $phoneNumber
     * @param string $type
     * @param string|null $contactName
     * @param string $addressLine1
     * @param string $addressLine2
     * @param string $addressLine3
     * @param string $addressLine4
     * @param string $town
     * @param string $postcode
     * @param string $county
     * @param string $country
     */
    public function __construct(
        public readonly string $website,
        public readonly string $phoneNumber,
        public readonly string $type,
        public readonly ?string $contactName,
        public readonly string $addressLine1,
        public readonly string $addressLine2,
        public readonly string $addressLine3,
        public readonly string $addressLine4,
        public readonly string $town,
        public readonly string $postcode,
        public readonly string $county,
        public readonly string $country,
    ) {
        //
    }

    /**
     * Create a new instance from a response
     *
     * @param array $data
     * @return static
     */
    public static function fromArray(array $data): static
    {
        return new static(
            $data['Website Address'],
            $data['Phone Number'],
            $data['Address Type'],
            $data['Individual'] ?? '',
            $data['Address Line 1'],
            $data['Address Line 2'],
            $data['Address LIne 3'],
            $data['Address Line 4'],
            $data['Town'],
            $data['Postcode'],
            $data['County'],
            $data['Country'],
        );
    }
}
