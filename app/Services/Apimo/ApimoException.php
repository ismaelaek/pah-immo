<?php

declare(strict_types=1);

namespace App\Services\Apimo;

use Illuminate\Http\Client\Response;
use RuntimeException;

class ApimoException extends RuntimeException
{
    public static function fromResponse(Response $response, string $path): self
    {
        $detail = $response->json('detail') ?? $response->reason();

        return new self("Apimo request to [{$path}] failed with status {$response->status()}: {$detail}", $response->status());
    }

    /**
     * Apimo returns some errors with HTTP 200 and a `{status, title, detail}` body.
     *
     * @param  array{status: int|string, detail?: string|null}  $body
     */
    public static function fromErrorBody(array $body, string $path): self
    {
        return new self("Apimo request to [{$path}] returned error {$body['status']}: ".($body['detail'] ?? 'no detail'), (int) $body['status']);
    }

    public static function missingCredentials(): self
    {
        return new self('Apimo credentials are missing. Set APIMO_PROVIDER_ID and APIMO_TOKEN.');
    }
}
