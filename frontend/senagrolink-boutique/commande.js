document.getElementById('orders-tab').addEventListener('click', async () => {
     var alertPlaceholder = document.getElementById('liveAlertPlaceholder');
            function alert(message, type) {
            var wrapper = document.createElement('div')
            wrapper.innerHTML = '<div class="alert alert-' + type + ' alert-dismissible" role="alert">' + message + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'

            alertPlaceholder.append(wrapper)
            }
    try {
        // 1. Récupérer l'ID de la boutique (à adapter selon votre système)
        const user = JSON.parse(localStorage.getItem('currentUser'));
        if (!user || user.type !== 'agriculteur') {
            throw new Error('Accès réservé aux agriculteurs');
        }

        // 2. Appel API pour récupérer les commandes
        const response = await fetch(`http://localhost/agrolink/backend/api/commande.php?id_boutique=${user.boutique.id_boutique}`, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('authToken')}`
            }
        });

        if (!response.ok) {
            throw new Error('Erreur lors de la récupération');
        }

        const commandes = await response.json();
        console.log(commandes)
        
        // 3. Affichage des commandes
        const container = document.getElementById('ordersTableBody');
        container.innerHTML = ''; // Vider le conteneur

        if (commandes.length === 0) {
            container.innerHTML = '<p>Aucun produit disponible</p>';
            return;
        }

        commandes.forEach(commande => {
    const card = document.createElement('tr');
    card.innerHTML = `
        <td>#${commande.id_commande}</td>
        <td>${commande.date_commande}</td>
        <td>${commande.nom_utilisateur}</td>
        <td>${commande.montant_total}</td>
        <td>
            <span class="badge ${commande.statut == 'Validé' ? 'bg-success' : commande.statut == 'Rejeté' ? 'bg-danger' : 'bg-warning'}">
                ${commande.statut}
            </span>
        </td>
        <td>
            <button class="btn btn-sm btn-outline-primary">
                <i class="fas fa-eye"></i>
            </button>
            <button class="btn btn-sm btn-outline-success btn-valider" data-id="${commande.id_commande}">
                <i class="fas fa-check"></i>
            </button>
            <button class="btn btn-sm btn-outline-danger btn-rejeter" data-id="${commande.id_commande}">
                <i class="fas fa-times"></i>
            </button>
        </td>
    `;
    container.appendChild(card);
});

// Ajouter les écouteurs de clics après avoir injecté le HTML
document.querySelectorAll('.btn-valider').forEach(button => {
    button.addEventListener('click', () => {
        const id = button.getAttribute('data-id');
        fetch(`http://localhost/agrolink/backend/api/commande.php?status=Validé&id_commande=${id}`)
            .then(response => response.json())
            .then(data => {
                alert('Commande validée  !', 'success')
                setTimeout(() => {
                    window.location.href = 'http://localhost/agrolink/frontend/senagrolink-boutique/agriculteur.html';
                }, 3000);
                // Tu peux aussi recharger les commandes ici
            })
            .catch(err => {
                console.error(err);
                alert("Erreur lors de la validation.");
            });
    });
});

document.querySelectorAll('.btn-rejeter').forEach(button => {
    button.addEventListener('click', () => {
        const id = button.getAttribute('data-id');
        fetch(`http://localhost/agrolink/backend/api/commande.php?status=Rejeté&id_commande=${id}`)
            .then(response => response.json())
            .then(data => {
                alert('Commande rejetée !', 'danger')
                setTimeout(() => {
                    window.location.href = 'http://localhost/agrolink/frontend/senagrolink-boutique/agriculteur.html';
                }, 3000);
                // Tu peux aussi recharger les commandes ici
            })
            .catch(err => {
                console.error(err);
                alert("Erreur lors du rejet.");
            });
    });
});


    } catch (error) {
        console.error('Erreur:', error);
        alert(error.message);
    }
});
// Ajouter les écouteurs d'événements pour les boutons d'édition
document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', (e) => {
        console.log("ok")
        const productId = e.currentTarget.getAttribute('data-id');
        const produit = produits.find(p => p.id == productId);
        
        if (produit) {
            // Stocker le produit dans localStorage
            localStorage.setItem('produitToEdit', JSON.stringify(produit));
            
            // Rediriger vers ajout.html
            window.location.href = 'ajout.html';
        }
    });
});
// function gotProduit(produit) {
//     if (produit) {
//         localStorage.setItem('produit',produit)
//     }
//     window.location.href = 'ajout_produit.html';
// }

// document.getElementById('products-tab').addEventListener('click', async () => {