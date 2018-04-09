'use strict';

let outputDirectories = {
        'js': 'public/asset/js'
    },
    projectFiles = {
        'load.min.js': ['asset/js/init.js', 'asset/js/config.js', 'asset/js/load.js'],
        'main.min.js': ['asset/js/src/**/*.js']
    },
    libraryFiles = {
        'jquery.min.js': ['node_modules/jquery/dist/jquery.min.js']
    },

    gulp = require('gulp'),
    modules = {
        babel: require('gulp-babel'),
        clean: require('gulp-clean'),
        concat: require('gulp-concat'),
        minify: require('gulp-minify'),
        rename: require('gulp-rename'),
        sequence: require('gulp-sequence')
    };

// Concatenates and compresses the JS files of the project.
gulp.task('js-build', () => {
    for (let destinationFileName in projectFiles) {
        gulp.src(projectFiles[destinationFileName])
            .pipe(modules.concat(destinationFileName))
            .pipe(modules.minify({
                ext: {
                    min: '.js'
                },
                noSource: true
            }))
            .pipe(gulp.dest(outputDirectories.js));
    }
});

// Concatenates, compresses and converts the JS files of the project to a ES5 compatible script.
gulp.task('js-build-fallback', () => {
    let destinationFileName = 'main.min.js';
    gulp.src(projectFiles[destinationFileName])
        .pipe(modules.concat('main.es5.min.js'))
        .pipe(modules.babel({
            presets: ['env']
        }))
        .pipe(modules.minify({
            ext: {
                min: '.js'
            },
            noSource: true
        }))
        .pipe(gulp.dest(outputDirectories.js));
});

// Copies 3rd party library files to the output directory.
gulp.task('js-copy-lib', () => {
    for (let destinationFileName in libraryFiles) {
        gulp.src(libraryFiles[destinationFileName])
            .pipe(modules.rename(destinationFileName))
            .pipe(gulp.dest(outputDirectories.js));
    }
});

// Cleans any previously built JS file from the output directory.
gulp.task('js-clean', () => {
    gulp.src(outputDirectories.js + '/*', {read: false})
        .pipe(modules.clean());
});

// Watches you.
gulp.task('watch', () => {
    gulp.watch('asset/js/**/*.js', ['js-build']); // We are not building the fallback on watch. Do it manually.
});

// Does everything at once.
gulp.task('default', (callback) => {
    modules.sequence(
        'js-clean',
        ['js-copy-lib', 'js-build', 'js-build-fallback'],
        callback
    );
});