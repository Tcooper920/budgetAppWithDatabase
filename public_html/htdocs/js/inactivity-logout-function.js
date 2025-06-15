// Popup model...
const modelPopup = document.getElementById("popup-model");
const okayButton = document.getElementById("modal-dismiss");
const modalInstance = new bootstrap.Modal(modelPopup); // Create a Bootstrap modal instance
const configEl = document.getElementById("logout-config");
const logoutUrl = configEl.getAttribute("data-logout-url");

// Logout user after inactivity...
function inactivityTime() {
	let warningTimer;
	let logoutTimer;
	const warningTimeout = 780000; // Warn after 13 minutes (13 * 60 * 1000)
	const logoutTimeout = 900000; // Logout after 15 minutes (15 * 60 * 1000)

	function resetTimer() {
		clearTimeout(warningTimer);
		clearTimeout(logoutTimer);

		warningTimer = setTimeout(() => {
			modalInstance.show(); // Display inactivity modal popup warning
		}, warningTimeout);

		logoutTimer = setTimeout(() => {
			window.location.href = window.location.origin + logoutUrl;
		}, logoutTimeout);
	}

	// Debounce reset for performance:
	// Delays the execution of a function until after a specified delay has passed since the last event.
	// This prevents the function from being called too often and impacting performance.
	function debounce(func, wait) {
		let timeout;
		return () => {
			clearTimeout(timeout);
			timeout = setTimeout(func, wait);
		};
	}

	const debouncedReset = debounce(resetTimer, 200);

	// Reset timer on any page interaction...
	window.addEventListener("load", resetTimer);
	document.addEventListener("keydown", resetTimer);
	document.addEventListener("click", resetTimer);
	document.addEventListener("scroll", debouncedReset);
	document.addEventListener("mousemove", debouncedReset);
}

inactivityTime();

// Toggle off popup modal when user clicks confirm button
okayButton.addEventListener("click", () => {
	modalInstance.hide();
});
