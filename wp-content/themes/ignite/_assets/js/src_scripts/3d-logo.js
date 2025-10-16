if ("ontouchstart" in window || navigator.maxTouchPoints > 0) {
// touch screen
} else {



document.addEventListener('DOMContentLoaded', () => {
	const logo = document.querySelector('#ITS-logo');
	const logoWrapper = document.querySelector('#ITS_MAIN_LOGO a');

	let currentX = 0;
	let currentY = 0;
	let targetX = 0;
	let targetY = 0;
	let animationFrame;

	const maxTilt = 80; // tilt for visible sides
	const scale = 1.02; // Slight scale for lift
	const easing = 0.208; // Smooth catch-up

	function animate() {
		currentX += (targetX - currentX) * easing;
		currentY += (targetY - currentY) * easing;

		logo.style.transform = `
      perspective(800px)
      rotateX(${currentY * maxTilt}deg)
      rotateY(${currentX * maxTilt}deg)
      scale(${scale})
    `;

		animationFrame = requestAnimationFrame(animate);
	}

	logoWrapper.addEventListener('mouseenter', () => {
		animationFrame = requestAnimationFrame(animate);
	});

	logoWrapper.addEventListener('mousemove', (e) => {
		const rect = logo.getBoundingClientRect();
		const x = (e.clientX - rect.left) / rect.width - 0.5;
		const y = (e.clientY - rect.top) / rect.height - 0.5;

		targetX = x * 1.5;
		targetY = y * 1.5;
	});

	logoWrapper.addEventListener('mouseleave', () => {
		cancelAnimationFrame(animationFrame);
		targetX = 0;
		targetY = 0;
		logo.style.transition = 'transform 0.6s cubic-bezier(0.25, 1, 0.5, 1)';
		logo.style.transform = 'perspective(800px) rotateX(0deg) rotateY(0deg) scale(1)';
		setTimeout(() => logo.style.transition = '', 600);
	});
});

}