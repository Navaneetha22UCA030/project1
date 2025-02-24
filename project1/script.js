document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const mainContent = document.querySelector(".main-content");
    const sidebarOverlay = document.querySelector(".sidebar-overlay");
    const sidebarToggle = document.getElementById("sidebarToggle");
    const closeButton = document.getElementById("xbutton_close");

    function toggleSidebar() {
        sidebar.classList.toggle("show");
        mainContent.classList.toggle("sidebar-shown");
        sidebarOverlay.classList.toggle("show");
    }

    // Only add event listeners if elements exist
    if (sidebarToggle) {
        sidebarToggle.addEventListener("click", toggleSidebar);
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener("click", toggleSidebar);
    }

    if (closeButton) {
        closeButton.addEventListener("click", function () {
            sidebar.classList.remove("show");
            mainContent.classList.remove("sidebar-shown");
            sidebarOverlay.classList.remove("show");
        });
    }
});
