require(
	{
		baseUrl: '//' + window.location.host + '/_Resources/Static/Packages/',
		paths: {
			'jquery': 'Emberjs/jquery/minified/jquery-1.7.2.min',
			'emberjs': 'Emberjs/Core/minified/ember-0.9.8.min'
		},
		shim: {
			'emberjs': {
				'deps': ['jquery'],
				'exports': 'Ember'
			}
		}
	},
	[
		'jquery',
		'emberjs'
	],
	function() {
		$('.dropdown-toggle').dropdown();
	}
);