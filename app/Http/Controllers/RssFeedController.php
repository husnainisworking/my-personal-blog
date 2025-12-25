<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class RssFeedController extends Controller
{
    public function index(): Response
    {
        // Cache the whole RSS XML for 1 hour so requests are fast (and Google/RSS readers don't spam your DB)
        $xml = Cache::remember('rss.feed.xml', 3600, function () {
            // Base URL from APP_URL in .env
            $baseUrl = rtrim((string) config('app.url'), '/');

            // Get the latest 20 published posts (only what we need for the feed).
            $posts = Post::published()
                ->latest('published_at')
                ->limit(20)
                ->get(['title', 'slug', 'excerpt', 'content', 'published_at']);

            // Escape XML special characters like &, <,> so the XML is valid.
            $escape = static fn (string $value): string => htmlspecialchars($value, ENT_XML1, 'UTF-8');

            // Build <item> entries (one per post).
            $items = '';
            foreach ($posts as $post) {
                $url = $baseUrl.'/posts/'.$post->slug; // public URL of the post

                // RSS description: prefer excerpt, fallback to stripped content text
                $description = $post->excerpt ?: strip_tags((string) $post->content);

                $items .= '<item>';
                $items .= '<title>'.$escape($post->title).'</title>';
                // title shown in RSS readers
                $items .= '<link>'.$escape($url).'</link>'; // clickable link
                $items .= '<guid isPermaLink="true">'.$escape($url).'</guid>';
                // unique id (we use the url)
                $items .= '<pubDate>'.$escape(
                    optional($post->published_at)->toRssString() ?? now()->toRssString()
                ).'</pubDate>';
                $items .= '<description>'.$escape($description).'</description>';
                $items .= '</item>';

            }

            // Channel metadata (this is the "feed info" in RSS readers).
            $channelTitle = 'My Personal Blog';
            $channelDescription = 'Latest posts from My Personal Blog';

            // Final RSS 2.0 XML output.
            return '<?xml version="1.0" encoding="UTF-8"?>'
            .'<rss version="2.0">'
            .'<channel>'
            .'<title>'.$escape($channelTitle).'</title>'
            .'<description>'.$escape($channelDescription).'</description>'
            .$items
            .'</channel>'
            .'</rss>';

        });

        // Return as RSS content-type so browsers/readers understand it's a feed.
        return response($xml, 200)->header('Content-Type', 'application/rss+xml; charset=UTF-8');
    }
}
