<?php

declare(strict_types=1);

namespace CraigPotter\Fca\DataObjects;

use Saloon\Http\Response;

class Firm
{
    public function __construct(
        public readonly int $frn,
        public readonly string $organisationName,
        public readonly string $businessType,
        public readonly string $status,
        public readonly string $statusEffectiveDate,
        public readonly string $companiesHouseNumber,
    ) {
        //
    }

    public static function fromResponse(Response $response): self
    {
        $data = $response->json('Data')[0];

        return new static(
            (int) $data['FRN'],
            $data['Organisation Name'],
            $data['Business Type'],
            $data['Status'],
            $data['Status Effective Date'],
            $data['Companies House Number'] ?? null,
        );
    }
}
