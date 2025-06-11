const mobileMenuItems = document.querySelector(".mobile-menu-items");

menuIcon.addEventListener("click", () => {
    mobileNavmenu.classList.add("active");
    mobileMenuItems.classList.add("active"); // penting
});

closeIcon.addEventListener("click", () => {
    mobileNavmenu.classList.remove("active");
    mobileMenuItems.classList.remove("active"); // penting
});
