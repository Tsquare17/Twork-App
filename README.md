[![Build Status](https://travis-ci.org/Tsquare17/Twork-App.svg?branch=master)](https://travis-ci.org/Tsquare17/Twork-App)

# Twork - WordPress Theme Framework.
- Clean templating with Twork's controllers and queries, and the Blade templating engine.
- Auto Sass and JavaScript compilation, and auto inject/reload with Browsersync.
- WP-CLI command for creating and registering controllers, custom posts, and queries.

### Requirements
- PHP >= 7.1

### Installation
- composer create-project twork/app ThemeName
- cd ThemeName && npm install

### Make a Controller
- wp twork make controller ExamplePage

### Make a New Custom Post Type
- wp twork make post ExamplePosts

### Make a New Query
- wp twork make query ExamplePosts
