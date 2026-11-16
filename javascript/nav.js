/**
 * Collapse the header's quick-links bar while scrolling down and restore it
 * while scrolling up.
 */
const initScrollAwareHeader = () => {
	const header = document.querySelector('[data-site-header]');
	const topBar = header?.querySelector('[data-top-bar]');

	if (!header) {
		return;
	}

	const directionThreshold = 8;
	let previousScrollPosition = Math.max(window.scrollY, 0);
	let expandedHeaderHeight = 0;
	let isScrollTicking = false;
	let isHeaderHeightTicking = false;
	let shouldAllowHeaderHeightShrink = false;

	const syncTopBarHeight = () => {
		if (!topBar) {
			return;
		}

		topBar.style.setProperty(
			'--top-bar-height',
			`${topBar.scrollHeight}px`
		);
	};

	const syncExpandedHeaderHeight = (allowShrink = false) => {
		if ('true' === header.dataset.topBarHidden) {
			return;
		}

		const measuredHeaderHeight = Math.ceil(
			header.getBoundingClientRect().height
		);

		if (
			0 < measuredHeaderHeight &&
			(allowShrink || measuredHeaderHeight > expandedHeaderHeight)
		) {
			expandedHeaderHeight = measuredHeaderHeight;
			document.documentElement.style.setProperty(
				'--site-header-expanded-height',
				`${expandedHeaderHeight}px`
			);
		}
	};

	const scheduleHeaderHeightSync = (allowShrink = false) => {
		shouldAllowHeaderHeightShrink =
			shouldAllowHeaderHeightShrink || allowShrink;

		if (isHeaderHeightTicking) {
			return;
		}

		isHeaderHeightTicking = true;

		window.requestAnimationFrame(() => {
			syncExpandedHeaderHeight(shouldAllowHeaderHeightShrink);
			shouldAllowHeaderHeightShrink = false;
			isHeaderHeightTicking = false;
		});
	};

	const setTopBarHidden = (isHidden) => {
		if (!topBar) {
			return;
		}

		if (isHidden && topBar.contains(document.activeElement)) {
			return;
		}

		header.dataset.topBarHidden = isHidden ? 'true' : 'false';
		topBar.setAttribute('aria-hidden', isHidden ? 'true' : 'false');

		if ('inert' in topBar) {
			topBar.inert = isHidden;
		}
	};

	const updateHeader = () => {
		const currentScrollPosition = Math.max(window.scrollY, 0);
		const scrollDelta = currentScrollPosition - previousScrollPosition;

		if (0 === currentScrollPosition) {
			setTopBarHidden(false);
			previousScrollPosition = currentScrollPosition;
		} else if (Math.abs(scrollDelta) >= directionThreshold) {
			setTopBarHidden(0 < scrollDelta);
			previousScrollPosition = currentScrollPosition;
		}

		isScrollTicking = false;
	};

	if (topBar) {
		setTopBarHidden(false);
	}

	syncTopBarHeight();
	syncExpandedHeaderHeight(true);

	if ('ResizeObserver' in window) {
		const headerResizeObserver = new ResizeObserver(() => {
			scheduleHeaderHeightSync();
		});

		headerResizeObserver.observe(header);
	}

	window.addEventListener('load', () => scheduleHeaderHeightSync(true), {
		once: true,
	});

	window.addEventListener('resize', () => {
		const topBarWasHidden = 'true' === header.dataset.topBarHidden;

		syncTopBarHeight();
		setTopBarHidden(false);
		scheduleHeaderHeightSync(!topBarWasHidden);
	});

	if (!topBar) {
		return;
	}

	topBar.addEventListener('transitionend', (event) => {
		if (
			'max-height' === event.propertyName &&
			'true' !== header.dataset.topBarHidden
		) {
			scheduleHeaderHeightSync(true);
		}
	});

	window.addEventListener(
		'scroll',
		() => {
			if (!isScrollTicking) {
				window.requestAnimationFrame(updateHeader);
				isScrollTicking = true;
			}
		},
		{ passive: true }
	);
};

/**
 * Open the global site search in an accessible native dialog.
 */
