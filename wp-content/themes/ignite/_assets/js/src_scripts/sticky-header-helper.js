/**
 * adding "smaller" class for the top fixed nav
 */


jQuery(document).ready(function($) {

	const threshold = 100;
	const buffer = 12;
	const header = $('header.site_main_header');

	function checkScroll() {
		const scrollTop = $(window).scrollTop();

		if (scrollTop > threshold + buffer && !header.hasClass('smaller')) {
			header.addClass('smaller');
		} else if (scrollTop < threshold - buffer && header.hasClass('smaller')) {
			header.removeClass('smaller');
		}
	}

	// On page load
	checkScroll();

	// Scroll event (debounced)
	let scrollTimeout;
	$(window).scroll(function() {
		if (scrollTimeout) clearTimeout(scrollTimeout);
		scrollTimeout = setTimeout(checkScroll, 10);
	});

	// Also run on full page load
	$(window).on('load', checkScroll);

});