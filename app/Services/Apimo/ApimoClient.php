<?php

declare(strict_types=1);

namespace App\Services\Apimo;

use Illuminate\Container\Attributes\Config;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Read-only client for the Apimo API v3.
 *
 * Every call consumes one request of the daily Apimo quota (reset every night, Paris time), so the client
 * counts requests per Paris day and refuses to send more than the configured budget. After a 429 it stops
 * calling Apimo until the next reset.
 */
class ApimoClient
{
    public const string QUOTA_TIMEZONE = 'Europe/Paris';

    public const int MAX_PROPERTIES_PER_PAGE = 1000;

    private const string QUOTA_EXHAUSTED_CACHE_KEY = 'apimo:quota-exhausted';

    public function __construct(
        #[Config('services.apimo.provider_id')] private readonly ?string $providerId,
        #[Config('services.apimo.token')] private readonly ?string $token,
        #[Config('services.apimo.base_url')] private readonly string $baseUrl,
        #[Config('services.apimo.daily_request_budget')] private readonly int $dailyRequestBudget,
    ) {}

    /**
     * GET /agencies: every agency connected to the partner account.
     *
     * @return list<array<string, mixed>>
     */
    public function agencies(): array
    {
        return $this->get('/agencies')['agencies'];
    }

    /**
     * GET /agencies/{agency_id}/properties for several agencies at once (`101_102_103`), ordered by update date.
     *
     * @param  array<int, string>  $agencyIds
     * @return array{total_items: int, timestamp: int|null, properties: list<array<string, mixed>>}
     */
    public function properties(array $agencyIds, ?int $updatedSince, int $offset): array
    {
        $page = $this->get('/agencies/'.implode('_', $agencyIds).'/properties', array_filter([
            'limit' => self::MAX_PROPERTIES_PER_PAGE,
            'offset' => $offset,
            'timestamp' => $updatedSince,
            'with' => ['advanced'],
        ], fn (mixed $value): bool => $value !== null));

        return [
            'total_items' => (int) $page['total_items'],
            'timestamp' => isset($page['timestamp']) ? (int) $page['timestamp'] : null,
            'properties' => $page['properties'],
        ];
    }

    /**
     * GET /catalogs/{catalog_id} in every language.
     *
     * @return list<array<string, mixed>>
     */
    public function catalog(string $catalog): array
    {
        return array_values($this->get("/catalogs/{$catalog}", ['culture' => 'all']));
    }

    /**
     * Requests sent today (Paris time) by this application.
     */
    public function requestsSentToday(): int
    {
        return (int) Cache::get($this->requestCounterKey(), 0);
    }

    /**
     * @param  array<string, mixed>  $query
     * @return array<array-key, mixed>
     */
    private function get(string $path, array $query = []): array
    {
        if (blank($this->providerId) || blank($this->token)) {
            throw ApimoException::missingCredentials();
        }

        $this->reserveRequest();

        $response = Http::baseUrl($this->baseUrl)
            ->withBasicAuth($this->providerId, $this->token)
            ->acceptJson()
            ->connectTimeout(10)
            ->timeout(120)
            ->get($path, $query);

        if ($response->status() === 429) {
            Cache::put(self::QUOTA_EXHAUSTED_CACHE_KEY, true, $this->nextQuotaReset());

            throw ApimoQuotaExceededException::dailyQuotaReached();
        }

        if ($response->failed()) {
            throw ApimoException::fromResponse($response, $path);
        }

        $body = $response->json();

        if (! is_array($body)) {
            throw ApimoException::fromResponse($response, $path);
        }

        if (isset($body['status'], $body['title'], $body['detail']) && (int) $body['status'] >= 400) {
            throw ApimoException::fromErrorBody($body, $path);
        }

        return $body;
    }

    private function reserveRequest(): void
    {
        if (Cache::get(self::QUOTA_EXHAUSTED_CACHE_KEY)) {
            throw ApimoQuotaExceededException::dailyQuotaReached();
        }

        $key = $this->requestCounterKey();

        Cache::add($key, 0, $this->nextQuotaReset()->addHour());

        if (Cache::increment($key) > $this->dailyRequestBudget) {
            throw ApimoQuotaExceededException::localBudgetReached($this->dailyRequestBudget);
        }
    }

    private function requestCounterKey(): string
    {
        return 'apimo:requests:'.Carbon::now(self::QUOTA_TIMEZONE)->toDateString();
    }

    private function nextQuotaReset(): Carbon
    {
        return Carbon::now(self::QUOTA_TIMEZONE)->addDay()->startOfDay();
    }
}
