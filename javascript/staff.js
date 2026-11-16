/**
 * Initialize the staff group jump-link carousel.
 */
const initStaffGroupSlider = (directory) => {
	if (directory.dataset.staffSliderInitialized === 'true') {
		return;
	}

	const slider = directory.querySelector('[data-staff-group-slider]');
	const $ = window.jQuery;

	if (!slider || !$ || typeof $.fn.slick !== 'function') {
		return;
	}

	const $slider = $(slider);
	const $previous = $(directory).find('[data-staff-group-prev]');
	const $next = $(directory).find('[data-staff-group-next]');
	const links = Array.from(
		directory.querySelectorAll('[data-staff-group-link]')
	);

	if (!links.length) {
		return;
	}

	directory.dataset.staffSliderInitialized = 'true';

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

/**
 * Open staff biographies in the archive's native dialogs.
 */
const initStaffDirectory = (directory) => {
	if ('true' === directory.dataset.staffDirectoryInitialized) {
		return;
	}

	directory.dataset.staffDirectoryInitialized = 'true';
	initStaffGroupSlider(directory);

	const dialogs = new Map(
		Array.from(directory.querySelectorAll('[data-staff-dialog]')).map(
			(dialog) => [dialog.dataset.staffSlug, dialog]
		)
	);
	let activeDialog = null;
	let activeTrigger = null;

	const setScrollLocked = (isLocked) => {
		document.documentElement.classList.toggle('overflow-hidden', isLocked);
	};

	const openDialog = (dialog, trigger = null, openedFromUrl = false) => {
		if (!dialog || 'function' !== typeof dialog.showModal) {
			return false;
		}

		activeDialog = dialog;
		activeTrigger = trigger;
		dialog.dataset.staffOpenedFromUrl = openedFromUrl ? 'true' : 'false';
		dialog.showModal();
		setScrollLocked(true);

		window.requestAnimationFrame(() => {
			dialog.querySelector('[data-staff-dialog-close]')?.focus();
		});

		return true;
	};

	directory.addEventListener('click', (event) => {
		const trigger = event.target.closest?.('[data-staff-bio-trigger]');

		if (
			!trigger ||
			event.defaultPrevented ||
			event.metaKey ||
			event.ctrlKey ||
			event.shiftKey ||
			event.altKey
		) {
			return;
		}

		const dialog = document.getElementById(
			trigger.getAttribute('aria-controls')
		);

		if (openDialog(dialog, trigger)) {
			event.preventDefault();
		}
	});

	dialogs.forEach((dialog) => {
		dialog
			.querySelector('[data-staff-dialog-close]')
			?.addEventListener('click', () => dialog.close());

		dialog.addEventListener('click', (event) => {
			if (event.target === dialog) {
				dialog.close();
			}
		});

		dialog.addEventListener('close', () => {
			const openedFromUrl = 'true' === dialog.dataset.staffOpenedFromUrl;
			const triggerToRestore = activeTrigger;

			delete dialog.dataset.staffOpenedFromUrl;
			activeDialog = null;
			activeTrigger = null;
			setScrollLocked(false);

			if (openedFromUrl) {
				const url = new URL(window.location.href);

				url.searchParams.delete('staff_bio');
				window.history.replaceState(
					window.history.state,
					'',
					`${url.pathname}${url.search}${url.hash}`
				);
			}

			if (triggerToRestore?.isConnected) {
				triggerToRestore.focus();
			}
		});
	});

	const requestedSlug = new URL(window.location.href).searchParams.get(
		'staff_bio'
	);
	const requestedDialog = requestedSlug ? dialogs.get(requestedSlug) : null;

	if (requestedDialog) {
		const section = window.location.hash
			? document.getElementById(window.location.hash.slice(1))
			: null;

		section?.scrollIntoView({ block: 'start' });

		window.requestAnimationFrame(() => {
			window.requestAnimationFrame(() => {
				openDialog(requestedDialog, null, true);
			});
		});
	}

	window.addEventListener('pagehide', () => {
		if (activeDialog?.open) {
			activeDialog.close();
		}
	});
};

const initStaffDirectories = () => {
	document
		.querySelectorAll('[data-staff-directory]')
		.forEach(initStaffDirectory);
};

if ('loading' === document.readyState) {
	document.addEventListener('DOMContentLoaded', initStaffDirectories);
} else {
	initStaffDirectories();
}
