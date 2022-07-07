var gulp    = require('gulp');
var sass    = require('gulp-sass')(require('sass'));

gulp.task('sass', function () {
    return gulp.src('./assets/sass/style.sass')
        .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
        .pipe(gulp.dest('./dist/css/'));
});

gulp.task('watch', function() {
    gulp.watch('./assets/sass/**/*.sass', gulp.series('sass'));
});

gulp.task('default', gulp.series('sass', 'watch'));
