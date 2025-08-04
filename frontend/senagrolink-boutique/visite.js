document.addEventListener('DOMContentLoaded', async () => {
    // Fonction pour extraire les paramètres d'URL
    const user = JSON.parse(localStorage.getItem('currentUser'));
    

    const url = window.location.href;
    var id_boutique = url.split('=')[1]
    id_boutique = decodeURIComponent(id_boutique)

    const response = await fetch(`http://localhost/agrolink/backend/api/produits.php?boutique=${id_boutique}`);
    const produits = await response.json();

    console.log(produits)
    const nombre_produit =produits.length

    // Afficher les produits
    const container = document.getElementById('card-body');
    container.innerHTML = ''; // Vider le conteneur

    if (produits.length === 0) {
        container.innerHTML = '<p>Aucun produits disponible</p>';
        return;
    }

     produits.forEach(produit => {
            const card = document.createElement('div');
            card.className = 'col-lg-4 col-md-6 animate-fade';
            card.innerHTML = `
                <div class="product-card">
                    <img src="pictures/sobleh.jpg"  alt="Oignons Rouges" class="product-img">
                    <div class="product-body">
                        <p class="product-id" style="display:none;">${produit.id_produit}</p>
                        <h3 class="product-title">${produit.nom}</h3>
                        <p class="product-description">${produit.description}</p>
                        <span class="product-price">${produit.prix_unitaire} FCFA/${produit.unite_vente}</span>
                        <div class="product-footer">
                            <div class="quantity-control">
                                <button class="quantity-btn minus">-</button>
                                <input type="number" class="quantity-input" value="1" min="1" max="${produit.quantite_stock}">
                                <button class="quantity-btn plus">+</button>
                            </div>
                            <button class="add-to-cart">
                                <i class="bi bi-cart-plus"></i> Ajouter
                            </button>
                        </div>
                    </div>
                </div>
            `;

            container.appendChild(card);
        });


        const cartBtn = document.querySelector('.cart-btn');
        const cartSidebar = document.querySelector('.cart-sidebar');
        const closeCartBtn = document.querySelector('.close-cart');
        const addToCartBtns = document.querySelectorAll('.add-to-cart');
        const cartItemsContainer = document.querySelector('.cart-items');
        const cartCount = document.querySelector('.cart-count');
        const cartSubtotal = document.querySelector('.cart-subtotal');
        const cartTotal = document.querySelector('.cart-total-price');
        const checkoutBtn = document.querySelector('.checkout-btn');

        let cart = [];
        let productCommande = [];
            
            // Ouvrir/fermer le panier
            cartBtn.addEventListener('click', toggleCart);
            closeCartBtn.addEventListener('click', toggleCart);
            
            function toggleCart() {
                cartSidebar.classList.toggle('active');
            }
            
            // Ajouter au panier
            addToCartBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Animation du bouton
                    this.classList.add('clicked');
                    setTimeout(() => this.classList.remove('clicked'), 500);
                    
                    // Récupérer les infos du produit
                    const productCard = this.closest('.product-card');
                    const productName = productCard.querySelector('.product-title').textContent;
                    const id_produit = productCard.querySelector('.product-id').textContent;
                    console.log(id_produit)
                    const productPriceText = productCard.querySelector('.product-price').textContent;
                    const productPrice = extractPrice(productPriceText);
                    const productImage = productCard.querySelector('.product-img').src;
                    const productQuantity = parseInt(productCard.querySelector('.quantity-input').value);
                    
                    // Vérifier si le produit est déjà dans le panier
                    const existingItem = cart.find(item => item.name === productName);
                    
                    if (existingItem) {
                        existingItem.quantity += productQuantity;
                    } else {
                        cart.push({
                            id: id_produit,
                            name: productName,
                            price: productPrice,
                            image: productImage,
                            quantity: productQuantity,
                            quantite: productQuantity,
                            prix_unitaire: productPrice
                        });
                        productCommande.push({
                            id_produit: id_produit,
                            quantite: productQuantity,
                            prix_unitaire: productPrice
                        });
                    }
                    
                    // Mettre à jour le panier
                    updateCart();
                    
                    // Ouvrir le panier
                    cartSidebar.classList.add('active');
                });
            });
            
            // Extraire le prix du texte
            function extractPrice(priceText) {
                // Supprimer tout ce qui n'est pas chiffre ou point
                const numericValue = priceText.replace(/[^0-9.]/g, '');
                return parseFloat(numericValue);
            }
            
            // Mettre à jour le panier
            function updateCart() {
                // Mettre à jour le compteur
                const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
                cartCount.textContent = totalItems;
                
                // Afficher/masquer le compteur
                cartCount.style.display = totalItems > 0 ? 'flex' : 'none';
                
                // Mettre à jour les articles du panier
                if (cart.length > 0) {
                    let cartHTML = '';
                    let subtotal = 0;
                    
                    cart.forEach((item, index) => {
                        const itemTotal = item.price * item.quantity;
                        subtotal += itemTotal;
                        
                        cartHTML += `
                            <div class="cart-item" data-index="${index}">
                                <img src="${item.image}" alt="${item.name}" class="cart-item-img">
                                <div class="cart-item-details">
                                    <h4 class="cart-item-title">${item.name}</h4>
                                    <span class="cart-item-price">${formatPrice(item.price)} FCFA</span>
                                    <div class="cart-item-quantity">
                                        <button class="quantity-btn minus">-</button>
                                        <span>${item.quantity}</span>
                                        <button class="quantity-btn plus">+</button>
                                        <button class="cart-item-remove">Supprimer</button>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    
                    cartItemsContainer.innerHTML = cartHTML;
                    
                    // Mettre à jour les totaux
                    cartSubtotal.textContent = `${formatPrice(subtotal)} FCFA`;
                    cartTotal.textContent = `${formatPrice(subtotal)} FCFA`;
                    
                    // Ajouter les événements aux nouveaux boutons
                    addCartItemEvents();
                } else {
                    cartItemsContainer.innerHTML = `
                        <div class="empty-cart">
                            <div class="empty-cart-icon">
                                <i class="bi bi-cart-x"></i>
                            </div>
                            <p>Votre panier est vide</p>
                            <button class="btn btn-outline-success mt-2">Continuer vos achats</button>
                        </div>
                    `;
                    cartSubtotal.textContent = '0 FCFA';
                    cartTotal.textContent = '0 FCFA';
                }
            }
            
            // Formater le prix avec séparateur de milliers
            function formatPrice(price) {
                return price.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, " ");
            }
            
            // Ajouter les événements aux éléments du panier
            function addCartItemEvents() {
                // Boutons plus/moins
                document.querySelectorAll('.cart-item .quantity-btn.minus').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const index = this.closest('.cart-item').dataset.index;
                        if (cart[index].quantity > 1) {
                            cart[index].quantity--;
                            productCommande[index].quantite--;
                            updateCart();
                        }
                    });
                });
                
                document.querySelectorAll('.cart-item .quantity-btn.plus').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const index = this.closest('.cart-item').dataset.index;
                        cart[index].quantity++;
                        productCommande[index].quantite++;
                        updateCart();
                    });
                });
                
                // Boutons supprimer
                document.querySelectorAll('.cart-item-remove').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const index = this.closest('.cart-item').dataset.index;
                        cart.splice(index, 1);
                        productCommande.splice(index, 1);
                        updateCart();
                    });
                });
            }
            // Passer la commande
            checkoutBtn.addEventListener('click', function() {
                if (cart.length > 0) {
                    alert('Commande passée avec succès! Total: ' + cartTotal.textContent);
                    commande = {
                            "id_utilisateur": user.id,
                            "id_boutique": id_boutique,
                            "statut" : "En Cours",
                            "adresse_livraison": user.localisation,
                            "produits": productCommande
                        }
                    const response =  fetch('http://localhost/agrolink/backend/api/commande.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(commande)
                    });
                    productCommande =[]
                    cart = [];
                    updateCart();
                    cartSidebar.classList.remove('active');

                    window.location.href = 'http://localhost/agrolink/fontend/senagrolink-boutique/acheteur.html';
                } else {
                    alert('Votre panier est vide!');
                }
            });
            
            // Gestion des quantités sur les produits
            document.querySelectorAll('.quantity-btn.minus').forEach(btn => {
                btn.addEventListener('click', function() {
                    const input = this.nextElementSibling;
                    if (parseInt(input.value) > 1) {
                        input.value = parseInt(input.value) - 1;
                    }
                });
            });
            
            document.querySelectorAll('.quantity-btn.plus').forEach(btn => {
                btn.addEventListener('click', function() {
                    const input = this.previousElementSibling;
                    input.value = parseInt(input.value) + 1;
                });
            });
            
            // Initialiser le panier
            updateCart();
            
})