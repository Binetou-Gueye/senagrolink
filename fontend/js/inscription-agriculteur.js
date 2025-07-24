document.addEventListener('DOMContentLoaded', function() {
            // Gestion des formulaires d'inscription
    const farmerForm = document.getElementById('inscriptionFormAgriculteur');
    const buyerForm = document.getElementById('buyerForm');
    const loginForm = document.getElementById('loginForm');
    const successMsg = document.getElementById('successMsg');
    const errorMsg = document.getElementById('errorMsg');
    const receptionMsg = document.getElementById('receptionMsg');
    
    // Stocker les utilisateurs dans localStorage
    if (!localStorage.getItem('currentUser')) {
        localStorage.setItem('currentUser', {});
    }
    
    // Inscription Agriculteur
    farmerForm.addEventListener('submit', async function(e) {
        e.preventDefault();
            
        const newFarmer = {
            type: 'agriculteur',
            nom: document.getElementById('nom').value,
            email: document.getElementById('email').value,
            mot_de_passe: document.getElementById('mot_de_passe').value,
            localisation: document.getElementById('localisation').value,
            telephone: document.getElementById('telephone').value,
            type_production: document.getElementById('type_production').value
        };        
        try {

            const response = await fetch('http://localhost/agrolink/backend/api/register.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(newFarmer)
            });

        const result = await response.json();
            
        if (result.success == true) {
            localStorage.setItem('currentUser', JSON.stringify(newFarmer));            
            
            // Afficher message de succès
            successMsg.textContent = "Inscription réussie en tant qu'agriculteur!";
            successMsg.classList.remove('d-none');
            receptionMsg.classList.remove('d-none');
            
            // Redirection après 3 secondes
            setTimeout(() => {
                window.location.href = 'http://localhost/agrolink/fontend/senagrolink-boutique/agriculteur.html';
            }, 3000);
        } else {
            errorMsg.textContent = "Erreur lors de l\'inscription";
            errorMsg.classList.remove('d-none');
            setTimeout(() => errorMsg.classList.add('d-none'), 3000);
            return;
        }
    } catch (error) {
        // const messageDiv = document.getElementById('message');
        // messageDiv.className = 'message error';
        // messageDiv.textContent = 'Erreur de connexion au serveur';
        console.log("erreur")
    }   
    });

    // Inscription Acheteur 
    buyerForm.addEventListener('submit', async function(e) {
        e.preventDefault();
            
        const newBuyer = {
            type: 'acheteur',
            nom: document.getElementById('buyerName').value,
            email: document.getElementById('buyerEmail').value,
            mot_de_passe: document.getElementById('buyerPassword').value,
            localisation: document.getElementById('buyerLocation').value,
            telephone: document.getElementById('buyerPhone').value,
            type_acheteur: document.getElementById('buyerType').value
        };      
        try {

            const response = await fetch('http://localhost/agrolink/backend/api/register.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(newBuyer)
            });

        const result = await response.json();
            
        if (result.success == true) {
            localStorage.setItem('currentUser', JSON.stringify(newBuyer));            
            
            // Afficher message de succès
            successMsg.textContent = "Inscription réussie en tant qu'acheteur!";
            successMsg.classList.remove('d-none');
            receptionMsg.classList.remove('d-none');
            
            // Redirection après 3 secondes
            setTimeout(() => {
                window.location.href = 'http://localhost/agrolink/fontend/senagrolink-boutique/acheteur.html';
            }, 3000);
        } else {
            errorMsg.textContent = "Erreur lors de l\'inscription";
            errorMsg.classList.remove('d-none');
            setTimeout(() => errorMsg.classList.add('d-none'), 3000);
            return;
        }
    } catch (error) {
        console.log("erreur")
    }   
    
});
    
    // // Inscription Acheteur
    // buyerForm.addEventListener('submit', function(e) {
    //     e.preventDefault();
        
    //     const users = JSON.parse(localStorage.getItem('users'));
    //     const email = document.getElementById('buyerEmail').value;
        
    //     // Vérifier si l'email existe déjà
    //     if (users.some(user => user.email === email)) {
    //         errorMsg.textContent = "Cet email est déjà utilisé!";
    //         errorMsg.classList.remove('d-none');
    //         setTimeout(() => errorMsg.classList.add('d-none'), 3000);
    //         return;
    //     }
        
    //     // Créer nouvel utilisateur acheteur
    //     const newBuyer = {
    //         type: 'buyer',
    //         name: document.getElementById('buyerName').value,
    //         email: email,
    //         phone: document.getElementById('buyerPhone').value,
    //         password: document.getElementById('buyerPassword').value,
    //         location: document.getElementById('buyerLocation').value,
    //         buyerType: document.getElementById('buyerType').value,
    //         registrationDate: new Date().toLocaleDateString()
    //     };
        
    //     users.push(newBuyer);
    //     localStorage.setItem('users', JSON.stringify(users));
    //     localStorage.setItem('currentUser', JSON.stringify(newBuyer));
        
    //     // Afficher message de succès
    //     successMsg.textContent = "Inscription réussie en tant qu'acheteur!";
    //     successMsg.classList.remove('d-none');
    //     receptionMsg.classList.remove('d-none');
        
    //     // Redirection après 3 secondes
    //     setTimeout(() => {
    //         window.location.href = 'acheteur.html';
    //     }, 3000);
    // });
    
    // // Connexion
    loginForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Stocker les utilisateurs dans localStorage
        if (!localStorage.getItem('currentUser')) {
            localStorage.setItem('currentUser', {});
        }
        
        const users = JSON.parse(localStorage.getItem('users'));
        const email = document.getElementById('email_login').value;
        const mot_de_passe = document.getElementById('mot_de_passe_login').value;

        console.log(email,mot_de_passe)
        try {

            const response = await fetch('http://localhost/agrolink/backend/api/login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ email, mot_de_passe })
            });

        const result = await response.json();
        console.log()
        if (result.success == true) {
            localStorage.setItem('currentUser', JSON.stringify(result.user));            
            
            // Afficher message de succès
            successMsg.textContent = "Bienvenu!";
            successMsg.classList.remove('d-none');
            receptionMsg.classList.remove('d-none');
            
            // Redirection après 3 secondes
            setTimeout(() => {
                if (result.user.type == 'acheteur') {
                    window.location.href = 'http://localhost/agrolink/fontend/senagrolink-boutique/acheteur.html';
                }else{
                    window.location.href = 'http://localhost/agrolink/fontend/senagrolink-boutique/agriculteur.html';
                }
            }, 3000);
        } else {
            errorMsg.textContent = "Erreur lors de l\'inscription";
            errorMsg.classList.remove('d-none');
            setTimeout(() => errorMsg.classList.add('d-none'), 3000);
            return;
        }
    } catch (error) {
        // const messageDiv = document.getElementById('message');
        // messageDiv.className = 'message error';
        // messageDiv.textContent = 'Erreur de connexion au serveur';
        console.log("erreur")
    }   

        // Trouver l'utilisateur
        const user = users.find(u => u.email === email && u.password === password);
        
        if (user) {
            // Connexion réussie
            localStorage.setItem('currentUser', JSON.stringify(user));
            successMsg.textContent = "Connexion réussie!";
            successMsg.classList.remove('d-none');
            
            // Redirection selon le type d'utilisateur
            setTimeout(() => {
                window.location.href = user.type === 'farmer' ? 'agriculteur.html' : 'acheteur.html';
            }, 1500);
        } else {
            // Échec de connexion
            errorMsg.textContent = "Email ou mot de passe incorrect!";
            errorMsg.classList.remove('d-none');
            setTimeout(() => errorMsg.classList.add('d-none'), 3000);
        }
    });
    
    // // Mot de passe oublié
    // document.getElementById('forgotPassword').addEventListener('click', function(e) {
    //     e.preventDefault();
    //     alert("Une demande de réinitialisation de mot de passe a été envoyée à l'administrateur.");
    // });
});