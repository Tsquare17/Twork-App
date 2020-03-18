var gulp = require('gulp');
var sass = require('gulp-sass');
var browserSync = require('browser-sync');
var autoprefixer = require('gulp-autoprefixer');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var reload = browserSync.reload;

gulp.task('sass', function() {
    return gulp.src('resources/assets/css/src/*.scss')
        .pipe(sass({ style: 'expanded' }))
        .pipe(autoprefixer({
            browsers: ['last 2 versions', 'ie 9'],
            cascade: false
        }))
        .pipe(gulp.dest('resources/assets/css/dist'))
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('resources/assets/css/dist'))
                // .pipe(reload({stream: true}));
        .pipe(browserSync.stream({ match: 'resources/assets/css/src/**/*.scss' }))
});

gulp.task('js', function() {
    return gulp.src(['resources/assets/js/src/**/*.js'])
        .pipe(uglify())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('resources/assets/js/dist'))
        .pipe(browserSync.stream({ match: 'resources/assets/js/src/**/*.js' }))
});

gulp.task('serve', function() {
    browserSync({
        host: 'localhost',
        port: 3000,
        proxy: 'http://localhost:8020/',
        injectChanges: true,
    });

    gulp.watch('./resources/assets/css/src/**/*.scss', ['sass']).on('change', reload);
    gulp.watch('./resources/assets/js/src/**/*.js', ['js']).on('change', reload);
    gulp.watch(['./**/*.php']).on('change', reload);
});

gulp.task('default', ['sass', 'js', 'serve']);