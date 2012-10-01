var config = module.exports;

config['Beech.Ehrm Unit'] = {
	rootPath: '../../',
	environment: 'browser',
	tests: ['Packages/Application/**/Tests/JavaScript/Unit/**/*.js'],
	extensions: [require('buster-lint')],
	'buster-lint': {
		linter: 'jshint',
		linterOptions: {
			eqeqeq: true,
			eqnull: false,
			nomen: false,
			unused: true,
			strict: true,
			trailing: true
		},
		excludes: [
			'.*/require.js',
			'.*/Library/.*',
			'.*/Tests/JavaScript/.*'
		]
	},
	libs: [
		'Packages/Application/Beech.Ehrm/Resources/Public/JavaScript/require.js'
	],
	resources: [
		'Web/_Resources/Static/Packages/Beech.*/JavaScript/**/*.js'
	],
	sources: [
		'Packages/Application/Beech.Ehrm/Tests/JavaScript/Unit.js'
	]
}