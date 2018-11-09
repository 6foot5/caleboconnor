
var gulp = require('gulp');
var watch = require('gulp-watch');
var autoprefixer = require('autoprefixer');
var postcss = require('gulp-postcss');
var cssvars = require('postcss-simple-vars');
var nested = require('postcss-nested');
var cssImport = require('postcss-import');
var mixins = require('postcss-mixins');

gulp.task('watch', function() {

  watch('./themes/say-hey/styles/**/*.css', function() {
    gulp.start('styles');
  });

});

gulp.task('styles', function() {
  return gulp.src('./themes/say-hey/styles/style.css')
    .pipe(postcss([cssImport, mixins, cssvars, nested, autoprefixer]))
    .on('error', function(errorInfo) {
      console.log(errorInfo.toString());
      this.emit('end');
    })
    .pipe(gulp.dest('./themes/say-hey'));
});
