<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Citation counts come from Crossref's free public API by DOI (implementation-plan.md
 * §3.9) — Google Scholar has no official API and scraping it violates its Terms of
 * Service, so this is the confirmed substitute. Publications the admin enters a
 * count for manually are never overwritten by this (see citation_source on the model).
 */
class CitationLookupService
{
    public function fetchCount(string $doi): ?int
    {
        try {
            $response = Http::timeout(8)->acceptJson()->get('https://api.crossref.org/works/'.$doi);
        } catch (\Throwable) {
            return null;
        }

        if (! $response->successful()) {
            return null;
        }

        $count = $response->json('message.is-referenced-by-count');

        return is_int($count) ? $count : null;
    }
}
