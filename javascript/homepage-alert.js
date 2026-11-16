/**
 * Open the homepage alert on a visitor's first visit and from its fixed trigger.
 */
const initHomepageAlert = () => {
	const trigger = document.querySelector('[data-homepage-alert-open]');
	const dialog = document.querySelector('[data-homepage-alert-dialog]');

	if (!trigger || !dialog || 'true' === dialog.dataset.alertInitialized) {
		return;
	}

	dialog.dataset.alertInitialized = 'true';

	const closeButton = dialog.querySelector('[data-homepage-alert-close]');
	const cookieName = dialog.dataset.homepageAlertCookie;
	const cookieValue = dialog.dataset.homepageAlertCookieValue;
	let activeTrigger = null;

	const getDismissalCookie = () => {
		const cookiePrefix = `${cookieName}=`;
		const cookie = document.cookie
			.split(';')
			.map((cookie) => cookie.trim())
			.find((cookie) => cookie.startsWith(cookiePrefix));

		return cookie
			? decodeURIComponent(cookie.slice(cookiePrefix.length))
			: null;
	};

	const getCookieAttributes = (maxAge) => {
		const secureAttribute =
			'https:' === window.location.protocol ? '; Secure' : '';

		return `Max-Age=${maxAge}; Path=/; SameSite=Lax${secureAttribute}`;
	};

	const clearDismissalCookie = () => {
		if (!cookieName) {
			return;
		}

		document.cookie = `${cookieName}=; ${getCookieAttributes(0)}`;
	};

	const setDismissalCookie = () => {
		if (!cookieName || !cookieValue) {
			return;
		}

		document.cookie = `${cookieName}=${encodeURIComponent(cookieValue)}; ${getCookieAttributes(31536000)}`;
	};

	const setScrollLocked = (isLocked) => {
		document.documentElement.classList.toggle(
			'saop-homepage-alert-open',
			isLocked
		);
	};

	const openDialog = (openedBy = null) => {
		if (dialog.open || 'function' !== typeof dialog.showModal) {
			return;
		}

		activeTrigger = openedBy;
		dialog.showModal();
		trigger.setAttribute('aria-expanded', 'true');
		setScrollLocked(true);

		window.requestAnimationFrame(() => closeButton?.focus());
	};

	trigger.addEventListener('click', () => openDialog(trigger));
	closeButton?.addEventListener('click', () => dialog.close());

	dialog.addEventListener('click', (event) => {
		if (event.target === dialog) {
			dialog.close();
		}
	});

	dialog.addEventListener('close', () => {
		const triggerToRestore = activeTrigger;

		setDismissalCookie();
		trigger.setAttribute('aria-expanded', 'false');
		setScrollLocked(false);
		activeTrigger = null;

		if (triggerToRestore?.isConnected) {
			triggerToRestore.focus();
		}
	});

	const savedCookieValue = getDismissalCookie();

	if (savedCookieValue && savedCookieValue !== cookieValue) {
		clearDismissalCookie();
	}

	if (savedCookieValue !== cookieValue) {
		window.requestAnimationFrame(() => openDialog());
	}
};

if ('loading' === document.readyState) {
	document.addEventListener('DOMContentLoaded', initHomepageAlert);
} else {
	initHomepageAlert();
}
