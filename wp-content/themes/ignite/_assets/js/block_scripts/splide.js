document.addEventListener('DOMContentLoaded', function () {


	Splide.defaults = {
		perPage: 1,
		perMove: 1,
		gap: '0',
		//height: '70vh', (these are defined with ACF settings)
		//fixedWidth: '100vw',
		//autoHeight: true,
		drag: true,
		snap: true,
		noDrag: 'input, textarea, .no-drag',
		dragMinThreshold: {
			mouse: 0,
			touch: 10,
		},
		keyboard: true,
		wheel: false,
		waitForTransition: true,
		wheelSleep: '1000',
		releaseWheel: true,
		trimSpace: false,
		cover: true,
		// type: 'loop',

	}

	var elms = document.getElementsByClassName( 'splide' );
	for ( var i = 0; i < elms.length; i++ ) {
		new Splide( elms[ i ] ).mount();
	}
/*
	var splide = new Splide( '.splide' );
    splide.mount();
 */


});
