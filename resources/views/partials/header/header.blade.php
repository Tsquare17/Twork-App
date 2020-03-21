<header id="header">
    <div id="branding">
        <div id="site-title">
            {!! is_front_page() || is_home() ? '<h1>' : '' !!}
            <a href="{{ esc_url(home_url('/')) }}" title="{{ esc_html(get_bloginfo('name')) }}" rel="home">
                {{ esc_html(get_bloginfo('name')) }}
            </a>
            {!! is_front_page() || is_home() ? '</h1>' : '' !!}
        </div>
    </div>
    @include('partials.header.nav')
</header>
