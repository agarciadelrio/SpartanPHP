const gulp    = require('gulp');
const plumber = require('gulp-plumber');
const rename  = require("gulp-rename");
const concat  = require('gulp-concat');
const sass    = require('gulp-sass');
const pug     = require('gulp-pug');
//const sourcemaps   = require('gulp-sourcemaps');
//const autoprefixer = require('autoprefixer');

const cfg = require('./gulpconfig.json');
const paths = cfg.paths;
const devpath = paths.dev;
const outpath = paths.www;

function onErrorHandler(err) {
  console.log(err);
  this.emit('end');
}

gulp.task('browser-sync', function() {
  //browserSync.init(cfg.browserSyncWatchFiles, cfg.browserSyncOptions);
});

gulp.task('sass', function() {
  const stream = gulp
    .src(`${devpath}/${paths.sass}/${paths.main_style}`)
    .pipe(
      plumber({
        errorHandler: onErrorHandler.bind(this)
      })
    )
    .pipe(sass({
      outputStyle: 'compressed',
      errLogToConsole: true
    }))
    .pipe(gulp.dest(`${outpath}/${paths.css}`));
  return stream;
});

gulp.task('pug', function() {
  var stream = gulp
    .src(`${devpath}/${paths.views}/**/*.pug`)
    .pipe(
      plumber({
        errorHandler: onErrorHandler.bind(this)
      })
    )
    .pipe(pug({
      doctype: 'html',
      pretty: true,
      basedir: `${devpath}/${paths.pug}`
    }))
    .pipe(rename(function (path) {
      path.extname = ".php";
    }))
    .pipe(gulp.dest(`${outpath}/${paths.views}`));
  return stream;
});

gulp.task('php', function() {
  const stream = gulp
    .src(`${devpath}/**/*.php`)
    .pipe(gulp.dest(outpath));
  return stream;
});

gulp.task('sql', function() {
  const stream = gulp
    .src(`${devpath}/conf/migrations/*.sql`)
    .pipe(gulp.dest(`${outpath}/conf/migrations`));
  return stream;
});

gulp.task('styles', function(callback) {
  gulp.series('sass')(callback);
});

gulp.task('scripts', function() {
  let scripts = [
    `${devpath}/js/jquery.min.js`,
    `${devpath}/js/bootstrap.min.js`,
  ];
  return gulp.src(scripts)
    .pipe(concat('all.js'))
    .pipe(gulp.dest(`${outpath}/js`));
});

gulp.task('dist', function() {
});

gulp.task('watch', function() {
  gulp.watch(`${devpath}/${paths.sass}/**/*`, gulp.series('styles'));
  gulp.watch([
    `${devpath}/${paths.views}/**/*.pug`,
    `${devpath}/${paths.pug}/**/*.pug`
  ], gulp.series('pug'));
  gulp.watch([
    `!${devpath}/${paths.vendor}/**/*.php`,
    `${devpath}/**/*.php`
  ], gulp.series('php'));
});

gulp.task('watch-bs', gulp.parallel('browser-sync', 'watch'));
gulp.task('compile', gulp.series('styles', 'scripts', 'dist'));
gulp.task('default', gulp.series('watch'));