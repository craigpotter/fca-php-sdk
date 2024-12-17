<?php

declare(strict_types=1);

namespace CraigPotter\Fca\Resources;

use Saloon\Http\Response;
use Saloon\Http\Connector;
use Saloon\PaginationPlugin\PagedPaginator;
use Saloon\Exceptions\Request\RequestException;
use CraigPotter\Fca\Requests\Firm\GetFirmDetails;
use CraigPotter\Fca\Requests\Firm\GetFirmAddresses;
use CraigPotter\Fca\Requests\Firm\GetFirmIndividuals;

class FirmResource extends Resource
{
    public function __construct(protected Connector $connector, public readonly int $frn)
    {
        parent::__construct($connector);
    }

    /**
     * Validate the FRN of the firm exists
     *
     * @return bool
     * @throws RequestException
     */
    public function exists(): bool
    {
        try {
            $response = $this->connector->send(new GetFirmDetails($this->frn));
        } catch (RequestException $e) {
            if (str_contains($e->getMessage(), 'Firm not found')) {
                return false;
            }

            throw $e;
        }

        return ! $response->failed();
    }

    /**
     * Get the details of a firm
     *
     * @return Response
     */
    public function get(): Response
    {
        return $this->connector->send(new GetFirmDetails($this->frn));
    }

    /**
     * Get the individuals of a firm
     *
     * @return PagedPaginator
     */
    public function individuals(): PagedPaginator
    {
        return $this->connector->paginate(new GetFirmIndividuals($this->frn));
    }

    /**
     * Get the addresses of a firm
     *
     * @return PagedPaginator
     */
    public function addresses(): PagedPaginator
    {
        return $this->connector->paginate(new GetFirmAddresses($this->frn));
    }
}
