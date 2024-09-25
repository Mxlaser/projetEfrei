document.addEventListener('DOMContentLoaded', function() {
    const hamburgerBtn = document.getElementById('btn-hamburger');
    const navbarMenu = document.getElementById('navbar-menu');

    if (hamburgerBtn) {
        hamburgerBtn.addEventListener('click', function() {
            // Bascule la classe 'active' pour afficher ou masquer le menu
            navbarMenu.classList.toggle('active');
        });
    }
});