const initSiteSearchDialog = () => {
	const triggers = Array.from(
		document.querySelectorAll('[data-site-search-open]')
	);
	const dialog = document.querySelector('[data-site-search-dialog]');

	if (
		!triggers.length ||
		!dialog ||
		'true' === dialog.dataset.siteSearchInitialized
	) {
		return;
	}

	dialog.dataset.siteSearchInitialized = 'true';

	const closeButton = dialog.querySelector('[data-site-search-close]');
	const searchInput = dialog.querySelector('[data-site-search-input]');
	let activeTrigger = null;
	let returnFocusElement = null;

	const setScrollLocked = (isLocked) => {
		document.documentElement.classList.toggle(
			'saop-site-search-open',
			isLocked
		);
	};

	const openDialog = (event) => {
		if (dialog.open || 'function' !== typeof dialog.showModal) {
			return;
		}

		activeTrigger = event.currentTarget;
		returnFocusElement = activeTrigger.closest('[data-mobile-menu-toolbar]')
			? activeTrigger
					.closest('[data-mobile-menu-shell]')
					?.querySelector('.mega-menu-toggle')
			: activeTrigger;
		document.dispatchEvent(
			new CustomEvent('saop:site-search-opening', {
				detail: { trigger: activeTrigger },
			})
		);

		dialog.showModal();
		activeTrigger.setAttribute('aria-expanded', 'true');
		setScrollLocked(true);

		window.requestAnimationFrame(() => searchInput?.focus());
	};

	triggers.forEach((trigger) =>
		trigger.addEventListener('click', openDialog)
	);
	closeButton?.addEventListener('click', () => dialog.close());

	dialog.addEventListener('click', (event) => {
		if (event.target === dialog) {
			dialog.close();
		}
	});

	dialog.addEventListener('close', () => {
		triggers.forEach((trigger) =>
			trigger.setAttribute('aria-expanded', 'false')
		);
		setScrollLocked(false);

		if (returnFocusElement?.isConnected) {
			returnFocusElement.focus();
		}

		activeTrigger = null;
		returnFocusElement = null;
	});

	window.addEventListener('pagehide', () => {
		if (dialog.open) {
			dialog.close();
		}
	});
};

/**
 * Present Max Mega Menu's mobile output inside the Figma-aligned drawer.
 */
