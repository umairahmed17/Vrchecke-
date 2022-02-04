var gulp = require("gulp");
var cssnano = require("gulp-cssnano");
var concat = require("gulp-concat");
var postcss = require("gulp-postcss");
var minifyCSS = require("gulp-minify-css");
var autoprefixer = require("gulp-autoprefixer");
var nested = require("postcss-nested");
var append = require("gulp-append");
const minify = require("gulp-minify");
const rename = require("gulp-rename");
/**
 * Browser Sync
 */
gulp.task("browserSync", function () {
  browserSync.init({
    server: {
      baseDir: "build",
    },
  });
});

/**
 * Compile css.
 */
gulp.task("styles", function () {
  return gulp
    .src(["./assets/css/source/*.css"])
    .pipe(autoprefixer("last 2 versions"))
    .pipe(postcss([nested, autoprefixer, cssnano]))
    .pipe(minifyCSS())
    .pipe(gulp.dest("./assets/css/"));
});

/**
 * Compress JS
 */
gulp.task("scripts", function () {
  return gulp
    .src(["./assets/js/src/*.js"])
    .pipe(minify({ keepBreaks: true }))
    .pipe(
      rename({
        suffix: ".min",
      })
    )
    .pipe(gulp.dest("./assets/js/"));
});

/**
 * Watch
 */
gulp.task("watch", function () {
  gulp.watch("./assets/css/source/*.css", gulp.series("styles"));
  gulp.watch("./assets/js/src/*.js", gulp.series("scripts"));
});
gulp.task("watch:js", function () {
  gulp.watch("./assets/js/src/*.js", gulp.series("scripts"));
});

/**
 * Adding to minfied file
 */
gulp.task("minAdd", function () {
  return gulp
    .src("./assets/css/vrchecke-form.min.css")
    .pipe(append("./assets/css/styles.min.css"));
});

/**
 * Default
 */
gulp.task("default", gulp.series("watch"));
