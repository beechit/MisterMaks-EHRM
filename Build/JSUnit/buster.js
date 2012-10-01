var config = module.exports;

config['Beech.Ehrm Unit'] = {
	rootPath: '../../',
	environment: 'browser',
	tests: ['Packages/Application/Beech.Ehrm/Tests/JavaScript/Unit/**/*.js'],
	libs: [
		'Packages/Application/Beech.Ehrm/Resources/Public/JavaScript/require.js'
	],
	resources: [
		'Web/_Resources/Static/Packages/**/*.js'
	],
	sources: [
		'Packages/Application/Beech.Ehrm/Tests/JavaScript/Unit.js'
	]
}