<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\Locale;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->localeFor($request);

        app()->setLocale($locale->value);
        Carbon::setLocale($locale->value);

        if ($request->route('locale') !== null) {
            URL::defaults(['locale' => $locale->value]);
        }

        return $next($request);
    }

    private function localeFor(Request $request): Locale
    {
        if ($request->is('admin', 'admin/*')) {
            return Locale::French;
        }

        $parameter = $request->route('locale');

        if (is_string($parameter)) {
            return Locale::tryFrom($parameter) ?? Locale::French;
        }

        return Locale::French;
    }
}
