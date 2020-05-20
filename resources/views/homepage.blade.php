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

    {!! $form->render() !!}

    @if($form->isSubmitted())
        {{'submitted'}}
        @if($form->isValid())
            {{'valid!'}}
        @endif
    @endif

    <div class="custom-posts">
        <h2>Custom Posts</h2>
        @forelse($customPosts->get() as $post)
            <h4>{{ the_title() }}</h4>
        @empty
            No Custom Posts
        @endforelse
        {!! $customPosts->pagination() !!}
    </div>
</main>
@wpSidebar
@wpFooter
