const toggleBtn = document.getElementById('filterToggle');
  const filterPanel = document.getElementById('filterPanel');

  toggleBtn.addEventListener('click', () => {
    filterPanel.classList.toggle('hidden');
  });

  // Optional: Toggle individual dropdowns when clicked
  document.querySelectorAll('.dropdown button').forEach(button => {
    button.addEventListener('click', () => {
      const menu = button.nextElementSibling;
      document.querySelectorAll('.dropdown-menu').forEach(m => {
        if (m !== menu) m.classList.add('hidden');
      });
      menu.classList.toggle('hidden');
    });
  });

  // Optional: Close dropdowns when clicking outside
  window.addEventListener('click', function(e) {
    if (!e.target.closest('.dropdown')) {
      document.querySelectorAll('.dropdown-menu').forEach(menu => {
        menu.classList.add('hidden');
      });
    }
  });
