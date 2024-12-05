document.addEventListener("DOMContentLoaded", function () {
    const nightModeToggle = document.getElementById('nightModeToggle');

    // Check for night mode preference on page load
    if (localStorage.getItem("nightMode") === "enabled") {
        document.body.classList.add("night-mode");
    }

    // Night mode toggle functionality
    nightModeToggle.addEventListener('click', () => {
        document.body.classList.toggle("night-mode");

        // Save preference in localStorage
        if (document.body.classList.contains("night-mode")) {
            localStorage.setItem("nightMode", "enabled");
        } else {
            localStorage.setItem("nightMode", "disabled");
        }
    });

    // Log the current local storage value for debugging
    console.log("Local storage nightMode:", localStorage.getItem("nightMode"));
});
