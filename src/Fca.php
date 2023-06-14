<?php

declare(strict_types=1);

namespace CraigPotter\Fca;

use Saloon\Http\Connector;
use Saloon\Contracts\Request;
use Saloon\Contracts\Response;
use Saloon\Contracts\Paginator;
use Saloon\Contracts\HasPagination;
use CraigPotter\Fca\Requests\GetEndpoint;
use Saloon\Http\Paginators\PagedPaginator;
use CraigPotter\Fca\Resources\FirmResource;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;

class Fca extends Connector implements HasPagination
{
    use AlwaysThrowOnErrors;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(public readonly string $authEmail, public readonly string $authKey)
    {
    }

    /**
     * Define default headers
     *
     * @return string[]
     */
    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'X-Auth-Email' => $this->authEmail,
            'X-Auth-Key' => $this->authKey,
        ];
    }

    /**
     * Resolve the base URL of the service.
     *
     * @return string
     */
    public function resolveBaseUrl(): string
    {
        return 'https://register.fca.org.uk/services/V0.1';
    }

    /**
     * Determine if the request has failed
     *
     * @param Response $response
     * @return bool|null
     */
    public function hasRequestFailed(Response $response): ?bool
    {
        return ! str_ends_with($response->json('Status', 'FAILED'), '-00');
    }

    /**
     * Get the firm resource
     *
     * @param int $frn
     * @return FirmResource
     */
    public function firm(int $frn): FirmResource
    {
        return new FirmResource($this, $frn);
    }

    public function getEndpoint(string $endpoint): Paginator
    {
        return $this->paginate(new GetEndpoint($endpoint));
    }

    public function paginate(Request $request, ...$additionalArguments): Paginator
    {
        /** @var PagedPaginator $paginator */
        $paginator = new PagedPaginator($this, $request, 20, ...$additionalArguments);

        $paginator->setTotalKeyName('ResultInfo.total_count');
        $paginator->setPageKeyName('pgnp');
        $paginator->setLimit(20);
        $paginator->setNextPageKeyName('ResultInfo.Next');
        $paginator->setCurrentPage(1);

        return $paginator;
    }
}
