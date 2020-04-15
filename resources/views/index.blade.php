@wpHeader
<main id="content">
    @wpPosts
            @include('partials.posts.entry')
            @wpComments
    @endWpPosts
</main>
@wpSidebar
@wpFooter
