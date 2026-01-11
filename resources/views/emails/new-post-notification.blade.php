
                <h2> New Post Published! </h2>

                <h3>{{ $post->title }}</h3>

                <p>
                    {{ Str::limit(strip_tags($post->content), 200) }}
        </p>

        <p>
            <a href="{{ route('posts.public.show', $post->slug) }}" style="display: inline-block; padding: 12px 24px; background-color: #4f46e5; color: white; text-decoration: none; border-radius: 5px;">
                Read Full Post
        </a>
        </p>

        <hr>

        <p style="font-size: 12px; color: #666;">
            You're receiving this email because you subscribed to our newsletter.
            <br>
                <a href="{{ route('newsletter.unsubscribe', $subscriber->unsubscribe_token) }}">Unsubscribe from future emails</a>
        </p>
    