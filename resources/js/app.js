import "bootstrap/js/dist/dom/data.js";
import "bootstrap/js/dist/dom/manipulator.js";
import "bootstrap/js/dist/util/config.js";
import "bootstrap/js/dist/util/index.js";
import "bootstrap/js/dist/alert.js";
import "bootstrap/js/dist/button.js";
import "bootstrap/js/dist/carousel.js";
import "bootstrap/js/dist/collapse.js";
import "bootstrap/js/dist/dropdown.js";
import "bootstrap/js/dist/modal.js";
import "bootstrap/js/dist/offcanvas.js";
import "bootstrap/js/dist/popover.js";
import "bootstrap/js/dist/scrollspy.js";
import "bootstrap/js/dist/tab.js";
import "bootstrap/js/dist/toast.js";
import "bootstrap/js/dist/tooltip.js";

// Custom JS for clothing store
document.addEventListener("DOMContentLoaded", function () {
    // Auto-init ALL Bootstrap components (modals, tooltips, etc.)
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Filter sync
    const filterForms = document.querySelectorAll(".filter-form");
    filterForms.forEach((form) => {
        form.addEventListener("change", function () {
            this.closest("form").submit();
        });
    });

    console.log("Bootstrap JS loaded - Modals ready!");
});
