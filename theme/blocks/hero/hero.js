(function ($) {
	'use strict';

	var subheadingInterval = 3000;
	var subheadingTransitionDuration = 500;

	function getContext($context) {
		if ($context && $context.jquery) {
			return $context;
		}

		if (
			$context &&
			($context.nodeType || $context === window || $context === document)
		) {
			return $($context);
		}

		return $(document);
	}

	function initializeHeroSlider($hero) {
		var $slider = $hero.find('[data-hero-slider]').first();

		if (!$slider.length || $slider.hasClass('slick-initialized')) {
			return;
		}

		if ($slider.find('[data-hero-slide]').length < 2) {
			return;
		}

		$slider.slick({
			adaptiveHeight: false,
			arrows: true,
			dots: true,
			fade: false,
			infinite: true,
			mobileFirst: true,
			slidesToScroll: 1,
			slidesToShow: 1,
			speed: 600,
		});
	}

	function initializeSubheadingRotator(container) {
		var subheadings = Array.from(
			container.querySelectorAll('[data-hero-subheading]')
		);

		if (
			'true' === container.dataset.rotatorInitialized ||
			subheadings.length < 2
		) {
			return;
		}

		container.dataset.rotatorInitialized = 'true';

		if (
			window.matchMedia &&
			window.matchMedia('(prefers-reduced-motion: reduce)').matches
		) {
			return;
		}

		var activeIndex = 0;
		var isTransitioning = false;
		var intervalId = window.setInterval(function () {
			if (!container.isConnected) {
				window.clearInterval(intervalId);
				return;
			}

			if (document.hidden || isTransitioning) {
				return;
			}

			isTransitioning = true;

			var currentSubheading = subheadings[activeIndex];
			var nextIndex = (activeIndex + 1) % subheadings.length;
			var nextSubheading = subheadings[nextIndex];

			currentSubheading.classList.remove('translate-y-0', 'opacity-100');
			currentSubheading.classList.add('-translate-y-full', 'opacity-0');
			currentSubheading.setAttribute('aria-hidden', 'true');

			nextSubheading.classList.remove(
				'invisible',
				'translate-y-full',
				'-translate-y-full'
			);
			nextSubheading.classList.add('translate-y-0', 'opacity-100');
			nextSubheading.setAttribute('aria-hidden', 'false');

			activeIndex = nextIndex;

			window.setTimeout(function () {
				currentSubheading.classList.add(
					'invisible',
					'translate-y-full'
				);
				currentSubheading.classList.remove('-translate-y-full');
				isTransitioning = false;
			}, subheadingTransitionDuration);
		}, subheadingInterval);
	}

	function initializeSubheadingRotators($hero) {
		$hero
			.find(
				'[data-hero-slide]:not(.slick-cloned) [data-hero-subheadings]'
			)
			.each(function () {
				initializeSubheadingRotator(this);
			});
	}

	function initializeHeroSliders($context) {
		console.log('Initializing hero sliders in context:', $context);
		getContext($context)
			.find('[data-saop-hero]')
			.addBack('[data-saop-hero]')
			.each(function () {
				var $hero = $(this);

				initializeHeroSlider($hero);
				initializeSubheadingRotators($hero);
			});
	}

	$(function () {
		initializeHeroSliders($(document));
	});

	if (window.acf) {
		window.acf.addAction('ready', function ($el) {
			initializeHeroSliders($el);
		});

		window.acf.addAction('append', function ($el) {
			initializeHeroSliders($el);
		});

		window.acf.addAction('render_block_preview/type=hero', function ($el) {
			initializeHeroSliders($el);
		});
	}
})(jQuery);
