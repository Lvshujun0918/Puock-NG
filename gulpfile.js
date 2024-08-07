const gulp = require('gulp')
const babel = require('gulp-babel');
const less = require('gulp-less');
const rename = require('gulp-rename');
const uglify = require('gulp-uglify');
const plumber = require('gulp-plumber');
const concat = require('gulp-concat');
const concatCss = require('gulp-concat-css');
const minifyCSS = require('gulp-minify-css');
const autoprefixer = require('autoprefixer');
const cleanCSS = require('gulp-clean-css');
const postcss = require('gulp-postcss');

const _core_script = "assets/js/*.js"
const _libs_script = "assets/libs/basic/**/*.js"
const _core_style = "assets/style/*.less"
const _libs_style = "assets/libs/basic/**/*.css"
const _dist = "assets/dist"

gulp.task('style', function () {
    return gulp.src(_core_style)
        .pipe(plumber())
        .pipe(less({
            compress: true
        }))
        .pipe(cleanCSS())
        .pipe(postcss([ autoprefixer({overrideBrowserslist: ['> 1%'], cascade: false}) ]))
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(_dist + '/style'))
})

gulp.task('lib_style', function () {
    return gulp.src(_libs_style)
        .pipe(concatCss("libs.min.css",{
            // inlineImports:false,
            rebaseUrls:false
        }))
        .pipe(minifyCSS({
            format: 'keep-breaks',
            semicolonAfterLastProperty: true,
            afterComment: true
        }))
        .pipe(gulp.dest(_dist + '/style'))
})

gulp.task('lib_script', function () {
    return gulp.src(_libs_script)
        .pipe(concat("libs.min.js"))
        .pipe(uglify({
            output: {
                comments: true,
            }
        }))
        .pipe(gulp.dest(_dist + '/js'))
})

gulp.task('script', function () {
    return gulp.src(_core_script)
        .pipe(plumber())
        .pipe(rename({suffix: '.min'}))
        .pipe(babel())
        .pipe(uglify())
        .pipe(gulp.dest(_dist + '/js'))
})

gulp.task('build', gulp.series(
    'style',
    'lib_style',
    'script',
    'lib_script',
))

gulp.task('watch', gulp.series('build', function () {
    gulp.watch(_core_style, gulp.series('style'))
    gulp.watch(_core_script, gulp.series('script'))
    gulp.watch(_libs_script, gulp.series('lib_script'))
    gulp.watch(_libs_style, gulp.series('lib_style'))
}))
