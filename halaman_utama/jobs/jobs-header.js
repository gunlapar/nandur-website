
const menuIcon = document.querySelector('.menu-icon');
const closeIcon = document.querySelector('.close-icon');
const mobileNavmenu = document.querySelector('.mobile-navmenu');
const mobileMenuItems = document.querySelector('.mobile-menu-items');

menuIcon.addEventListener('click', () => {
  mobileNavmenu.classList.add('active');
  mobileMenuItems.classList.add('active'); // ini penting!
});

closeIcon.addEventListener('click', () => {
  mobileNavmenu.classList.remove('active');
  mobileMenuItems.classList.remove('active'); // ini juga penting!
});

