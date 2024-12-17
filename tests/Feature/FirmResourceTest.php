<?php

declare(strict_types=1);

use CraigPotter\Fca\Fca;
use Saloon\Http\PendingRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use CraigPotter\Fca\DataObjects\Address;
use CraigPotter\Fca\DataObjects\Individual;
use Saloon\Exceptions\Request\RequestException;
use CraigPotter\Fca\Requests\Firm\GetFirmDetails;
use CraigPotter\Fca\Requests\Firm\GetFirmAddresses;
use CraigPotter\Fca\Requests\Firm\GetFirmIndividuals;

it('can get a check a firms fca number exists', function () {
    $mockClient = new MockClient([
        GetFirmDetails::class => MockResponse::fixture('firm.details'),
    ]);

    $fca = new Fca($_ENV['AUTH_EMAIL'], $_ENV['AUTH_KEY']);
    $fca->withMockClient($mockClient);

    $response = $fca->firm(439536)->exists();

    expect($response)->toBeTrue();
});

it('can get a check a firms fca number does not exists', function () {
    $mockClient = new MockClient([
        GetFirmDetails::class => MockResponse::fixture('firm.details.not-found'),
    ]);

    $fca = new Fca($_ENV['AUTH_EMAIL'], $_ENV['AUTH_KEY']);
    $fca->withMockClient($mockClient);

    $response = $fca->firm(00000)->exists();

    expect($response)->toBeFalse();
});

it('can get a firms details', function () {
    $mockClient = new MockClient([
        GetFirmDetails::class => MockResponse::fixture('firm.details'),
    ]);

    $fca = new Fca($_ENV['AUTH_EMAIL'], $_ENV['AUTH_KEY']);
    $fca->withMockClient($mockClient);

    $response = $fca->firm(439536)->get();

    expect($response->status())->toBe(200);
    $dto = $response->dto();

    expect($dto)
        ->toBeInstanceOf(CraigPotter\Fca\DataObjects\Firm::class)
        ->and($dto->frn)
        ->toBe(439536);
});

it('throws an exception when the firm is not found', function () {
    $mockClient = new MockClient([
        GetFirmDetails::class => MockResponse::fixture('firm.details.not-found'),
    ]);

    $fca = new Fca($_ENV['AUTH_EMAIL'], $_ENV['AUTH_KEY']);
    $fca->withMockClient($mockClient);

    expect(fn () => $fca->firm(00000)->get())
        ->toThrow(RequestException::class, 'Firm not found');
});

it('can get a firms individuals via pagination', function () {
    $mockClient = new MockClient([
        GetFirmIndividuals::class => function (PendingRequest $pendingRequest) {
            return match ($pendingRequest->query()->get('pgnp')) {
                1 => MockResponse::fixture('firm.individuals.1'),
                2 => MockResponse::fixture('firm.individuals.2'),
                3 => MockResponse::fixture('firm.individuals.3'),
            };
        },
    ]);

    $fca = new Fca($_ENV['AUTH_EMAIL'], $_ENV['AUTH_KEY']);
    $fca->withMockClient($mockClient);

    $paginated = $fca->firm(225566)->individuals();

    $total = [];
    foreach ($paginated as $response) {
        $data = $response->dto();
        $total = array_merge($total, $data);

        foreach ($data as $individual) {
            expect($individual)
                ->toBeInstanceOf(Individual::class)
                ->toHaveProperties(['name', 'status', 'irn']);
        }
    }
    expect($total)->toHaveCount(47);
    $mockClient->assertSentCount(3);
});

it('can get a firm addresses', function () {
    $mockClient = new MockClient([
        GetFirmAddresses::class => MockResponse::fixture('firm.address'),
    ]);

    $fca = new Fca($_ENV['AUTH_EMAIL'], $_ENV['AUTH_KEY']);
    $fca->withMockClient($mockClient);

    $paginated = $fca->firm(225566)->addresses();

    $total = [];
    foreach ($paginated as $response) {
        $data = $response->dto();
        $total = array_merge($total, $data);

        foreach ($data as $address) {
            expect($address)
                ->toBeInstanceOf(Address::class)
                ->toHaveProperties([
                    'website',
                    'contactName',
                    'type',
                    'addressLine1',
                    'addressLine2',
                    'addressLine3',
                    'addressLine4',
                    'postcode',
                    'town',
                    'county',
                    'country',
                    'phoneNumber',
                ]);
        }
    }
    expect($total)->toHaveCount(2);
    $mockClient->assertSentCount(1);
});
