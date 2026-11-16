/* global jQuery */

(function ($) {
	'use strict';

	const reducedMotion = window.matchMedia(
		'(prefers-reduced-motion: reduce)'
	).matches;

	/**
	 * Initialize the Latest News carousel when it contains multiple posts.
	 */
	const initializeLatestNews = (archive) => {
		const $track = $(archive).find('[data-news-latest-track]');

		if (!$track.length || $track.hasClass('slick-initialized')) {
			return;
		}

		if ($track.children().length < 2) {
			return;
		}

		$track.slick({
			adaptiveHeight: false,
			arrows: false,
			autoplay: !reducedMotion,
			autoplaySpeed: 4000,
			centerMode: true,
			centerPadding: '35px',
			cssEase: 'ease-in-out',
			dots: true,
			dotsClass:
				'flex items-center justify-center gap-3 md:gap-9.5 lg:gap-14',
			appendDots: $(archive).find('[data-news-latest-dots]'),
			customPaging(slick, index) {
				return $(
					'<button class="block h-1 w-4.5 rounded-full bg-secondary-4 transition-[width,background-color] duration-500 md:w-5.25 lg:w-7.75" type="button" />'
				).attr('aria-label', `Go to latest news post ${index + 1}`);
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
						centerPadding: '26.5%',
					},
				},
				{
					breakpoint: 1024,
					settings: {
						centerPadding: '10.5%',
						slidesToShow: 2,
					},
				},
			],
		});
	};

	/**
	 * Connect the Discover tablist with its server-rendered panels.
	 */
	const initializeDiscoverTabs = (archive) => {
		const tabs = Array.from(archive.querySelectorAll('[data-news-tab]'));
		const panels = Array.from(
			archive.querySelectorAll('[data-news-panel]')
		);

		if (!tabs.length || !panels.length) {
			return;
		}

		const activatePanel = (activeIndex, moveFocus = false) => {
			panels.forEach((panel, index) => {
				panel.hidden = index !== activeIndex;
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

		const selectedIndex = tabs.findIndex(
			(tab) => tab.getAttribute('aria-selected') === 'true'
		);

		activatePanel(selectedIndex >= 0 ? selectedIndex : 0);
	};

	/**
	 * Move direct category requests to the preselected Discover panel.
	 */
	const scrollToDiscover = (archive) => {
		if (!archive.hasAttribute('data-news-scroll-to-discover')) {
			return;
		}

		const discover = archive.querySelector('#news-discover');

		if (!discover) {
			return;
		}

		window.requestAnimationFrame(() => {
			discover.scrollIntoView({ behavior: 'auto', block: 'start' });
		});
	};

	/**
	 * Initialize the archive once after the document is ready.
	 */
	const initializeNewsArchive = (archive) => {
		if (archive.dataset.newsInitialized === 'true') {
			return;
		}

		archive.dataset.newsInitialized = 'true';
		initializeLatestNews(archive);
		initializeDiscoverTabs(archive);
		scrollToDiscover(archive);
	};

	$(function () {
		document.querySelectorAll('[data-news-archive]').forEach((archive) => {
			initializeNewsArchive(archive);
		});
	});
})(jQuery);
