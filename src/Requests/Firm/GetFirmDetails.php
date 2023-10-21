<?php

declare(strict_types=1);

namespace CraigPotter\Fca\Requests\Firm;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use CraigPotter\Fca\DataObjects\Firm;

class GetFirmDetails extends Request
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
        return '/Firm/' . $this->frn;
    }

    /**
     * Create a DTO from the response
     *
     * @param Response $response
     * @return mixed
     */
    public function createDtoFromResponse(Response $response): mixed
    {
        return Firm::fromResponse($response);
    }
}
