/* global jQuery */

(function ($) {
	'use strict';

	/**
	 * Initialize the ministry group jump-link carousel.
	 */
	const initializeMinistryGroups = (archive) => {
		if (archive.dataset.ministryInitialized === 'true') {
			return;
		}

		const $slider = $(archive).find('[data-ministry-group-slider]');
		const $previous = $(archive).find('[data-ministry-group-prev]');
		const $next = $(archive).find('[data-ministry-group-next]');
		const links = Array.from(
			archive.querySelectorAll('[data-ministry-group-link]')
		);

		if (
			!$slider.length ||
			!links.length ||
			typeof $.fn.slick !== 'function'
		) {
			return;
		}

		archive.dataset.ministryInitialized = 'true';

		const setCurrentLink = (currentLink) => {
			links.forEach((link) => {
				if (link === currentLink) {
					link.setAttribute('aria-current', 'location');
				} else {
					link.removeAttribute('aria-current');
				}
			});
		};

		links.forEach((link) => {
			link.addEventListener('click', () => setCurrentLink(link));
		});

		$previous.removeAttr('hidden');
		$next.removeAttr('hidden');

		$slider.slick({
			accessibility: true,
			adaptiveHeight: false,
			arrows: true,
			draggable: true,
			infinite: false,
			mobileFirst: true,
			nextArrow: $next,
			prevArrow: $previous,
			rows: 0,
			slidesToScroll: 1,
			slidesToShow: 2,
			speed: 400,
			swipeToSlide: true,
			touchMove: true,
			waitForAnimate: false,
			responsive: [
				{
					breakpoint: 768,
					settings: {
						slidesToShow: 4,
					},
				},
				{
					breakpoint: 1024,
					settings: {
						slidesToShow: 5,
					},
				},
			],
		});

		const hashIndex = links.findIndex(
			(link) => link.hash && link.hash === window.location.hash
		);

		if (hashIndex >= 0) {
			setCurrentLink(links[hashIndex]);
			$slider.slick('slickGoTo', hashIndex, true);
		}
	};

	$(function () {
		document
			.querySelectorAll('[data-ministry-archive]')
			.forEach(initializeMinistryGroups);
	});
})(jQuery);
