<?php

declare(strict_types=1);

namespace CraigPotter\Fca;

use Saloon\Http\Connector;
use Saloon\Contracts\Request;
use Saloon\Contracts\Response;
use Saloon\PaginationPlugin\Paginator;
use CraigPotter\Fca\Requests\GetEndpoint;
use CraigPotter\Fca\Resources\FirmResource;
use Saloon\PaginationPlugin\PagedPaginator;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Saloon\PaginationPlugin\Contracts\HasPagination;

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

    /**
     * Get an endpoint
     *
     * @param string $endpoint
     * @return Paginator
     */
    public function getEndpoint(string $endpoint): Paginator
    {
        return $this->paginate(new GetEndpoint($endpoint));
    }

    public function paginate(Request $request, ...$additionalArguments): PagedPaginator
    {
        return new class(connector: $this, request: $request) extends PagedPaginator {
            protected ?int $perPageLimit = 20;

            /**
             * Determine if the response is the last page
             *
             * @param Response $response
             * @return bool
             */
            protected function isLastPage(Response $response): bool
            {
                $count = (int) $response->json('ResultInfo.total_count', 0);
                $perPage = (int) $response->json('ResultInfo.per_page', $count);
                $currentPage = (int) $response->json('ResultInfo.page', 1);

                return $count <= ($perPage * $currentPage);

            }

            /**
             * Get the results from the page
             *
             * @return array
             */
            protected function getPageItems(Response $response, Request $request): array
            {
                $items = $request->createDtoFromResponse($response);
                return $items ?? $response->json('Data', []);
            }

            /**
             * Apply pagination to the request
             *
             * @param Request $request
             * @return Request
             */
            protected function applyPagination(Request $request): Request
            {
                $request->query()->add('pgnp', $this->page);

                if (isset($this->perPageLimit)) {
                    $request->query()->add('pageSize', $this->perPageLimit);
                }

                return $request;
            }
        };
    }
}
