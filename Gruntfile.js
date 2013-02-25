module.exports = function (grunt) {

	'use strict';

	// Project configuration.
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		meta: {
			banner: '/*! <%= pkg.name %> - v<%= pkg.version %> - ' +
			'<%= grunt.template.today("yyyy-mm-dd") %> */'
		},

		jshint: {
			all: [
				'Gruntfile.js',
				'Packages/Beech/**/Resources/Public/JavaScript/*.js'
			],
			options: grunt.file.readJSON('.jshintrc')
		},

		concat: {
			deploy: {
				src: [],
				dest: 'Build/Dist/main-<%= pkg.version %>.min.js'
			}
		},

		uglify: {
			deploy: {
				src: ['<banner>', '<config:concat.deploy.dest>'],
				dest: 'Build/Dist/main-<%= pkg.version %>.min.js'
			}
		},

		watch: {
			js: {
				files: 'Resources/Public/JavaScript/**/*.js',
				tasks: 'jshint'
			}
		}
	});

	// Load some stuff
	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-watch');

	// Default task.
	grunt.registerTask('default', ['jshint', 'concat', 'uglify']);

};