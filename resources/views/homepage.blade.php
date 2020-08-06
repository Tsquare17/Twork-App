@wpHeader
<main id="content homepage">
    <h1>{{$title}}</h1>
    <div class="posts">
        <h2>Posts</h2>
        @wpPosts
            @include('partials.posts.entry')
            @wpComments
        @endWpPosts
    </div>

    <div class="custom-posts">
        <h2>Custom Posts</h2>
        @forelse($customPosts->fetch() as $null)
            <h4>{{ the_title() }}</h4>
        @empty
            No Custom Posts
        @endforelse
        {!! $customPosts->pagination() !!}
    </div>
</main>
@wpSidebar
@wpFooter
