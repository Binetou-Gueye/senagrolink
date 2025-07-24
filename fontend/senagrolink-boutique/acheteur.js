document.addEventListener('DOMContentLoaded', async () => {
    // Récupérer les données de l'agriculteur depuis le localStorage
    const buyerData = JSON.parse(localStorage.getItem('currentUser')) || {};
    

    // Afficher les informations du profil si elles existent
    if (buyerData.nom) {
        document.getElementById('welcomeMessage').textContent = `Bienvenue sur votre espace acheteur, ${buyerData.nom} !`;
        document.getElementById('buyerNameDisplay').textContent = buyerData.nom;
        document.getElementById('buyerEmailDisplay').textContent = buyerData.email;
        document.getElementById('buyerPhoneDisplay').textContent = buyerData.telephone;
        document.getElementById('buyerLocationDisplay').textContent = buyerData.localisation;
        document.getElementById('buyerTypeDisplay').textContent = buyerData.type_acheteur;
        document.getElementById('date_creation').textContent = buyerData.date_creation;
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


    // Recupper les commandes de l'acheteur
    try {
        // 1. Récupérer l'ID de la boutique (à adapter selon votre système)
        const user = JSON.parse(localStorage.getItem('currentUser'));
        if (!user || user.type !== 'acheteur') {
            throw new Error('Accès réservé aux agriculteurs');
        }

        // 2. Appel API pour récupérer les commandes
        const response = await fetch(`http://localhost/agrolink/backend/api/commande.php?id_acheteur=${user.id}`, {
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
        const orders = commandes || [];
        const ordersContainer = document.getElementById('orders-list');
        if (orders.length === 0) {
            document.getElementById('no-orders').style.display = 'block';
        } else {
            document.getElementById('no-orders').style.display = 'none';
            ordersContainer.innerHTML = orders.map(order => `
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Commande #${order.id_commande}</h5>
                        <p>Boutique: ${order.shopName || order.boutique || ''}</p>
                    </div>
                    <div class="card-body">
                        <h6>Produits:</h6>
                        <ul>
                            ${(order.produits || []).map(item => `
                                <li>${item.nom_produit} - ${item.quantite}x ${item.prix_unitaire} FCFA</li>
                            `).join('')}
                        </ul>
                        <hr>
                        <h6>Paiement:
                        <span> ${order.payment && order.payment.method === 'orangeMoney' ? 'Orange Money' : (order.payment && order.payment.method === 'wave' ? 'Wave' : 'Espéce')}</span>
                        <span style ="${order.payment && order.payment.method === 'orangeMoney' ? 'Orange Money' : (order.payment && order.payment.method === '' ? '' : 'display :none;')}" >
                        Numéro: ${order.payment ? order.payment.number : ''}</span>
                        <span style = "${order.payment && order.payment.method === 'orangeMoney' ? 'Orange Money' : (order.payment && order.payment.method === '' ? '' : 'display :none;')}">
                        Statut: ${order.payment ? order.payment.status : ''}</span>
                        </h6>
                        <hr>
                        <h5>Total: ${order.montant_total} FCFA</h5>
                        <span class="badge ${order.statut == 'Validé' ? 'bg-success' : order.statut == 'Rejeté' ? 'bg-danger' : 'bg-warning' }">
                            ${order.statut}
                        </span>
                    </div>
                </div>
            `).join('');
        }


    } catch (error) {
        console.error('Erreur:', error);
        alert(error.message);
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