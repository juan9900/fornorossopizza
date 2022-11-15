document.addEventListener( 'DOMContentLoaded', function () {
	new Splide( '#card-slider', {
		perPage    : 3,
		breakpoints: {
			600: {
				perPage: 1,
			},
			1024: {
				perPage: 2,
			}
		},
		'arrowPath': 'm15.5 0.932-4.3 4.38 14.5 14.6-14.5 14.5 4.3 4.4 14.6-14.6 4.4-4.3-4.4-4.4-14.6-14.6z',
		type: 'loop',
	} ).mount();
} );