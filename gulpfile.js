'use strict';

let outputDirectories = {
        'css': 'public/asset/css',
        'font': 'public/asset/font',
        'image': 'public/asset/image',
        'js': 'public/asset/js',
    },
    projectFiles = {
        'load.min.js': ['asset/js/init.js', 'asset/js/config.js', 'asset/js/load.js'],
        'main.min.js': ['asset/js/src/**/*.js', 'asset/js/main.js']
    },
    libraryFiles = {
        'vendor.min.js': [
            'node_modules/jquery/dist/jquery.min.js',
            'node_modules/sortablejs/Sortable.min.js'
        ]
    },

    gulp = require('gulp'),
    modules = {
        babel: require('gulp-babel'),
        base64: require('gulp-base64'),
        clean: require('gulp-clean'),
        concat: require('gulp-concat'),
        minify: require('gulp-minify'),
        rename: require('gulp-rename'),
        sass: require('gulp-sass'),
        sequence: require('gulp-sequence')
    };


// Builds the CSS files from the SCSS source.
gulp.task('css-build', () => {
    gulp.src('asset/scss/*.scss')
        .pipe(modules.sass({
            outputStyle: 'compressed',
            includePaths: ['node_modules']
        }))
        .pipe(modules.base64({
            extensions: [/.*inline.*/],
            maxImageSize: 1048576
        }))
        .pipe(modules.rename((path) => {
            path.extname = '.min.css'
        }))
        .pipe(gulp.dest(outputDirectories.css));
});

// Cleans any previously built CSS file from the output directory.
gulp.task('css-clean', () => {
    gulp.src(outputDirectories.css + '/*', { read: false })
        .pipe(modules.clean());
});


// Copies the font files to the output directory.
gulp.task('font-copy', () => {
    gulp.src('node_modules/font-awesome/fonts/**')
        .pipe(gulp.dest(outputDirectories.font));
});

// Cleans any previously built font file from the output directory.
gulp.task('font-clean', () => {
    gulp.src(outputDirectories.font + '/*', { read: false })
        .pipe(modules.clean());
});


// Copies the images to the output directory.
gulp.task('image-copy', () => {
    gulp.src(['asset/image/**', '!asset/image/inline/**'], { nodir: true })
        .pipe(gulp.dest(outputDirectories.image));
});

// Cleans any previously built image file from the output directory.
gulp.task('image-clean', () => {
    gulp.src(outputDirectories.image + '/*', { read: false })
        .pipe(modules.clean());
});


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
            .pipe(modules.concat(destinationFileName))
            .pipe(gulp.dest(outputDirectories.js));
    }
});

// Cleans any previously built JS file from the output directory.
gulp.task('js-clean', () => {
    gulp.src(outputDirectories.js + '/*', { read: false })
        .pipe(modules.clean());
});

// Watches you.
gulp.task('watch', () => {
    gulp.watch('asset/scss/**', ['css-build']);
    gulp.watch('asset/image/**', ['image-copy']);
    gulp.watch('asset/js/**/*.js', ['js-build']); // We are not building the fallback on watch. Do it manually.
});

// Does everything at once.
gulp.task('default', (callback) => {
    modules.sequence(
        ['css-clean', 'font-clean', 'image-clean', 'js-clean'],
        ['css-build'],
        ['font-copy'],
        ['image-copy'],
        ['js-copy-lib', 'js-build', 'js-build-fallback'],
        callback
    );
});