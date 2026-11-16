/* global document, window */

(function () {
	'use strict';

	const desktopMedia = window.matchMedia('(min-width: 1024px)');

	/**
	 * Return all featured-link blocks inside a document or ACF preview scope.
	 */
	const getBlocks = (scope = document) => {
		const roots = scope?.jquery ? scope.toArray() : [scope];
		const blocks = [];

		roots.forEach((root) => {
			if (root.matches?.('[data-featured-links]')) {
				blocks.push(root);
			}

			blocks.push(
				...Array.from(
					root.querySelectorAll?.('[data-featured-links]') || []
				)
			);
		});

		return Array.from(new Set(blocks));
	};

	/**
	 * Apply one mobile row's expanded or collapsed state.
	 */
	const setExpanded = (item, expanded) => {
		const toggle = item.querySelector('[data-featured-link-toggle]');
		const panel = item.querySelector('[data-featured-link-panel]');
		const destination = item.querySelector(
			'[data-featured-link-destination]'
		);

		if (!toggle || !panel || !destination) {
			return;
		}

		item.dataset.featuredLinkExpanded = expanded ? 'true' : 'false';
		toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
		panel.setAttribute('aria-hidden', expanded ? 'false' : 'true');
		destination.tabIndex = expanded ? 0 : -1;

		panel.classList.toggle('pointer-events-none', !expanded);
		panel.classList.toggle('pointer-events-auto', expanded);
		panel.classList.toggle('translate-x-full', !expanded);
		panel.classList.toggle('translate-x-0', expanded);
		panel.classList.toggle('opacity-0', !expanded);
		panel.classList.toggle('opacity-100', expanded);

		if (
			!expanded &&
			panel.contains(document.activeElement) &&
			typeof toggle.focus === 'function'
		) {
			toggle.focus();
		}
	};

	/**
	 * Close every mobile row except an optional row that is about to open.
	 */
	const closeItems = (except = null) => {
		document
			.querySelectorAll('[data-featured-link-item]')
			.forEach((item) => {
				if (item !== except) {
					setExpanded(item, false);
				}
			});
	};

	/**
	 * Prepare newly rendered block markup without duplicating initialization.
	 */
	const initializeBlocks = (scope = document) => {
		getBlocks(scope).forEach((block) => {
			if (block.dataset.featuredLinksInitialized === 'true') {
				return;
			}

			block.dataset.featuredLinksInitialized = 'true';
			block
				.querySelectorAll('[data-featured-link-item]')
				.forEach((item) => setExpanded(item, false));
		});
	};

	document.addEventListener('click', (event) => {
		const destination = event.target.closest?.(
			'[data-featured-link-destination]'
		);

		if (destination || desktopMedia.matches) {
			return;
		}

		const item = event.target.closest?.('[data-featured-link-item]');

		if (!item) {
			closeItems();
			return;
		}

		const isExpanded = item.dataset.featuredLinkExpanded === 'true';

		closeItems(item);
		setExpanded(item, !isExpanded);
	});

	document.addEventListener('keydown', (event) => {
		if (event.key !== 'Escape' || desktopMedia.matches) {
			return;
		}

		closeItems();
	});

	const resetForDesktop = () => closeItems();

	if (typeof desktopMedia.addEventListener === 'function') {
		desktopMedia.addEventListener('change', resetForDesktop);
	} else {
		desktopMedia.addListener(resetForDesktop);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', () => initializeBlocks());
	} else {
		initializeBlocks();
	}

	if (window.acf) {
		window.acf.addAction(
			'render_block_preview/type=featured-links',
			initializeBlocks
		);
		window.acf.addAction(
			'render_block_preview/type=saop-featured-links',
			initializeBlocks
		);
	}
})();
