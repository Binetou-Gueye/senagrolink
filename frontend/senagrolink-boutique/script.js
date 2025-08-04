// Redirection après confirmation de déconnexion
    document.getElementById('confirmDeconnexion').addEventListener('click', function() {
        localStorage.removeItem("currentUser")
        // Alternative avec délai (2 secondes) si vous voulez afficher un message
        setTimeout(function() {
            window.location.href = 'http://localhost/agrolink/fontend/pages/inscription.html';
        }, 2000);
    });