const initMobileMegaMenu = () => {
	const shell = document.querySelector('[data-mobile-menu-shell]');
	const menu = shell?.querySelector('ul.max-mega-menu');
	const toggle = shell?.querySelector('.mega-menu-toggle');
	const toolbar = shell?.querySelector('[data-mobile-menu-toolbar]');
	const closeButton = shell?.querySelector('[data-mobile-menu-close]');
	const quickLinks = shell?.querySelector('[data-mobile-menu-quick-links]');
	const jquery = window.jQuery;

	if (!shell || !menu || !toggle || !closeButton || !jquery) {
		return;
	}

	const configuredBreakpoint = Number.parseInt(menu.dataset.breakpoint, 10);
	const mobileBreakpoint =
		Number.isFinite(configuredBreakpoint) && 0 <= configuredBreakpoint
			? configuredBreakpoint
			: 768;
	const mobileViewport = window.matchMedia(
		`(max-width: ${mobileBreakpoint}px)`
	);
	const jqueryMenu = jquery(menu);
	const rootItems = jqueryMenu.children('li.mega-menu-item');
	const rootLinksWithSubmenus = Array.from(menu.children)
		.map((item) => ({
			item,
			link: item.querySelector(':scope > a.mega-menu-link'),
			submenu: item.querySelector(':scope > ul.mega-sub-menu'),
		}))
		.filter(({ link, submenu }) => link && submenu);
	let isOpen = false;
	let shouldRestoreFocus = true;

	const syncMobileViewportClass = (isMobile) => {
		document.documentElement.classList.toggle(
			'saop-mega-menu-mobile',
			isMobile
		);
	};

	syncMobileViewportClass(mobileViewport.matches);

	const getFocusableElements = () =>
		Array.from(
			shell.querySelectorAll(
				'a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])'
			)
		).filter(
			(element) =>
				!element.hasAttribute('hidden') &&
				'true' !== element.getAttribute('aria-hidden') &&
				null !== element.offsetParent
		);

	const hasOpenRootPanel = () =>
		Array.from(menu.children).some((item) =>
			item.classList.contains('mega-toggle-on')
		);

	const syncSubmenuState = () => {
		shell.dataset.mobileSubmenuOpen = hasOpenRootPanel() ? 'true' : 'false';
	};

	const hideOtherRootPanels = (currentItem, menuApi) => {
		rootItems.each(function () {
			if (
				this === currentItem ||
				!this.classList.contains('mega-toggle-on')
			) {
				return;
			}

			const link = this.querySelector(':scope > a.mega-menu-link');

			if (link && menuApi?.hidePanel) {
				menuApi.hidePanel(jquery(link), true);
			}
		});
	};

	const setOpenState = (open) => {
		if (open && !mobileViewport.matches) {
			return;
		}

		isOpen = open;
		shell.dataset.mobileMenuOpen = open ? 'true' : 'false';
		document.documentElement.classList.toggle(
			'saop-mobile-menu-open',
			open
		);
		toolbar?.setAttribute('aria-hidden', open ? 'false' : 'true');
		quickLinks?.setAttribute('aria-hidden', open ? 'false' : 'true');

		if (open) {
			window.requestAnimationFrame(() => closeButton.focus());
			return;
		}

		shell.dataset.mobileSubmenuOpen = 'false';

		if (shouldRestoreFocus && toggle.isConnected) {
			toggle.focus();
		}

		shouldRestoreFocus = true;
	};

	const closeMenu = (restoreFocus = true) => {
		if (!isOpen && !toggle.classList.contains('mega-menu-open')) {
			return;
		}

		shouldRestoreFocus = restoreFocus;
		const menuApi = jqueryMenu.data('maxmegamenu');

		if (menuApi?.hidePanel) {
			jqueryMenu
				.find('li.mega-toggle-on > a.mega-menu-link')
				.each(function () {
					menuApi.hidePanel(jquery(this), true);
				});
		} else if (menuApi?.hideAllPanels) {
			menuApi.hideAllPanels();
		}

		if (menuApi?.hideMobileMenu) {
			// The theme hides Max Mega Menu's native toggle while the drawer is
			// open, so force the API close past its visible-toggle guard.
			menuApi.hideMobileMenu(true);
		} else {
			toggle.click();
			setOpenState(false);
		}
	};

	jqueryMenu.on('mmm:showMobileMenu', () => {
		setOpenState(true);
		syncSubmenuState();
	});

	jqueryMenu.on('mmm:hideMobileMenu', () => setOpenState(false));

	rootItems.on('open_panel', function () {
		if (!mobileViewport.matches) {
			return;
		}

		const menuApi = jqueryMenu.data('maxmegamenu');

		hideOtherRootPanels(this, menuApi);

		window.requestAnimationFrame(syncSubmenuState);
	});

	rootItems.on('close_panel', () =>
		window.requestAnimationFrame(syncSubmenuState)
	);

	rootLinksWithSubmenus.forEach(({ item, link }) => {
		link.addEventListener(
			'click',
			(event) => {
				if (
					!mobileViewport.matches ||
					0 !== event.button ||
					event.altKey ||
					event.ctrlKey ||
					event.metaKey ||
					event.shiftKey
				) {
					return;
				}

				const menuApi = jqueryMenu.data('maxmegamenu');

				if (!menuApi?.showPanel) {
					return;
				}

				if (item.classList.contains('mega-toggle-on')) {
					// The second activation follows the parent URL regardless of
					// the click behavior saved in the plugin theme.
					event.stopImmediatePropagation();

					if (
						!link.hasAttribute('href') ||
						'#' === link.getAttribute('href')
					) {
						event.preventDefault();
						menuApi.hidePanel(jquery(link), true);
						syncSubmenuState();
					}

					return;
				}

				event.preventDefault();
				event.stopImmediatePropagation();
				hideOtherRootPanels(item, menuApi);
				menuApi.showPanel(jquery(link), true);
				syncSubmenuState();
			},
			{ capture: true }
		);
	});

	if ('MutationObserver' in window) {
		const submenuObserver = new MutationObserver(syncSubmenuState);

		rootItems.each(function () {
			submenuObserver.observe(this, {
				attributes: true,
				attributeFilter: ['class'],
			});
		});
	}

	closeButton.addEventListener('click', (event) => {
		event.preventDefault();
		event.stopPropagation();
		closeMenu(true);
	});

	document.addEventListener('click', (event) => {
		if (!isOpen || shell.contains(event.target)) {
			return;
		}

		closeMenu(true);
	});

	document.addEventListener('saop:site-search-opening', () => {
		if (isOpen) {
			closeMenu(false);
		}
	});

	document.addEventListener('keydown', (event) => {
		if (!isOpen) {
			return;
		}

		if ('Escape' === event.key) {
			event.preventDefault();
			closeMenu(true);
			return;
		}

		if ('Tab' !== event.key) {
			return;
		}

		const focusableElements = getFocusableElements();

		if (!focusableElements.length) {
			event.preventDefault();
			return;
		}

		const firstElement = focusableElements[0];
		const lastElement = focusableElements.at(-1);

		if (event.shiftKey && document.activeElement === firstElement) {
			event.preventDefault();
			lastElement.focus();
		} else if (!event.shiftKey && document.activeElement === lastElement) {
			event.preventDefault();
			firstElement.focus();
		}
	});

	mobileViewport.addEventListener('change', (event) => {
		syncMobileViewportClass(event.matches);

		if (!event.matches) {
			closeMenu(false);
		}
	});
};

if ('loading' === document.readyState) {
	document.addEventListener('DOMContentLoaded', () => {
		initScrollAwareHeader();
		initMobileMegaMenu();
		initSiteSearchDialog();
	});
} else {
	initScrollAwareHeader();
	initMobileMegaMenu();
	initSiteSearchDialog();
}
