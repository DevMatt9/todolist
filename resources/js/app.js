import "./bootstrap";

const toggleBtn = document.getElementById("toggleDarkMode");
const body = document.body;

// Check local storage for dark mode preference
if (localStorage.getItem("darkMode") === "enabled") {
    body.classList.add("dark-mode");
    toggleBtn.textContent = "☀️ Mode Clair";
}

// Add event listener to toggle button
toggleBtn.addEventListener("click", () => {
    body.classList.toggle("dark-mode");
    if (body.classList.contains("dark-mode")) {
        localStorage.setItem("darkMode", "enabled");
        toggleBtn.textContent = "☀️ Mode Clair";
    } else {
        localStorage.setItem("darkMode", "disabled");
        toggleBtn.textContent = "🌙 Mode Sombre";
    }
});
