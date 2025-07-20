document.addEventListener('DOMContentLoaded', function() {
    // Récupérer les données de l'agriculteur depuis le localStorage
    const buyerData = JSON.parse(localStorage.getItem('currentUser')) || {};
    

    // Afficher les informations du profil si elles existent
    if (buyerData.nom) {
        document.getElementById('welcomeMessage').textContent = `Bienvenue, ${buyerData.nom} !`;
        document.getElementById('buyerNameDisplay').textContent = buyerData.nom;
        document.getElementById('buyerEmailDisplay').textContent = buyerData.email;
        document.getElementById('buyerPhoneDisplay').textContent = buyerData.telephone;
        document.getElementById('buyerLocationDisplay').textContent = buyerData.localisation;
        document.getElementById('buyerTypeDisplay').textContent = buyerData.type_acheteur;
        document.getElementById('registrationDateDisplay').textContent = buyerData.registrationDate;
    }


    // Affichage des commandes
    const orders = JSON.parse(localStorage.getItem('buyerOrders')) || [];
    const ordersContainer = document.getElementById('orders-list');
    if (orders.length === 0) {
        document.getElementById('no-orders').style.display = 'block';
    } else {
        document.getElementById('no-orders').style.display = 'none';
        ordersContainer.innerHTML = orders.map(order => `
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Commande #${order.id}</h5>
                    <p>Boutique: ${order.shopName || order.boutique || ''}</p>
                </div>
                <div class="card-body">
                    <h6>Produits:</h6>
                    <ul>
                        ${(order.items || []).map(item => `
                            <li>${item.name} - ${item.quantity}x ${item.price} FCFA</li>
                        `).join('')}
                    </ul>
                    <hr>
                    <h6>Paiement:</h6>
                    <p>Méthode: ${order.payment && order.payment.method === 'orangeMoney' ? 'Orange Money' : (order.payment && order.payment.method === 'wave' ? 'Wave' : '')}</p>
                    <p>Numéro: ${order.payment ? order.payment.number : ''}</p>
                    <p>Statut: ${order.payment ? order.payment.status : ''}</p>
                    <hr>
                    <h5>Total: ${order.total} FCFA</h5>
                    <span class="badge bg-${order.status === 'Livré' ? 'success' : 'warning'}">
                        ${order.status}
                    </span>
                </div>
            </div>
        `).join('');
    }

 // Redirection après confirmation de déconnexion
    document.getElementById('confirmDeconnexion').addEventListener('click', function() {
        localStorage.removeItem("currentUser")
        // Alternative avec délai (2 secondes) si vous voulez afficher un message
        setTimeout(function() {
            window.location.href = 'http://localhost/agrolink/fontend/pages/inscription.html';
        }, 2000);
    });
});