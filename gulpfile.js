var gulp = require('gulp');
const browserSync = require('browser-sync').create();
var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var livereload = require('gulp-livereload');
var babel = require('gulp-babel');
var sourcemaps = require('gulp-sourcemaps');
var siteUrls = [
	{
		src: 'assets/scss/**/*.scss',
		dst: 'assets/css'
	},
];


gulp.task('styles', function () {

	for (var i = 0; i < siteUrls.length; i++) {
		gulp.src(siteUrls[i].src)
			.pipe(sourcemaps.init())
			.pipe(sass().on('error', sass.logError))
			.pipe(sourcemaps.write())
			.pipe(autoprefixer())
			.pipe(gulp.dest(siteUrls[i].dst))
			.pipe(livereload())
			.pipe(browserSync.stream());
	}

});

gulp.task('watch', function () {
	livereload.listen();
	for (var i = 0; i < siteUrls.length; i++) {
		gulp.watch(siteUrls[i].src, ['styles']);
	}

	gulp.watch('assets/es6/**/*.js', ['es6']);
});


gulp.task('browser-sync', function () {
	browserSync.init({
		proxy: "recepie.loc/",
		host: "192.168.0.112",
		port: 3000,
		notify: true,
		ui: {
			port: 3001
		},
		open: false
	});
});

gulp.task('es6', () =>
	gulp.src('assets/es6/**/*.js')
		.pipe(babel({
			presets: ['babel-preset-env']
		}))
		.on('error', console.error.bind(console))
		.pipe(gulp.dest('assets/js/'))

);

gulp.task('copy', function(){

	let dest = '../../../../simple-recipe-wp/trunk';

	gulp.src(['*']).pipe(gulp.dest(dest + ''));
	gulp.src(['add_recipe/**/*']).pipe(gulp.dest(dest + '/add_recipe'));
	gulp.src(['archive/**/*']).pipe(gulp.dest(dest + '/archive'));
	gulp.src(['assets/**/*']).pipe(gulp.dest(dest + '/assets'));
	gulp.src(['includes/**/*']).pipe(gulp.dest(dest + '/includes'));
	gulp.src(['languages/**/*']).pipe(gulp.dest(dest + '/languages'));
	gulp.src(['my_account/**/*']).pipe(gulp.dest(dest + '/my_account'));
	gulp.src(['single_recipe/**/*']).pipe(gulp.dest(dest + '/single_recipe'));
	gulp.src(['smrc_templates/**/*']).pipe(gulp.dest(dest + '/smrc_templates'));
	gulp.src(['widgets/**/*']).pipe(gulp.dest(dest + '/widgets'));
	gulp.src(['wp-custom-fields-theme-options/**/*']).pipe(gulp.dest(dest + '/wp-custom-fields-theme-options'));

});

gulp.task('default', ['styles', 'watch', 'es6', 'browser-sync']);