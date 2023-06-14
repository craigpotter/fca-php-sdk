<?php

declare(strict_types=1);

namespace CraigPotter\Fca\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetEndpoint extends Request
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
     * @param string $endpoint
     */
    public function __construct(protected string $endpoint)
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
        return $this->endpoint;
    }
}
