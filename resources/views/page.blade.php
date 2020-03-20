@wpHeader
<main id="content">
    <h1>{{$title}}</h1>
    @wpPosts
        {!! get_the_content() !!}
    @endWpPosts
    @wpTemplatePart('nav', 'below')
</main>
@wpSidebar
@wpFooter
