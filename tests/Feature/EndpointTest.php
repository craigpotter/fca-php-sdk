<?php

declare(strict_types=1);

use CraigPotter\Fca\Fca;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

test('any endpoint can be requested', function () {
    $mockClient = new MockClient([
        '*' => MockResponse::make(['Status' => 'FSR-API-02-03-00'], 200),

    ]);

    $fca = new Fca($_ENV['AUTH_EMAIL'], $_ENV['AUTH_KEY']);
    $fca->withMockClient($mockClient);
    /** @var \Saloon\Http\Paginators\PagedPaginator $response */
    $response = $fca->getEndpoint('/Firm/439536');

    foreach ($response as $page) {
        expect($page->status())->toBe(200);
    }
});
