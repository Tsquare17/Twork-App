@wpHeader
<main id="content">
    <h1>{{$title}}</h1>
    @wpPosts
        @wpTemplatePart('entry')
        @wpComments
    @endWpPosts

    @wpTemplatePart('nav', 'below')
</main>
@wpSidebar
@wpFooter
