<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\NewsItem;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function robots(): Response
    {
        $body = "User-agent: *\nAllow: /\n\nSitemap: ".url('/sitemap.xml')."\n";

        return response($body, 200)->header('Content-Type', 'text/plain');
    }

    public function sitemap(): Response
    {
        $urls = collect([
            ['loc' => route('home'), 'priority' => '1.0'],
            ['loc' => route('about.show'), 'priority' => '0.8'],
            ['loc' => route('news.index'), 'priority' => '0.7'],
            ['loc' => route('research.index'), 'priority' => '0.7'],
            ['loc' => route('publications.index'), 'priority' => '0.7'],
            ['loc' => route('awards.index'), 'priority' => '0.6'],
            ['loc' => route('blog.index'), 'priority' => '0.7'],
            ['loc' => route('quotes.index'), 'priority' => '0.5'],
            ['loc' => route('contact.show'), 'priority' => '0.5'],
        ]);

        BlogPost::published()->get(['slug'])->each(
            fn (BlogPost $post) => $urls->push(['loc' => route('blog.show', $post), 'priority' => '0.6'])
        );

        NewsItem::active()->get(['slug'])->each(
            fn (NewsItem $newsItem) => $urls->push(['loc' => route('news.show', $newsItem), 'priority' => '0.5'])
        );

        $entries = $urls->map(fn (array $url) => sprintf(
            "    <url>\n        <loc>%s</loc>\n        <priority>%s</priority>\n    </url>",
            htmlspecialchars($url['loc'], ENT_XML1 | ENT_QUOTES),
            $url['priority']
        ))->implode("\n");

        $xml = '<'.'?xml version="1.0" encoding="UTF-8"?'.">\n"
            .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n"
            .$entries."\n</urlset>\n";

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
