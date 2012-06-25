({
	baseUrl: '../Web/_Resources/Static/Packages/',
	paths: {
		'mod1': 'Beech.Ehrm/JavaScript/mod/mod1',
		'jquery': 'Emberjs/jquery-1.7.2.min',
		'emberjs': 'Emberjs/Core/ember-0.9.6.min'
	},
	name: 'Beech.Ehrm/JavaScript/app',
	out: '../Web/_Resources/Persistent/script.js',
	shim: {
		'emberjs': {
			'deps': ['jquery'],
			'exports': 'Ember'
		}
	}
})