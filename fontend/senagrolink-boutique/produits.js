document.getElementById('products-tab').addEventListener('click', async () => {
    try {
        // 1. Récupérer l'ID de la boutique (à adapter selon votre système)
        const user = JSON.parse(localStorage.getItem('currentUser'));
        if (!user || user.type !== 'agriculteur') {
            throw new Error('Accès réservé aux agriculteurs');
        }

        // 2. Appel API pour récupérer les produits
        const response = await fetch(`http://localhost/agrolink/backend/api/produits.php?agriculteur=${user.id}`, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('authToken')}`
            }
        });

        if (!response.ok) {
            throw new Error('Erreur lors de la récupération');
        }

        const produits = await response.json();
        console.log(produits)
        
        // 3. Affichage des produits
        const container = document.getElementById('productsList');
        container.innerHTML = ''; // Vider le conteneur

        if (produits.length === 0) {
            container.innerHTML = '<p>Aucun produit disponible</p>';
            return;
        }

        produits.forEach(produit => {
            const card = document.createElement('div');
            card.className = 'product-item';
            card.innerHTML = `
                <div class="d-flex justify-content-between mb-1">
                    <h5>${produit.nom}</h5>
                    <div>
                        <span class="badge badge-organic">${produit.quantite_stock}</span> restants
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-success">${produit.prix_unitaire} FCFA/kg</span>
                    <div>
                        <button class="btn btn-sm btn-outline-primary me-1">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(card);
        });

    } catch (error) {
        console.error('Erreur:', error);
        alert(error.message);
    }
});