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
        .pipe(autoprefixer())
        .pipe(gulp.dest('resources/assets/css/dist'))
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('resources/assets/css/dist'))
        .pipe(browserSync.stream())
});

gulp.task('js', function() {
    return gulp.src(['resources/assets/js/src/**/*.js'])
        .pipe(uglify())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('resources/assets/js/dist'))
});

gulp.task('serve', () => {
    browserSync({
        host: 'localhost',
        port: 3000,
        proxy: 'http://localhost:8020/',
        injectChanges: true,
    });

    gulp.watch('./resources/assets/css/src/**/*.scss', gulp.series('sass'));
    gulp.watch('./resources/assets/js/src/**/*.js', gulp.series('js')).on('change', reload);
    gulp.watch(['./**/*.php']).on('change', reload);
});

gulp.task('default', gulp.series('sass', 'js', 'serve'));