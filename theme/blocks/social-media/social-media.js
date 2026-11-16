/* global document, jQuery, MutationObserver, window */

(function ($) {
	'use strict';

	const blockSelector = '[data-saop-social-media]';
	const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
	const observers = new WeakMap();

	/**
	 * Stop waiting for delayed Smash Balloon markup in one block.
	 */
	const stopObserving = (block) => {
		const observation = observers.get(block);

		if (!observation) {
			return;
		}

		observation.observer.disconnect();
		window.clearTimeout(observation.timeout);
		observers.delete(block);
	};

	/**
	 * Add a block-local mount for Slick's generated pagination.
	 */
	const getDotsMount = ($feed) => {
		let $dots = $feed.children('[data-instagram-dots]').first();

		if (!$dots.length) {
			$dots = $('<div data-instagram-dots />');
			$feed.append($dots);
		}

		return $dots;
	};

	/**
	 * Turn Smash Balloon's rendered grid items into a Slick carousel.
	 *
	 * @return {boolean} Whether the block is ready and no longer needs watching.
	 */
	const initializeBlock = (block) => {
		const $block = $(block);
		const $feed = $block.find('[data-instagram-feed]').first();

		if (!$feed.length || typeof $.fn.slick !== 'function') {
			return true;
		}

		const $track = $feed.find('[id="sbi_images"]').first();

		if (!$track.length || $track.children('.sbi_item').length < 2) {
			return false;
		}

		if ($track.hasClass('slick-initialized')) {
			block.dataset.instagramSliderInitialized = 'true';
			$track.slick('setPosition');
			stopObserving(block);
			return true;
		}

		const $dots = getDotsMount($feed);

		// Clear controls left by a replaced or refreshed plugin feed.
		$dots.empty();

		$track.on('init.saopSocialMedia', () => {
			block.dataset.instagramSliderInitialized = 'true';
			$dots
				.find('.saop-instagram-dots')
				.attr('aria-label', 'Instagram carousel navigation');
		});

		$track.slick({
			adaptiveHeight: false,
			arrows: false,
			// autoplay: !reducedMotion.matches,
			autoplay: false,
			autoplaySpeed: 4000,
			centerMode: true,
			centerPadding: '30px',
			cssEase: 'ease-in-out',
			dots: true,
			dotsClass: 'saop-instagram-dots',
			appendDots: $dots,
			customPaging(slick, index) {
				return $('<button type="button" />').attr(
					'aria-label',
					`Go to Instagram post ${index + 1}`
				);
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

		stopObserving(block);
		return true;
	};

	/**
	 * Watch briefly when ACF inserts a preview before the plugin renders posts.
	 */
	const observeBlock = (block) => {
		if (observers.has(block) || typeof MutationObserver === 'undefined') {
			return;
		}

		const observer = new MutationObserver(() => {
			if (!block.isConnected || initializeBlock(block)) {
				stopObserving(block);
			}
		});

		observer.observe(block, {
			childList: true,
			subtree: true,
		});

		const timeout = window.setTimeout(() => stopObserving(block), 10000);

		observers.set(block, { observer, timeout });
	};

	/**
	 * Initialize blocks in the document or within a new ACF preview scope.
	 */
	const initializeSocialMediaSliders = (scope = document) => {
		$(scope)
			.find(blockSelector)
			.addBack(blockSelector)
			.each(function () {
				if (!initializeBlock(this)) {
					observeBlock(this);
				}
			});
	};

	const scheduleInitialization = (scope = document) => {
		window.setTimeout(() => initializeSocialMediaSliders(scope), 0);
	};

	// Smash Balloon fires this event after each feed instance is constructed.
	$(window).on('sbiafterfeedcreate.saopSocialMedia', () => {
		scheduleInitialization();
	});

	$(function () {
		scheduleInitialization();
	});

	const updateMotionPreference = () => {
		$(`${blockSelector} [id="sbi_images"].slick-initialized`).each(
			function () {
				$(this).slick(
					reducedMotion.matches ? 'slickPause' : 'slickPlay'
				);
			}
		);
	};

	if (typeof reducedMotion.addEventListener === 'function') {
		reducedMotion.addEventListener('change', updateMotionPreference);
	} else {
		reducedMotion.addListener(updateMotionPreference);
	}

	if (window.acf) {
		window.acf.addAction('ready', scheduleInitialization);
		window.acf.addAction('append', scheduleInitialization);
		window.acf.addAction(
			'render_block_preview/type=social-media',
			scheduleInitialization
		);
		window.acf.addAction(
			'render_block_preview/type=saop-social-media',
			scheduleInitialization
		);
	}
})(jQuery);
