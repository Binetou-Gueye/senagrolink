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
    // 2. Appel API pour récupérer les produits
    const response = await fetch(`http://localhost/agrolink/backend/api/commande.php?acheteur=${buyerData.id}`, {
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('authToken')}`
        }
    });
    const orders = await response.json();
    const ordersContainer = document.getElementById('orders-list');
    if (orders.length === 0) {
        document.getElementById('no-orders').style.display = 'block';
    } else {
        // document.getElementById('no-orders').style.display = 'none';
        ordersContainer.innerHTML = orders.map(order => `
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Commande #${order.id_commande}</h5>
                    <p>Boutique: ${order.shopName || order.nom_boutique || ''}</p>
                </div>
                <div class="card-body">
                    <h6>Produits:</h6>
                    <ul>
                        ${(order.produits || []).map(item => `
                            <li>${item.nom_produit} - ${item.quantite}x ${item.prix_unitaire} FCFA</li>
                        `).join('')}
                    </ul>
                    <hr>
                    <h6>Paiement: A la livraison </h6>
                    <hr>
                    <h5>Total: ${order.montant_total} FCFA</h5>
                    <span class="badge bg-${order.status === 'Livré' ? 'success' : 'warning'}">
                        ${order.statut}
                    </span>
                </div>
            </div>
        `).join('');
    }
  
});