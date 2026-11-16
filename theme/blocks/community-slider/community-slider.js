/* global document, jQuery, window */

(function ($) {
	'use strict';

	/**
	 * Initialize a panel's carousel the first time that panel becomes visible.
	 *
	 * Slick cannot correctly measure a track inside a hidden tab panel. Delaying
	 * initialization avoids zero-width slides; setPosition recalculates an
	 * existing carousel when a visitor returns to its tab.
	 */
	const initializeTrack = (panel) => {
		const $panel = $(panel);
		const $track = $panel.find('[data-community-track]');

		if (!$track.length) {
			return;
		}

		if ($track.hasClass('slick-initialized')) {
			$track.slick('setPosition');
			return;
		}

		$track.slick({
			adaptiveHeight: false,
			arrows: false,
			autoplay: true,
			autoplaySpeed: 4000,
			centerMode: true,
			centerPadding: '30px',
			cssEase: 'ease-in-out',
			dots: true,
			dotsClass: 'flex items-center justify-center gap-4',
			appendDots: $panel.find('[data-community-dots]'),
			customPaging(slick, index) {
				return $(
					'<button class="block h-1 w-3.5 rounded-full bg-secondary-4 transition-[width,background-color] duration-500" type="button" />'
				).attr('aria-label', `Go to post ${index + 1}`);
			},
			infinite: true,
			mobileFirst: true,
			pauseOnDotsHover: true,
			pauseOnFocus: true,
			pauseOnHover: true,
			slidesToScroll: 1,
			slidesToShow: 1,
			speed: 500,
			swipeToSlide: true,
			responsive: [
				{
					breakpoint: 768,
					settings: {
						centerPadding: '19%',
					},
				},
				{
					breakpoint: 1024,
					settings: {
						centerPadding: '16%',
						slidesToShow: 2,
					},
				},
			],
		});
	};

	/**
	 * Connect one block's category tabs to its post panels.
	 */
	const initializeBlock = (block) => {
		// ACF can call this initializer more than once for the same preview.
		if (block.dataset.communityInitialized === 'true') {
			return;
		}

		block.dataset.communityInitialized = 'true';

		const tabs = Array.from(block.querySelectorAll('[data-community-tab]'));
		const panels = Array.from(
			block.querySelectorAll('[data-community-panel]')
		);

		const activatePanel = (activeIndex, moveFocus = false) => {
			panels.forEach((panel, index) => {
				panel.hidden = index !== activeIndex;

				if (index === activeIndex) {
					initializeTrack(panel);
				}
			});

			tabs.forEach((tab, index) => {
				const isActive = index === activeIndex;

				tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
				tab.tabIndex = isActive ? 0 : -1;
			});

			if (moveFocus) {
				tabs[activeIndex]?.focus();
			}
		};

		tabs.forEach((tab, index) => {
			tab.addEventListener('click', () => activatePanel(index));

			// Follow the standard keyboard interaction for ARIA tabs.
			tab.addEventListener('keydown', (event) => {
				const destinations = {
					ArrowRight: (index + 1) % tabs.length,
					ArrowLeft: (index - 1 + tabs.length) % tabs.length,
					Home: 0,
					End: tabs.length - 1,
				};

				if (!(event.key in destinations)) {
					return;
				}

				event.preventDefault();
				activatePanel(destinations[event.key], true);
			});
		});

		activatePanel(0);
	};

	/**
	 * Initialize blocks in either the document or a newly rendered ACF preview.
	 */
	const initializeCommunitySliders = (scope = document) => {
		$(scope)
			.find('[data-saop-community]')
			.addBack('[data-saop-community]')
			.each(function () {
				initializeBlock(this);
			});
	};

	$(function () {
		initializeCommunitySliders();
	});

	if (window.acf) {
		window.acf.addAction('ready', initializeCommunitySliders);
		window.acf.addAction('append', initializeCommunitySliders);
		window.acf.addAction(
			'render_block_preview/type=community-slider',
			initializeCommunitySliders
		);
		window.acf.addAction(
			'render_block_preview/type=saop-community-slider',
			initializeCommunitySliders
		);
	}
})(jQuery);
