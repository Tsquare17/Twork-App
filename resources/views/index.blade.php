@wpHeader
<main id="content">
    @wpPosts
            @wpTemplatePart('entry')
            @wpComments
    @endWpPosts
    @wpTemplatePart('nav', 'below')
</main>
@wpSidebar
@wpFooter
