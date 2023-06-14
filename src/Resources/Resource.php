<?php

declare(strict_types=1);
namespace CraigPotter\Fca\Resources;

use Saloon\Contracts\Connector;

class Resource
{
    /**
     * Constructor
     *
     * @param Connector $connector
     */
    public function __construct(protected Connector $connector)
    {
        //
    }
}
