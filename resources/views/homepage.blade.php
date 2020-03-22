@wpHeader
<main id="content homepage">
    <h1>{{$title}}</h1>
    <div class="posts">
        <h2>Posts</h2>
        @wpPosts
            @wpTemplatePart('entry')
            @wpComments
        @endWpPosts
    </div>

    <div class="custom-posts">
        <h2>Custom Posts</h2>
        @forelse($customPosts as $post)
            <h4>{{ $post->title }}</h4>
        @empty
            No Custom Posts
        @endforelse
    </div>
    @wpTemplatePart('nav', 'below')
</main>
@wpSidebar
@wpFooter
