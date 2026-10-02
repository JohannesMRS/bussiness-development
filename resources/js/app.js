

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.querySelectorAll('[data-theme-toggle]').forEach((toggle) => {
	toggle.setAttribute('aria-pressed', String(document.documentElement.classList.contains('dark')));
});

document.addEventListener('click', (event) => {
	const target = event.target;
	const toggle = target instanceof Element ? target.closest('[data-theme-toggle]') : null;

	if (!toggle) {
		return;
	}

	const isDark = document.documentElement.classList.toggle('dark');
	toggle.setAttribute('aria-pressed', String(isDark));

	try {
		window.localStorage.setItem('theme', isDark ? 'dark' : 'light');
	} catch {
		// Theme remains available for the current page even when storage is disabled.
	}
});

const revealElements = document.querySelectorAll('[data-reveal]');

if ('IntersectionObserver' in window && ! window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
	const revealObserver = new IntersectionObserver((entries, observer) => {
		entries.forEach((entry) => {
			if (entry.isIntersecting) {
				entry.target.classList.add('is-visible');
				observer.unobserve(entry.target);
			}
		});
	}, { threshold: 0.12 });

	revealElements.forEach((element) => revealObserver.observe(element));
} else {
	revealElements.forEach((element) => element.classList.add('is-visible'));
}
