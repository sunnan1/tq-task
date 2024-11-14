@component('mail::message')
    # Your Post Has Been Published!

    Hello {{ $post->author->name }},

    Your post titled **"{{ $post->title }}"** has been published successfully on {{ $post->published_at }}.

    @component('mail::panel')
        {{ $post->description }}
    @endcomponent

    Thank you for sharing your content with us!

    Regards,
    {{ config('app.name') }}
@endcomponent
