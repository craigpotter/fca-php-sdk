<?php

declare(strict_types=1);

namespace CraigPotter\Fca\Requests\Firm;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Contracts\Response;
use CraigPotter\Fca\DataObjects\Individual;

class GetFirmIndividuals extends Request
{
    /**
     * The HTTP method of the request.
     *
     * @var Method
     */
    protected Method $method = Method::GET;

    /**
     * Constructor
     *
     * @param int $frn
     */
    public function __construct(protected int $frn)
    {
        //
    }

    /**
     * Resolve the endpoint of the request.
     *
     * @return string
     */
    public function resolveEndpoint(): string
    {
        return '/Firm/' . $this->frn . '/Individuals';
    }

    /**
     * Create a DTO from the response
     *
     * @param Response $response
     * @return mixed
     */
    public function createDtoFromResponse(Response $response): mixed
    {
        $data = $response->json('Data');
        $dtos = [];
        foreach ($data as $individual) {
            $dtos[] = Individual::fromArray($individual);
        }

        return $dtos;
    }
}
