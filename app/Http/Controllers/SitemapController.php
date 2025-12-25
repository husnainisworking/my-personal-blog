<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $xml = Cache::remember('sitemap.xml', 3600, function () {
            $baseUrl = rtrim((string) config('app.url'), '/');

            $urls = [];

            // Homepage
            $urls[] = [
                'loc' => $baseUrl.'/',
                'lastmod' => now()->toAtomString(),
            ];

            // Published posts
            $posts = Post::published()
                ->latest('published_at')
                ->get(['slug', 'updated_at']);

            foreach ($posts as $post) {
                $urls[] = [
                    'loc' => $baseUrl.'/posts/'.$post->slug,
                    'lastmod' => optional($post->updated_at)->toAtomString(),
                ];
            }
            $escapeXml = static fn (string $value): string => htmlspecialchars($value, ENT_XML1, 'UTF-8');

            $body = '';
            foreach ($urls as $url) {
                $body .= '<url>';
                $body .= '<loc>'.$escapeXml($url['loc']).'</loc>';

                if (! empty($url['lastmod'])) {
                    $body .= '<lastmod>'.$escapeXml($url['lastmod']).'</lastmod>';
                }

                $body .= '</url>';
            }

            return '<?xml version="1.0" encoding="UTF-8"?>'
            .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'
            .$body
            .'</urlset>';
        });

        return response($xml, 200)->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
