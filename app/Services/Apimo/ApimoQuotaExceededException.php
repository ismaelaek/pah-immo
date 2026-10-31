<?php

declare(strict_types=1);

namespace App\Services\Apimo;

/**
 * Thrown when Apimo answers 429 (daily quota exhausted) or when the local daily request budget is spent.
 */
class ApimoQuotaExceededException extends ApimoException
{
    public static function dailyQuotaReached(): self
    {
        return new self('The Apimo daily request quota is exhausted until it resets tonight.', 429);
    }

    public static function localBudgetReached(int $budget): self
    {
        return new self("The local Apimo budget of {$budget} requests for today is spent. Raise APIMO_DAILY_REQUEST_BUDGET to allow more.", 429);
    }
}
