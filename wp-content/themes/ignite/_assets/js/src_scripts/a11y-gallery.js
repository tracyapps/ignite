// Defaults
let activeButton = null;
let transitionTimeout = 300; // fallback, can be overridden by CSS

const gallery = document.querySelector('.js-gallery');

const getTransitionTimeout = () => {
	const duration = parseFloat(
		getComputedStyle(document.documentElement).getPropertyValue('--duration-expand')
	);
	transitionTimeout = duration ? duration * 1000 : 300;
};

const deactivateAll = (items) => {
	gallery.classList.remove('is-zoomed');
	items.forEach((item) => {
		item.classList.remove('is-zoomed');
		item.style.transform = '';
		item.style.zIndex = '';
		const btn = item.querySelector('button');
		btn.setAttribute('aria-expanded', 'false');
	});

	if (activeButton) {
		activeButton.focus();
		activeButton = null;
	}
};

const activateItem = (items, item, button) => {
	// If already active, toggle off
	if (item.classList.contains('is-zoomed')) {
		deactivateAll(items);
		return;
	}

	deactivateAll(items);

	gallery.classList.add('is-zoomed');
	item.classList.add('is-zoomed');
	button.setAttribute('aria-expanded', 'true');

	// Animate (center & scale effect)
	item.style.zIndex = '10';
	item.style.transform = 'scale(2.5)'; // adjust scale to your preference

	activeButton = button;
};

const handleClickEvents = () => {
	const items = gallery.querySelectorAll('.gallery_image');

	items.forEach((item) => {
		const button = item.querySelector('button');
		button.addEventListener('click', () => {
			activateItem(items, item, button);
		});
	});
};

const handleKeyboardEvents = () => {
	document.addEventListener('keydown', (e) => {
		if (!activeButton) return;

		const currentItem = activeButton.closest('.gallery_image');
		const sibling = (dir) =>
			dir === 'prev'
				? currentItem.previousElementSibling
				: currentItem.nextElementSibling;

		switch (e.code) {
			case 'Escape':
				activeButton.click();
				break;
			case 'ArrowLeft': {
				const prev = sibling('prev');
				if (prev) prev.querySelector('button').click();
				break;
			}
			case 'ArrowRight': {
				const next = sibling('next');
				if (next) next.querySelector('button').click();
				break;
			}
		}
	});
};

const setup = () => {
	if (!gallery) return;

	getTransitionTimeout();
	handleClickEvents();
	handleKeyboardEvents();

	window.addEventListener('resize', () => {
		deactivateAll(gallery.querySelectorAll('.gallery_image'));
	});
};

setup();
