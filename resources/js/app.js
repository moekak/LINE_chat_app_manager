import './bootstrap.js';
document.querySelectorAll('input, textarea').forEach(element => {
      element.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                  event.preventDefault();
            }
      });
});