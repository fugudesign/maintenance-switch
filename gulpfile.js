var gulp = require('gulp');
var sass = require('gulp-sass');
var pi = require('pipe-iterators');

// GULP
gulp.task('default', ['sass']);

gulp.task('watch', function () {
    gulp.watch('./**/*.scss', ['sass']);
});

gulp.task('sass', ['sass-assets', 'sass-admin', 'sass-public'])

gulp.task('sass-admin', function () {
    return gulp.src('admin/css/*.scss')
    .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
    .pipe(gulp.dest('admin/css'));
});

gulp.task('sass-public', function () {
    return gulp.src('public/css/*.scss')
    .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
    .pipe(gulp.dest('public/css'));
});

gulp.task('sass-assets', function () {
    return gulp.src('assets/css/*.scss')
    .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
    .pipe(gulp.dest('assets/css'));
});
