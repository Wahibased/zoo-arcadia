document.addEventListener('DOMContentLoaded', function () {
    // Gestion de l'affichage de la navbar via l'icône du menu
    const menuBtn = document.getElementById('menu-btn');
    const navbar = document.querySelector('.navbar');

    // Masquer la navbar au chargement de la page
    navbar.style.display = 'none';

    // Ajouter un événement 'click' à l'icône du menu
    menuBtn.addEventListener('click', function () {
        // Basculer la visibilité de la navbar
        if (navbar.style.display === 'none' || navbar.style.display === '') {
            navbar.style.display = 'block';  // Afficher la navbar
        } else {
            navbar.style.display = 'none';  // Cacher la navbar
        }
    });

    // Gestion de l'affichage du formulaire de connexion via l'icône admin
    const loginBtn = document.getElementById('login-btn');
    const loginForm = document.querySelector('.login-form');

    // Masquer le formulaire de connexion au chargement de la page
    loginForm.style.display = 'none';

    // Ajouter un événement 'click' à l'icône admin
    loginBtn.addEventListener('click', function () {
        // Basculer l'affichage du formulaire de connexion
        if (loginForm.style.display === 'none' || loginForm.style.display === '') {
            loginForm.style.display = 'block';  // Afficher le formulaire
        } else {
            loginForm.style.display = 'none';  // Cacher le formulaire
        }
    });
});

