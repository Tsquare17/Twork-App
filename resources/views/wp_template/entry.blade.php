<article id="post-{{ get_the_ID() }}" @php(post_class())>
    <header>
        {{ is_singular() ? '<h1 class="entry-title">' : '<h2 class="entry-title">' }}

        <a href="{{ get_the_permalink() }}" title="{{ the_title_attribute() }}" rel="bookmark">
            {{ the_title() }}
        </a>

        {{ is_singular() ? '</h1>' : '</h2>' }} {{ get_edit_post_link() }}
    </header>
</article>
