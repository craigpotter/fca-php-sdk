# FCA API SDK for PHP
[![Latest Version on Packagist](https://img.shields.io/packagist/v/craigpotter/fca-php-sdk.svg?style=flat-square)](https://packagist.org/packages/craigpotter/fca-php-sdk)
[![Tests](https://img.shields.io/github/actions/workflow/status/craigpotter/fca-php-sdk/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/craigpotter/fca-php-sdk/actions/workflows/tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/craigpotter/fca-php-sdk.svg?style=flat-square)](https://packagist.org/packages/craigpotter/fca-php-sdk)


This is an unofficial PHP SDK for [FCA's API](https://register.fca.org.uk/Developer/s/) and is powered by [Saloon](https://github.com/Sammyjo20/Saloon).

> **Note:** This SDK is still in development and is not yet ready for production use.
> Use at your own risk.

## Installation

You can install the package via composer:

```bash
composer require craigpotter/fca-php-sdk
```

## Requirements

You will need to have a valid API key from FCA to use this SDK. 

You can obtain one by [registering as a developer](https://register.fca.org.uk/Developer/s/).

## Example Usage

``` php
use CraigPotter\Fca\Fca;

// Create a new FCA instance (This is required for all requests)
$fca = new Fca('my.email@dev.test', 'my-api-key');

// 12345 is the FCA firm reference number

$firmFrnExists = $fca->firm(12345)->exists(); // Returns a boolean

$response = $fca->firm(12345)->get();

$firm = $response->dto(); // Returns a Firm DTO
$json = $response->json(); // Returns the raw JSON response
```

## Documentation

> **Warning:** This documentation is still a work in progress.

Some responses have the option of a DTO (Data Transfer Object) which can be used to access the response data.

This does not work for all responses, but is documented where it is available.  
For all other responses, you can access the raw JSON response using `$response->json()`.

### Firms

#### Check an FRN exists
```php
use CraigPotter\Fca\Fca;

// Create a new FCA instance (This is required for all requests)
$fca = new Fca('my.email@dev.test', 'my-api-key');

// 12345 is the FCA firm reference number

$firmFrnExists = $fca->firm(12345)->exists(); // Returns a boolean
```

#### Get a firm by FRN
```php
use CraigPotter\Fca\Fca;

// Create a new FCA instance (This is required for all requests)
$fca = new Fca('my.email@dev.test', 'my-api-key');

// 12345 is the FCA firm reference number

$response = $fa->firm(12345)->get(); // Returns a Saloon response object

$firm = $response->dto(); // Returns a Firm DTO
$firm->frn; // 12345
$firm->name; // "My Firm"
$firm->status; // "Active"
$firm->statusDate; // "2021-01-01"
$firm->statusReason; // "Active"
$firm->companiesHouseNumber; // "12345678"

$json = $response->json(); // Returns the raw JSON response
```

#### Get the individuals associated with a firm by FRN
This endpoint is paginated and will return a maximum of 20 results per page.  
You should loop through the pages to get all results.
```php
use CraigPotter\Fca\Fca;

// Create a new FCA instance (This is required for all requests)
use CraigPotter\Fca\Fca;

// Create a new FCA instance (This is required for all requests)
$fca = new Fca('my.email@dev.test', 'my-api-key');

// 12345 is the FCA firm reference number

$paginatedData = $fa->firm(12345)->individuals(); // Returns a Saloon response object

$paginatedData->totalPages(); // Returns the total number of pages
$paginatedData->totalResults(); // Returns the total number of results
foreach ($paginatedData as $page) {
    $paginatedData->getCurrentPage(); // Returns the current page number
    $individuals = $page->dto(); // Returns an array of Individual DTOs for this page
    $json = $page->json(); // Returns the raw JSON response for this page
    
    
    foreach ($individuals as $individual) {
        $individual->irn; // "JOHNSMITH12345"
        $individual->name; // "John Smith"
        $individual->status; // "Approved by regulator"
    }
}
```

#### Get the addresses associated with a firm by FRN
This endpoint is paginated and will return a maximum of 20 results per page.  
You should loop through the pages to get all results.
```php
use CraigPotter\Fca\Fca;

// Create a new FCA instance (This is required for all requests)
use CraigPotter\Fca\Fca;

// Create a new FCA instance (This is required for all requests)
$fca = new Fca('my.email@dev.test', 'my-api-key');

// 12345 is the FCA firm reference number

$paginatedData = $fa->firm(12345)->addresses(); // Returns a Saloon response object

$paginatedData->totalPages(); // Returns the total number of pages
$paginatedData->totalResults(); // Returns the total number of results
foreach ($paginatedData as $page) {
    $paginatedData->getCurrentPage(); // Returns the current page number
    $addresses = $page->dto(); // Returns an array of Address DTOs for this page
    $json = $page->json(); // Returns the raw JSON response for this page
    
    
    foreach ($addresses as $address) {
        $address->website; // "www.example.org"
        $address->phoneNumber; // "44123456778"
        $address->type; // "Principal Place of Business"
        $address->contactName; // "John Smith"
        $address->addressLine1; // "1 Example Street"
        $address->addressLine2; // "Aberdeen"
        $address->addressLine3; // "Aberdeen"
        $address->addressLine4; // "Aberdeen"
        $address->town; // "Aberdeen"
        $address->county; // "Aberdeenshire"
        $address->country; // "United Kingdom"
        $address->postcode; // "AB1 2CD"
    }
}
```

## Testing

Copy `.env.example` to `.env` and update accordingly.
```bash
vendor/bin/pest
```

## Credits

- [Craig Potter](https://github.com/craigpotter)
- [All Contributors](../../../contributors)

## License

The MIT License (MIT). Please see [License File](../LICENSE.md) for more information.

